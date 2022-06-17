<?php

include 'db_details_web.php';

$conn = new mysqli("localhost", "joe", "password", "progressBar");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM progress_bar";
$res = $conn->query($sql);
$row = $res->fetch_assoc();

echo $row["word_num"];

$conn->close();

?>