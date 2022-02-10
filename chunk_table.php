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

if ($res->num_rows > 0) {

  while($row = $res->fetch_assoc()) {

    $lb = $row['line_break'];
    $tokno = $row['tokno'];
    $chunk = $row['text_word'];
    if($chunk == "'") {$chunk = "\'"; }

    $tokno_next = $tokno + 1;

    $sql2 = "SELECT line_break, text_word FROM display_text WHERE tokno = $tokno_next";
    $res2 = $conn->query($sql2);
    $row2 = $res2->fetch_assoc();
    $lb_next = $row2['line_break'];


    while($lb_next == 0) {
      
      $next_word = $row2['text_word'];
      if($next_word == "'") {$next_word = "\'"; }
      $chunk = $chunk.$next_word;

      $tokno_next = $tokno_next + 1;

      $sql2 = "SELECT line_break, text_word FROM display_text WHERE tokno = $tokno_next";
      $res2 = $conn->query($sql2);
      $row2 = $res2->fetch_assoc();
      $lb_next = $row2['line_break'];

    }

    $dt_end = $tokno_next - 1;
    $sql3 = "INSERT INTO chunks (chunk, dt_start, dt_end) VALUES ('$chunk', $tokno, $dt_end)";


  }

}

?>
