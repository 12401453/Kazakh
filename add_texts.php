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
$sql = "SET NAMES UTF8";
$res = $conn->query($sql);
?>

<div id="new_text">
<div class="link-buttons" onclick="texts_link()">View texts</div><br>

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
  

  <div><input type="submit" class="deletion_button" value="Delete a text" onclick="showDeletion()"></div>
  <div id="deletion" style="visibility: hidden">
  <div id="delete_button">
  <label for="textselect">Text to delete:</label>
  <select id="textselect" name="textselect" onclick="deleteText()">
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
  <input type="submit" id="cleartext_button" class="deletion_button" style="margin-bottom: 12px;" value="Clear all texts" onclick="clearTable()"></button><br>
</div>
</div>


<script src ="functions.js"></script>

<span id="spoofspan" style="display: inline;"></span>

</body>
</html>
<script>
  const textselector = document.getElementById('textselect');

textselector.addEventListener('change', event => {
      let dummyselect = document.createElement('select');
      let dummyoption = document.createElement('option');

      dummyoption.textContent = event.target.options[event.target.selectedIndex].text;  //event.target is the <select> element; .options is a list of its options
     // dummyselect.style.cssText += "visibility: hidden; position: fixed; font-family: Calibri; font-size: 18px;";
      dummyselect.id = 'hidden_select';
      dummyselect.appendChild(dummyoption);
      event.target.after(dummyselect);//.after('') inserts stuff directly after the entire element, i.e. after its </> closing tag
      
      const dummywidth = dummyselect.getBoundingClientRect().width;
      event.target.style.width = `${dummywidth}px`;
      
      dummyselect.remove();
});
textselector.style.width = "100px";
</script>
