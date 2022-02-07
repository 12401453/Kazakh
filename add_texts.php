<!DOCTYPE html> 
<html>

<head>
  <link rel="stylesheet" type="text/css" href="tooltip_eng_style_2.css" id="tt_styles">
  <link href="style.css" rel="stylesheet" media="all and (min-width: 769px)" type="text/css">
<link href="style_small_screen.css" rel="stylesheet" media="all and (max-width: 768px)" type="text/css">

<meta name="viewport" content="width=device-width, initial-scale=1">


 <?php include 'style.php'; ?>


</head>


<body style="background-color: #071022;">
  <br>
<?php

include 'db_details_web.php';


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>

<div id="new_text">
<form action="javascript:void(0)" id="formid" name="addtextform" accept-charset="utf-8">
<span id="lang_button">
  <label for="langselect">Language:</label>
  <select id="langselect" name="langselect" form="formid">
  <?php 

  $sql = "SELECT * FROM languages";
  $res = $conn->query($sql);

  if ($res->num_rows > 0) {

      while($row = $res->fetch_assoc()) {

      echo '<option value="'.$row["lang_id"].'">'.$row["lang_name"].'</option>';
      }
  }    

?>  
  </select></span><br><br><br>





<textarea id="text_title" form="formid" name="text_title" placeholder="Text Title"></textarea><br><br>
  <textarea id="newtext" form="formid" name="new_text" placeholder="text body"></textarea>
  <br>
  <input type="submit" class="submit_btn" value="Add text" id="add_btn" onclick="loadText()">
  </form> 
  <br>
  <input type="submit" class="submit_btn" style="margin-bottom: 12px" value="Clear all texts" onclick="clearTable()"></button><br>

</div>


<script src ="functions.js"></script>

<span id="spoofspan" style="display: inline;"></span>

</body>
</html>
