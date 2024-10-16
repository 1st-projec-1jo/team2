<?php
    require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
    require_once(MY_PATH_DB_LIB);

    /* 일정 관리 리스트(백) 호출 */
    require_once(MY_LIST_BACK);

?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./css/common.css">
  <link rel="stylesheet" href="./css/detail.css" />

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
                    <button class="btn-label bcolor-earthy">수정</button>
                  </a>

                  <a href="./delete.php?date=<?php echo $date ?>&id=<?php echo $select_id["id"] ?>">
                    <button class="btn-label bcolor-darkred">삭제</button>
                  </a>
                </div>

                <!-- 나머지 내용 출력 DIV -->
                <div class="detail_part">
                  <div class="detail_item detail_label">일정명</div>
                  <div class="detail_item detail_content"><?php echo $select_id["title"] ?></div>
                </div>

                <div class="detail_part">
                  <div class="detail_item detail_label">예정시간</div>
                  <div class="detail_item detail_content"><?php echo str_pad((string)$select_id["hour"], 2, "0", STR_PAD_LEFT) ?> 시간</div>
                </div>

                <div class="detail_part">
                  <div class="detail_item detail_label">소비칼로리</div>
                  <div class="detail_item detail_content"><?php echo $select_id["calory"] ?> Kcal</div>
                </div>

                <div class="detail_part">
                  <div class="detail_item detail_label">운동부위</div>
                  <div class="detail_item detail_content"><?php echo $select_id["part"] ?></div>
                </div>

                <div class="detail_part">
                  <div class="detail_item detail_label">운동강도</div>
                  <div class="detail_item detail_content"><?php echo $select_id["level"] ?></div>
                </div>

                <!-- <div class="detail_part detail_memo"> -->
                <div class="detail_part">
                  <div class="detail_item detail_label detail_memo-label">
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
                <!-- <div class="detail_gauge_bar">
                  <span></span>
                </div> -->
              </div>
            <?php } ?>

      </div>
    </div>
  </div>

</body>
</html>