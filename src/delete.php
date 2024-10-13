<?php
    require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
    require_once(MY_PATH_DB_LIB);

    $conn = null;

    try {
        if(strtoupper($_SERVER["REQUEST_METHOD"]) === "GET") {
            
            $id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
            
            $date = isset($_GET["date"]) ? $_GET["date"] : "";

            if($id < 1) {
                throw new Exception("파라미터 오류");
            }

            $conn = my_db_conn();

            $arr_prepare = [
                "date" => $date
            ];

            $result = my_list_select($conn, $arr_prepare);


        }else if(strtoupper($_SERVER["REQUEST_METHOD"]) === "POST") {

            $id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;
            
            $date = isset($_POST["date"]) ? (int)$_POST["date"] : "";

            if($id < 1) {
                throw new Exception("파라미터 오류");
            }

            $conn = my_db_conn();
            
            $conn->beginTransaction();

            $arr_prepare = [
                "id" => $id
            ];

            my_project_delete($conn, $arr_prepare);

            $conn->commit();

            header("Location: /pop_up.php?&date=".$date);
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
    <title>Document</title>
</head>
<body>
   <form action="delete.php" method="POST">
   <input type="hidden" name="id" id="id" value="<?php echo $id ?>">
   <input type="hidden" name="date" id="date" value="<?php echo $date ?>">
  <div class="container">
    <div class="container_box">
      <div class="container_l">
        <div class="date_btn">
          <button type="button"><?php echo $date ?></button>
        </div>
        <hr>
        <br>
        <hr>
        <div class="list_box">
            <?php foreach($result as $value) { ?>
            <div class="list">
                <div class="list_check">
                    <button type="button" class="chk_btn"><img src="./img/src/img/free-icon-check-7543187.png" width="30px" height="30px"></button>
                </div>
                <div class="list_title">
                    <a href="./detail.html"><div><?php echo $value["title"] ?></div></a>
                </div>
            </div>
           <?php } ?>
          <div class="list_plus">
            <div class="list_plus_btn">
            <p>+</p>
          </div>            
          </div>
        </div>
      </div>
      <div class="container_r">
        <div class="delete_box">
            <p>삭제하면 더이상 되돌릴 수 없습니다.</p>
            <p>삭제하시겠습니까?</p>
            <div class="pop_up_btn_box">    
                <button type="submit" class="insert_btn">삭제</button></a>
                <a href=""><button class="delete_btn">취소</button></a>
            </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>