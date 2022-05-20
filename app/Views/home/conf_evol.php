<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	  <!--Sub Navbar-->
	<div class = "sub-navbar">
		<p><i class="glyphicon glyphicon-play-circle"></i> 기본설정::게임설정</p>
		<?php if(!$npg_deny) :?>
			<a href="<?php echo siteFurl().'home/conf_powerball';?>" class="sub-navbar-a" >파워볼</a>
			<a href="<?php echo siteFurl().'home/conf_powerladder';?>" class="sub-navbar-a " >파워사다리</a>
   		<?php endif ?>   
    	<?php if(!$bpg_deny) :?>
			<a href="<?php echo siteFurl().'home/conf_bogleball';?>" class="sub-navbar-a" >보글파워볼</a>
			<a href="<?php echo siteFurl().'home/conf_bogleladder';?>" class="sub-navbar-a " >보글사다리</a>
   		<?php endif ?>   
		<?php if(!$cas_deny) :?>
			<a href="<?php echo siteFurl().'home/conf_evol';?>" class="sub-navbar-a active" >에볼루션</a>
			<?php if($kgon_enable) :?>
				<a href="<?php echo siteFurl().'home/conf_casino';?>" class="sub-navbar-a" >호텔카지노</a>
			<?php endif ?>  
   		<?php endif ?>   
		<?php if(!$slot_deny) :?>
			<?php if($_ENV['app.type'] != APPTYPE_2) :?>
			<a href="<?php echo siteFurl().'home/conf_slot_1';?>" class="sub-navbar-a">정품슬롯</a>
			<?php endif ?>
			<?php if($_ENV['app.type'] == APPTYPE_0 || $_ENV['app.type'] == APPTYPE_1) :?>
			<a href="<?php echo siteFurl().'home/conf_slot_2';?>" class="sub-navbar-a">네츄럴슬롯</a>
			<?php elseif($_ENV['app.type'] == APPTYPE_2) :?>
			<a href="<?php echo siteFurl().'home/conf_slot_2';?>" class="sub-navbar-a" >네츄럴슬롯</a>
			<?php endif ?>
		<?php endif ?>
	</div>
	<!--Site Setting-->
	<div class="confsite-game-panel" id="<?=$game_id?>">
		<!---->
		<h4><i class="glyphicon glyphicon-hand-right"></i> <?=$game_name?> 게임관련 설정</h4>	
		<div class="confsite-game-text-div">
			<p>유저 게임승인:</p> 
			<input type="checkbox" id="confpb-bet-check-id" style="zoom:120%; margin-top:4px;">
			<label style="font-size:13px; font-weight:normal; padding-top:0px;"> 유저게임승인</label>
		</div>
		<div class="confsite-game-text-div">
			<p>에이젼트 코드:</p> 
			<input type = "text" class="conf-text-input" style="min-width:200px;"  id="confpb-agent-code-id" disabled>
			
		</div>
		<div class="confsite-game-text-div">
			<p>에이젼트 보유알:</p> 
			<input type = "text" class="conf-text-input" style="min-width:200px;" id="confpb-agent-egg-id"  disabled>
			<button class="refresh_btn" id="refresh_btn" style="margin-left:5px; margin-top:5px;"></button>
		</div>
		
		<div class="confsite-game-text-div">
			<p>에이젼트 페이지:</p>
			<button class="confsite-cancel-button" id="confsite-agent-btn-id" style="margin-bottom:20px; width:200px;">바로 가기</button>
		</div>
		
		<div class = "confsite-button-group" style="margin-top:50px;">
			<button class="confsite-cancel-button" id="confsite-cancel-btn-id">취소</button>
			<button class="confsite-ok-button"  id="confsite-ok-btn-id">저장</button>
		</div>
	</div>



<!--main_navbar.php-->
</div>
<?php if(array_key_exists("app.produce", $_ENV)) :?>
    <script src="<?php echo site_furl('/assets/js/confcs-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/confcs-script.js?v=1');?>"></script>
<?php endif ?>
<?= $this->endSection() ?>