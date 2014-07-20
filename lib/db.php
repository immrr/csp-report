<?php

include ROOT."conf/conf.php";

class DB {
  private static $mysqli;
  private static $table;

  public static function init($host, $user, $pw, $db, $_table) {
    if (!self::$mysqli) {
      self::$mysqli = new mysqli($host, $user, $pw, $db);

      if (self::$mysqli->connect_errno) {
        error_log("Failed to connect to MySQL: (" .
          self::$mysqli->connect_errno . ") " . self::$mysqli->connect_error);
      }

      self::$table = $_table;
    }
  }

  public static function add($host, $path, $query, $uri, $referrer, $blocked_uri,
                             $violated, $violated_directive, $original_policy,
                             $source, $sample, $line, $browser) {

    $sql = "INSERT INTO ".self::$table."(timestamp, host, path, query, uri, referrer,
            blocked, violated, violated_directive, original_policy, source,
            sample, line, browser) VALUES (NOW(),?,?,?,?,?,?,?,?,?,?,?,?,?)";


    $init_table = true;
    while (true) {
      if (($stmt = self::$mysqli->prepare($sql))) {
        break;
      } else {
        // 1146 == ER_NO_SUCH_TABLE
        if ($init_table && self::$mysqli->errno == 1146) {
          $init_table = false;
          $setup = file_get_contents(ROOT.'setup/db.sql');
          if (self::$mysqli->query($setup)) {
            continue;
          } else {
            error_log("Create table failed: (" . self::$mysqli->errno . ") " . self::$mysqli->error);
            return false;
          }
        }
        error_log("Prepare failed: (" . self::$mysqli->errno . ") " . self::$mysqli->error);
        return false;
      }
    }

    if (!$stmt->bind_param("sssssssssssss",
                           $host, $path, $query, $uri, $referrer, $blocked_uri,
                           $violated, $violated_directive, $original_policy,
                           $source, $sample, $line, $browser)) {
      error_log("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
      return false;
    }

    if (!$stmt->execute()) {
          error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
          return false;
    }
    return true;
  }
}

DB::init(DB_HOST, DB_USER, DB_PW, DB_DB, DB_TABLE);
?>
