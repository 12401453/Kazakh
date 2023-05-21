<?php

$multiword_id = 0;
if(isset($_POST["multiword_id"])) {
    $multiword_id = $_POST["multiword_id"];
}
$multiword_meaning_no = 0;
if(isset($_POST["multiword_meaning_no"])) {
    $multiword_meaning_no = $_POST["multiword_meaning_no"];
}
$mw_meaning = null;
if(isset($_POST["mw_meaning"])) {
    $mw_meaning = addslashes($_POST["mw_meaning"]);
}
if($mw_meaning == "") {
    $mw_meaning = null;
}

include 'db_details_web.php';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$sql = "SET NAMES UTF8";
$res = $conn->query($sql);

$sql_stmt = $conn->prepare("UPDATE multiword_lemmas SET eng_trans".$multiword_meaning_no." = ? WHERE multiword_id = ?");
$sql_stmt->bind_param("si", $mw_meaning, $multiword_id);
$sql_stmt->execute();
$sql_stmt->close();

$conn->close();
?>