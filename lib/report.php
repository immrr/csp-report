<?php

include ROOT."lib/db.php";
include ROOT."lib/getbrowser.php";

function process($data) {
  $data = json_decode($data, true);

  if ($data['csp-report']) {
    $data = $data['csp-report'];

    $parsed_url = parse_url($data["document-uri"]);

    $violated = explode(" ", $data["violated-directive"]);
    if (sizeof($violated) > 0) {
      $violated = $violated[0];
    }

    $browser = getBrowser();
    if ($browser) {
      $browser = $browser['name'] . ", " . $browser['version'] . ", " .
                 $browser['platform'];
    }

    if (!DB::add($parsed_url["host"],
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
                 $data["line-number"],
                 $browser)) {
      exit;
    }
  }
}
?>
