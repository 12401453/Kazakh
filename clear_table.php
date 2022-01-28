

<?php


include 'db_details_web.php';


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


$sql = "SET NAMES UTF8";
$res = $conn->query($sql);

$sql = "DROP TABLE display_text";
$result = $conn->query($sql);
$sql ="CREATE TABLE display_text (text_word VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_bin, line_break TINYINT(1), word_engine_id INT UNSIGNED, tokno INT AUTO_INCREMENT PRIMARY KEY) DEFAULT CHARSET=utf8 COLLATE utf8_bin";
$result = $conn->query($sql);

$sql = "DROP TABLE word_engine";
$result = $conn->query($sql);
$sql ="CREATE TABLE word_engine (word_engine_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, word VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_bin UNIQUE, lemma_id INT UNSIGNED) DEFAULT CHARSET=utf8 COLLATE utf8_bin";
$result = $conn->query($sql);

$sql = "DROP TABLE texts";
$result = $conn->query($sql);
$sql = "CREATE TABLE texts (text_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, text_title TEXT CHARACTER SET utf8 COLLATE utf8_bin, dt_start INT UNSIGNED, dt_end INT UNSIGNED, lang_id INT UNSIGNED) DEFAULT CHARSET=utf8 COLLATE utf8_bin";
$result = $conn->query($sql);

$sql = "DROP TABLE lemmas";
$result = $conn->query($sql);
$sql = "CREATE TABLE lemmas (lemma_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, lemma VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_bin UNIQUE, eng_trans1 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans2 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans3 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans4 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans5 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans6 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans7 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans8 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans9 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans10 TEXT CHARACTER SET utf8 COLLATE utf8_bin) DEFAULT CHARSET=utf8 COLLATE utf8_bin";
$result = $conn->query($sql);


$conn->close(); 

?>
