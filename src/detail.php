<?php

  require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
  require_once(MY_PATH_DB_LIB);

  /* 일정 관리 리스트(백) 호출 */
  require_once(MY_LIST_BACK);

  try {

    // date 유효성 검사
    $date = isset($_GET["date"]) ? $_GET["date"] : null;
  
    if(!is_null($date) && mb_strlen($date) === 10) { // 길이 맞는지 확인
      $arr_date = explode("-", $date); // 0 year, 1 month, 2 day 3개로 분할
  
      if(count($arr_date) !== 3) { // 분할된 배열이 3개로 왔는지 확인
        throw new Exception("잘못된 접근 확인됨"); // 안왓으면 던짐
      }
  
      foreach($arr_date as $no => $item) { // 0 year, 1 month, 2 day 3개
        if(is_numeric($item) && $item > 0) { // 숫자여야하고 그 숫자는 0이나 음수가 아니여야함
          if($no === 0){ continue; } // year은 패스
    
          // month는 12월을 넘지않아야하며, day는 그달의 말일을 넘으면 안된다
          if(($no === 1 && $item > 12) || ($no === 2 && $item > date("t", strtotime($date)))) {
            throw new Exception("잘못된 접근 확인됨"); // 넘었으면 던짐
          }
        }else { // 아니면 던짐
          throw new Exception("잘못된 접근 확인됨");
        }
      }
    }else { // 아니면 던짐
      throw new Exception("잘못된 접근 확인됨");
    }

    $conn = my_db_conn(); // 칼로리 합계를 가져와야 하기에 DB 접속

    if(isset($arr_prepare)) { // 기존에 프리페어가 있으면
      unset($arr_prepare); // 기존 프리페어 배열 삭제
    }

    $arr_prepare["date"] = $date; // 프리페어 재기입

    $sum_cal = my_select_calory_sum($conn, $arr_prepare); // 칼로리 합산
    $sum_cal = $sum_cal !== "0" ? (int)$sum_cal : 0; // 문자열로 온걸 형변환

    $pct = (int)(round(($sum_cal * 100) / MY_CALORY_MAX)); // 달성도 퍼센트 계산, % 출력용
    
    $pct_bar = $pct > 100 ? 100 : $pct; // 게이지바 조정용, 높이를 맞춰야하기에 100 이상을 초과하면 안됨

    $pct_line = $pct_bar - 0.9; // 회색선 조정용
    $pct_box = $pct_bar - 4.9; // 칼로리 박스 조정용

  }catch(Throwable $th) {
    require_once(MY_PATH_ERROR);
    exit;
  }
  
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/css/common.css">
  <link rel="stylesheet" href="/css/detail.css">

  <style>
    /* 게이지 바 */
    .detail_gauge_bar span {
      height: <?php echo $pct_bar; ?>%;
        animation: plus 0.9s 1;

      /* 100% 되는 조건식 넣어서 레디우스 수정 */
      <?php if($pct_bar === 100) { ?>
        border-radius: 30px 30px 10px 10px;
      <?php } ?>
    }

    /* 선, 퍼센트 계산값 - 1 */
    .detail_gauge_line {
      bottom: <?php echo $pct_line; ?>%;
      
      /* 게이지가 0%면 움직이지 않게 고정 */
      <?php if($pct_bar > 0) {?>
        animation: up 0.9s 1;
      <?php } ?>
    }

    /* 칼로리 박스, 퍼센트 계산값 -5 */
    .detail_gauge_box {
      /* bottom: 25%; */
      bottom: <?php echo $pct_box; ?>%;
      
      /* 게이지가 0%면 움직이지 않게 고정 */
      <?php if($pct_bar > 0) {?>
        animation: kcal 0.92s 1;
      <?php } ?>
    }

    @keyframes plus {
      0% { height: 0; }

      100% { 
        height: <?php echo $pct_bar; ?>%; 
      }
    }

    @keyframes up {
      0% { bottom: 0; }

      100% { 
        bottom: <?php echo $pct_line; ?>%; 
      }
    }

    @keyframes kcal {
      0% { bottom: 0; }

      100% { 
        bottom: <?php echo $pct_box; ?>%; 
      }
    }
  </style>

  <title>
    <?php 
      if(isset($date)) { 
        echo $date; 
      } 
    ?>&nbsp;상세 일정
  </title>
</head>
<body>
  <div class="container">
    <div class="container_box">

      <!-- 일정 관리 리스트(프론트) 호출 -->
      <?php require_once(MY_LIST_FRONT) ?>

      <!-- 우측 컨테이너 DIV -->
      <div class="container_r">

        <!-- 우측 상세 DIV -->
        <div class="detail">

          <!-- 선택해둔 일정이 없으면 -->
          <?php 
          if(empty($select_id)) { ?>
            <div class="non_info">
              <div class="detail_non_box">
                선택하신 일정이 없습니다 <br />
                일정을 선택해주세요.
              </div>
            </div>

          <!-- 있으면 정보 출력 -->
          <?php 
          }else { ?>
            <!-- 좌측 일정 정보 출력 DIV -->
            <div class="detail_info">

              <!-- 좌측 상단 공백용 DIV -->
              <div></div>

              <!-- 탑 우측 이동용 버튼 DIV -->
              <div class="detail_top_btn"><?php
                // 혹시나 모를 데이터 없음을 대비하여 검사 
                if(isset($date) && (isset($select_id["id"]) && $select_id["id"] !== 0)) { ?>
                  <a href="/update.php?date=<?php echo $date ?>&id=<?php echo $select_id["id"] ?>">
                    <button class="btn_label bcolor_earthy">
                      수정
                    </button>
                  </a>

                  <a href="/delete.php?date=<?php echo $date ?>&id=<?php echo $select_id["id"] ?>">
                    <button class="btn_label bcolor_darkred">
                      삭제
                    </button>
                  </a><?php 
                } ?>
              </div>

              <!-- 나머지 내용 출력 DIV -->
              <div class="detail_part">
                <div class="detail_item detail_label">
                  제목
                </div>

                <div class="detail_item detail_content"><?php 
                  if(isset($select_id["title"]) && $select_id["title"] !== "") { 
                    echo $select_id["title"];
                  }else {?>
                    <span class="color_red">
                      미설정
                    </span>
                  <?php } ?>
                </div>
              </div>

              <div class="detail_part">
                <div class="detail_item detail_label">
                  운동 시간
                </div>

                <div class="detail_item detail_content"><?php 
                  if(isset($select_id["hour"]) && $select_id["hour"] !== "") { 
                    echo $select_id["hour"]; ?>&nbsp;시간<?php
                  }else {?>
                    <span class="color_red">
                      미설정
                    </span>
                  <?php } ?>
                </div>
              </div>

              <div class="detail_part">
                <div class="detail_item detail_label">
                  칼로리
                </div>

                <div class="detail_item detail_content"><?php 
                  if(isset($select_id["calory"]) && $select_id["calory"] !== "") { 
                    echo $select_id["calory"]; ?>&nbsp;kcal<?php
                  }else {?>
                    <span class="color_red">
                      미설정
                    </span>
                  <?php } ?>
                </div>
              </div>

              <div class="detail_part">
                <div class="detail_item detail_label">
                  운동 부위
                </div>

                <div class="detail_item detail_content"><?php 
                  if(isset($select_id["part"]) && $select_id["part"] !== "") { 
                    echo $select_id["part"]; 
                  }else{?>
                    <span class="color_red">
                      미설정
                    </span>
                  <?php } ?>
                </div>
              </div>

              <div class="detail_part">
                <div class="detail_item detail_label">
                  운동 강도
                </div>

                <div class="detail_item detail_content"><?php 
                  if(isset($select_id["level"]) && $select_id["level"] !== "") { 
                    echo $select_id["level"]; 
                  }else{?>
                    <span class="color_red">
                      미설정
                    </span>
                  <?php } ?>
                </div>
              </div>

              <div class="detail_part">
                <div class="detail_item detail_label detail_memo_label">
                  메모
                </div>

                <div class="detail_content detail_memo_content">
                  <div><?php echo $select_id["memo"] ?></div>
                </div>
              </div>

              <!-- 좌측 하단 공백용 DIV -->
              <div></div>

            </div><?php 
          } ?>

          <!-- 우측 게이지 정보 출력 DIV -->
          <div class="detail_gauge">

            <!-- 우측 상단 공백용 DIV -->
            <div></div>

            <!-- 목표 달성치 텍스트 DIV -->
            <div>
              <div class="detail_gauge_top">
                <div class="detail_item detail_gauge_title"><?php 
                      if(isset($pct)) { 
                        // 100% 완료
                        if($pct === 100) {
                        ?><span class="color_green">
                            오늘치 운동 목표량 달성!
                          </span><?php
                        // 100% 초과
                        }elseif($pct > 100) { 
                        ?><span class="color_red">
                            오늘치 운동 목표량 초과!
                          </span><?php
                      }else { 
                          ?>달성 목표치: <?php echo MY_CALORY_MAX ?>&nbsp;kcal<?php
                      }
                    }else { 
                        ?>달성 목표치: <?php echo MY_CALORY_MAX ?>&nbsp;kcal<?php 
                    } ?>
                </div>
              </div>
            </div>

            <!-- 게이지 바 출력 DIV -->
            <div class="detail_gauge_print">
              <div class="detail_gauge_bar">
                <span <?php 
                  if(isset($pct)){ 
                    // 100% 완료
                    if($pct === 100) {
                      ?>class="color_green"<?php 
                    // 100% 초과
                    }elseif($pct > 100){
                      ?>class="color_red"<?php
                    }
                  }
                ?>><?php 
                  echo $pct ?>%
                </span>
              </div>

              <!-- 선과 칼로리 표시 DIV -->
              <div class="detail_gauge_view">
                <!-- 게이지 옆의 선 DIV -->
                <div class="detail_gauge_line">
                  <hr />
                </div>

                <!-- 칼로리 표시 박스 DIV -->
                <div class="detail_gauge_box">
                  <span <?php 
                    if(isset($sum_cal)){ 
                      // 칼로리와 설정값이 일치 혹은 이상인데 100% 라면 초록 폰트
                      if(($sum_cal === MY_CALORY_MAX) || ($sum_cal >= MY_CALORY_MAX && (isset($pct) && $pct === 100))) {
                        ?>class="color_green"<?php 
                      // 넘으면 초과로 판단하여 빨강 폰트 적용
                      }elseif($sum_cal > MY_CALORY_MAX){
                        ?>class="color_red"<?php
                      }
                    }
                  ?>>
                    <?php echo $sum_cal ?>
                  </span>&nbsp;kcal
                </div>
              </div>
            </div>

            <!-- 우측 하단 공백용 DIV -->
            <div></div>

          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>