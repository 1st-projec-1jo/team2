<?php

/* Maria DB 설정 */
define("MY_MARIADB_HOST", "team2");
define("MY_MARIADB_PORT", "6522");
define("MY_MARIADB_USER", "team2");
define("MY_MARIADB_PASSWORD", "team2");
define("MY_MARIADB_NAME", "team2");
define("MY_MARIADB_CHARSET", "utf8mb4");
define("MY_MARIADB_DSN", "mysql:host=".MY_MARIADB_HOST.";port=".MY_MARIADB_PORT.";dbname=".MY_MARIADB_NAME.";charset=".MY_MARIADB_CHARSET);

/* PHP Path 설정 */
define("MY_PATH_ROOT", $_SERVER["DOCUMENT_ROOT"]); // web server document root
define("MY_PATH_DB_LIB", MY_PATH_ROOT."/lib/db_lib.php"); // DB 라이브러리
define("MY_PATH_ERROR", MY_PATH_ROOT."/error.php"); // Error page

/* 로직 설정 */