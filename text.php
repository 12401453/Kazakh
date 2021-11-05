<!DOCTYPE html> 
<html>

<head>
  <link rel="stylesheet" type="text/css" href="tooltip_eng_style_2.css" id="tt_styles">


<meta name="viewport" content="width=device-width, initial-scale=1">


 <?php include 'style.php'; ?>


</head>


<body style="background-color: #071022;">
  <br>

<div id="main_text">
  <?php include 'controls.php'; ?>
  <br><br><br>
<p1><br>
<br>



<?php

include 'db_details_web.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


// $res = mysql_query("SET NAMES UTF8");

$sql = "SET NAMES UTF8";
$res = $conn->query($sql);

$sql = "SELECT * FROM display_text WHERE tokno > 0 AND tokno < 5000";
$result = $conn->query($sql);

include 'get.php';
?>

</p1>
</div>

<div id="new_text">
<form method ="POST" action="javascript:void(0)" id="formid" accept-charset="utf-8">
  <textarea id="newtext" form="formid" name="new_text"></textarea>
  <br>
  <input type="submit" class="submit_btn" value="Add text" id="add_btn" onclick="loadText()">
  </form>
  <input type="submit" class="submit_btn" value="Clear text" onclick="clearTable()"></button>

</div>


<script src ="functions.js"></script>

<span id="spoofspan" style="display: inline;"></span>

</body>
</html>
