<?php

if(isset($_POST['lemma_meaning_no'])) {
  $lemma_meaning_no = $_POST['lemma_meaning_no'];
}

if(isset($_POST['lemma_id'])) {
  $lemma_id = $_POST['lemma_id'];
}

include 'db_details_web.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SET NAMES UTF8";
$res = $conn->query($sql);

$eng_trans_selector = "eng_trans".$lemma_meaning_no;

$sql = "SELECT $eng_trans_selector FROM lemmas WHERE lemma_id = $lemma_id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$lemma_textarea_content = $row[$eng_trans_selector];

$json_response = json_encode(array("lemma_textarea_content" => htmlentities($lemma_textarea_content), "lemma_meaning_no" => $lemma_meaning_no));
echo $json_response;

$conn->close();
    
?>
