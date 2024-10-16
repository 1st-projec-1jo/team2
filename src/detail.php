<?php
    require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
    require_once(MY_PATH_DB_LIB);

    /* 일정 관리 리스트(백) 호출 */
    require_once(MY_LIST_BACK);

    try {

      $conn = my_db_conn();

      unset($arr_prepare); // 기존 프리페어 삭제
      $arr_prepare["date"] = $date;

      $sum_cal = my_select_calory_sum($conn, $arr_prepare); // 칼로리 합산
      $sum_cal = $sum_cal !== "0" ? (int)$sum_cal : 0;

      $pct = round(($sum_cal * 100) / MY_CALORY_MAX); // 달성도 퍼센트 계산
      
      $pct_bar = $pct > 100 ? 100 : $pct; // 게이지바 조정용
      
      $pct_line = $pct_bar - 1; // 선 조정용
      $pct_box = $pct_bar - 5; // 박스 조정용

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
  <link rel="stylesheet" href="/css/detail.css" />

  <style>
    /* 게이지 바 */
    .detail_gauge_bar span {
      /* height: 30%; */
      height: <?php echo $pct_bar; ?>%;
      animation: plus 0.9s 1;

      /* 100% 되는 조건식 넣어서 레디우스 수정 */
      <?php if($pct_bar === 100) { ?>
        border-radius: 30px 30px 10px 10px;
      <?php } ?>
    }

    /* 선, 퍼센트 계산값 - 1 */
    .detail_gauge_line {
      /* bottom: 29%; */
      bottom: <?php echo $pct_line; ?>%;
      animation: up 0.9s 1;
    }

    /* 박스, 퍼센트 계산값 -5 */
    .detail_gauge_box {
      /* bottom: 25%; */
      bottom: <?php echo $pct_box; ?>%;
      animation: kcal 0.92s 1;
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

  <title>Document</title>
</head>
<body>
  <div class="container">
    <div class="container_box">

      <!-- 일정 관리 리스트(프론트) 호출 -->
      <?php require_once(MY_LIST_FRONT) ?>

      <div class="container_r">
          <!-- 우측 상세 DIV -->
          <div class="detail">
            <!-- 선택해둔 일정이 없으면 -->
            <?php if(empty($select_id)){ ?>
              <div class="non_info">
                선택하신 일정이 없습니다 <br />
                일정을 선택헤주세요.
              </div>
            <!-- 있으면 정보 출력 -->
            <?php }else { ?>
              <!-- 좌측 일정 정보 출력 DIV -->
              <div class="detail_info">
                <div></div>

                <!-- 탑 우측 이동용 버튼 DIV -->
                <div class="detail_top_btn">
                  <a href="/update.php?date=<?php echo $date ?>&id=<?php echo $select_id["id"] ?>">
                    <button class="btn_label bcolor_earthy">수정</button>
                  </a>

                  <a href="/delete.php?date=<?php echo $date ?>&id=<?php echo $select_id["id"] ?>">
                    <button class="btn_label bcolor_darkred">삭제</button>
                  </a>
                </div>

                <!-- 나머지 내용 출력 DIV -->
                <div class="detail_part">
                  <div class="detail_item detail_label">제목</div>
                  <div class="detail_item detail_content"><?php 
                    if(!is_null($select_id["title"]) && $select_id["title"] !== "") { 
                      echo $select_id["title"];
                    }else {?>
                      <span class="color_red">
                        미설정
                      </span>
                    <?php } ?>
                  </div>
                </div>

                <div class="detail_part">
                  <div class="detail_item detail_label">운동 시간</div>
                  <div class="detail_item detail_content"><?php 
                    if(!is_null($select_id["hour"]) && $select_id["hour"] !== "") { 
                      echo $select_id["hour"]; ?> 시간<?php
                    }else {?>
                      <span class="color_red">
                        미설정
                      </span>
                    <?php } ?>
                  </div>
                </div>

                <div class="detail_part">
                  <div class="detail_item detail_label">칼로리</div>
                  <div class="detail_item detail_content"><?php 
                    if(!is_null($select_id["calory"]) && $select_id["calory"] !== "") { 
                      echo $select_id["calory"]; ?> kcal<?php
                    }else {?>
                      <span class="color_red">
                        미설정
                      </span>
                    <?php } ?>
                  </div>
                </div>

                <div class="detail_part">
                  <div class="detail_item detail_label">운동 부위</div>
                  <div class="detail_item detail_content"><?php 
                    if(!is_null($select_id["part"]) && $select_id["part"] !== "") { 
                      echo $select_id["part"]; 
                    }else{?>
                      <span class="color_red">
                        미설정
                      </span>
                    <?php } ?>
                  </div>
                </div>

                <div class="detail_part">
                  <div class="detail_item detail_label">운동 강도</div>
                  <div class="detail_item detail_content"><?php 
                    if(!is_null($select_id["level"]) && $select_id["level"] !== "") { 
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
                  <div class="detail_item detail_content detail_memo_content">
                    <div><?php echo $select_id["memo"] ?></div>
                  </div>
                </div>

                <div></div>
              </div>

              <!-- 우측 게이지 정보 출력 DIV -->
              <!-- 권장 800 Kcal 예정 -->
              <div class="detail_gauge">
                <div></div>

                <!-- 목표 달성치 텍스트 DIV -->
                <div>
                  <div class="detail_gauge_top">
                    <div class="detail_item detail_gauge_title">
                      <?php if(isset($pct) && $pct >= 100) { ?>
                        <span class="color_green">
                          오늘치 운동 목표량 달성!
                        </span>
                      <?php }else { ?>
                        달성 목표치: <?php echo MY_CALORY_MAX ?> kcal
                      <?php } ?>
                    </div>
                  </div>
                </div>

                <!-- 게이지 바 출력 DIV -->
                <div class="detail_gauge_print">
                  <div class="detail_gauge_bar">
                    <span <?php 
                      if(isset($pct)){ 
                        if($pct === 100) {
                          ?>class="color_green"<?php 
                        }elseif($pct > 100){
                          ?>class="color_red"<?php
                        }
                      }?>>
                      <?php echo $pct ?>%
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
                        if($sum_cal === MY_CALORY_MAX) {
                          ?>class="color_green"<?php 
                        }elseif($sum_cal > MY_CALORY_MAX){
                          ?>class="color_red"<?php
                        }
                      }?>>
                        <?php echo $sum_cal ?>
                      </span>&nbsp;kcal
                    </div>
                  </div>
                </div>

                <div></div>
              </div>
            <?php } ?>

      </div>
    </div>
  </div>

</body>
</html>