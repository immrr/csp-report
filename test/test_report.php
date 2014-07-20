<?php

define("ROOT", dirname(__FILE__)."/../");
include ROOT."lib/report.php";

$handle = @fopen(ROOT."test/samples.txt", "r");
if ($handle) {
  while (($data = fgets($handle)) !== false) {
    process($data);
  }
  fclose($handle);
}

?>
