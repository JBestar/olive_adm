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
		<h4><i class="glyphicon glyphicon-hand-right"></i> 배팅계정설정</h4>
		<?php if($mb_level > LEVEL_ADMIN) {  ?>
		<div class="confsite-game-text-div">
			<p>배팅사이트명:</p> 
			<input type = "text" class="conf-text-input"  id="conf-betsite-input-id">
		</div>
		<?php } ?>
		<div class="confsite-game-text-div">
			<p>배팅계정 아이디:</p> 
			<input type = "text" class="conf-text-input"  id="conf-userid-input-id">
		</div>
		<div class="confsite-game-text-div">
			<p>배팅계정 비밀번호:</p> 
			<input type = "text" class="conf-text-input"  id="conf-userpwd-input-id">
		</div>
		<div class="confsite-game-text-div">
			<p>파워볼 누르기율:</p> 
			<input type = "text" class="conf-text-input"  id="conf-pball-input-id"><label> %</label>
		</div>
		<div class="confsite-game-text-div">
			<p>파워사다리 누르기율:</p> 
			<input type = "text" class="conf-text-input"  id="conf-pladder-input-id"><label> %</label>
		</div>
		<div class="confsite-game-text-div">
			<p>키노사다리 누르기율:</p> 
			<input type = "text" class="conf-text-input"  id="conf-kladder-input-id"><label> %</label>
		</div>
		<div class = "confsite-button-group">
			<button class="confsite-cancel-button"  id="confsite-cancel-btn-id">취소</button>
			<button class="confsite-ok-button" id="confsite-ok-btn-id">저장</button>
		</div>
	</div>
<!--main_navbar.php-->
</div>


<script src="<?php echo base_url('assets/js/conf-betsite-script.js');?>"></script>

<?= $this->endSection() ?>