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
  ."           created_at DESC, "
  ."           id DESC          "
  ;

  $stmt = $conn->prepare($sql);
  $result_flg = $stmt -> execute($arr_param);

  if(!$result_flg){
    throw new Exception("쿼리 실행 실패");
  }

  return $stmt -> fetchAll();
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
