<?php

$text_id = '';
if(isset($_POST['textselect'])) {
  $text_id = $_POST['textselect'];
}
else {$text_id = 0;}
if ($text_id == 0) { exit(0); }




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



$sql = "SELECT chunk_id FROM chunks WHERE dt_start = $dt_start + 1";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$chunk_start = $row["chunk_id"];

$sql = "SELECT chunk_id FROM chunks WHERE dt_end = $dt_end - 1";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$chunk_end = $row["chunk_id"];

$chunk_end_plus1 = $chunk_end + 1;


/*
$length = $dt_end - $dt_start;
$words_per_page = 750;
$pg1_dt_end = $length <= 750 ? $dt_end : $dt_start + $words_per_page; */

$length = $chunk_end - $chunk_start;
$words_per_page = 750;
$pg1_chunk_end = $length <= $words_per_page ? $chunk_end : $chunk_start + $words_per_page - 1;

$sql = "SELECT dt_end FROM chunks WHERE chunk_id = $pg1_chunk_end";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$pg1_dt_end = $row["dt_end"];

$sql = "SELECT * FROM display_text WHERE tokno > $dt_start AND tokno <= $pg1_dt_end";
$result = $conn->query($sql);

$sql = "SELECT dt_start, dt_end, chunk_id FROM chunks WHERE dt_start > $dt_start AND dt_end <= $dt_end";
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
    
    if($length > $words_per_page) {echo '</span>';}
    echo '<div id="wordcount"><br>*****WORD COUNT: '.$word_count.'*******</div></div>';
    if($length > $words_per_page) {
      echo '<br><div id="pagenos">';
      $page_nos = ceil(($length/$words_per_page));
      for($i = $page_nos; $i > 1; $i--) {
        $page_cur = /*0-$i+1+$page_nos;*/ 1+ $page_nos- $i;
        echo '<span class="pageno" onclick="selectText_splitup('.$chunk_start.', '.$chunk_start+$words_per_page;
        echo ', '.$page_nos.', '.$page_cur;
        echo ')">';
        echo $page_cur.'</span>';
        $chunk_start+=$words_per_page;
      }
      echo '<span class="pageno" onclick="selectText_splitup('.$chunk_start.', '.$chunk_end_plus1.', '.$page_nos.', '.$page_nos;
      echo ')">';
      echo $page_nos.'</span></div>';

    }

  

   // echo '<br><br>*****WORD COUNT: '.$word_count.'*******';

    } else {
      echo "";
    }
    $conn->close();
    
    ?>
