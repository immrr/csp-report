<?php

header("status: 204");

$data = file_get_contents('php://input');
if (!$data) exit;

define("ROOT", dirname(__FILE__));
include ROOT."lib/report.php";

process($data);

?>
