

<?php

function mb_substr_replace($original, $replacement, $position, $length)
{
    $startString = mb_substr($original, 0, $position, "UTF-8");
    $endString = mb_substr($original, $position + $length, mb_strlen($original), "UTF-8");

    $out = $startString . $replacement . $endString;

    return $out;
}

$new_text = '';
if(isset($_POST['new_text'])) {
  $new_text = $_POST['new_text'];
 // echo 'set';
}






include 'db_details_web.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


// $res = mysql_query("SET NAMES UTF8");

$sql = "SET NAMES UTF8";
$res = $conn->query($sql);

//this gay approach needs to be replaced with a regex for acceptable characters, or better a regexp for unacceptable characters
//mb_ereg([a-zA-ZÀ-ÖØ-öø-ȳЀ-ӹ-әӘіІҢңғҒүҮұҰқҚөӨһҺ], );

$unwantedChars = array(',', '!', '?', '«', '»', '.', ':', ';', '\'', '"', '–');

$word = strtok($new_text, " ");

$i = 1;
while($word != false) {
  $tokno_before_explode = $i;
  $tokno_after_explode = 0;

  $word_stripped = mb_strtolower(str_replace($unwantedChars, '', $word));
 // echo "Word".$i.":  ".$word."\n";
 // echo "Word_stripped".$i.":  ".$word_stripped."\n";
 
  if(str_contains($word, "\n")) {
    $both_words = explode("\n", $word);
    $array_size = count($both_words);
    $array_size_minus_1 = $array_size - 1;
    
    $tokno_before_explode = $i;
    $tokno_after_explode = $i + $array_size_minus_1;
    
    for ($c = 0; $c < $array_size - 1; $c++) {
    $sql = "INSERT INTO display_text (text_word, line_break) VALUES ('$both_words[$c]', 1)";
    $result = $conn->query($sql);
    $i++;
      }
    $sql = "INSERT INTO display_text (text_word) VALUES ('$both_words[$array_size_minus_1]')";
    $result = $conn->query($sql);
    $i++;

  }
  else {
  $sql = "INSERT INTO display_text (text_word) VALUES ('$word')";
  $result = $conn->query($sql);
  $i++;
  }
  $word = strtok(" ");
  
 
   //add punctuation-less and case-less word to the word_engine
  if($word_stripped != "") {

    if(str_contains($word_stripped, "\n")) {           //deals with line-breaks, as the \n is not used to split up the words due to formatting issues, so we need to extract  e.g. 'ме' and 'қазақстанда' from a string which is originally 'ме\n\nқазақстанда'
      $both_words = explode("\n", $word_stripped);
      $array_size = count($both_words);
      $array_size_minus_1 = $array_size - 1;
      


      $sql = "INSERT IGNORE INTO word_engine (word) VALUES ('$both_words[0]')";
      $result = $conn->query($sql);
      $sql = "UPDATE display_text SET word_engine_id ='$both_words[0]' WHERE tokno = $tokno_before_explode";
      $result = $conn->query($sql);
      
      $sql = "INSERT IGNORE INTO word_engine (word) VALUES ('$both_words[$array_size_minus_1]')";
      $result = $conn->query($sql);
      $sql = "UPDATE display_text SET word_engine_id ='$both_words[$array_size_minus_1]' WHERE tokno = $tokno_after_explode";
      $result = $conn->query($sql);

    }

    if(str_contains($word_stripped, "-")) {
      $hyph_pos = mb_strpos($word_stripped, "-");
      if($hyph_pos == 0)  { 
        $word_stripped = substr_replace($word_stripped, '', 0, 1); 
      } //get rid of hyphens at the beginning of words
      if(mb_substr($word_stripped, $hyph_pos -1, 1) == "\n") { 
        $word_stripped = mb_substr_replace($word_stripped, '', $hyph_pos, 1);  
      } //get rid of hyphens at the beginning of words where the previous character is a line-break
      
      
      $both_words = explode("-", $word_stripped);
      $array_size = count($both_words);
      $array_size_minus_1 = $array_size - 1;

      if($both_words[0] == "" || $both_words[0] == "\n") { }


    }


    else {
    $sql = "INSERT IGNORE INTO word_engine (word) VALUES ('$word_stripped')";
    $result = $conn->query($sql);
    $sql = "UPDATE display_text SET word_engine_id ='$word_stripped' WHERE tokno = $tokno_before_explode";
    $result = $conn->query($sql);
    }

  }
 
}

$conn->close(); 

?>
