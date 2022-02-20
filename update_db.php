

<?php

function utf8_substr_replace($original, $replacement, $position, $length)
{
    $startString = mb_substr($original, 0, $position, "UTF-8");
    $endString = mb_substr($original, $position + $length, mb_strlen($original), "UTF-8");

    $out = $startString . $replacement . $endString;

    return $out;
}

function mb_strtolower_Turkish ($string) {

  while(gettype(mb_strpos($string, 'İ')) != "boolean") {
    $big_i_pos = mb_strpos($string, 'İ');
    $string = utf8_substr_replace($string, 'i', $big_i_pos, 1);
  }

   while(gettype(mb_strpos($string, 'I')) != "boolean") {
    $I_pos = mb_strpos($string, 'I');
    $string = utf8_substr_replace($string, 'ı', $I_pos, 1);
  }

  return mb_strtolower($string, 'UTF-8');
}

function downCase($string, $lang_id) {
  if($lang_id == 7) {
       return mb_strtolower_Turkish($string);
  }
  else return mb_strtolower($string, 'UTF-8');

}


$new_text = '';
if(isset($_POST['new_text'])) {
  $new_text = $_POST['new_text'];

}

//$new_text = addslashes($new_text);


$text_title = '';
if(isset($_POST['text_title'])) {
  $text_title = $_POST['text_title'];

}

$lang_id = '';
if(isset($_POST['langselect'])) {
  $lang_id = $_POST['langselect'];

}


include 'db_details_web.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$dt_start = 0;

$sql = "SET NAMES UTF8";
$res = $conn->query($sql);

$sql = "SELECT COUNT(*) AS dt_start FROM display_text";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$dt_start = $row["dt_start"];




$word = strtok($new_text, " ");


$regexp = "/[-'$%+=~#@><}{_!”“„?\n\r\t,.&^«»:;–\"\[)\](]/u"; //the 'u' modifier is needed to force UTF-8 encoding and prevent multibyte fuckery where cyrillic characters can consist partly of the hex-value of characters in the regex

while($word != false) {

  if( preg_match_all($regexp, $word, $arr_punct) ) {

    $arr =  preg_split($regexp, $word);
    $arr_size = count($arr);
    $arr_size_minus_1 = $arr_size - 1;

    $line_break = 1;
    
    for($c = 0; $c < $arr_size_minus_1; $c++) {
      
      $text_word = $arr[$c];
      $punct = $arr_punct[0][$c];
      
      
      if($text_word != "") {

        $engine_word = downCase($text_word, $lang_id);

        $sql = "INSERT IGNORE INTO word_engine (word, lang_id) VALUES ('$engine_word', '$lang_id')";
        $result = $conn->query($sql);

        $sql = "SELECT word_engine_id FROM word_engine WHERE word = '$engine_word'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $word_engine_id = $row["word_engine_id"];

        $sql = "INSERT INTO display_text (text_word, line_break, word_engine_id) VALUES ('$text_word', $line_break, $word_engine_id)";
        $result = $conn->query($sql);

      }

      $line_break = 0;

      if($punct == "\n" || $punct == "\r") {
        $line_break = 2;
      }
      if($punct == "\t") {
        $line_break = 3;
      }

      if($arr[0] == "" && $c == 0) {
        $line_break = 1;
      }  
      
      if($punct == "'") {$punct = "\'";}

      $sql = "INSERT INTO display_text (text_word, line_break) VALUES ('$punct', $line_break)";
      $result = $conn->query($sql);


      if($c == $arr_size_minus_1 - 1 && $arr[$arr_size_minus_1] != "") {
        $c++;
        $text_word = $arr[$c];
        $engine_word = downCase($text_word, $lang_id);
        
        $sql = "INSERT IGNORE INTO word_engine (word, lang_id) VALUES ('$engine_word', '$lang_id')";
        $result = $conn->query($sql);

        $sql = "SELECT word_engine_id FROM word_engine WHERE word = '$engine_word'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $word_engine_id = $row["word_engine_id"];

        $sql = "INSERT INTO display_text (text_word, line_break, word_engine_id) VALUES ('$text_word', 0, $word_engine_id)";
        $result = $conn->query($sql);     

      }

      $line_break = 0;

    }

  }

  else {

    $engine_word = downCase($word, $lang_id);

    $sql = "INSERT IGNORE INTO word_engine (word, lang_id) VALUES ('$engine_word', '$lang_id')";
    $result = $conn->query($sql);

    $sql = "SELECT word_engine_id FROM word_engine WHERE word = '$engine_word'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $word_engine_id = $row["word_engine_id"];

    $sql = "INSERT INTO display_text (text_word, line_break, word_engine_id) VALUES ('$word', 1, $word_engine_id)";
    $result = $conn->query($sql);

  }
  
  $word = strtok(" ");
}

$sql = "SELECT COUNT(*) AS dt_end FROM display_text";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$dt_end = $row["dt_end"];
$dt_end++;

$sql = "INSERT INTO texts (text_title, dt_start, dt_end, lang_id) VALUES ('$text_title', '$dt_start', '$dt_end', '$lang_id')";
$res = $conn->query($sql);

$conn->close(); 

?>
