<?php


include 'db_details_web.php';


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


$sql = "SET NAMES UTF8";
$res = $conn->query($sql);

$sql = "DROP TABLE chunks";
$res = $conn->query($sql);

$sql = "CREATE TABLE chunks (chunk VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_bin, dt_start INT UNSIGNED,dt_end INT UNSIGNED, chunk_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY) DEFAULT CHARSET=utf8 COLLATE utf8_bin";
$res = $conn->query($sql);

$sql = "SELECT line_break, tokno, text_word FROM display_text";
$res = $conn->query($sql);

//$sql = "SELECT COUNT(*) AS dt_count FROM display_text";
$count = $res->num_rows;

$chunk = '';
$dt_end = 0;
$tokno = 0;
$x = 1;

if ($res->num_rows > 0) {

  while($row = $res->fetch_assoc()) {

    $x++;
    $lb = $row['line_break'];
    $tokno = $row['tokno'];
    
    if($chunk == '') {
    $dt_start = $tokno;
    $dt_end = $tokno;
    }
    else {
    $dt_end++;
    }
    
    
    $next_word = $row['text_word'];
    if($next_word == "'") {$next_word = "\'"; }
    $chunk = $chunk.$next_word;
  
    echo $tokno."   ".$tokno_next."   ".$chunk."   ".$count."   ".$x."\n";
    $tokno_next = $tokno + 1;
    
    if($x > $count) {
    $sql_chunk = "INSERT INTO chunks (chunk, dt_start, dt_end) VALUES ('$chunk', $dt_start, $dt_end)";
    $res_chunk = $conn->query($sql_chunk);
    break;
    }

    $sql2 = "SELECT line_break, text_word FROM display_text WHERE tokno = $tokno_next";
    $res2 = $conn->query($sql2);
    $row2 = $res2->fetch_assoc();
    $lb_next = $row2['line_break'];

    

    if($lb_next > 0) {
    
    $sql_chunk = "INSERT INTO chunks (chunk, dt_start, dt_end) VALUES ('$chunk', $dt_start, $dt_end)";
    $res_chunk = $conn->query($sql_chunk);
    $chunk = '';
    
    }
  

  }

}

?>
