<!DOCTYPE html> 
<html>

<head>
  <link rel="stylesheet" type="text/css" href="tooltip_edit.css" id="tt_styles">


<meta name="viewport" content="width=device-width, initial-scale=1">


 <?php include 'style.php'; ?>


</head>
<?php
include 'db_details_web.php';


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$sql = "SET NAMES UTF8";
$res = $conn->query($sql);
?>

<body style="background-color: #071022;">
  <br>

<div id="main_text">
<div id="control_buttons">
<div id="select_button">
  <label for="textselect">Choose text:</label>
  <select id="textselect" name="textselect" onchange="selectText()">
  <option value="0"></option>
  <?php 

  $sql = "SELECT * FROM texts";
  $res = $conn->query($sql);

  if ($res->num_rows > 0) {

      while($row = $res->fetch_assoc()) {

      echo '<option value="'.$row["text_id"].'">'.$row["text_title"].'</option>';
      }
  }    

?>
  </select></div>


  <?php include 'controls.php'; ?></div>
  <br><br><br>
<p1 id="p1">

</p1>
</div>


<script src ="functions.js"></script>

<span id="spoofspan" style="display: inline;"></span>

</body>
</html>
