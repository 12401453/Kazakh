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

$sql = "SELECT dt_start, dt_end, text_title FROM texts WHERE text_id = $text_id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();

$dt_start = $row["dt_start"];
$dt_end = $row["dt_end"];
$text_title = $row["text_title"];
echo '<h1 id="title">'.$text_title.'</h1><div id="textbody">';

$sql = "SELECT * FROM display_text WHERE tokno > $dt_start AND tokno < $dt_end";
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    echo '<span>&emsp;</span>';
      while($row = $result->fetch_assoc()) {
        $text_word = $row["text_word"];
        $tokno = $row["tokno"];
        $line_break = $row["line_break"];
        $word_engine_id = $row["word_engine_id"];
     
       
    
    if($line_break == 2) { echo '<br>'; }
    if($line_break == 3) {echo '  '; }
    if($line_break == 1) { echo '</span> <span class="chunk">';} //the space is important

    if($word_engine_id != "") {echo '<span class="tooltip" id="word_engine_id_'.$word_engine_id.'">';}
    echo $text_word;
    if($word_engine_id != "") { 
  
      echo '</span>';
    }

    
    }
    echo '</div>';
    } else {
      echo "";
    }
    $conn->close();

    ?>
