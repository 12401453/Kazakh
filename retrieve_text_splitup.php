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

$page_cur = '';
if(isset($_POST['page_cur'])) {
  $page_cur = $_POST['page_cur'];
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

$sql = "SELECT dt_end FROM chunks WHERE chunk_id = $chunk_end";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$dt_end = $row["dt_end"];

$sql = "SELECT * FROM display_text WHERE tokno >= $dt_start AND tokno <= $dt_end";
$result = $conn->query($sql);

$sql = "SELECT dt_start, dt_end, chunk_id FROM chunks WHERE dt_start >= $dt_start AND dt_end <= $dt_end";
$res_chunk = $conn->query($sql);
$row_chunk = $res_chunk->fetch_assoc();

$word_count = 0;


if ($result->num_rows > 0) {
 $just_done_lb = false;
 if($page_cur == 1) echo '<span>&emsp;</span>';
    while($row = $result->fetch_assoc()) {
      $text_word = $row["text_word"];
      $tokno = $row["tokno"];
      $line_break = $row["line_break"];
      $word_engine_id = $row["word_engine_id"];
   
     
      $bool_wordeng_id_null = is_null($word_engine_id);

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
        } */
      }

    //  if($line_break == 2) { echo '<br>'; }
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

      if($row_chunk["dt_end"] == $tokno) { echo '</span> '; //the space is important
        $row_chunk = $res_chunk->fetch_assoc(); 
        $word_count++;
      }  
  
  } 
    // echo '<div id="wordcount"><br>*****WORD COUNT: '.$word_count.'*******</div>';
    echo '</div>';

    } else {
      echo "";
    }
    $conn->close();

    ?>
