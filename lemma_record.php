<?php

$word_engine_id = 0;
if(isset($_POST['word_engine_id'])) {
  $word_engine_id = $_POST['word_engine_id'];
}

if(isset($_POST['lemma_form'])) {
  $lemma_form = addslashes($_POST['lemma_form']);
}

if(isset($_POST['lemma_meaning'])) {
  $lemma_meaning = addslashes($_POST['lemma_meaning']);
}



$lang_id = 0;
if(isset($_POST['lang_id'])) {
  $lang_id = $_POST['lang_id'];
}

$lemma_meaning_no = 1;
if(isset($_POST['lemma_meaning_no'])) {
  $lemma_meaning_no = $_POST['lemma_meaning_no'];
}

$clicked_lemma_meaning_no = 1;
if(isset($_POST['clicked_lemma_meaning_no'])) {
  $clicked_lemma_meaning_no = $_POST['clicked_lemma_meaning_no'];
}

$pos = 1;

if(isset($_POST['pos'])) {
  $pos = $_POST['pos'];
}


if(isset($_POST['tokno_current'])) {
  $tokno_current = $_POST['tokno_current'];
}

$eng_trans_sql_string = 'eng_trans'.$lemma_meaning_no;


include 'db_details_web.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


$sql = "SET NAMES UTF8";
$res = $conn->query($sql);

$sql = "SELECT lemma_id FROM display_text WHERE tokno = $tokno_current";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$lemma_id_current = $row["lemma_id"];

$sql = "SELECT first_lemma_id FROM word_engine WHERE word_engine_id = $word_engine_id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$first_lemma_id = $row["first_lemma_id"];

if(is_null($lemma_id_current)) {

  $sql = "INSERT IGNORE INTO lemmas (lemma, ".$eng_trans_sql_string.", lang_id, pos) VALUES ('$lemma_form', '$lemma_meaning', $lang_id, $pos)";
  $res = $conn->query($sql);

  $sql = "SELECT lemma_id FROM lemmas WHERE lemma = '$lemma_form' AND lang_id = $lang_id AND pos = $pos";
  $res = $conn->query($sql);
  $row = $res->fetch_assoc();
  $lemma_id_target = $row["lemma_id"];

  $sql = "UPDATE lemmas SET ".$eng_trans_sql_string." = '$lemma_meaning' WHERE lemma_id = $lemma_id_target";
  $res = $conn->query($sql);

  $sql = "UPDATE display_text SET lemma_id = $lemma_id_target, lemma_meaning_no = $clicked_lemma_meaning_no WHERE tokno = $tokno_current";
  $res = $conn->query($sql);
}

else {

  $sql = "SELECT lemma_id FROM lemmas WHERE lemma = '$lemma_form' AND lang_id = $lang_id AND pos = $pos";
  $res = $conn->query($sql);
  $row = $res->fetch_assoc();
  $lemma_id_target = $row["lemma_id"];

  //if this word already has an assigned lemma but we want to change it to a lemma which isn't already in the lemmas table
  if(is_null($lemma_id_target)) {
    $sql = "INSERT IGNORE INTO lemmas (lemma, ".$eng_trans_sql_string.", lang_id, pos) VALUES ('$lemma_form', '$lemma_meaning', $lang_id, $pos)";
    $res = $conn->query($sql);

    $sql = "SELECT lemma_id FROM lemmas WHERE lemma = '$lemma_form' AND lang_id = $lang_id AND pos = $pos";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    $lemma_id_target = $row["lemma_id"];

    $sql = "UPDATE display_text SET lemma_id = $lemma_id_target, lemma_meaning_no = $clicked_lemma_meaning_no WHERE tokno = $tokno_current";
    $res = $conn->query($sql);

  }
  //if this word already has an assigned lemma but we want to change it to a lemma which IS already in the lemmas table
  else if($lemma_id_target != $lemma_id_current) {
    $sql = "UPDATE display_text SET lemma_id = $lemma_id_target, lemma_meaning_no = $clicked_lemma_meaning_no WHERE tokno = $tokno_current";
    $res = $conn->query($sql);

    $sql = "UPDATE lemmas SET ".$eng_trans_sql_string." = '$lemma_meaning' WHERE lemma_id = $lemma_id_target";
    $res = $conn->query($sql);
  }
  else {
    $sql = "UPDATE display_text SET lemma_meaning_no = $clicked_lemma_meaning_no WHERE tokno = $tokno_current";
    $res = $conn->query($sql);

    $sql = "UPDATE lemmas SET ".$eng_trans_sql_string." = '$lemma_meaning' WHERE lemma_id = $lemma_id_target";
    $res = $conn->query($sql);

  }

}

if(is_null($first_lemma_id)) {
  $sql = "UPDATE word_engine SET first_lemma_id = $lemma_id_target WHERE word_engine_id = $word_engine_id";
  $res = $conn->query($sql);
}

$conn->close();
    
?>
