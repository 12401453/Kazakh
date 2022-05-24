

<?php


if(isset($_POST['toknos'])) {
  $toknos_comma_sep = $_POST['toknos'];
}

if($toknos_comma_sep == "") {
  exit(0);
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
$lemmas_arr = array();
foreach ($tokno_arr as $tokno) {

  $sql = "SELECT lemma_id, lemma_meaning_no FROM display_text WHERE tokno = $tokno";
  $res = $conn->query($sql);
  $row = $res->fetch_assoc();
  if($row["lemma_id"]) {
    $lemma_id = $row["lemma_id"];
    $lemma_meaning_no = $row["lemma_meaning_no"];

    $eng_trans_selector = "eng_trans".$lemma_meaning_no;

    $sql = "SELECT lemma, $eng_trans_selector, pos FROM lemmas WHERE lemma_id = $lemma_id";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    $lemma_form = $row["lemma"];
    $lemma_trans = $row[$eng_trans_selector];
    $pos = $row["pos"];

  }
  else {
    $word_eng_id = $word_eng_id_arr[$x];
    $sql = "SELECT first_lemma_id FROM word_engine WHERE word_engine_id = $word_eng_id";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    $lemma_id = $row["first_lemma_id"];

    $sql = "SELECT lemma, eng_trans1, pos FROM lemmas WHERE lemma_id = $lemma_id";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    $lemma_form = $row["lemma"];
    $lemma_trans = $row["eng_trans1"];
    $pos = $row["pos"];
  }

  
  $lemmas_arr[] = array("lemma_form" => htmlentities($lemma_form), "lemma_trans" => htmlentities($lemma_trans), "pos" => $pos);
  $x++;
}
echo json_encode($lemmas_arr);

$conn->close();
    
?>
