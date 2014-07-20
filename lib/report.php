<?php

include ROOT."lib/db.php";

function process($data) {
  $mysqli = DB::$mysqli;
  $table  = DB::$table;

  $data = json_decode($data, true);

  if ($data['csp-report']) {
    $data = $data['csp-report'];

    $parsed_url = parse_url($data["document-uri"]);

    if (!($stmt = $mysqli->prepare("INSERT INTO ".$table.
      "(timestamp, host, path, query, uri, referrer, blocked, violated, violated_directive, original_policy,
        source, sample, line) VALUES (NOW(),?,?,?,?,?,?,?,?,?,?,?,?)"))) {
      error_log("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
      exit;
    }

    $violated = split(" ", $data["violated-directive"])[0];

    if (!$stmt->bind_param("ssssssssssss",
                           $parsed_url["host"],
                           $parsed_url["path"],
                           $parsed_url["query"],
                           $data["document-uri"],
                           $data["referrer"],
                           $data["blocked-uri"],
                           $violated,
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
}
?>