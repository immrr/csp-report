<?php

$mysqli = new mysqli("localhost", "user", "pw", "csp_report");
$table  = "csp_report";

if ($mysqli->connect_errno) {
  error_log("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}
?>
