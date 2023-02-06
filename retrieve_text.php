<?php

$text_id = '';
if(isset($_POST['textselect'])) {
  $text_id = $_POST['textselect'];
  setcookie("text_id", $text_id);
}
else {$text_id = 0;}
if ($text_id == 0) {
  echo "<br><br>";
  exit(0); 
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

echo '<h1 id="title">'.$text_title.'</h1><br><div id="textbody">';



$sql = "SELECT chunk_id FROM chunks WHERE dt_start = $dt_start";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$chunk_start = $row["chunk_id"];

$chunk_start_minus_1 = $chunk_start - 1;

$sql = "SELECT chunk_id FROM chunks WHERE dt_end = $dt_end";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$chunk_end = $row["chunk_id"];

$chunk_end_plus1 = $chunk_end + 1;

$length = $chunk_end_plus1 - $chunk_start;
$words_per_page = 750;
$words_per_page_minus_1 = $words_per_page - 1;
$pg1_chunk_end = $length <= $words_per_page ? $chunk_end : $chunk_start + $words_per_page - 1;

$sql = "SELECT dt_end FROM chunks WHERE chunk_id = $pg1_chunk_end";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$pg1_dt_end = $row["dt_end"];

$sql = "SELECT * FROM display_text WHERE tokno >= $dt_start AND tokno <= $pg1_dt_end";
$result = $conn->query($sql);

$sql = "SELECT dt_start, dt_end, chunk_id FROM chunks WHERE dt_start >= $dt_start AND dt_end <= $dt_end";
$res_chunk = $conn->query($sql);
$row_chunk = $res_chunk->fetch_assoc();

$word_count = 0;


if ($result->num_rows > 0) {

  $just_done_lb = false;

  echo '<span>&emsp;</span>';
    while($row = $result->fetch_assoc()) {
      $text_word = $row["text_word"];
      $tokno = $row["tokno"];
      $line_break = $row["line_break"];
      $word_engine_id = $row["word_engine_id"];

      $bool_wordeng_id_null = is_null($word_engine_id);
    //  $bool_lemma_id_null = true;

      $outer_tt_span = '';

      if($bool_wordeng_id_null == false) {
        $lemma_id = $row["lemma_id"];
        $multiword_id = $row["multiword_id"];
        
        $sql = "SELECT first_lemma_id FROM word_engine WHERE word_engine_id = $word_engine_id";
        $res = $conn->query($sql);
        $row2 = $res->fetch_assoc();
        $first_lemma_id = $row2["first_lemma_id"];
        
        //if-statements are cheaper than PHP's dogshit slow string concatenation
        if(is_null($multiword_id)) {
          if(is_null($first_lemma_id)) $outer_tt_span = "<span data-word_engine_id=\"$word_engine_id\" data-tokno=\"$tokno\" class=\"tooltip\">";
          else if(is_null($lemma_id)) $outer_tt_span = "<span data-word_engine_id=\"$word_engine_id\" data-tokno=\"$tokno\" class=\"tooltip lemma_set_unexplicit\">";
          else $outer_tt_span = "<span data-word_engine_id=\"$word_engine_id\" data-tokno=\"$tokno\" class=\"tooltip lemma_set_unexplicit lemma_set\">";
        }
        else {
          $multiword_count = $row["multiword_count"];
          if(is_null($first_lemma_id)) $outer_tt_span = "<span data-word_engine_id=\"$word_engine_id\" data-tokno=\"$tokno\" class=\"tooltip multiword\" data-multiword=\"$multiword_count\">";
          else if(is_null($lemma_id)) $outer_tt_span = "<span data-word_engine_id=\"$word_engine_id\" data-tokno=\"$tokno\" class=\"tooltip lemma_set_unexplicit multiword\" data-multiword=\"$multiword_count\">";
          else $outer_tt_span = "<span data-word_engine_id=\"$word_engine_id\" data-tokno=\"$tokno\" class=\"tooltip lemma_set_unexplicit lemma_set multiword\" data-multiword=\"$multiword_count\">";
        }
      
        /*
        if(is_null($lemma_id) == false) {
          $outer_tt_span = '<span class="tooltip lemma_set_unexplicit lemma_set" data-word_engine_id="'.$word_engine_id.'" data-tokno="'.$tokno.'">';
        }
        else if(is_null($first_lemma_id) == false) {
          $outer_tt_span = '<span class="tooltip lemma_set_unexplicit" data-word_engine_id="'.$word_engine_id.'" data-tokno="'.$tokno.'">';
        }
        else {
          $outer_tt_span = '<span class="tooltip" data-word_engine_id="'.$word_engine_id.'" data-tokno="'.$tokno.'">';
        }*/
      }

     // if($line_break == 2) { echo '<br>'; }
      if($line_break == 2 && $just_done_lb == false) { echo '<br><br>'; $just_done_lb = true; }
      else if($line_break == 2) { $just_done_lb = false; }
      if($just_done_lb == true && $line_break != 2) {$just_done_lb = false; } 
      if($line_break == 3) {echo '  '; }
      if($row_chunk["dt_start"] == $tokno) { echo '<span class="chunk">';}
          
      if($bool_wordeng_id_null == false) {
        echo $outer_tt_span;        
      }
      echo $text_word;

      if($bool_wordeng_id_null == false) { 
        echo '</span>';
      }
      if($row_chunk["dt_end"] == $tokno) { echo '</span> ';  $row_chunk = $res_chunk->fetch_assoc(); $word_count++;}  //the space is important
  
    } 
    
    if($length > $words_per_page) {echo '</span>';}
   // echo '<div id="wordcount"><br>*****WORD COUNT: '.$word_count.'*******</div>';
    echo '</div>';
    if($length > $words_per_page) {
      echo '<br><div id="pagenos">';
      $page_nos = ceil(($length/$words_per_page));
      for($i = $page_nos; $i > 1; $i--) {
        $page_cur = /*0-$i+1+$page_nos;*/ 1+ $page_nos- $i;
        echo '<span class="pageno" onclick="selectText_splitup(';
        echo $chunk_start;
        echo ', ';
        echo $chunk_start+$words_per_page_minus_1;
        echo ', '.$page_nos.', '.$page_cur;
        echo ')">';
        echo $page_cur.'</span>';
        $chunk_start+=$words_per_page;
      }
      echo '<span class="pageno" onclick="selectText_splitup('.$chunk_start.', '.$chunk_end.', '.$page_nos.', '.$page_nos;
      echo ')">';
      echo $page_nos.'</span></div>';

    }

    } else {
      echo "";
    }
    $conn->close();
    
    ?>
