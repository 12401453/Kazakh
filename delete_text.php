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



$sql = "SELECT MAX(text_id) AS end_text FROM texts";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$end_text_id = $row["end_text"];

$bool_end_text = ($end_text_id == $text_id);

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


$dt_delete_SQL = "DELETE FROM display_text WHERE tokno >= $dt_start";
$ch_delete_SQL = "DELETE FROM chunks WHERE dt_start >= $dt_start";

$dt_mid_SQL = " AND tokno <= $dt_end";
$ch_mid_SQL = " AND dt_end <= $dt_end";
if($bool_end_text == true) {
  $dt_sql = $dt_delete_SQL;
  $ch_sql = $ch_delete_SQL;
}
else {
  $dt_sql = $dt_delete_SQL.$dt_mid_SQL;
  $ch_sql = $ch_delete_SQL.$ch_mid_SQL;
}

$sql = $dt_sql;
$res = $conn->query($sql);
$sql = $ch_sql;
$res = $conn->query($sql);

if($bool_end_text == true) {
  $sql = "INSERT INTO display_text (text_word) VALUES ('DELETE_BOUNDARY')";
  $res = $conn->query($sql);
  $sql = "INSERT INTO chunks (dt_start, dt_end) VALUES (NULL, NULL)";
  $res = $conn->query($sql);
}

$sql = "DELETE FROM texts WHERE text_id = $text_id";
$res = $conn->query($sql);

?>