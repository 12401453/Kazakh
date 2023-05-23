<?php

$word_eng_ids = "";
if(isset($_POST["word_eng_ids"])) {
    $word_eng_ids = $_POST["word_eng_ids"];
}
$lang_id = 0;
if(isset($_POST["lang_id"])) {
    $lang_id = $_POST["lang_id"];
}

$word_eng_ids = explode(",", $word_eng_ids);
$word_eng_count = count($word_eng_ids);

include 'db_details_web.php';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$sql = "SET NAMES UTF8";
$res = $conn->query($sql);

$null_count = 1;
$sql_string = "SELECT multiword_id FROM multiwords WHERE ";
//need to change this so it specifies the rest of the word_eng_idX columns as NULL
for($i = 0; $i < $word_eng_count; $i++) {
    $sql_string .= "word_eng_id".($i + 1)." = ".$word_eng_ids[$i];
    $sql_string .= " AND ";
    $null_count++;
}
for(; $null_count < 11; $null_count++) {
    $sql_string .= "word_eng_id".$null_count." = 0 AND ";
}
$sql_string .= "lang_id = ".$lang_id;


$sql_stmt = $conn->prepare($sql_string);
$sql_stmt->execute();
$sql_stmt->bind_result($multiword_id);
$sql_stmt->fetch();

$mw_lemma_form = "";
$mw_lemma_meaning = "";
$pos = 1;
if(is_null($multiword_id)) {
    $multiword_id = 0;
}
else {
    $sql_stmt->prepare("SELECT multiword_lemma_form, pos, eng_trans1 FROM multiword_lemmas WHERE multiword_id = ?");
    $sql_stmt->bind_param("i", $multiword_id);
    $sql_stmt->execute();
    $sql_stmt->bind_result($mw_lemma_form, $pos, $mw_lemma_meaning);
    $sql_stmt->fetch();
    if(is_null($mw_lemma_meaning)) $mw_lemma_meaning = ""; //incase someone has deleted eng_trans1 but left others
}
$sql_stmt->close();

$json_response = json_encode(array("multiword_tag_content" => $mw_lemma_form, "multiword_textarea_content" => $mw_lemma_meaning, "multiword_id" => $multiword_id, "pos" => $pos));

echo $json_response;
$conn->close();
?>
