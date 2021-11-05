

<?php


$new_text = '';
if(isset($_POST['new_text'])) {
  $new_text = $_POST['new_text'];

}


include 'db_details_web.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


$sql = "SET NAMES UTF8";
$res = $conn->query($sql);

$word = strtok($new_text, " ");
$regexp = "/[-!?\n\r\t,.«»:;–\"'\[)\](]/u"; //the 'u' modifier is needed to force UTF-8 encoding and prevent multibyte fuckery where cyrillic characters can consist partly of the hex-value of characters in the regex 

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

        $engine_word = mb_strtolower($text_word);

        $sql = "INSERT IGNORE INTO word_engine (word) VALUES ('$engine_word')";
        $result = $conn->query($sql);

        $sql = "SELECT word_id FROM word_engine WHERE word = '$engine_word'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $word_engine_id = $row["word_id"];

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
      
      $sql = "INSERT INTO display_text (text_word, line_break) VALUES ('$punct', $line_break)";
      $result = $conn->query($sql);


      if($c == $arr_size_minus_1 - 1 && $arr[$arr_size_minus_1] != "") {
        $c++;
        $text_word = $arr[$c];
        $engine_word = mb_strtolower($text_word);
        
        $sql = "INSERT IGNORE INTO word_engine (word) VALUES ('$engine_word')";
        $result = $conn->query($sql);

        $sql = "SELECT word_id FROM word_engine WHERE word = '$engine_word'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $word_engine_id = $row["word_id"];

        $sql = "INSERT INTO display_text (text_word, line_break, word_engine_id) VALUES ('$text_word', 0, $word_engine_id)";
        $result = $conn->query($sql);     

      }

      $line_break = 0;

    }

  }

  else {

    $engine_word = mb_strtolower($word);

    $sql = "INSERT IGNORE INTO word_engine (word) VALUES ('$engine_word')";
    $result = $conn->query($sql);

    $sql = "SELECT word_id FROM word_engine WHERE word = '$engine_word'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $word_engine_id = $row["word_id"];

    $sql = "INSERT INTO display_text (text_word, line_break, word_engine_id) VALUES ('$word', 1, $word_engine_id)";
    $result = $conn->query($sql);

  }
  
  $word = strtok(" ");
}

$conn->close(); 

?>
