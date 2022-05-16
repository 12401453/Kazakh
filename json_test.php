<?php

$word_engine_id = 0;
if(isset($_POST['word_engine_id'])) {
  $word_engine_id = $_POST['word_engine_id'];
}
else {$word_engine_id = 5;}

$tokno_current = 0;
if(isset($_POST['tokno_current'])) {
  $tokno_current = $_POST['tokno_current'];
}




include 'db_details_web.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


$sql = "SET NAMES UTF8";
$res = $conn->query($sql);
/*
$sql = "UPDATE display_text SET word_engine_id = NULL WHERE tokno = $tokno_current";
$res = $conn->query($sql);

$sql = "DELETE FROM word_engine WHERE word_engine_id = $word_engine_id";
$res = $conn->query($sql);
*/

$sql = "SELECT word, first_lemma_id FROM word_engine WHERE word_engine_id = $word_engine_id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$word = $row["word"];
$lemma_id = 0;
$eng_trans1 = "";
if(is_null($row["first_lemma_id"]) == false) {
  $lemma_id = $row["first_lemma_id"];

  $sql = "SELECT eng_trans1 FROM lemmas WHERE lemma_id = $lemma_id";
  $res = $conn->query($sql);
  $row = $res->fetch_assoc();
  $eng_trans1 = $row["eng_trans1"];
}

$sql = "SELECT lemma_meaning_no FROM display_text WHERE tokno = $tokno_current";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$lemma_meaning_no = $row["lemma_meaning_no"];

$json_response = json_encode(array("word" => $word, "eng_trans1" => $eng_trans1, "lemma_meaning_no" => $lemma_meaning_no));
echo $json_response;


$conn->close();
    
?>
