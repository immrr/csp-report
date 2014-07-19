<?php

header("status: 204");

$data = file_get_contents('php://input');

if (!$data) exit;

include "conf.php";

$data = json_decode($data, true);

if ($data['csp-report']) {
  $data = $data['csp-report'];

  if (!($stmt = $mysqli->prepare("INSERT INTO ".$table.
    "(timestamp, uri, referrer, blocked, violated, original_policy,
      source, sample, line) VALUES (NOW(),?,?,?,?,?,?,?,?)"))) {
    error_log("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
    exit;
  }

  if (!$stmt->bind_param("ssssssss",
                         $data["document-uri"],
                         $data["referrer"],
                         $data["blocked-uri"],
                         $data["violated-directive"],
                         $data["original-policy"],
                         $data["source-file"],
                         $data["script-sample"],
                         $data["line-number"])) {
    error_log("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
    exit;
  }

  if (!$stmt->execute()) {
        error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
  }
}

?>
