

<?php
if ($result->num_rows > 0) {
echo '<span>&emsp;</span>';
  while($row = $result->fetch_assoc()) {
    $word = $row["text_word"];
    $tokno = $row["tokno"];
    $line_break = $row["line_break"];
    $word_engine_id = $row["word_engine_id"];
   // $lat_id = $row["cyr_id"];
   // $word_engine_id = $row["word_engine_id"];
 
   

if($line_break == 2) { echo '<br>'; }
if($line_break == 3) {echo 'TAB'; }
if($line_break == 1) { echo '</span> <span class="chunk">';}
     

echo '<span class="tooltip_cyr">';
echo '<span ';
if($word_engine_id != "") {echo 'class="tooltip"';}
echo ' id="tokno_',$tokno,'">' . $word .'<span class="tooltiptext5"></span></span></span>';

}
} else {
  echo "0 results";
}
$conn->close();

?>
