<?= $this->extend('header') ?>
<?= $this->section('content') ?>
<!--Sub Navbar-->
	<div class = "sub-navbar">
		<p><i class="glyphicon glyphicon-cog"></i> 기본설정::점검설정</p>
		<a href="<?php echo siteFurl().'home/conf_site';?>" class="sub-navbar-a" >본사설정</a>
		<a href="<?php echo siteFurl().'home/conf_betsite';?>" class="sub-navbar-a" >보험설정</a>
		<a href="<?php echo siteFurl().'home/conf_maintain';?>" class="sub-navbar-a active" >점검설정</a>
	</div>

	<!--Site Setting-->
	<div class="confsite-site-panel">

		<h4><i class="glyphicon glyphicon-hand-right"></i> 사이트점검설정</h4>						
		<div class="confsite-site-check-div">
			<label> 운영상태</label>			
			<select name="pbresult-number" class="pbresult-number-select" id="confsite-maintain-select-id" style="width: 200px;">
				<?php if(is_null($objConfig) || $objConfig->conf_active != 1) {  ?>
				<option value="0" selected>정상운영</option>
				<?php } else {?>
				<option value="0">정상운영</option>
				<?php } ?>
				<?php if(is_null($objConfig) || $objConfig->conf_active == 1) {  ?>
				<option value="1" selected>점검</option>
				<?php } else {?>
				<option value="1">점검</option>
				<?php } ?>
			</select>
		</div>
		<div class="confsite-site-text-div" style="margin-top: 10px;">
			<p style="font-size: 14px; padding:5px 0px;"> 점검내용</p>
			<textarea rows="16" id="confsite-maintain-text-id" style="width: 350px;"
			 ><?php if(!is_null($objConfig)) {  ?><?=$objConfig->conf_content?><?php } ?></textarea>					
		</div>
		

		<div class = "confsite-button-group">
			<button class="confsite-cancel-button" id="confsite-cancel-btn-id">취소</button>
			<button class="confsite-ok-button" id="confsite-ok-btn-id">저장</button>
		</div>
	</div>



<!--main_navbar.php-->
</div>

<?php if(array_key_exists("app.produce", $_ENV)) :?>
    <script src="<?php echo site_furl('/assets/js/confmaintain-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/confmaintain-script.js?v=1');?>"></script>
<?php endif ?>
<?= $this->endSection() ?>