<?php


include 'db_details_web.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SET NAMES UTF8";
$res = $conn->query($sql);


$sql = "SELECT MAX(word_engine_id) AS max_eng FROM word_engine";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$max_eng = $row["max_eng"];
if(is_null($max_eng)) {
  $max_eng = 0;
}
$word_engine_id = $max_eng + 1;


$engine_word = "faggots";
$lang_id = 3;
//$word_engine_id = 20;

/*
$sql_3 = "SELECT 1 FROM word_engine WHERE word = '$engine_word' AND lang_id = $lang_id AND word_engine_id = $word_engine_id";
$res_3 = $conn->query($sql_3);
$row_3 = $res_3->fetch_assoc();
$sql_3 = $row_3[$engine_word];

$sql_2= "SELECT '$engine_word', $lang_id, $word_engine_id FROM DUAL WHERE NOT EXISTS ($sql_3) LIMIT 1";
$res_2 = $conn->query($sql_2);
$row_2 = $res_2->fetch_assoc();
$sql_2 = $row_2[$engine_word];

$sql = "INSERT INTO word_engine (word, lang_id, word_engine_id) $sql_2";
        $result = $conn->query($sql);
*/

$sql = "INSERT INTO word_engine (word, lang_id, word_engine_id) SELECT '$engine_word', $lang_id, $word_engine_id FROM DUAL WHERE NOT EXISTS (SELECT 1 from word_engine WHERE word = '$engine_word' and lang_id = $lang_id) LIMIT 1";
$result = $conn->query($sql);





$conn->close();

?>