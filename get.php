

<?php
if ($result->num_rows > 0) {
echo '<span>&emsp;</span>';
  while($row = $result->fetch_assoc()) {
    $kaz_id = $row["kaz_text_id"];
    $tokno = $row["tokno"];
    $line_break = $row["line_break"];
   // $lat_id = $row["cyr_id"];
   // $word_engine_id = $row["word_engine_id"];


                                echo '<span class="tooltip_cyr">';
echo '<span ';
if($word_engine_id != '') {echo 'class="tooltip"';}
echo ' id="tokno_',$tokno,'">' . $kaz_id .'<span class="tooltiptext5"></span></span></span>' . " " ;

if($line_break == 1) { echo '<br>'; }

}
} else {
  echo "0 results";
}
$conn->close();

?>
