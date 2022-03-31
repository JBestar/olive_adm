<!--?= $this->extend('header') ?-->
<!--?= $this->section('SideBar')?-->
<div class="main-sidebar" id = "main-sidebar-id">
  
  <button class="main-dropdown-btn <?=$confdropdownbtn?>"><i class="glyphicon glyphicon-home"></i>  기본설정
    <i class="glyphicon glyphicon-chevron-right" style = "float:right; padding-right: 10px; font-size: 10px"></i>
  </button>
  <div class="main-dropdown-container" <?=$confdropdown?> >
    <?php if($mb_level >= LEVEL_ADMIN) {  ?>
    <a href="<?php echo base_url().'home/conf_site';?>" class="<?=$conf_site?>"><i class="glyphicon glyphicon-cog"></i>  본사설정</a>
    <a href="<?php echo base_url().'home/conf_powerball';?>" class="<?=$conf_game?>"><i class="glyphicon glyphicon-play-circle"></i>  게임설정</a>
    <a href="<?php echo base_url().'home/conf_sound';?>" class="<?=$conf_other?>"><i class="glyphicon glyphicon-wrench"></i>  기타설정</a>
    <?php } ?>
    <a href="<?php echo base_url().'home/conf_password';?>" class="<?=$conf_password?>"><i class="glyphicon glyphicon-lock"></i> 비밀번호 변경</a>
  </div>
  
  <button class="main-dropdown-btn <?=$userdropdownbtn?>"><i class="glyphicon glyphicon-user"></i>  회원관리
    <i class="glyphicon glyphicon-chevron-right" style = "float:right; padding-right: 10px; font-size: 10px"></i>
  </button>
  <div class="main-dropdown-container" <?=$userdropdown?>>
    <!--?php if($mb_level >= LEVEL_ADMIN) {  ?-->
    <!--a href="<?php echo base_url().'user/company';?>" class="<?=$user_company?>"><i class="glyphicon glyphicon-cd"></i>  부본사</!--a-->
    <!--?php } if($mb_level >= LEVEL_COMPANY) {  ?-->
    <!--a href="<?php echo base_url().'user/agency';?>" class="<?=$user_agency?>"><i class="glyphicon glyphicon-cd"></i>  총판</!--a-->
    <!--?php }  if($mb_level >= LEVEL_AGENCY) {  ?-->
    <!--a href="<?php echo base_url().'user/employee';?>" class="<?=$user_employee?>"><i class="glyphicon glyphicon-cd"></i>  매장</!--a-->
    <!--?php }  if($mb_level >= LEVEL_EMPLOYEE) {  ?-->
    <?php { ?>
    <a href="<?php echo base_url().'user/member/0';?>" class="<?=$user_member?>"><i class="glyphicon glyphicon-cd"></i> 회원</a>
    <?php } ?>
  </div>
  
  <button class="main-dropdown-btn <?=$bankdropdownbtn?>"><i class="glyphicon glyphicon-usd"></i>  충환전관리
    <i class="glyphicon glyphicon-chevron-right" style = "float:right; padding-right: 10px; font-size: 10px"></i>
  </button>
  <div class="main-dropdown-container" <?=$bankdropdown?>>
    <?php if($mb_level >= LEVEL_ADMIN) {  ?>
    <a href="<?php echo base_url().'bank/deposit';?>" class="<?=$bank_deposit?>"><i class="glyphicon glyphicon-plus-sign"></i>  충전관리</a>
    <a href="<?php echo base_url().'bank/withdraw';?>" class="<?=$bank_withdraw?>"><i class="glyphicon glyphicon-minus-sign"></i>  환전관리</a>
    <?php } ?>
    <a href="<?php echo base_url().'bank/exchange';?>" class="<?=$bank_exchange?>"><i class="glyphicon glyphicon-ok-sign"></i> 머니거래내역</a>
    <a href="<?php echo base_url().'bank/transfer';?>" class="<?=$bank_transfer?>"><i class="glyphicon glyphicon-transfer"></i>  머니이동내역</a>
    
  </div>
  
  <?php if($mb_level >= LEVEL_ADMIN) {  ?>
  <button class="main-dropdown-btn  <?=$resultdropdownbtn?>"><i class="glyphicon glyphicon-th-list"></i>  게임관리
    <i class="glyphicon glyphicon-chevron-right" style = "float:right; padding-right: 10px; font-size: 10px"></i>
  </button>
  <div class="main-dropdown-container" <?=$resultdropdown?>>
  <a href="<?php echo base_url().'result/pbresult';?>" class="<?=$gameresult?>"><i class="glyphicon glyphicon-book"></i>  게임결과</a>
  <a href="<?php echo base_url().'result/pbbetchange/0/0';?>" class="<?=$gameedit?>"><i class="glyphicon glyphicon-tag"></i>  적중특례</a>
  </div>
  <?php } ?>

  <button class="main-dropdown-btn  <?=$betdropdownbtn?>"><i class="glyphicon glyphicon-refresh"></i>  배팅
    <i class="glyphicon glyphicon-chevron-right" style = "float:right; padding-right: 10px; font-size: 10px"></i>
  </button>
  <div class="main-dropdown-container" <?=$betdropdown?>>
    <?php if($mb_level >= LEVEL_ADMIN) {  ?>
    <a href="<?php echo base_url().'bet/pbrealtime';?>" class="<?=$bet_realtime?>"><i class="glyphicon glyphicon-dashboard"></i>  실시간배팅</a>
    <?php } ?>    
    <a href="<?php echo base_url().'bet/pbhistory';?>" class="<?=$bet_history?>"><i class="glyphicon glyphicon-book"></i>  배팅내역</a>
    <a href="<?php echo base_url().'bet/allcalculate';?>" class="<?=$bet_calculate?>"><i class="glyphicon glyphicon-tag"></i> 정산내역</a>
  </div>
  <?php if($mb_level >= LEVEL_ADMIN) {  ?>
  <button class="main-dropdown-btn  <?=$boarddropdownbtn?>" ><i class="glyphicon glyphicon-envelope"></i>  게시판
    <i class="glyphicon glyphicon-chevron-right" style = "float:right; padding-right: 10px; font-size: 10px"></i>
  </button>  
  <div class="main-dropdown-container" <?=$boarddropdown?>>
    <a href="<?php echo base_url().'board/notice';?>" class="<?=$board_notice?>"><i class="glyphicon glyphicon-info-sign"></i>  공지사항</a>
    <!--
    <a href="<?php echo base_url().'board/event';?>" class="<?=$board_event?>"><i class="glyphicon glyphicon-info-sign"></i>  이벤트</a>
    -->
    <a href="<?php echo base_url().'board/message';?>" class="<?=$board_message?>"><i class="glyphicon glyphicon-info-sign"></i>  쪽지관리</a>
  </div>
  <?php } ?>
  <a href="<?php echo base_url().'pages/logout';?>"><i class="glyphicon glyphicon-log-out"></i>  로그아웃</a>
</div>
<!--?= $this->renderSection('MainNavBar') ?-->
<!--?= $this->endSection() ?-->