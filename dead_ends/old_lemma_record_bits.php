////////////////////////////////////////////OLD SHIT+++++++++++++++
$sql = "UPDATE display_text SET lemma_meaning_no = $lemma_meaning_no, pos = $pos WHERE tokno = $tokno_current";
$res = $conn->query($sql);

$sql = "SELECT lemma_id FROM word_engine WHERE word_engine_id = $word_engine_id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$lemma_id_wordeng = $row["lemma_id"];

$sql = "SELECT lemma_id FROM lemmas WHERE lemma = '$lemma_form' AND pos = $pos AND lang_id = $lang_id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$lemma_id_lemmas = $row["lemma_id"];

/* $sql = "SELECT lemma_id FROM lemmas WHERE lemma = '$lemma_form' AND lang_id = $lang_id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$lemma_id_repeat = $row["lemma_id"]; */



if(is_null($lemma_id_wordeng)) {
  //if no lemma has been assigned to the word_engine form but the lemma could have already been recorded
  $sql = "SELECT lemma_id FROM lemmas WHERE lemma = '$lemma_form' AND lang_id = $lang_id";
  $res = $conn->query($sql);
  $row = $res->fetch_assoc();
  $lemma_id_repeat = $row["lemma_id"];

  if(is_null($lemma_id_repeat)) {

    $sql = "SELECT MAX(lemma_id) AS max_lemma_id FROM lemmas";
    $res= $conn->query($sql);
    $row = $res->fetch_assoc();
    $new_lemma_id = $row["max_lemma_id"] + 1;
  }
  else {
    $new_lemma_id = $lemma_id_repeat;
  }



  $sql = "INSERT IGNORE INTO lemmas (lemma_id, lemma, ".$eng_trans_sql_string.", lang_id, pos) VALUES ('$new_lemma_id', '$lemma_form', '$lemma_meaning', $lang_id, $pos)"; //this will get ignored if the lemma, lang_id and pos together are already in the table
  $res = $conn->query($sql);

  if(is_null($lemma_id_lemmas)) {
    //if no lemma has been assigned to that word_engine form AND the lemma has not already been recorded
   /* $sql = "SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'Kazakh' AND TABLE_NAME = 'lemmas'";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc(); 
    $lemma_id_wordeng = $row["AUTO_INCREMENT"] - 1; */
    $lemma_id_wordeng = $new_lemma_id;

    $sql = "UPDATE word_engine SET lemma_id = $lemma_id_wordeng WHERE word_engine_id = $word_engine_id";
    $res = $conn->query($sql);
  }
  else {
    //if no lemma has been assigned to that word_engine form BUT the lemma HAS already been recorded
    $sql = "UPDATE word_engine SET lemma_id = $lemma_id_lemmas WHERE word_engine_id = $word_engine_id";
    $res = $conn->query($sql);
  }

}
else {  
  //if this word_engine form already has an assigned lemma but it needs to be changed
  if(is_null($lemma_id_lemmas)) {
    
    $sql = "SELECT lemma_id FROM lemmas WHERE lemma = '$lemma_form' AND lang_id = $lang_id";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    $lemma_id_repeat = $row["lemma_id"];

    if(is_null($lemma_id_repeat)) {
      $sql = "SELECT MAX(lemma_id) AS max_lemma_id FROM lemmas";
      $res= $conn->query($sql);
      $row = $res->fetch_assoc();
      $new_lemma_id = $row["max_lemma_id"] + 1;
    }
    else {
      $new_lemma_id = $lemma_id_repeat;
    }
   
    // if the new lemma form is not already in the table
    $sql = "INSERT IGNORE INTO lemmas (lemma_id, lemma, ".$eng_trans_sql_string.", lang_id, pos) VALUES ('$new_lemma_id', '$lemma_form', '$lemma_meaning', $lang_id, $pos)"; 
    $res = $conn->query($sql);
    
    /* $sql = "SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'Kazakh' AND TABLE_NAME = 'lemmas'";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    $lemma_id_wordeng = $row["AUTO_INCREMENT"] - 1; */
    $lemma_id_wordeng = $new_lemma_id;


    $sql = "UPDATE word_engine SET lemma_id = $lemma_id_wordeng WHERE word_engine_id = $word_engine_id";
    $res = $conn->query($sql);

  }

  else if($lemma_id_lemmas != $lemma_id_wordeng) {
    //if the changed lemma does already exist in the table but it is not the same as the one currently assigned to that word_engine word
    $sql = "UPDATE word_engine SET lemma_id = $lemma_id_lemmas WHERE word_engine_id = $word_engine_id";
    $res = $conn->query($sql);
  }

  else {
    //if the changed lemma does already exist and it is already assigned to this word_engine word but its details need changing
    $sql = "UPDATE lemmas SET lemma = '$lemma_form', ".$eng_trans_sql_string." = '$lemma_meaning', lang_id = $lang_id WHERE lemma_id = $lemma_id_wordeng AND pos = $pos";
    $res = $conn->query($sql);
  }

}
//////////////////////////OLD SHIT ^^^^^