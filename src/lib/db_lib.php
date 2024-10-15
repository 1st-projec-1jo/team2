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

