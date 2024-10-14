<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/config.php"); 
require_once(MY_PATH_DB_LIB);

$conn = null;

try {
    $conn = my_db_conn();

} catch(Throwable$th) {
    require_once(MY_PATH_ERROR);
    exit;
}

?>



<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./css/common.css">
  <link rel="stylesheet" href="./css/index.css">
  <title>Daily Routine</title>
</head>
<body>
  <div class="index">
    <h1>우주인의 데일리 루틴</h1>
    <div class="index_btn">
      <a href="/main.php"><button type="button" class="start">시작</button></a>
    </div>
  </div>
</body>
</html>