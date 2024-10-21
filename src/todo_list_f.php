<div class="container_l">
  <div class="date_btn">
    <a href="/main.php<?php if(isset($_GET["date"])) { ?>?year=<?php echo $ex[0] ?>&month=<?php echo $ex[1]?><?php } ?>"><button type="button"><?php echo $date ?></button></a>
  </div>
  <hr>
  <br>
  <hr>
  <div class="list_box">
        <div class="list_scroll">
          <?php foreach($result as $value) { ?>
            <div id="list<?php echo $value["id"] ?>" class="list 
              <?php if(isset($id)) { ?><?php 
                if($id === $value["id"]) { ?> list_selected <?php } ?> <?php } ?><?php
                if($value["complete"] === 1) { ?> list_title list_chked <?php } ?> ">
                  <div class="list_check">
                      <!-- list_title (기본 클래스) -->
                      <!-- list_selected (해당 일정을 선택했을 때) -->
                      <!-- list_chked (해당 일정을 체크했을 때) -->
                    <a href="/check_popup.php?date=<?php echo $date ?>&id=<?php echo $value["id"] ?>">
                      <button type="button" class="chk_btn<?php if($value["complete"] === 1) { ?> chk_green <?php } ?>"></button>
                    </a>
                  </div>
                  <div class="list_title">
                    <a href="/detail.php?date=<?php echo $date ?>&id=<?php echo $value["id"] ?>"><div><?php echo $value["title"]?></div></a>
                  </div>
              <?php } ?>
            </div>
        </div>
    
    <?php if($hour_arr < 24) { ?>
    <div class="list_plus">
      <div class="list_plus_btn">
        <a href="./insert.php?date=<?php echo $date ?>"><button type="button">+</button></a>
      </div>            
    </div>
    <?php } ?>
  </div>
</div>

