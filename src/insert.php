<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/config.php"); 
require_once(MY_PATH_DB_LIB);
require_once(MY_LIST_BACK);

$conn = null;

try{
  if(strtoupper($_SERVER["REQUEST_METHOD"]) === "GET"){

    $conn = my_db_conn();
    
    // $date = isset($_GET["date"]) ? $_GET["date"] : "";

    $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
    $month = isset($_GET['month']) ? $_GET['month'] : date('m');
    $month = str_pad((string)$month, 2, "0", STR_PAD_LEFT);
    

    $arr_prepare = [
      "date" => $date
    ];

    $list_chk = my_pop_up_count_select($conn, $arr_prepare);
    
    // 해당 날짜 운동 시간 총합
    
    $arr_prepare  = [
      "date" => $date
    ];
    
    $hour_sum = (int)my_exe_hour($conn, $arr_prepare);

  } else {
    $conn = my_db_conn();

    $date = isset($_POST["date"]) ? $_POST["date"] : "";

    $conn->beginTransaction();
    
    $arr_prepare  = [
      "date" => $_POST["date"]
      ,"title" => $_POST["title"]
      ,"hour" => $_POST["hour"]
      ,"calory" => $_POST["calory"]
      ,"part" => $_POST["part"]
      ,"level" => $_POST["level"]
      ,"memo" => $_POST["memo"]
    ];
    
    if($_POST["memo"] === "") {
      $arr_prepare["memo"] = null;
    } 

    my_list_insert($conn, $arr_prepare);
    
    $id = $conn->lastInsertId();
    $conn -> commit();
    
    header("Location: /detail.php?date=".$date."&id=".$id."#list".$id);
    exit;

  }
} catch(Throwable $th) {
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
  <link rel="stylesheet" href="./css/common.css">
  <link rel="stylesheet" href="./css/insert_update.css">
  <title>Insert</title>
</head>
<body>
  <div class="container">
    <form action="/insert.php" method="post">
      <div class="container_box">
        <!-- 일정 관리 리스트 호출 -->
        <?php require_once(MY_LIST_FRONT) ?>
        
        <div class="container_r">
            <input type="hidden" name="date" id="date" value="<?php echo $date ?>">
            <div class="header_btn">

              <?php if($list_chk !== 0){ ?>
                <a href="/detail.php?date=<?php echo $date ?>"><button type="button">취소</button></a>
                <?php }  else { ?>
                <a href="/main.php?year=<?php echo $year ?>&month=<?php echo $month ?>"><button type="button">취소</button></a>
              <?php } ?>
              <button type="submit">작성</button>
            </div>
            <div class="title_box">
              <div class="title">제목</div>
              <input type="text" class="title_content" name="title" id="title" maxlength="10" required>
            </div>
              
            <div class="exe_time">
              <div class="time">운동 시간</div>
              <div class="time_box">
              <select name="hour" id="hour" class="time_content" required>
                <option value="">선택</option>
                <?php for($hour = 1; $hour <= (24 - $hour_sum); $hour++){ ?>
                  <option value="<?php echo $hour ?>"><?php echo $hour ?></option>
                <?php } ?>
              </select>
                <div class="time_text">시간</div>
              </div>
            </div>
            
            <div class="kcal_box">
              <div class="kcal">칼로리</div>
              <input type="number" min="0" max="4000" class="kcal_content" name="calory" id="calory" maxlength="2" required>
            </div>

            <div class="body_box">
              <div class="part">운동 부위</div>
              <div>
                <select name="part" id="part" class="part_content" required>
                  <option value="">선택</option>
                  <option value="유산소">유산소</option>
                  <option value="엉덩이">엉덩이</option>
                  <option value="복부">복부</option>
                  <option value="허벅지">허벅지</option>
                  <option value="팔">팔</option>
                  <option value="다리">다리</option>
                  <option value="전신">전신</option>
                </select>
              </div>

              <div class="level">운동 강도</div>
              <div>
                <select name="level" id="level" class="part_content" required>
                  <option value="">선택</option>
                  <option value="고강도">고강도</option>
                  <option value="중강도">중강도</option>
                  <option value="저강도">저강도</option>
                </select>
              </div>
            </div>


            <div class="memo_box">
              <div class="memo">메모</div>
              <div class="memo_content">
                <textarea name="memo" id="memo" maxlength="1000" placeholder="최대 1000자"></textarea>
              </div>
            </div>
          </div>
        </div>
      </form>
  </div>
</body>
</html>