<?php
  // 여기에 일정 관리 리스트 처리, $result와 $select_id 구해야함
  require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
  require_once(MY_PATH_DB_LIB);

  $conn = null;

  try {
      if(strtoupper($_SERVER["REQUEST_METHOD"]) === "GET") {
          
          $id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
          
          $date = isset($_GET["date"]) ? $_GET["date"] : "";

          if($id < 1) {
              throw new Exception("파라미터 오류");
          }

          $conn = my_db_conn();

          $arr_prepare = [
            "date" => $date
            ,"id" => $id
        ];

        $select_id = my_list_select_id($conn, $arr_prepare);

          $arr_prepare_select = [
              "date" => $date
          ];

        $result = my_list_select($conn, $arr_prepare_select);

      }
    }catch(Throwable $th) {
        require_once(MY_PATH_ERROR);
        exit;
      }
  ?>