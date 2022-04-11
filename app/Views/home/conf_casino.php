<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	  <!--Sub Navbar-->
	<div class = "sub-navbar">
		<p><i class="glyphicon glyphicon-play-circle"></i> 기본설정::게임설정</p>
		<a href="<?php echo base_url().'home/conf_powerball';?>" class="sub-navbar-a" >파워볼</a>
		<a href="<?php echo base_url().'home/conf_powerladder';?>" class="sub-navbar-a " >파워사다리</a>
		<a href="<?php echo base_url().'home/conf_bogleball';?>" class="sub-navbar-a" >보글파워볼</a>
		<a href="<?php echo base_url().'home/conf_bogleladder';?>" class="sub-navbar-a " >보글사다리</a>
		<a href="<?php echo base_url().'home/conf_evol';?>" class="sub-navbar-a <?=$active_ev?>" >에볼루션</a>
  		<a href="<?php echo base_url().'home/conf_slot_1';?>" class="sub-navbar-a <?=$active_sl1?>">슬롯</a>
  		<a href="<?php echo base_url().'home/conf_slot_2';?>" class="sub-navbar-a <?=$active_sl2?>">네츄럴슬롯</a>
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
		</div>
		
		<div class="confsite-game-text-div">
			<p>입금 금액:</p> 
			<input type = "text" class="conf-text-input"  style="min-width:200px;" id="confpb-minmoney-input-id"><label> 원 / 회</label>
		</div>
		
		<div class = "confsite-button-group" style="margin-top:50px;">
			<button class="confsite-cancel-button" id="confsite-cancel-btn-id">취소</button>
			<button class="confsite-ok-button"  id="confsite-ok-btn-id">저장</button>
		</div>
	</div>



<!--main_navbar.php-->
</div>

<script src="<?php echo base_url('assets/js/confcs-script.js?v=1');?>"></script>
<?= $this->endSection() ?>