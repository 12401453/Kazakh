<?php

$word_engine_id = 0;
if(isset($_POST['word_engine_id'])) {
  $word_engine_id = $_POST['word_engine_id'];
}
else {$word_engine_id = 0;}




include 'db_details_web.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


$sql = "SET NAMES UTF8";
$res = $conn->query($sql);

$sql = "SELECT word, lemma_id FROM word_engine WHERE word_engine_id = $word_engine_id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$lemma_id = $row["lemma_id"];

if(is_null($lemma_id)) {
  $lemma_tag_content = $row["word"];
  $lemma_textarea_content = "";
}
else {
  $sql = "SELECT lemma FROM lemmas WHERE lemma_id = $lemma_id";
  $res = $conn->query($sql);
  $row = $res->fetch_assoc();
  $lemma_tag_content = $row["lemma"];

  $sql = "SELECT eng_trans1 FROM lemmas WHERE lemma_id = $lemma_id";
  $res = $conn->query($sql);
  $row = $res->fetch_assoc();
  $lemma_textarea_content = $row["eng_trans1"];
  if(is_null($lemma_textarea_content)) {
    $lemma_textarea_content = "";
  }
}

$json_response = json_encode(array("lemma_tag_content" => htmlentities($lemma_tag_content), "lemma_textarea_content" => htmlentities($lemma_textarea_content)));
echo $json_response;

$conn->close();
    
?>
