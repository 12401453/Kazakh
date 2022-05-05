<?php

$word_engine_id = 0;
if(isset($_POST['word_engine_id'])) {
  $word_engine_id = $_POST['word_engine_id'];
}

if(isset($_POST['lemma_form'])) {
  $lemma_form = $_POST['lemma_form'];
}

if(isset($_POST['lemma_meaning'])) {
  $lemma_meaning = $_POST['lemma_meaning'];
}

$lang_id = 0;
if(isset($_POST['lang_id'])) {
  $lang_id = $_POST['lang_id'];
}

$pos = 1;


include 'db_details_web.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


$sql = "SET NAMES UTF8";
$res = $conn->query($sql);

$sql = "SELECT lemma_id FROM word_engine WHERE word_engine_id = $word_engine_id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$lemma_id = $row["lemma_id"];
if(is_null($lemma_id)) {
  


  $sql = "INSERT IGNORE INTO lemmas (lemma, eng_trans1, lang_id, pos) VALUES ('$lemma_form', '$lemma_meaning', $lang_id, $pos)"; 
  $res = $conn->query($sql);
  $sql = "SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'Kazakh' AND TABLE_NAME = 'lemmas'";
  $res = $conn->query($sql);
  $row = $res->fetch_assoc();
  $lemma_id = $row["AUTO_INCREMENT"] - 1;


  $sql = "UPDATE word_engine SET lemma_id = $lemma_id WHERE word_engine_id = $word_engine_id";
  $res = $conn->query($sql);

}
else {  

  $sql = "UPDATE lemmas SET lemma = '$lemma_form', eng_trans1 = '$lemma_meaning', lang_id = $lang_id, pos = $pos WHERE lemma_id = $lemma_id";
  $res = $conn->query($sql);

}

$conn->close();
    
?>
