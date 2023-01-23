<?php

$word_engine_id = 0;
if(isset($_POST['word_engine_id'])) {
  $word_engine_id = $_POST['word_engine_id'];
}


$tokno_current = 0;
if(isset($_POST['tokno_current'])) {
  $tokno_current = $_POST['tokno_current'];
}

$lang_id = 1;
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

$sql = "SELECT word, first_lemma_id FROM word_engine WHERE word_engine_id = $word_engine_id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$word = $row["word"];

$first_lemma_id = $row["first_lemma_id"];

$sql = "SELECT lemma_id FROM display_text WHERE tokno = $tokno_current";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$lemma_id = $row["lemma_id"];

$lemma_meaning_no = NULL;

if(is_null($lemma_id)) {
  
  if(is_null($first_lemma_id)) {
    $lemma_tag_content = $word;
    
    //this should be rewritten to match logic of C++ version and also loop through eng_transes until it finds a non-empty one
    $sql = "SELECT eng_trans1, pos FROM lemmas WHERE lemma = '$word' AND lang_id = $lang_id";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    $lemma_textarea_content = is_null($row['eng_trans1']) ? "" : $row['eng_trans1'];
    $pos = $row['pos'];
    if(is_null($pos)) {
      $lemma_textarea_content = "";
      $pos = 1;
    }
  }
  else {
    $sql = "SELECT lemma, eng_trans1, pos FROM lemmas WHERE lemma_id = $first_lemma_id";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    $lemma_tag_content = $row["lemma"];
    $lemma_textarea_content = is_null($row["eng_trans1"]) ? "" : $row["eng_trans1"];
    $pos = $row["pos"];
  }
  
  $lemma_id = $first_lemma_id; //sets the JS lemma_id variable to equal the default lemma_id for words not-yet manually annotated so that the switchMeaningsAJAX() function works
}
else {
  
  $sql = "SELECT lemma_meaning_no FROM display_text WHERE tokno = $tokno_current";
  $res = $conn->query($sql);
  $row = $res->fetch_assoc();
  $lemma_meaning_no = $row["lemma_meaning_no"];

  $sql = "SELECT lemma, pos FROM lemmas WHERE lemma_id = $lemma_id";
  $res = $conn->query($sql);
  $row = $res->fetch_assoc();
  $lemma_tag_content = $row["lemma"];
  $pos = $row["pos"];
  
  if(is_null($lemma_meaning_no)) {
    $eng_trans_selector = "eng_trans1";
    
  }
  else {
    $eng_trans_selector = "eng_trans".$lemma_meaning_no;
  }
  $sql_eng_trans = "SELECT $eng_trans_selector FROM lemmas WHERE lemma_id = $lemma_id";
  $res = $conn->query($sql_eng_trans);
  $row = $res->fetch_assoc();
  $lemma_textarea_content = $row[$eng_trans_selector];
  if(is_null($lemma_textarea_content)) {
    $lemma_textarea_content = "";
  }
}



$json_response = json_encode(array("lemma_tag_content" => $lemma_tag_content, "lemma_textarea_content" => $lemma_textarea_content, "lemma_meaning_no" => $lemma_meaning_no, "lemma_id" => $lemma_id, "pos" => $pos));
echo $json_response;

$conn->close();
    
?>
