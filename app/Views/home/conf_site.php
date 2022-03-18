<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	<!--Sub Navbar-->
  	<div class="sub-navbar">
  		<p><i class="glyphicon glyphicon-cog"></i> 기본설정::본사설정</p>
  		<a href="<?php echo base_url().'home/conf_site';?>" class="sub-navbar-a active">본사설정</a>
  		<a href="<?php echo base_url().'home/conf_betsite';?>" class="sub-navbar-a">보험설정</a>
  		<a href="<?php echo base_url().'home/conf_maintain';?>" class="sub-navbar-a">점검설정</a>
  		<?php if($mb_level > LEVEL_ADMIN) {  ?>
  		<button class="sub-navbar-but" style="display: none;" onclick="cleanDb(1);">디비초기화</button>
  		<button class="sub-navbar-but" onclick="cleanDb(0);">디비정리</button>
  		<?php } ?>
  	</div>
  	<!--Site Setting-->
  	<div class="confsite-site-panel">
  		<!---->
  		<h4><i class="glyphicon glyphicon-hand-right"></i> 사이트설정</h4>
  		<div class="confsite-site-text-div">
  			<p>사이트명:</p>
  			<?php if(is_null($arrConfig)) {  ?>
  			<input type="text" id="confsite-sitename-input-id">
  			<?php } else {?>
  			<input type="text" id="confsite-sitename-input-id" value="<?=$arrConfig[0]['conf_content']?>">
  			<?php } ?>
  		</div>
  		<div class="confsite-site-text-div">
  			<p>도메인명:</p>
  			<?php if(is_null($arrConfig)) {  ?>
  			<input type="text" id="confsite-domainname-input-id">
  			<?php } else {?>
  			<input type="text" id="confsite-domainname-input-id" value="<?=$arrConfig[1]['conf_content']?>">
  			<?php } ?>
  		</div>
  		<div class="confsite-site-text-div">
  			<p>홈페이지:</p>
  			<?php if(is_null($arrConfig)) {  ?>
  			<input type="text" id="confsite-homepage-input-id">
  			<?php } else {?>
  			<input type="text" id="confsite-homepage-input-id" value="<?=$arrConfig[2]['conf_content']?>">
  			<?php } ?>
  		</div>
  		<div class="confsite-site-text-div">
  			<p>관리주소:</p>
  			<?php if(is_null($arrConfig)) {  ?>
  			<input type="text" id="confsite-adminpage-input-id">
  			<?php } else {?>
  			<input type="text" id="confsite-adminpage-input-id" value="<?=$arrConfig[3]['conf_content']?>">
  			<?php } ?>
  		</div>
  		<div class="confsite-site-text-div">
  			<p>입금통장:</p>
  			<?php if(is_null($arrConfig)) {  ?>
  			<input type="text" id="confsite-bank-input-id">
  			<?php } else {?>
  			<input type="text" id="confsite-bank-input-id" value="<?=$arrConfig[7]['conf_content']?>">
  			<?php } ?>
  		</div>

  		<!--
		<h4><i class="glyphicon glyphicon-hand-right"></i> 메인공지사항</h4>						
		<div class="confsite-site-text-div">
			<textarea rows="8" id="confsite-mainnotice-text-id"><?php if(!is_null($arrConfig)) {  ?><?=$arrConfig[4]['conf_content']?><?php } ?></textarea>					
		</div>
		<div class="confsite-site-check-div">
			<?php if(is_null($arrConfig) || $arrConfig[4]['conf_active'] != 1) {  ?>
			<input type="checkbox" id="confsite-mainnotice-check-id">
			<?php } else {?>
			<input type="checkbox" id="confsite-mainnotice-check-id" checked>				
			<?php } ?>			 
			<label> 회원로그인시 메인공지사항 현시</label>			
		</div>
		-->
  		<!---->
  		<h4><i class="glyphicon glyphicon-hand-right"></i> 입금안내</h4>
  		<div class="confsite-site-text-div">
  			<textarea rows="8"
  				id="confsite-deposite-text-id"><?php if(!is_null($arrConfig)) {  ?><?=$arrConfig[5]['conf_content']?><?php } ?></textarea>
  		</div>

  		<!---->
  		<h4><i class="glyphicon glyphicon-hand-right"></i> 출금안내</h4>
  		<div class="confsite-site-text-div">
  			<textarea rows="8"
  				id="confsite-withdraw-text-id"><?php if(!is_null($arrConfig)) {  ?><?=$arrConfig[6]['conf_content']?><?php } ?></textarea>
  		</div>

  		<!---->
  		<h4><i class="glyphicon glyphicon-hand-right"></i> 계좌문의 매크로</h4>
  		<div class="confsite-site-text-div">
  			<textarea rows="8"
  				id="confsite-bankmacro-text-id"><?php if(!is_null($arrConfig)) {  ?><?=$arrConfig[8]['conf_content']?><?php } ?></textarea>
  		</div>

  		<div class="confsite-button-group">
  			<button class="confsite-cancel-button" id="confsite-cancel-btn-id">취소</button>
  			<button class="confsite-ok-button" id="confsite-ok-btn-id">저장</button>
  		</div>
  	</div>



  	<!--main_navbar.php-->
  	</div>


  	<script src="<?php echo base_url('assets/js/confsite-script.js');?>"></script>
	  <?= $this->endSection() ?>