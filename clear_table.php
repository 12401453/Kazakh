

<?php


include 'db_details_web.php';


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


$sql = "SET NAMES UTF8";
$res = $conn->query($sql);

$sql = "DROP TABLE IF EXISTS display_text";
$result = $conn->query($sql);
$sql ="CREATE TABLE display_text (text_word VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_bin, line_break TINYINT(1), word_engine_id INT UNSIGNED, tokno INT UNSIGNED AUTO_INCREMENT PRIMARY KEY) DEFAULT CHARSET=utf8 COLLATE utf8_bin";
$result = $conn->query($sql);

$sql = "DROP TABLE IF EXISTS chunks";
$res = $conn->query($sql);
$sql = "CREATE TABLE chunks (dt_start INT UNSIGNED,dt_end INT UNSIGNED, chunk_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY) DEFAULT CHARSET=utf8 COLLATE utf8_bin";
$res = $conn->query($sql);

$sql = "DROP TABLE IF EXISTS word_engine";
$result = $conn->query($sql);                                 //the 'word' column used to be UNIQUE but that is bollocks
$sql ="CREATE TABLE word_engine (word_engine_id INT UNSIGNED AUTO_INCREMENT UNIQUE, word VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_bin, lemma_id INT UNSIGNED, lang_id SMALLINT UNSIGNED, PRIMARY KEY(word, lang_id)) DEFAULT CHARSET=utf8 COLLATE utf8_bin";
$result = $conn->query($sql);

$sql = "DROP TABLE IF EXISTS texts";
$result = $conn->query($sql);
$sql = "CREATE TABLE texts (text_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, text_title TEXT CHARACTER SET utf8 COLLATE utf8_bin, dt_start INT UNSIGNED, dt_end INT UNSIGNED, lang_id SMALLINT UNSIGNED, text_tag VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_bin) DEFAULT CHARSET=utf8 COLLATE utf8_bin";
$result = $conn->query($sql);

$sql = "DROP TABLE IF EXISTS lemmas";
$result = $conn->query($sql);
$sql = "CREATE TABLE lemmas (lemma_id INT UNSIGNED AUTO_INCREMENT UNIQUE, lemma VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_bin UNIQUE, eng_trans1 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans2 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans3 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans4 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans5 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans6 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans7 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans8 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans9 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans10 TEXT CHARACTER SET utf8 COLLATE utf8_bin, lang_id SMALLINT UNSIGNED, pos SMALLINT UNSIGNED, PRIMARY KEY(lemma, lang_id, pos)) DEFAULT CHARSET=utf8 COLLATE utf8_bin";
$result = $conn->query($sql);

$sql = "DROP TABLE IF EXISTS multiword_index";
$result = $conn->query($sql);
$sql = "CREATE TABLE multiword_index (tokno_start INT UNSIGNED, tokno_end INT UNSIGNED, multiword_id INT UNSIGNED) DEFAULT CHARSET=utf8 COLLATE utf8_bin";
$result = $conn->query($sql);

$sql = "DROP TABLE IF EXISTS multiwords";
$result = $conn->query($sql);
$sql = "CREATE TABLE multiwords (multiword_id INT UNSIGNED, word_engine_id1 INT UNSIGNED, word_engine_id2 INT UNSIGNED, word_engine_id3 INT UNSIGNED, word_engine_id4 INT UNSIGNED, word_engine_id5 INT UNSIGNED, word_engine_id6 INT UNSIGNED, word_engine_id7 INT UNSIGNED, word_engine_id8 INT UNSIGNED, word_engine_id9 INT UNSIGNED, word_engine_id10 INT UNSIGNED, eng_trans1 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans2 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans3 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans4 TEXT CHARACTER SET utf8 COLLATE utf8_bin, eng_trans5 TEXT CHARACTER SET utf8 COLLATE utf8_bin, lang_id SMALLINT UNSIGNED) DEFAULT CHARSET=utf8 COLLATE utf8_bin";
$result = $conn->query($sql);

$sql = "DROP TABLE IF EXISTS context_trans";
$result = $conn->query($sql);
$sql = "CREATE TABLE context_trans (tokno_start INT UNSIGNED, tokno_end INT UNSIGNED, multiword_id INT UNSIGNED, eng_trans TEXT CHARACTER SET utf8 COLLATE utf8_bin) DEFAULT CHARSET=utf8 COLLATE utf8_bin";
$result = $conn->query($sql);

$sql = "DROP TABLE IF EXISTS languages";
$result = $conn->query($sql);
$sql = "CREATE TABLE languages (lang_id SMALLINT UNSIGNED PRIMARY KEY, lang_name VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_bin UNIQUE) DEFAULT CHARSET=utf8 COLLATE utf8_bin";
$result = $conn->query($sql);
$sql = "INSERT INTO languages (lang_id, lang_name) VALUES (8, 'Danish'), (7, 'Turkish'), (1, 'Russian'), (2, 'Kazakh'), (3, 'Polish'), (4, 'Bulgarian')";
$result = $conn->query($sql);

$sql = "DROP TABLE IF EXISTS abbreviations";
$result = $conn->query($sql);
$sql = "CREATE TABLE abbreviations (abbr_id INT UNSIGNED PRIMARY KEY, eng_trans TEXT CHARACTER SET utf8 COLLATE utf8_bin, word_engine_id1 INT UNSIGNED, word_engine_id2 INT UNSIGNED, word_engine_id3 INT UNSIGNED, word_engine_id4 INT UNSIGNED, word_engine_id5 INT UNSIGNED, word_engine_id6 INT UNSIGNED, word_engine_id7 INT UNSIGNED, lang_id SMALLINT UNSIGNED) DEFAULT CHARSET=utf8 COLLATE utf8_bin";
$result = $conn->query($sql);

$sql = "DROP TABLE IF EXISTS progress_bar";
$result = $conn->query($sql);
$sql = "CREATE TABLE progress_bar (word_num INT UNSIGNED)";
$result = $conn->query($sql);
$sql = "INSERT INTO progress_bar (word_num) VALUES (0)";
$result = $conn->query($sql);


$conn->close(); 

?>
