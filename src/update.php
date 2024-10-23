<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/config.php"); 
require_once(MY_PATH_DB_LIB);
require_once(MY_LIST_BACK);

$conn = null;

try{
  if(strtoupper($_SERVER["REQUEST_METHOD"]) === "GET") {
    
    $id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
    // $date = isset($_GET["date"]) ? $_GET["date"] : "";

    if($id < 1) {
      throw new Exception("파라미터 오류");
    }
    
    $conn = my_db_conn();

    $arr_prepare = [
      "date" => $date
      ,"id" => $id
    ];
    
    $select_id = my_list_select_id($conn, $arr_prepare);
    $select_id_hour = $select_id["hour"]; // 해당 날짜 운동시간
    
    // 해당 날짜 운동 시간 총합

    $arr_prepare_2  = [
      "date" => $date
    ];
    
    $hour_sum = my_exe_hour($conn, $arr_prepare_2);

  } else {
    $id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;
    $date = isset($_POST["date"]) ? $_POST["date"] : "";
    $title = isset($_POST["title"]) ? $_POST["title"] : "";
    $hour = isset($_POST["hour"]) ? (int)$_POST["hour"] : 1;
    $calory = isset($_POST["calory"]) ? $_POST["calory"] : "";
    $part = isset($_POST["part"]) ? $_POST["part"] : "";
    $level = isset($_POST["level"]) ? $_POST["level"] : "";
    $memo = isset($_POST["memo"]) ? $_POST["memo"] : "";


    if($id < 1 || $title === "" || $date === ""){
      throw new Exception("파라미터 오류");
    }

    $conn = my_db_conn();

    $conn->beginTransaction();

    $arr_prepare = [
      "id" => $id
      ,"date" => $date
      ,"title" => $title
      ,"hour" => $hour
      ,"calory" => $calory
      ,"part" => $part
      ,"level" => $level
      ,"memo" => $memo
    ];

    if($_POST["memo"] === "") {
      $arr_prepare["memo"] = null;
    } 

    my_list_update($conn, $arr_prepare);

    $conn->commit();

    header("Location: /detail.php?date=".$date."&id=".$id."#list".$id);
    exit;

  }

} catch(Throwable $th) {
  if(!is_null($conn) && $conn->inTransaction()){
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
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/insert_update.css">
    <title>Update</title>
</head>
<body>
  <div class="container">
    <form action="/update.php" method="post">
      <div class="container_box">
        <!-- 일정 관리 리스트 호출 -->
        <?php require_once(MY_LIST_FRONT) ?>


        <div class="container_r">

          <input type="hidden" name="date" value="<?php echo $date ?>">
          <input type="hidden" name="id" value="<?php echo $id ?>">

          <div class="header_btn">
            <a href="/detail.php?date=<?php echo $date ?>&id=<?php echo $id ?>"><button type="button">취소</button></a>
            <button type="submit">수정</button>
          </div>
          <div class="title_box">
            <div class="title">제목</div>
            <input type="text" class="title_content" name="title" id="title" maxlength="10" value="<?php echo $select_id["title"] ?>" required>
          </div>
            
          <div class="exe_time">
            <div class="time">운동 시간</div>
            <div class="time_box">
              <select name="hour" id="hour" class="time_content" required>
                <?php for($hour = 1; $hour <= (24 - $hour_sum + $select_id_hour); $hour++){ ?>
                  <option value="<?php echo $hour ?>" <?php echo $select_id["hour"] === $hour ? "selected" : ""; ?>><?php echo $hour ?></option>
                <?php } ?>
              </select>
              <div class="time_text">시간</div>
            </div>
          </div>
          
          <div class="kcal_box">
            <div class="kcal">칼로리</div>
            <input type="number" name="calory" id="calory" min="0" max="4000" class="kcal_content" value="<?php echo $select_id["calory"] ?>" required>
          </div>

          <div class="body_box">
            <div class="part">운동 부위</div>
            <div>
              <select name="part" id="part" class="part_content" required>
                <option value="유산소" <?php echo $select_id["part"] === "유산소" ? "selected" : ""; ?>>유산소</option>
                <option value="엉덩이" <?php echo $select_id["part"] === "엉덩이" ? "selected" : ""; ?>>엉덩이</option>
                <option value="복부" <?php echo $select_id["part"] === "복부" ? "selected" : ""; ?>>복부</option>
                <option value="허벅지" <?php echo $select_id["part"] === "허벅지" ? "selected" : ""; ?>>허벅지</option>
                <option value="팔" <?php echo $select_id["part"] === "팔" ? "selected" : ""; ?>>팔</option>
                <option value="다리" <?php echo $select_id["part"] === "다리" ? "selected" : ""; ?>>다리</option>
                <option value="전신" <?php echo $select_id["part"] === "전신" ? "selected" : ""; ?>>전신</option>
              </select>
            </div>


            <div class="level">운동 강도</div>
            <div>
              <select name="level" id="level" class="part_content" required>
                <option value="고강도" <?php echo $select_id["level"] === "고강도" ? "selected" : ""; ?>>고강도</option>
                <option value="중강도" <?php echo $select_id["level"] === "중강도" ? "selected" : ""; ?>>중강도</option>
                <option value="저강도" <?php echo $select_id["level"] === "저강도" ? "selected" : ""; ?>>저강도</option>
              </select>
            </div>
          </div>


          <div class="memo_box">
            <div class="memo">메모</div>
            <div class="memo_content">
              <textarea name="memo" id="memo" maxlength="1000"><?php echo $select_id["memo"] ?></textarea>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</body>
</html>