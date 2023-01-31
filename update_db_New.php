<?php

$text_title = '';
if(isset($_POST['text_title'])) {
  $text_title = $_POST['text_title'];
}
$text_title = addslashes($text_title);

$lang_id = '';
if(isset($_POST['lang_id'])) {
  $lang_id = $_POST['lang_id'];

}


$text_words = '';
if(isset($_POST['text_words'])) {
  $text_words = $_POST['text_words']; //trim() removed because done on JS end
}
$word_eng_words = '';
if(isset($_POST['word_eng_words'])) {
  $word_eng_words = $_POST['word_eng_words']; //trim() removed because done on JS end
}
$space_index = '';
if(isset($_POST['space_index'])) {
  $space_index = $_POST['space_index']; //trim() removed because done on JS end
}

//echo $text_words."\n";
//echo $word_eng_words."\n";

//include 'db_details_web.php';
$servername = "localhost";
$username = "joe";
$password = "password";
$dbname = "Kazakh_test";

//$dbname_pb = "progressBar";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

/*$conn_2 = new mysqli($servername, $username, $password, $dbname_pb);

if ($conn_2->connect_error) {
  die("Connection failed: " . $conn_2->connect_error);
} */

$sql = "SET NAMES UTF8";
$res = $conn->query($sql);

$sql = "START TRANSACTION";
$result = $conn->query($sql);

$sql = "SELECT MAX(tokno) AS dt_start FROM display_text";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$dt_start = $row["dt_start"];
if(is_null($dt_start)) {
  $dt_start = 0;
}
$dt_start++;

//echo $text_words."\n";

$text_words_arr = json_decode($text_words, true);
$word_eng_words_arr = json_decode($word_eng_words, true);

//var_dump($text_words_arr);
//var_dump($word_eng_words_arr);

$length = count($text_words_arr);
echo $length;


$sql_stmt1 = $conn->prepare("INSERT INTO display_text (text_word) VALUES (?)");
$sql_stmt2 = $conn->prepare("INSERT IGNORE INTO word_engine (word, lang_id) VALUES (?, ?)");
//$sql_stmt3 = $conn->prepare("INSERT INTO display_text (word_engine_id, text_word) SELECT word_engine_id, ? FROM word_engine WHERE word = ? AND lang_id = ?"); //my suspicion is that this is forcing a COMMIT and slowing it down, this is currently unusably slow
$sql_stmt3 = $conn->prepare("INSERT INTO display_text (word_engine_id, text_word) VALUES (?, ?)");

$sql_stmt1->bind_param("s", $text_word);
$sql_stmt2->bind_param("si", $word, $lang_id);
//$sql_stmt3->bind_param("ssi", $text_word, $word, $lang_id);
$sql_stmt3->bind_param("is", $word_engine_id, $text_word);

for($x = 0; $x < $length; $x++) {

  $text_word = addslashes($text_words_arr[$x]);
  $word = urldecode($word_eng_words_arr[$x]);
  if(strlen($word) == 0) {   
    $sql_stmt1->execute();
    //echo "no word_eng_word\n";
  }
  else {
    $sql_stmt2->execute();
    
    $sql = "SELECT word_engine_id FROM word_engine WHERE word = '$word' AND lang_id = $lang_id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $word_engine_id = $row["word_engine_id"];
    
    $sql_stmt3->execute();
    //echo "there is a word_eng_word\n";
  }
}

$space_index_arr = explode(",", $space_index);
$length = count($space_index_arr);


$sql_stmt4 = $conn->prepare("UPDATE display_text SET space = 1 WHERE tokno = ?");
$sql_stmt4->bind_param("i", $space_tokno);

for($x = 0; $x < $length; $x++) {
  $space_index_val = $space_index_arr[$x];
  if($space_index_val != "") {
    $space_tokno = $dt_start + $space_index_val;
    $sql_stmt4->execute();
  }

  
}

$sql = "COMMIT";
$result = $conn->query($sql);


$sql = "SELECT MAX(tokno) AS dt_end FROM display_text";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$dt_end = $row["dt_end"];

$sql = "INSERT INTO texts (text_title, dt_start, dt_end, lang_id) VALUES ('$text_title', '$dt_start', '$dt_end', $lang_id)";
$res = $conn->query($sql);

$conn->close(); 


/*$sql = "UPDATE progress_bar SET word_num = $word_count";
$res = $conn_2->query($sql);

$conn_2->close();
*/
?>
