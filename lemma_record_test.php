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
if($lemma_meaning == "" || $lemma_meaning == "null") {
  $lemma_meaning = "NULL";
}
else {
  $lemma_meaning = "'$lemma_meaning'";
}
//ensure that SQL strings do not contain quoted '$lemma_meaning', as otherwise NULL will be input as a string instead of as NULL


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

$sql = "INSERT IGNORE INTO lemmas (lemma, lang_id, pos) VALUES ('$lemma_form', $lang_id, $pos)";
$res = $conn->query($sql);

$sql = "SELECT lemma_id FROM lemmas WHERE lemma = '$lemma_form' AND lang_id = $lang_id AND pos = $pos";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$lemma_id_target = $row["lemma_id"];

 //this will update all the meanings that had been touched by the frontend before the lemma was submitted
$sql = "UPDATE lemmas SET ".$eng_trans_sql_string." = $lemma_meaning WHERE lemma_id = $lemma_id_target";
$res = $conn->query($sql);

if(is_null($lemma_id_current)) {

  //this is about updating which lemma_meaning_no is assigned to this particular display_word so should only run for the clicked_lemma_meaning_no
  if($lemma_meaning_no == $clicked_lemma_meaning_no) {
    $sql = "UPDATE display_text SET lemma_id = $lemma_id_target, lemma_meaning_no = $clicked_lemma_meaning_no WHERE tokno = $tokno_current";
    $res = $conn->query($sql);
  } 
}
else {
  //if this word already has an assigned lemma but we want to change it to a lemma which IS already in the lemmas table
  if($lemma_id_target != $lemma_id_current) {
    if($lemma_meaning_no == $clicked_lemma_meaning_no) {
      $sql = "UPDATE display_text SET lemma_id = $lemma_id_target, lemma_meaning_no = $clicked_lemma_meaning_no WHERE tokno = $tokno_current";
      $res = $conn->query($sql);
    }
  }
  else {
    if($lemma_meaning_no == $clicked_lemma_meaning_no) {
      $sql = "UPDATE display_text SET lemma_meaning_no = $clicked_lemma_meaning_no WHERE tokno = $tokno_current";
      $res = $conn->query($sql);
    } 
  }
  
}

if(is_null($first_lemma_id)) {
  $sql = "UPDATE word_engine SET first_lemma_id = $lemma_id_target WHERE word_engine_id = $word_engine_id";
  $res = $conn->query($sql);
}

$conn->close();
    
?>
