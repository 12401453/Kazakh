<?php

$text_id = '';
if(isset($_POST['textselect'])) {
  $text_id = $_POST['textselect'];
}




include 'db_details_web.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


$sql = "SET NAMES UTF8";
$res = $conn->query($sql);

$sql = "SELECT dt_start, dt_end FROM texts WHERE text_id = $text_id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$dt_start = $row["dt_start"];
$dt_end = $row["dt_end"];

/*
$sql = "SELECT chunk_id FROM chunks WHERE dt_start = $dt_start";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$chunk_start = $row["chunk_id"];

$sql = "SELECT chunk_id FROM chunks WHERE dt_end = $dt_end";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$chunk_end = $row["chunk_id"]; */ //the chunk_id stuff isn't necessary if you do it with just dt_end and dt_start I reckon

$sql = "DELETE FROM display_text WHERE tokno >= $dt_start AND tokno <= $dt_end";
$res = $conn->query($sql);

$sql = "DELETE FROM chunks WHERE dt_start >= $dt_start AND dt_end <= $dt_end";
$res = $conn->query($sql);



?>