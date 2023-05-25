<?php
if(isset($_POST['multiword_id'])) {
  $multiword_id = $_POST['multiword_id'];
}

if(isset($_POST['lang_id'])) {
  $lang_id = $_POST['lang_id'];
}

$word_eng_ids = "";
if(isset($_POST["word_eng_ids"])) {
    $word_eng_ids = $_POST["word_eng_ids"];
}
$toknos = "";
if(isset($_POST["toknos"])) {
    $toknos = $_POST["toknos"];
}
/*
$multiword_id = 5;
$word_eng_ids = [3253,2347];
$toknos = [19049,19050];
$lang_id = 2; */

$word_eng_ids = explode(",", $word_eng_ids);
$toknos = explode(",", $toknos); 
$mw_length = count($toknos);  

include 'db_details_web.php';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$sql = "SET NAMES UTF8";
$res = $conn->query($sql);

$sql_string1 = "UPDATE display_text SET multiword_id = NULL, multiword_meaning_no = NULL, multiword_count = NULL WHERE ";
$sql_string2 = "SELECT multiword_count FROM display_text WHERE multiword_id = $multiword_id AND (";
for($i = 0; $i < $mw_length; $i++) {
    $sql_string1 .= "tokno = $toknos[$i]";
    $sql_string2 .= "word_engine_id = $word_eng_ids[$i]";
    if($i + 1 < $mw_length) {
        $sql_string1 .= " OR ";
        $sql_string2 .= " OR ";
    }
}
$sql_string2 .= ")";

//echo $sql_string1."\n";
//echo $sql_string2."\n";

$res = $conn->query($sql_string1);
$mw_counts = $conn->query($sql_string2)->fetch_all();
//var_dump($mw_counts);
$mw_counts_length = count($mw_counts);

$x = 1;
$mw_leftover = false;
for($i = 0, $j = 1; $i < $mw_counts_length && $j < $mw_counts_length; $i++, $j++){
    if($mw_counts[$i] == $mw_counts[$j]) $x++;
    if($x == $mw_length) {
        $mw_leftover = true;
        break;
    }
}

//echo "\$mw_leftover: $mw_leftover\n";

if($mw_leftover == false) {
    $sql_string = "DELETE FROM multiwords WHERE multiword_id = $multiword_id AND ";
    $null_count = 1;
    //need to change this so it specifies the rest of the word_eng_idX columns as NULL
    for($i = 0; $i < $mw_length; $i++) {
        $sql_string .= "word_eng_id".($i + 1)." = ".$word_eng_ids[$i];
        $sql_string .= " AND ";
        $null_count++;
    }
    for(; $null_count < 11; $null_count++) {
        $sql_string .= "word_eng_id".$null_count." = 0 AND ";
    }
    $sql_string .= "lang_id = ".$lang_id;

    $res = $conn->query($sql_string);
    //echo $sql_string."\n";

    $no_of_mw_leftover = count($conn->query("SELECT multiword_id FROM multiwords WHERE multiword_id = $multiword_id")->fetch_all());
    //echo "multiwords left: ".$no_of_mw_leftover."\n";
    if($no_of_mw_leftover == 0) {
        $res = $conn->query("DELETE FROM multiword_lemmas WHERE multiword_id = $multiword_id");
        //echo "DELETE FROM multiword_lemmas WHERE multiword_id = $multiword_id\n";
    }
}

$conn->close();
?>