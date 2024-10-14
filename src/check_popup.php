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

            if(is_null($date)) {
                throw new Exception("파라미터 오류");
            }

            $conn = my_db_conn();

            $arr_prepare = [
                "date" => $date
                ,"id" => $id
            ];

            $select_id = my_list_select_id($conn, $arr_prepare);

            $arr_prepare_select = [
                "date" => $date
            ];

            $result = my_list_select($conn, $arr_prepare_select);


        }else if(strtoupper($_SERVER["REQUEST_METHOD"]) === "POST") {

            $id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;

            $complete = isset($_POST["complete"]) ? (int)$_POST["complete"] : 0;
            
            $date = isset($_POST["date"]) ? $_POST["date"] : "";

            if($id < 1) {
                throw new Exception("파라미터 오류");
            }

            if(is_null($date)) {
                throw new Exception("파라미터 오류");
            }

            if($complete === 0) {

                $conn = my_db_conn();
                
                $conn->beginTransaction();
    
                
                $arr_prepare = [
                    "complete" => 1
                    ,"id" => $id
                ];
                
                $result = my_project_update_popup($conn, $arr_prepare);
                
                $conn->commit();
            }

            if($complete === 1) {

                $conn = my_db_conn();
                
                $conn->beginTransaction();
    
                
                $arr_prepare = [
                    "complete" => 0
                    ,"id" => $id
                ];
                
                $result = my_project_update_popup($conn, $arr_prepare);

                $conn->commit();
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
    <link rel="stylesheet" href="./css/check_popup.css">
    <title>Document</title>
</head>
<body>
   <form action="check_popup.php" method="POST">
   <input type="hidden" name="id" id="id" value="<?php echo $id ?>">
   <input type="hidden" name="date" id="date" value="<?php echo $date ?>">
   <input type="hidden" name="complete" id="complete" value="<?php echo $select_id["complete"] ?>">
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
                    <a href="./pop_up.php?date=<?php echo $date ?>&id=<?php echo $value["id"] ?>"><div><?php echo $value["title"] ?></div></a>
                </div>
            </div>
           <?php } ?>
          <div class="list_plus">
            <div class="list_plus_btn">
                <a href="insert.php?date=<?php echo $date ?>"><p>+</p></a>
            </div>            
          </div>
        </div>
      </div>

      <?php if($select_id["complete"] === 0) { ?>
      <div class="container_r">
         <div class="delete_box">
                    <p><?php echo $select_id["title"] ?></p>
                    <p>이 운동 일정을 완료하시겠습니까?</p>
             <div class="pop_up_btn_box">    
                     <button type="submit" class="insert_btn">완료</button></a>
                    <a href="detail.php?date=<?php echo $date ?>&id=<?php echo $id ?>"><button class="delete_btn">취소</button></a>
             </div>
        </div>
    </div>
    <?php } ?>

      <?php if($select_id["complete"] === 1) { ?>
      <div class="container_r">
         <div class="delete_box">
                    <p><?php echo $select_id["title"] ?></p>
                    <p>이 운동 일정을</p>
                    <p>미완료 상태로 되돌리시겠습니까?</p>
             <div class="pop_up_btn_box">    
                     <button type="submit" class="insert_btn">확인</button></a>
                    <a href="detail.php?date=<?php echo $date ?>&id=<?php echo $id ?>"><button class="delete_btn">취소</button></a>
             </div>
        </div>
      </div>
      <?php } ?>

    </div>
  </div>
</body>
</html>