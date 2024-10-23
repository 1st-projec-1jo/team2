<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/config.php"); 


/**
 * DB Connection
 */
function my_db_conn() {
  $option = [
      PDO::ATTR_EMULATE_PREPARES      => false,
      PDO::ATTR_ERRMODE              => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE   => PDO::FETCH_ASSOC
  ];

  return new PDO(MY_MARIADB_DSN, MY_MARIADB_USER, MY_MARIADB_PASSWORD, $option);
}



/*******************************************************************************************
 * ↓ 상기님 파트
*******************************************************************************************/

function my_list_select(PDO $conn, array $arr_param){

  $sql = 
  " SELECT            "
  ." *                "
  ." FROM             "
  ."         sports_cal    "
  ." WHERE            "
  ."         deleted_at IS NULL "
  ."         AND date = :date "
  ." ORDER BY         "
  ."           complete ASC, "
  ."           created_at DESC "
  // ."           id DESC          "
  ;

  $stmt = $conn->prepare($sql);
  $result_flg = $stmt -> execute($arr_param);

  if(!$result_flg){
    throw new Exception("쿼리 실행 실패");
  }

  return $stmt -> fetchAll();
}

function my_list_select_id(PDO $conn, array $arr_param) {

  $sql = 
  " SELECT            "
  ." *                "
  ." FROM             "
  ."         sports_cal    "
  ." WHERE            "
  ."         deleted_at IS NULL "
  ."         AND date = :date "
  ."         AND id = :id "
  ;

  $stmt = $conn->prepare($sql);
  $result_flg = $stmt -> execute($arr_param);

  if(!$result_flg){
    throw new Exception("쿼리 실행 실패");
  }

  return $stmt -> fetch();
}

function my_pop_up_count_select(PDO $conn, array $arr_param) {
  $sql =
    " SELECT            "
    ."  COUNT(*) cnt               "
    ."  FROM             "
    ."         sports_cal    "
    ." WHERE            "
    ."         deleted_at IS NULL "
    ."       AND date = :date "
    ;

    $stmt = $conn->prepare($sql);
    $result_flg = $stmt -> execute($arr_param);

    if(!$result_flg){
      throw new Exception("쿼리 실행 실패");
    }

    return $stmt->fetch()["cnt"];
}

function my_project_delete(PDO $conn, array $arr_param) {
  $sql =
    " UPDATE "
    ." sports_cal "
    ." SET "
    ." updated_at = NOW() "
    ." ,deleted_at = NOW() "
    ." WHERE "
    ." id = :id "
  ;

  $stmt = $conn->prepare($sql); // 쿼리 준비
  $result_flg = $stmt->execute($arr_param); // 쿼리 실행

  if(!$result_flg){
    throw new Exception("쿼리 실행 실패");
  }

  $result_cnt = $stmt->rowCount();

  if($result_cnt !== 1) {
    throw new Exception("delete Count 이상");
  }

  return true;
}

function my_project_update_popup(PDO $conn, array $arr_param) {
  $sql =
    " UPDATE "
    ." sports_cal "
    ." SET "
    ." updated_at = NOW() "
    ." ,complete = :complete "
    ." WHERE "
    ." id = :id "
  ;

  $stmt = $conn->prepare($sql); // 쿼리 준비
  $result_flg = $stmt->execute($arr_param); // 쿼리 실행

  if(!$result_flg){
    throw new Exception("쿼리 실행 실패");
  }

  $result_cnt = $stmt->rowCount();

  if($result_cnt !== 1) {
    throw new Exception("delete Count 이상");
  }

  return true;
}

/*******************************************************************************************
 * ↓ 경진님 파트
*******************************************************************************************/

/**
 * Insert 처리
 */
function my_list_insert(PDO $conn, array $arr_param) {
  $sql =
      " INSERT INTO sports_cal ( "
      ."      date "
      ."      ,title "
      ."      ,hour "
      ."      ,calory "
      ."      ,part "
      ."      ,level "
      ."      ,memo "
      ." ) "
      ." VALUES( "
      ."      :date "
      ."      ,:title "
      ."      ,:hour "
      ."      ,:calory "
      ."      ,:part "
      ."      ,:level "
      ."      ,:memo "
      ." ) "
  ;


  $stmt = $conn->prepare($sql);
  $result_flg = $stmt->execute($arr_param);

  if(!$result_flg) {
      throw new Exception("쿼리 실행 실패");
  }

  $result_cnt = $stmt->rowCount();

  if($result_cnt !== 1) {
      throw new Exception("Insert Count 이상");
  }

  return true;
}

function my_list_update(PDO $conn, array $arr_param){
  $sql = 
    " UPDATE "
    ."   sports_cal "
    ." SET "
    ."      date = :date "
    ."      ,title = :title "
    ."      ,hour = :hour "
    ."      ,calory = :calory "
    ."      ,part = :part "
    ."      ,level = :level "
    ."      ,memo = :memo "
    ."      ,updated_at = NOW() "
    ." WHERE "
    ."      id = :id "
  ;

  $stmt = $conn->prepare($sql);
  $result_flg = $stmt->execute($arr_param);

  if(!$result_flg) {
    throw new Exception("퀴리 싪행 실패");
  }

  $result_cnt = $stmt->rowCount();

  if($result_cnt !== 1){
    throw new Exception ("Update Count 이상");
  }

  return $stmt->fetchAll();
}

function my_exe_hour(PDO $conn, array $arr_param){
  $sql =
    " SELECT "
    ."  SUM(hour) sum "
    ."  FROM "
    ."      sports_cal "
    ." WHERE            "
    ."      deleted_at IS NULL "
    ."  AND date = :date "
    ;

    $stmt = $conn->prepare($sql);
    $result_flg = $stmt -> execute($arr_param);

    if(!$result_flg){
      throw new Exception("쿼리 실행 실패");
    }

    return $stmt->fetch()["sum"];
}

/*******************************************************************************************
 * ↓ 주연님 파트
*******************************************************************************************/
function main_cal_list_cnt(PDO $conn, array $arr_param) {
  $sql =
    " SELECT "
    ."      date "
    ."      ,COUNT(*) cnt "
    ." FROM "
    ."      sports_cal "
    ." WHERE "
    ."    deleted_at IS NULL "
	  ." AND DATE >= :start_day "
	  ." AND DATE <= :end_day "
    ." GROUP BY "
    ."    date "
    ;
    $stmt = $conn->prepare($sql);
    $result_flg = $stmt -> execute($arr_param);

    if(!$result_flg){
      throw new Exception("쿼리 실행 실패");
    }
    return $stmt -> fetchAll();
}


/*******************************************************************************************
 * ↓ 현석님 파트
*******************************************************************************************/

// 해당 데이터에 속하는 모든 칼로리의 합산 계산 함수
function my_select_calory_sum(PDO $conn, array $arr_param) {
  $sql = 
    " SELECT "
    ."      SUM(calory) AS sum_kcal "
    ." FROM "
    ."      sports_cal "
    ." WHERE "
    ."       deleted_at IS NULL "
    ."   AND complete = 1 "
    ."   AND date = :date "
  ;

  $stmt = $conn->prepare($sql);

  if(!$stmt->execute($arr_param)){
    throw new Exception("칼로리 합계 계산 실패");
  }

  return $stmt->fetch()["sum_kcal"];
}

// date 유효성 검사를 체크하기 위한 함수
function my_check_date_exception(string $date){
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

  return true;
}