<?php
  // 여기에 일정 관리 리스트 처리, $result와 $select_id 구해야함

  $conn = null;

  try {
      if(strtoupper($_SERVER["REQUEST_METHOD"]) === "GET") {
          
        $date = isset($_GET["date"]) ? $_GET["date"] : "";  

        // $date의 문자열 길이가 10글자인지 확인
        if(mb_strlen($date) !== 10) {
          throw new Exception("파라미터 오류");
        }

        $conn = my_db_conn();

        // 파라미터의 id값이 들어왔을 경우
        if(isset($_GET["id"])) {

            $id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

            if($id < 1) {
              throw new Exception("파라미터 오류");
            }

            $arr_prepare = [
                "date" => $date
                ,"id" => $id
            ];  

            $select_id = my_list_select_id($conn, $arr_prepare);

            // 파라미터 date 값에 파라미터 id의 데이터가 없을 경우
            if($select_id === false) {
              throw new Exception("해당 데이터 없음");
            }

      }
    
      $arr_prepare_hour = [
        "date" => $date
      ];

      $hour_arr = my_exe_hour($conn, $arr_prepare_hour);

      $arr_prepare_select = [
          "date" => $date
      ];

      $result = my_list_select($conn, $arr_prepare_select);

      $ex = explode("-", $date);

      }
    }catch(Throwable $th) {
        require_once(MY_PATH_ERROR);
        exit;
      }
  ?>