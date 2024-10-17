<?php
    require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
    require_once(MY_PATH_DB_LIB);
    require_once(MY_LIST_BACK);

    $conn = null;

    try {
        if(strtoupper($_SERVER["REQUEST_METHOD"]) === "GET") {

        }else if(strtoupper($_SERVER["REQUEST_METHOD"]) === "POST") {

            $id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;
            
            $date = isset($_POST["date"]) ? $_POST["date"] : "";

            if($id < 1) {
                throw new Exception("파라미터 오류");
            }

            if(is_null($date)) {
                throw new Exception("파라미터 오류");
            }

            $conn = my_db_conn();
            
            $conn->beginTransaction();

            $arr_prepare = [
                "id" => $id
            ];

            my_project_delete($conn, $arr_prepare);

            $conn->commit();

            $arr_prepare = [
                "date" => $date
            ];

            $result = my_pop_up_count_select($conn, $arr_prepare);

            if($result === 0) { 
                header("Location: /main.php?date=".$date);
                exit;
            }

            header("Location: /detail.php?&date=".$date);
            exit;
        }

     }
     catch(Throwable $th) { 
        if(!is_null($conn) && $conn->inTransaction()) {
            $conn->rollBack();
        }
        require_once(MY_PATH_ERROR);
        exit;
     }
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/delete.css">
    <link rel="stylesheet" href="./css/common.css">
    <title>Document</title>
</head>
<body>
   <form action="delete.php" method="POST">
    <?php if(isset($_GET["id"])) { ?>
   <input type="hidden" name="id" id="id" value="<?php echo $id ?>">
    <?php } ?>
   <input type="hidden" name="date" id="date" value="<?php echo $date ?>">
  <div class="container">
    <div class="container_box">
     <?php require_once(MY_LIST_FRONT); ?>
      <div class="container_r">
        <div class="delete_box">
            <p>삭제하면 더이상 되돌릴 수 없습니다.</p>
            <p>삭제하시겠습니까?</p>
            <div class="pop_up_btn_box">    
                <button type="submit" class="insert_btn">삭제</button></a>
                <a href="/detail.php?date=<?php echo $date ?><?php if(isset($_GET["id"])) { ?>&id=<?php echo $id ?> <?php } ?>"><button type="button" class="delete_btn">취소</button></a>
            </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>