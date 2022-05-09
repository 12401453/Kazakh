<?php

  $text_id = '';
  if(isset($_POST['textselect'])) {
    $text_id = $_POST['textselect'];
  }
  else {$text_id = 0;}
  if ($text_id == 0) {
    echo "<br><br>";
    exit(0); 
  }

  include 'db_details_web.php';

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }


  $sql = "SET NAMES UTF8";
  $res = $conn->query($sql);

  $sql = "SELECT lang_id FROM texts WHERE text_id = $text_id";
  $res = $conn->query($sql);
  $row = $res->fetch_assoc();

  $lang_id = $row["lang_id"];

  echo $lang_id;


  $conn->close();
    
?>
