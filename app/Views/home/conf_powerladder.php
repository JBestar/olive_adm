<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	  <!--Sub Navbar-->
	<div class = "sub-navbar">
		<p><i class="glyphicon glyphicon-play-circle"></i> 기본설정::게임설정</p>
		<?php if(!$npg_deny) :?>
		<a href="<?php echo siteFurl().'home/conf_powerball';?>" class="sub-navbar-a" >파워볼</a>
		<a href="<?php echo siteFurl().'home/conf_powerladder';?>" class="sub-navbar-a <?=$active_ps?>" >파워사다리</a>
		<?php endif ?>   
    	<?php if(!$bpg_deny) :?>
			<a href="<?php echo siteFurl().'home/conf_bogleball';?>" class="sub-navbar-a" >보글파워볼</a>
			<a href="<?php echo siteFurl().'home/conf_bogleladder';?>" class="sub-navbar-a <?=$active_bs?>" >보글사다리</a>
   		<?php endif ?>   
		<a href="<?php echo siteFurl().'home/conf_evol';?>" class="sub-navbar-a">에볼루션</a>
		<?php if($_ENV['app.type'] != APPTYPE_2) :?>
  		<a href="<?php echo siteFurl().'home/conf_slot_1';?>" class="sub-navbar-a">정품슬롯</a>
		<?php endif ?>
		<?php if($_ENV['app.type'] == APPTYPE_0 || $_ENV['app.type'] == APPTYPE_1) :?>
  		<a href="<?php echo siteFurl().'home/conf_slot_2';?>" class="sub-navbar-a">네츄럴슬롯</a>
		  <?php elseif($_ENV['app.type'] == APPTYPE_2) :?>
		<a href="<?php echo siteFurl().'home/conf_slot_2';?>" class="sub-navbar-a" >네츄럴슬롯</a>
		<?php endif ?>
	</div>
	<!--Site Setting-->
	<div class="confsite-game-panel" id="<?=$game_id?>">
		<!---->
		<h4><i class="glyphicon glyphicon-hand-right"></i> <?=$game_name?> 게임관련 설정</h4>	
		
		<div class="confsite-game-text-div">
			<p>유저 배팅승인:</p> 
			<input type="checkbox" id="confpb-bet-check-id"  style="zoom:120%; margin-top:4px;">
			<label style="font-size:13px; font-weight:normal; padding-top:0px;"> 유저배팅승인</label>
		</div>
		<div class="confsite-game-text-div">
			<p>배팅 마감시간:</p> 
			<input type = "number" class="conf-seconds-input" id="confpb-endsec-input-id"><label> 초</label>
		</div>
		<div class="confsite-game-text-div">
			<p>배팅 최소금액:</p> 
			<input type = "number" class="conf-text-input"  id="confpb-minmoney-input-id"><label> 원</label>
		</div>
		<div class="confsite-game-text-div">
			<p>배팅 최대금액:</p> 
			<input type = "number" class="conf-text-input"  id="confpb-maxmoney-input-id"><label> 원</label>
		</div>
		<div class="confsite-game-text-div">
  			<p>적중 최대금액:</p>
  			<input type="number" class="conf-text-input" id="confpb-winmoney-input-id"><label> 원</label>
  		</div>
		<div class="confsite-game-text-div">
			<p>누르기율:</p> 
			<input type = "number" class="conf-text-input"  id="confpb-percent-input-id"><label> %</label>
		</div>
		<div class="confsite-game-text-div">
		<p style="font-size: 16px; font-weight: bold;"><?=$game_name?> 단폴</p>

  		</div>
		<div class="confsite-game-text-div">
			<p> 좌 :: 우 배당율</p> 
			<input type = "text" class="conf-text-input" id="confpb-ratio1-input-id">
		</div>
		<div class="confsite-game-text-div">
			<p> 3줄 :: 4줄 배당율</p> 
			<input type = "text" class="conf-text-input" id="confpb-ratio2-input-id">
		</div>
		<div class="confsite-game-text-div">
			<p> 홀 :: 짝 배당율</p> 
			<input type = "text" class="conf-text-input"  id="confpb-ratio3-input-id">
		</div>

		<div class="confsite-game-text-div">
  			<p style="font-size: 16px; font-weight: bold;"><?=$game_name?> 조합</p>

  		</div>
		  <div class="confsite-game-text-div">
  			<div>
  				<p> 좌3 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio4-input-id">
  			</div>
  			<div>
  				<p> 좌4 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio5-input-id">
  			</div>
  		</div>
  		<div class="confsite-game-text-div">
  			<div>
  				<p> 우3 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio6-input-id">
  			</div>
  			<div>
  				<p> 우4 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio7-input-id">
  			</div>
  		</div>
		<div class = "confsite-button-group" style="margin-top:50px;">
			<button class="confsite-cancel-button" id="confsite-cancel-btn-id">취소</button>
			<button class="confsite-ok-button"  id="confsite-ok-btn-id">저장</button>
		</div>
	</div>



<!--main_navbar.php-->
</div>
<?php if(array_key_exists("app.produce", $_ENV)) :?>
    <script src="<?php echo site_furl('/assets/js/confps-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/confps-script.js?v=1');?>"></script>
<?php endif ?>
<?= $this->endSection() ?>