<?php

if(isset($_POST['word_engine_id'])) {
  $word_engine_id = $_POST['word_engine_id'];
}

if(isset($_POST['tokno_current'])) {
  $tokno = $_POST['tokno_current'];
}

if(isset($_POST['lang_id'])) {
  $lang_id = $_POST['lang_id'];
}

//$word_engine_id = 1147;
//$tokno = 9729;
//$lang_id = 8;

include 'db_details_web.php';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$sql = "SET NAMES UTF8";
$res = $conn->query($sql);

$pos = 1;

$regexp = "#[։ ؞ ࠷ ࠹ ࠽ ࠾ !！﹗ ⹓ ߹ ᥄ ?？﹖ ⁈ ⁇ ⸮ ⹔ ؟ ፧ ᥅ ꘏ ꛷ ꫱ 𑅃 ‽ .．﹒ ۔ ܁ ܂ ። ᠃ ᠉ ᙮ ⸼ ꓿ ꘎ ꛳ 𖫵 𖺘 𛲟 。｡ । ॥ ꣎ ꣏ ᰻ ᰼ ꡶ ꡷ ᜵ ᜶ ꤯ ၊ ။ ᪨-᪫ ᭞ ᭟ ꧈ ꧉ ꩝-꩟ ꫰ ꯫ 𐩖 𐩗 𑁇 𑁈 𑃀 𑃁 𑅁 𑅂 𑇅 𑇆 𑈸 𑈹 𑑋 𑑌 𑗂 𑗃 𑙁 𑙂 𑜼 𑜽 𑥄 𑱁 𑱂 𑽃 𑽄 𖩮 𖩯 ᱾ ᱿ ؝ ܀ ፨ ᭚ ᭛ ᭽ ᭾ 𐽕-𐽙 𐾆-𐾉 𑂾 𑂿 𑇞 𑇟 𑊩 𑜾 𑥆 𑻷 𑻸 𑩂 𑩃 𑪛 𑪜 𑇍 𑈻 𑈼 𑗉-𑗗 𖬷 𖬸 𖭄 𝪈 ‼ ⁉]#u";

$tokno_initial = $tokno;

$sql_stmt = $conn->prepare("SELECT text_word, tokno, word_engine_id FROM display_text WHERE tokno = ?");
$sql_stmt->bind_param("i", $tokno);

$display_text_word_eng_id = 0;
$adjacent_toknos_array = "[";

for($i = -1; $i > -51; $i--) {
  $tokno += $i;

  $sql_stmt->execute();
  $sql_stmt->bind_result($text_word, $adjacent_tokno, $display_text_word_eng_id);
  $sql_stmt->fetch();

  if(is_null($display_text_word_eng_id)) {
    if(preg_match($regexp, $text_word) === 1) break;
  }
  else {
    $adjacent_toknos_array .= $adjacent_tokno.",";
  }
}
for($i = 1; $i < 51; $i++) {
  $tokno += $i;

  $sql_stmt->execute();
  $sql_stmt->bind_result($text_word, $adjacent_tokno, $display_text_word_eng_id);
  $sql_stmt->fetch();

  if(is_null($display_text_word_eng_id)) {
    if(preg_match($regexp, $text_word) === 1) break;
  }
  else {
    $adjacent_toknos_array .= $adjacent_tokno.",";
  }
}

$adjacent_toknos_array .= "]";

$sql_stmt->close();

$sql_stmt = $conn->prepare("SELECT multiword_id, multiword_meaning_no FROM display_text WHERE tokno = ?");
$sql_stmt->bind_param("i", $tokno_initial);
$sql_stmt->execute();
$sql_stmt->bind_result($multiword_id, $multiword_meaning_no);
$sql_stmt->fetch();

if(!is_null($multiword_id)) {
  $sql_stmt->prepare("SELECT multiword_lemma_form, pos, eng_trans".$multiword_meaning_no." FROM multiword_lemmas WHERE multiword_id = ?");
  $sql_stmt->bind_param("i", $multiword_id);
  $sql_stmt->execute();
  $sql_stmt->bind_result($mw_lemma_form, $pos, $mw_lemma_meaning);
  $sql_stmt->fetch();

  if(is_null($pos)) {
    $pos = 1;
  }
  if(is_null($mw_lemma_form)) {
    $mw_lemma_form = "";
  }
  if(is_null($mw_lemma_meaning)) {
    $mw_lemma_meaning = "";
  }
}

$json_response = json_encode(array("multiword_tag_content" => $mw_lemma_form, "multiword_textarea_content" => $mw_lemma_meaning, "multiword_meaning_no" => $multiword_meaning_no, "multiword_id" => $multiword_id, "pos" => $pos, "adjacent_toknos" => $adjacent_toknos_array));

echo $json_response;
$conn->close();
    
?>
