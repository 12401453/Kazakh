

<?php
if ($result->num_rows > 0) {
echo '<span>&emsp;</span>';
  while($row = $result->fetch_assoc()) {
    $word = $row["text_word"];
    $tokno = $row["tokno"];
    $line_break = $row["line_break"];
    $word_engine_id = $row["word_engine_id"];
 
   

if($line_break == 2) { echo '<br>'; }
if($line_break == 3) {echo '  '; }
if($line_break == 1) { echo '</span> <span class="chunk">';}
     

echo '<span class="tooltip_cyr">';
if($word_engine_id != "") {echo '<span class="tooltip" id="word_engine_id_'.$word_engine_id.'">';}
echo $word;
if($word_engine_id != "") { echo '<span class="tooltiptext5"></span></span>'; }
echo '</span>';

}
} else {
  echo "0 results";
}
$conn->close();

?>
