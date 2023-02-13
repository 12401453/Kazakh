<?php

if(isset($_POST['multiword_meaning_no'])) {
  $multiword_meaning_no = $_POST['multiword_meaning_no'];
}

if(isset($_POST['multiword_id'])) {
  $multiword_id = $_POST['multiword_id'];
}
/*
if(isset($_POST['pos'])) {
  $pos = $_POST['pos'];
} */

include 'db_details_web.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SET NAMES UTF8";
$res = $conn->query($sql);

$eng_trans_selector = "eng_trans".$multiword_meaning_no;

$sql = "SELECT $eng_trans_selector FROM multiword_lemmas WHERE multiword_id = $multiword_id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$lemma_textarea_content = $row[$eng_trans_selector];
if(is_null($lemma_textarea_content)) $lemma_textarea_content = "";

//$json_response = json_encode(array("lemma_textarea_content" => $lemma_textarea_content, "lemma_meaning_no" => $lemma_meaning_no)); //remove htmlentites() from textarea_content because it is being set with the textarea.value property instead of as innerHTML
//echo $json_response;
echo trim($lemma_textarea_content);
$conn->close();
    
?>
