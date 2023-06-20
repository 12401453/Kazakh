<!DOCTYPE html> 
<html>

<head>
<link rel="stylesheet" type="text/css" href="tooltip_prevs.css" id="tt_styles">
<link rel="stylesheet" type="text/css" href="flexbox.css" id="flexbox_styles">

  <link href="style.css" rel="stylesheet" media="all and (min-width: 769px)" type="text/css">
<link href="style_small_screen.css" rel="stylesheet" media="all and (max-width: 768px)" type="text/css">


<meta name="viewport" content="width=device-width, initial-scale=1">




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
    
<div class="link-buttons" onclick="add_text_link()">Add/remove texts</div><br>

<div id="select_button">
  <label for="textselect">Choose text:</label>
  <select id="textselect" name="textselect" onchange="selectText()" style="width: 100px;">
  <option value="0"></option>
  <?php 

  if(isset($_COOKIE["text_id"])) {
    $prev_text_id = $_COOKIE["text_id"];
  }
  else {
    $prev_text_id = 0;
  }
  

  $sql = "SELECT * FROM texts";
  $res = $conn->query($sql);

  if ($res->num_rows > 0) {

      while($row = $res->fetch_assoc()) {

      echo '<option value="'.$row["text_id"].'">'.$row["text_title"].'</option>';
      }
  }    

?>
  </select></div>
  <br><br><br>


  <?php include 'controls.php'; ?><br>
  
<p1 id="p1">
  <?php 
  if($prev_text_id == 0) {
    echo '<br><br>';
  }
  else {
    include 'voidRetrieveText.php';
  }
  ?>
</p1>

</div>


<script src ="functions.js"></script>

<span id="spoofspan" style="display: inline;"></span>
<span id="spoofspan2"><span id="hidden_lemma_tag"></span></span>

</body>
</html>

<script>
  /*
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
textselector.style.width = "100px"; */

let cookie_textselect = <?php echo $prev_text_id; ?>

const textselector = document.getElementById('textselect');
const textselect_resize = (event) => {
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
};

textselector.addEventListener('change', textselect_resize);
textselector.addEventListener('cookie_selection', textselect_resize);

window.onload = () => {    
    if(cookie_textselect) {
      let textselect = document.getElementById("textselect");
      let text_options = textselect.options;
      let textid_deleted = true;
      for(let i = 0; i < text_options.length; i++) {
        if(text_options[i].value == cookie_textselect){
            textid_deleted = false;
            text_options[i].selected = true;
            textselect.dispatchEvent(new Event('cookie_selection'));
            setLangId();
            break;
        }
      }
      if(textid_deleted == false) {
        let pagenos = document.querySelectorAll('.pageno');
        pagenos.forEach(pageno => {
          if(Number(pageno.innerHTML) == 1) {
            pageno.classList.add("current_pageno");
          }
        });

        let tt_btns = document.querySelectorAll('.tooltip');

        tt_btns.forEach(tt_btn => {
          tt_btn.onclick = showAnnotate;
        });
        document.querySelectorAll('.multiword').forEach(mw_btn => {
            mw_btn.onclick = showMultiwordAnnotate;
        });
      }
      else console.log("cookie text has been deleted by another client");   
    }
    else console.log("no text_id cookie");
};
</script>
