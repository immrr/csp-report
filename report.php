<?php

header("HTTP/1.1 204 No Content");

$data = file_get_contents('php://input');
if (!$data) exit;

define("ROOT", dirname(__FILE__).'/');
require ROOT."lib/report.php";

process($data);

?>
