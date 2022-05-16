<?php

if(isset($_POST['lemma_form'])) {
  $lemma_form = addslashes($_POST['lemma_form']);
}

if(isset($_POST['lemma_meaning_no'])) {
  $lemma_meaning_no = $_POST['lemma_meaning_no'];
}

if(isset($_POST['lang_id'])) {
  $lang_id = $_POST['lang_id'];
}

if(isset($_POST['pos'])) {
  $pos = $_POST['pos'];
}

include 'db_details_web.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SET NAMES UTF8";
$res = $conn->query($sql);

$eng_trans_selector = "eng_trans".$lemma_meaning_no;

$sql = "SELECT $eng_trans_selector, lemma_id FROM lemmas WHERE lemma = '$lemma_form' AND pos = $pos AND lang_id = $lang_id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$lemma_textarea_content = $row[$eng_trans_selector];
$lemma_id = $row["lemma_id"];

$json_response = json_encode(array("lemma_textarea_content" => $lemma_textarea_content, "lemma_id" => $lemma_id)); //remove htmlentites() from textarea_content because it is being set with the textarea.value property instead of as innerHTML
echo $json_response;


$conn->close();
    
?>
