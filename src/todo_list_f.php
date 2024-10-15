<div class="container_l">
  <div class="date_btn">
    <a href="./main.html"><button type="button"><?php echo $date ?></button></a>
  </div>
  <hr>
  <br>
  <hr>
  <div class="list_box">
    <?php foreach($result as $value) { ?>
       <div class="list 
       <?php  if(isset($id)) { ?>
       <?php 
        if($id === $value["id"]) { ?> list_selected <?php } ?> <?php } ?>
        <?php
        if($value["complete"] === 1)   { ?> list_title list_chked <?php } ?> ">
            <div class="list_check">
              <!-- selected -> list_selected -->
              <a href="/check_popup.php?date=<?php echo $date ?>&id=<?php echo $value["id"] ?>">
                <button type="button" class="chk_btn<?php 
                if($value["complete"] === 1) { ?> chk_green <?php } ?>"></button>
              </a>
            </div>
            <div class="list_title">
              <a href="/detail.php?date=<?php echo $date ?>&id=<?php echo $value["id"] ?>"><div><?php echo $value["title"]?></div></a>
            </div>
          </div>
          <?php } ?>
    
    <div class="list_plus">
      <div class="list_plus_btn">
        <a href="./insert.php?date=<?php echo $date ?>"><button type="button">+</button></a>
      </div>            
    </div>
 
  </div>
</div>

