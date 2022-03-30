<?php

$text_id = '';
if(isset($_POST['textselect'])) {
  $text_id = $_POST['textselect'];
}

$chunk_start = '';
if(isset($_POST['dt_start'])) {
  $chunk_start = $_POST['dt_start'];
}

$chunk_end = '';
if(isset($_POST['dt_end'])) {
  $chunk_end = $_POST['dt_end'];
}





include 'db_details_web.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


$sql = "SET NAMES UTF8";
$res = $conn->query($sql);

$sql = "SELECT dt_start FROM chunks WHERE chunk_id = $chunk_start";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$dt_start = $row["dt_start"];

$sql = "SELECT dt_end FROM chunks WHERE chunk_id = $chunk_end + 1";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$dt_end = $row["dt_end"];

$sql = "SELECT * FROM display_text WHERE tokno >= $dt_start AND tokno < $dt_end";
$result = $conn->query($sql);

$sql = "SELECT dt_start, dt_end, chunk_id FROM chunks WHERE dt_start >= $dt_start AND dt_end < $dt_end";
$res_chunk = $conn->query($sql);
$row_chunk = $res_chunk->fetch_assoc();

$word_count = 0;


if ($result->num_rows > 0) {
  echo '<span>&emsp;</span>';
    while($row = $result->fetch_assoc()) {
      $text_word = $row["text_word"];
      $tokno = $row["tokno"];
      $line_break = $row["line_break"];
      $word_engine_id = $row["word_engine_id"];
   
     


      if($line_break == 2) { echo '<br>'; }
      if($line_break == 3) {echo '  '; }
      if($row_chunk["dt_start"] == $tokno) { echo '<span class="chunk">';}
          
      if($word_engine_id != "") {echo '<span class="tooltip" id="word_engine_id_'.$word_engine_id.'">';}
      echo $text_word;
      if($word_engine_id != "") { 
      
        echo '<span class="tooltiptext5">'.'<input type="submit" class="tooltip_opt" value="Edit" id="editbtn"><input type="submit" class="tooltip_opt" value="Ignore" id="delbtn">'.'</span></span>';
      }
      if($row_chunk["dt_end"] == $tokno) { echo '</span> ';  $row_chunk = $res_chunk->fetch_assoc(); $word_count++;}  //the space is important
  
  } 
    echo '</div>';
    




    } else {
      echo "";
    }
    $conn->close();

    ?>
