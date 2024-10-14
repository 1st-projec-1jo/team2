<?php 
	// GET으로 넘겨 받은 year값이 있다면 넘겨 받은걸 year변수에 적용하고 없다면 현재 년도
	$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
	// GET으로 넘겨 받은 month값이 있다면 넘겨 받은걸 month변수에 적용하고 없다면 현재 월
	$month = isset($_GET['month']) ? $_GET['month'] : date('m');
    $m = isset($_GET['month']) ? $_GET['month'] : date('m');
	$date = "$year-$month"; // 현재 날짜
	$time = strtotime($date); // 현재 날짜의 타임스탬프
	$start_week = date('w', $time); // 1. 시작 요일
	$total_day = date('t', $time); // 2. 현재 달의 총 날짜

    // 필요없는 느낌
	$total_week = ceil(($total_day + $start_week) / 7);  // 3. 현재 달의 총 주차
    
    // 2월 28일 설정
    $day = date("t", $time);

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
                        <?php if($m ==1): ?>
                        <!-- 이전달 -->
                        <a href="/main.php?year=<?php echo $year-1?>&month=<?php echo $month+11?>">이전 달</a>
                        <h2><?php echo $year."-".$month ?></h2>
                        <a href="/main.php?year=<?php echo $year ?>&month=<?php echo $month+1 ?>">다음 달</a>
                        
                        
                        <?php elseif($m ==12):?> 
                        <a href="/main.php?year=<?php echo $year ?>&month=<?php echo $month-1 ?>">이전 달</a>
                        <h2><?php echo $year."-".$month ?></h2>
                        <a href="/main.php?year=<?php echo $year+1 ?>&month=<?php echo $month=1 ?>">다음 달</a>
                    
                    
                        <?php else: ?> 
                        <a href="/main.php?year=<?php echo $year ?>&month=<?php echo $month-1 ?>">이전 달</a>
                        <h2><?php echo $year."-".$month ?></h2>
                        <a href="/main.php?year=<?php echo $year ?>&month=<?php echo $month+1 ?>">다음 달</a>
                        <?php endif ?>  
                    </div>

        <!-- 날짜 선택 방법1. 셀렉트 박스로 -->
        <form action="/main.php">
            <select name="year" id="year">
                <?php for($y=2000; $y<=2024; $y++) { ?>
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

            <button type="submit">이동</button>
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
            <?php for($i=0; $i<$start_week; $i++) { ?>
                    <div></div>
                
                    
            <?php }?>
    
            <?php for($i=1; $i<=$day; $i++) { ?>
                <a href="/pop_up.php">
                    <div><?php echo $i ?></div>
                    <!-- if로 데이터 받아오기 -->
                    <p>개의 일정</p>

                </a>
                    
            <?php }?>
        </div>



        
        </div>
    </div>
</body>
</html>