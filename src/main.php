<?php
//데이터 가져오기
require_once($_SERVER["DOCUMENT_ROOT"]."/config.php"); 
require_once(MY_PATH_DB_LIB);

$conn = null;

try {

    //달력 구현하기
	// GET으로 넘겨 받은 year값이 있다면 넘겨 받은걸 year변수에 적용하고 없다면 현재 년도
	$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
	// GET으로 넘겨 받은 month값이 있다면 넘겨 받은걸 month변수에 적용하고 없다면 현재 월
	$month = isset($_GET['month']) ? $_GET['month'] : date('m');
    $m = isset($_GET['month']) ? $_GET['month'] : date('m');
	$date = "$year-$month"; // 현재 날짜
	$time = strtotime($date); // 현재 날짜의 타임스탬프
	$start_week = date('w', $time); // 1. 시작 요일
	$day = date("t", $time); // 2. 현재 달의 총 날짜(2월 28일 설정)
    $today  = date("Y-m-d");
    $total_week = ceil(($day + $start_week) / 7);  // 3. 현재 달의 총 주차

    $conn = my_db_conn();
    $arr_prepare = [
        "start_day" => $date."-01"
        , "end_day" => $date."-".$day
    ];
    $result = main_cal_list_cnt($conn, $arr_prepare);
   
    $arr_day_cnt = [];

    foreach($result as $item) {
        $arr_day_cnt[$item["date"]] = $item["cnt"];
    }

} catch (Throwable $th) {
    echo $th->getMessage();
    exit;
}
?>






<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/common_2.css">
    <title>main</title>
</head>
<body>
    <div class="container">
        <div class="main container_box">
            <div class="main_title">날짜를 선택해주세요</div>
                <div class="calender">
                    <div class="cal_btn">
                        
                    
                        <!-- 이전달과 다음달 버튼 -->
                        <?php if($m ==1): ?>
                        <a href="/main.php?year=<?php echo $year-1?>&month=<?php echo $month+11?>"><img src="/img/icon_rocket_l.png" width="40px" height="35px"></a>
                        <h2><?php echo $year."-".$month ?></h2>
                        <a href="/main.php?year=<?php echo $year ?>&month=<?php echo $month+1 ?>"><img src="/img/icon_rocket_r.png" width="40px" height="35px"></a>
                        
                        
                        <?php elseif($m ==12):?> 
                        <a href="/main.php?year=<?php echo $year ?>&month=<?php echo $month-1 ?>"><img src="/img/icon_rocket_l.png" width="40px" height="35px"></a>
                        <h2><?php echo $year."-".$month ?></h2>
                        <a href="/main.php?year=<?php echo $year+1 ?>&month=<?php echo $month=1 ?>"><img src="/img/icon_rocket_r.png" width="40px" height="35px"></a>
                    
                    
                        <?php else: ?> 
                        <a href="/main.php?year=<?php echo $year ?>&month=<?php echo $month-1 ?>"><img src="/img/icon_rocket_l.png" width="40px" height="35px"></a>
                        <h2><?php echo $year."-".$month ?></h2>
                        <a href="/main.php?year=<?php echo $year ?>&month=<?php echo $month+1 ?>"><img src="/img/icon_rocket_r.png" width="40px" height="35px"></a>
                        <?php endif ?>  
                    </div>

                <!-- 날짜 선택 방법1. 셀렉트 박스로 -->
                <form action="/main.php">
                <select name="year" id="year">
                    <?php for($y=2000; $y<=2050; $y++) { ?>
                    <option value="<?php echo $y?>"<?php if($year==$y) echo "selected"; ?>><?php echo $y?>년</option>
                    <?php }?>
                </select>

                <select name="month" id="month">
                    <?php for($m=1; $m<=12; $m++) { ?>
                    <option value="<?php if($m !==10 && $m !==11 && $m!==12) { echo "0".$m;}  
                    else {echo $m;} ?>"<?php if($month==$m) echo "selected"; ?>>


                    <?php echo $m ?>월</option>
                    <?php }?>
                </select>

                <button class="btn_move" type="submit">이동</button>
                </form>
            </div>

        
                        
        <!-- 요일 -->
        <div class="weeks">
                <div>sun</div>
                <div>mon</div>
                <div>tue</div>
                <div>wed</div>
                <div>thu</div>
                <div>fri</div>
                <div>sat</div>
        </div>

        <!-- 날짜 -->
        <div class="days">
            <!-- 달력에 날짜가 없는 빈공간 표시 (앞)-->
            <?php for($i=0; $i<$start_week; $i++) { ?>
                    <div class="day_blank"></div>
            <?php }?>
    
            <!-- 1 ~ 31날짜 반복 표시 -->
            <?php for($i=1; $i<=$day; $i++) { ?>

            <!-- 현재 날짜에 숫자 동그라미 색깔 지정 -->
            <?php if($date.'-'.str_pad((string)$i, 2, "0", STR_PAD_LEFT) === $today) {?>
                <a href="/main_popup.php?date=<?php echo $date.'-'.str_pad((string)$i, 2, "0", STR_PAD_LEFT) ?>">
                <button class="today_btn" type="button"><?php echo $i ?></button> 
            <?php }?>

            <?php if($date.'-'.str_pad((string)$i, 2, "0", STR_PAD_LEFT) !== $today) { ?>
                <a href="/main_popup.php?date=<?php echo $date.'-'.str_pad((string)$i, 2, "0", STR_PAD_LEFT) ?>">
                <div><?php echo $i ?></div> 
            <?php }?>
                    
            <!-- 일정 데이터 받아오기 -->
            <?php
            if(array_key_exists($date.'-'.str_pad((string)$i, 2, "0", STR_PAD_LEFT), $arr_day_cnt)) {
            ?>
                
            <p><img src="/img/icon_rocket.png" width="30px" height="25px"><?php echo $arr_day_cnt[$date.'-'.str_pad((string)$i, 2, "0", STR_PAD_LEFT)] ?>개의 일정</p>
                
            <?php
            }
            ?>

                </a>
                    
            <?php }?>

                <!-- 달력에 날짜가 없는 빈공간 표시 (끝) -->
                <?php for($i=0; $i<((($total_week)*7)-(($day)+$start_week)); $i++) { ?>
                            <div class="day_blank"></div>
                <?php } ?>
        
        
            </div>


        
        </div>
    </div>
</body>
</html>