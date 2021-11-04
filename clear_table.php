

<?php


include 'db_details_web.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


$sql = "SET NAMES UTF8";
$res = $conn->query($sql);

$sql = "DROP TABLE display_text";
$result = $conn->query($sql);

$sql ="CREATE TABLE display_text (text_word VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_bin, line_break TINYINT(1), word_engine_id VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_bin, tokno INT AUTO_INCREMENT PRIMARY KEY) DEFAULT CHARSET=utf8 COLLATE utf8_bin";
$result = $conn->query($sql);

$sql = "DROP TABLE word_engine";
$result = $conn->query($sql);

$sql ="CREATE TABLE word_engine (word_id INT AUTO_INCREMENT PRIMARY KEY, word VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_bin UNIQUE, eng_trans1 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans2 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans3 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans4 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans5 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans6 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans7 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans8 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans9 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans10 TEXT CHARACTER SET utf8 COLLATE utf8_bin) DEFAULT CHARSET=utf8 COLLATE utf8_bin";
$result = $conn->query($sql);


$conn->close(); 

?>
