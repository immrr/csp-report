<?php

include ROOT."lib/db.php";
include ROOT."lib/getbrowser.php";

function process($data) {
  $data = json_decode($data, true);

  if ($data['csp-report']) {
    $data = $data['csp-report'];

    if (isset($data["document-uri"])) {
      $parsed_url = parse_url($data["document-uri"]);
      $parsed_url = array_merge(
        array("host" => "", "path" => "", "query" => ""),
        $parsed_url);
    } else {
      return;
    }

    $violated = explode(" ", $data["violated-directive"]);
    if (sizeof($violated) > 0) {
      $violated = $violated[0];
    } else {
      $violated = '';
    }

    $browser = getBrowser();
    if ($browser) {
      $browser = $browser['name'] . ", " .
                 $browser['version'] . ", " .
                 $browser['platform'];
    }

    $data = array_merge(
      array(
        "document-uri"       => "",
        "referrer"           => "",
        "blocked-uri"        => "",
        "violated-directive" => "",
        "original-policy"    => "",
        "source-file"        => "",
        "script-sample"      => "",
        "line-number"        => ""
      ), $data);

    DB::add($parsed_url["host"],
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
            $browser);
  }
}
?>
