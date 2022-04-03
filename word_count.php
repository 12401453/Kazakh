<?php

$new_text = '';
if(isset($_POST['new_text'])) {
  $new_text = $_POST['new_text'];
}

echo count(explode(" ", $new_text));

?>

