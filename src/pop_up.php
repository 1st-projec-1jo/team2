<?php
    require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
    require_once(MY_PATH_DB_LIB);


    try {
        $conn = my_db_conn();

        $date = isset($_GET["date"]) ? $_GET["date"] : "";

        $arr_prepare = [
            "date" => $date
        ];


        $result = my_pop_up_count_select($conn, $arr_prepare);
    }
    catch(Throwable $th) {
       echo $th->getMessage();
    }
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/pop_up.css">
    <title>팝업 페이지</title>
</head>
<body>
    <div class="container">
        <div class="container_box">
            <?php if($result === 0) { ?>
            <div class="pop_up_box">
                <p class="pop_up_cal"><?php echo $date ?></p>
                <br>
                <br>
                <p class="pop_up_content">이 날짜에 새로 작성하시겠습니까?</p>
                <div class="pop_up_btn_box">    
                <a href="insert.php?date=<?php echo $date ?>"><button class="insert_btn">작성</button></a>
                <a href="main.php"><button class="delete_btn">취소</button></a>
                </div>
            </div>
            <?php } ?>

            <?php if($result !== 0) { ?>
            <div class="pop_up_box">
                <div class="back_btn">
                <a href="main.php"><button class="X_btn"></button></a>
                </div>
                <p class="pop_up_cal"><?php echo $date ?></p>
                <br>
                <br>
                <p class="pop_up_content">이 날짜에 <?php echo $result ?>개의 일정이 있습니다.</p>
                <p class="pop_up_content">일정을 추가하시겠습니까?</p>
                <div class="pop_up_btn_box pop_up_btn_box2">    
                <a href="insert.php?date=<?php echo $date ?>"><button class="insert_btn">추가</button></a>
                <a href="detail.php"><button class="delete_btn">보기</button></a>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>