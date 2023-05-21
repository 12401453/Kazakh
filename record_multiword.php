<?php

//sample send_data: word_eng_ids=27294,2146&toknos=128192,128193&multiword_lemma_form=k%C5%82a%C5%9B%C4%87%20si%C4%99&multiword_lemma_meaning=lay%20down&multiword_meaning_no=1&pos=2&lang_id=3&anchor_tokno=128192

$word_eng_ids = "";
if(isset($_POST["word_eng_ids"])) {
    $word_eng_ids = $_POST["word_eng_ids"];
}
$toknos = "";
if(isset($_POST["toknos"])) {
    $toknos = $_POST["toknos"];
}
$multiword_lemma_form = "";
if(isset($_POST["multiword_lemma_form"])) {
    $multiword_lemma_form = addslashes($_POST["multiword_lemma_form"]);
}
$multiword_lemma_meaning = "";
if(isset($_POST["multiword_lemma_meaning"])) {
    $multiword_lemma_meaning = addslashes($_POST["multiword_lemma_meaning"]);
}
$multiword_meaning_no = 1;
if(isset($_POST["multiword_meaning_no"])) {
    $multiword_meaning_no = $_POST["multiword_meaning_no"];
}
$pos = 1;
if(isset($_POST["pos"])) {
    $pos = $_POST["pos"];
}
$lang_id = 0;
if(isset($_POST["lang_id"])) {
    $lang_id = $_POST["lang_id"];
}
$anchor_tokno = 0;
if(isset($_POST["anchor_tokno"])) {
    $anchor_tokno = $_POST["anchor_tokno"];
}


$word_eng_ids = explode(",", $word_eng_ids);
$toknos = explode(",", $toknos);

$word_count = count($word_eng_ids);

include 'db_details_web.php';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$sql = "SET NAMES UTF8";
$res = $conn->query($sql);

$res = $conn->query("START TRANSACTION");

$sql_stmt = $conn->prepare("INSERT IGNORE INTO multiword_lemmas (multiword_lemma_form, pos, lang_id) VALUES (?, ?, ?)");
$sql_stmt->bind_param("sii", $multiword_lemma_form, $pos, $lang_id);
$sql_stmt->execute();

$sql_stmt->prepare("SELECT multiword_id FROM multiword_lemmas WHERE multiword_lemma_form = ? AND pos = ? AND lang_id = ?");
$sql_stmt->bind_param("sii", $multiword_lemma_form, $pos, $lang_id);
$sql_stmt->execute();
$sql_stmt->bind_result($multiword_id);
$sql_stmt->fetch();

$sql_stmt->prepare("SELECT multiword_count FROM display_text WHERE tokno = ?");
$sql_stmt->bind_param("i", $anchor_tokno);
$sql_stmt->execute();
$sql_stmt->bind_result($multiword_count);
$sql_stmt->fetch();

if(is_null($multiword_count)) {
    $sql_stmt->prepare("SELECT MAX(multiword_count) AS max_count FROM display_text");
    $sql_stmt->execute();
    $sql_stmt->bind_result($multiword_count);
    $sql_stmt->fetch();
    $multiword_count += 1;
}
else {
    $sql_stmt->prepare("UPDATE display_text SET multiword_id = NULL, multiword_meaning_no = NULL, multiword_count = NULL WHERE multiword_count = ?");
    $sql_stmt->bind_param("i", $multiword_count);
    $sql_stmt->execute();
}

$sql_stmt->prepare("UPDATE display_text SET multiword_id = ?, multiword_count = ? WHERE tokno = ?");
$tokno = $toknos[0];
$sql_stmt->bind_param("iii", $multiword_id, $multiword_count, $tokno);
for($i = 0; $i < $word_count; $i++) {
    $tokno = $toknos[$i];
    $sql_stmt->execute();
}

$sql_string = "INSERT IGNORE INTO multiwords (multiword_id, ";
for($i = 0; $i < $word_count; $i++) {
    $sql_string .= "word_engine_id".strval($i + 1).", ";
}
$sql_string .= "lang_id) VALUES (".$multiword_id.", ";
for($i = 0; $i < $word_count; $i++) {
    $sql_string .= $word_eng_ids[$i].", ";
}
$sql_string .= $lang_id.")";
$sql_stmt->prepare($sql_string);
$sql_stmt->execute();

$sql_stmt->prepare("UPDATE multiword_lemmas SET eng_trans".$multiword_meaning_no." = ? WHERE multiword_id = ?");
$sql_stmt->bind_param("si", $multiword_lemma_meaning, $multiword_id);
$sql_stmt->execute();

$sql_stmt->prepare("UPDATE display_text SET multiword_meaning_no = ? WHERE multiword_count = ?");
$sql_stmt->bind_param("ii", $multiword_meaning_no, $multiword_count);
$sql_stmt->execute();

$sql_stmt->close();

$res = $conn->query("COMMIT");

echo $multiword_count.",".$multiword_id;

?>
