<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	  <!--Sub Navbar-->
	  
	<div class = "sub-navbar">
		<p><i class="glyphicon glyphicon-user"></i> 기본설정::보험설정</p>
		<a href="<?php echo base_url().'home/conf_site';?>" class="sub-navbar-a" >본사설정</a>
		<a href="<?php echo base_url().'home/conf_betsite';?>" class="sub-navbar-a active" >보험설정</a>
		<a href="<?php echo base_url().'home/conf_maintain';?>" class="sub-navbar-a" >점검설정</a>
	</div>
	<!--Site Setting-->
	<div class="confsite-game-panel">
		<!---->
		<h4><i class="glyphicon glyphicon-hand-right"></i> 보험계정설정</h4>
		<div class="confsite-game-text-div">
			<p>보험배팅승인:</p> 
			<input type="checkbox" id="confpb-bet-check-id" style="zoom:120%; margin-top:0px;"><label style="font-size:14px; font-weight:normal; padding-top:0px;"> 배팅승인</label>
		</div>
		<div class="confsite-game-text-div">
			<p>도메인 주소:</p> 
			<input type = "text" class="conf-text-input" style="min-width:200px;" id="conf-betsite-input-id">
		</div>
		<div class="confsite-game-text-div">
			<p>계정 아이디:</p> 
			<input type = "text" class="conf-text-input"  style="min-width:200px;" id="conf-userid-input-id">
		</div>
		<div class="confsite-game-text-div">
			<p>계정 비밀번호:</p> 
			<input type = "text" class="conf-text-input"  style="min-width:200px;" id="conf-userpwd-input-id">
		</div>
		<!--
		<div class="confsite-game-text-div">
			<p>파워볼 누르기율:</p> 
			<input type = "number" class="conf-text-input"  id="conf-pball-input-id"><label> %</label>
		</div>
		<div class="confsite-game-text-div">
			<p>파워사다리 누르기율:</p> 
			<input type = "number" class="conf-text-input"  id="conf-pladder-input-id"><label> %</label>
		</div>
		<div class="confsite-game-text-div">
			<p>보글파워볼 누르기율:</p> 
			<input type = "number" class="conf-text-input"  id="conf-bball-input-id"><label> %</label>
		</div>
		<div class="confsite-game-text-div">
			<p>보글사다리 누르기율:</p> 
			<input type = "number" class="conf-text-input"  id="conf-bladder-input-id"><label> %</label>
		</div>
		-->
		<div class = "confsite-button-group">
			<button class="confsite-cancel-button"  id="confsite-cancel-btn-id">취소</button>
			<button class="confsite-ok-button" id="confsite-ok-btn-id">저장</button>
		</div>
	</div>
<!--main_navbar.php-->
</div>


<script src="<?php echo base_url('assets/js/conf_betsite-script.js?v=3');?>"></script>

<?= $this->endSection() ?>