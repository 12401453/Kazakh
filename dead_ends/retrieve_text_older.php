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
$length = $dt_end - $dt_start;
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
     
      echo '<span class="tooltiptext5">'.'<input type="submit" class="tooltip_opt" value="Edit" id="editbtn"><input type="submit" class="tooltip_opt" value="Ignore" id="delbtn">'.'</span></span>';
    }

    
    }
    echo '</div>';
    if($length > 1000) {
      echo '<div id="pagenos">';
      $page_nos = ceil(($length/1000));
      for($i = $page_nos; $i > 1; $i--) {
        echo '<span class="pageno" onclick="selectText_splitup('.$dt_start.', '.$dt_start+1001;
        echo ')">';
        echo 0-$i+1+$page_nos.'</span>';
        $dt_start+=1000;
      }
      echo '<span class="pageno" onclick="selectText_splitup('.$dt_start.', '.$dt_end;
      echo ')">';
      echo $page_nos.'</span></div>';

    }




    } else {
      echo "";
    }
    $conn->close();

    ?>
