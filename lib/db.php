<?php

include ROOT."conf/conf.php";

class DB {
  public static $mysqli;
  public static $table;

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
}

DB::init(DB_HOST, DB_USER, DB_PW, DB_DB, DB_TABLE);
?>
