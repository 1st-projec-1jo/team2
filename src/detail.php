<?php
    require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
    require_once(MY_PATH_DB_LIB);

    /* 일정 관리 리스트(백) 호출 */
    require_once(MY_LIST_BACK);

    $conn = null;

?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./css/common.css">

  <title>Document</title>
</head>
<body>
  <div class="container">
    <div class="container_box">

      <!-- 일정 관리 리스트(프론트) 호출 -->
      <?php require_once(MY_LIST_FRONT) ?>

      <div class="container_r"></div>
    </div>
  </div>

</body>
</html>