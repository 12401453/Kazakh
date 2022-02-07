<!DOCTYPE html> 
<html>

<head>
<link rel="stylesheet" type="text/css" href="tooltip_edit.css" id="tt_styles">

  <link href="style.css" rel="stylesheet" media="all and (min-width: 769px)" type="text/css">
<link href="style_small_screen.css" rel="stylesheet" media="all and (max-width: 768px)" type="text/css">


<meta name="viewport" content="width=device-width, initial-scale=1">


 <?php // include 'style_small_screen.php'; ?>


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

<div id="select_button">
  <label for="textselect">Choose text:</label>
  <select id="textselect" name="textselect" onchange="selectText_js_tt()">
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
  </select></div><br><br><br>

<div id="whole_text">
  <?php include 'controls.php'; ?><br>
  
<p1 id="p1"><br><br>

</p1>
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

const showButtons = (event) => {
    if(event.target.className == 'tooltip') { 
      let display_word = event.target.innerHTML;

      event.target.innerHTML = display_word+'<span class="tooltiptext5" id="popup"><input type="submit" class="tooltip_opt" value="Edit" id="editbtn"><input type="submit" class="tooltip_opt" value="Ignore" id="delbtn"></span>';
    console.log(event.target.id);

    document.getElementById('popup').addEventListener('mouseout', hideButtons);
    event.target.addEventListener('mouseout', hideButtons);



    }
} 

const hideButtons = (event) => {
    console.log("left");

    let popup = document.getElementById('popup');
    popup.remove();
  /*
  if(event.target.className == 'tooltip') { 
    console.log('left');
    let display_word = event.target.innerHTML;
    
    
    display_word = display_word.replace('<span class="tooltiptext5" id="popup"><input type="submit" class="tooltip_opt" value="Edit" id="editbtn"><input type="submit" class="tooltip_opt" value="Ignore" id="delbtn"></span>', '');
    event.target.innerHTML = display_word;
    console.log(display_word);
  } */
} 

window.addEventListener('mouseover', showButtons);
</script>
