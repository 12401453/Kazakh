

<?php


if(isset($_POST['toknos'])) {
  $toknos_comma_sep = $_POST['toknos'];
}

if(isset($_POST['word_eng_ids'])) {
  $word_eng_ids_comma_sep = $_POST['word_eng_ids'];
}



include 'db_details_web.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


$sql = "SET NAMES UTF8";
$res = $conn->query($sql);

$tokno_arr = explode(",", $toknos_comma_sep);
$word_eng_id_arr = explode(",", $word_eng_ids_comma_sep);

$x = 0;
foreach ($tokno_arr as $tokno) {

  $sql = "SELECT lemma_id, lemma_meaning_no FROM display_text WHERE tokno = $tokno";
  $res = $conn->query($sql);
  $row = $res->fetch_assoc();
  if($row["lemma_id"]) {
    $lemma_id = $row["lemma_id"];
    $lemma_meaning_no = $row["lemma_meaning_no"];

    $eng_trans_selector = "eng_trans".$lemma_meaning_no;

    $sql = "SELECT lemma, $eng_trans_selector FROM lemmas WHERE lemma_id = $lemma_id";



  }
  else {
    $word_eng_id = $word_eng_id_arr[$x];
    $sql = "SELECT first_lemma_id FROM word_engine WHERE word_engine_id = $word_eng_id";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    echo $row["first_lemma_id"].": ";
  }

  echo $tokno."\n";

  $x++;
}

$conn->close();
    
?>
