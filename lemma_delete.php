<?php


if(isset($_POST['word_engine_id'])) {
  $word_engine_id = $_POST['word_engine_id'];
}

if(isset($_POST['lemma_id'])) {
  $lemma_id = $_POST['lemma_id'];
}

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
$sql = "SELECT lemma FROM lemmas WHERE lemma_id = $lemma_id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$lemma_form = $row["lemma"]; */

$sql = "DELETE FROM lemmas WHERE lemma_id = $lemma_id";
$res = $conn->query($sql);

/*
$sql = "SELECT lemma_id FROM lemmas WHERE lemma = '$lemma_form'";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$leftover_lemma_id = $row["lemma_id"];
*/

$sql = "SELECT lemma_id from display_text where word_engine_id = $word_engine_id AND lemma_id IS NOT NULL AND lemma_id != $lemma_id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$leftover_lemma_id = $row["lemma_id"];


if(is_null($leftover_lemma_id)) {
  $sql = "UPDATE word_engine SET first_lemma_id = NULL WHERE word_engine_id = $word_engine_id";
  $res = $conn->query($sql);
  $sql = "UPDATE display_text SET lemma_meaning_no = NULL, lemma_id = NULL WHERE word_engine_id = $word_engine_id";
  $res = $conn->query($sql);

  echo "";
}
else {

  $sql = "UPDATE word_engine SET first_lemma_id = $leftover_lemma_id WHERE word_engine_id = $word_engine_id";
  $res = $conn->query($sql);
  $sql = "UPDATE display_text SET lemma_meaning_no = 1, lemma_id = $leftover_lemma_id WHERE word_engine_id = $word_engine_id";
  $res = $conn->query($sql); 

  echo "true";
}

$conn->close();
    
?>
