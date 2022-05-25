<?= $this->extend('header') ?>
<?= $this->section('content') ?>
<script src="<?php echo site_furl('/assets/js/summernote-lite.js');?>"></script>
<script src="<?php echo site_furl('/assets/js/summernote-ko-KR.js');?>"></script>

<link rel="stylesheet" href="<?php echo siteFurl().'assets/css/summernote-lite.css';?>">

<!--Sub Navbar-->
<div class="sub-navbar">
	<p><i class="glyphicon glyphicon-cog"></i> 기본설정::본사설정</p>
	<a href="<?php echo siteFurl().'home/conf_site';?>" class="sub-navbar-a active">본사설정</a>
	<a href="<?php echo siteFurl().'home/conf_betsite';?>" class="sub-navbar-a">보험설정</a>
	<a href="<?php echo siteFurl().'home/conf_maintain';?>" class="sub-navbar-a">점검설정</a>
	<?php if($mb_level > LEVEL_ADMIN) {  ?>
	<button class="sub-navbar-but" style="display: block;" id="cleanDb-but" onclick="cleanDb(1);">디비초기화</button>
	<button class="sub-navbar-but" style="display: none;" id="deleteDb-but" onclick="cleanDb(0);">디비정리</button>
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
		<input type="text" id="confsite-sitename-input-id" value="<?=$arrConfig[CONF_SITENAME-1]->conf_content?>">
		<?php } ?>
	</div>
	<div class="confsite-site-text-div">
		<p>도메인명:</p>
		<?php if(is_null($arrConfig)) {  ?>
		<input type="text" id="confsite-domainname-input-id">
		<?php } else {?>
		<input type="text" id="confsite-domainname-input-id" value="<?=$arrConfig[CONF_DOMAIN-1]->conf_content?>">
		<?php } ?>
	</div>
	<div class="confsite-site-text-div">
		<p>홈페이지:</p>
		<?php if(is_null($arrConfig)) {  ?>
		<input type="text" id="confsite-homepage-input-id">
		<?php } else {?>
		<input type="text" id="confsite-homepage-input-id" value="<?=$arrConfig[CONF_USERPAGE-1]->conf_content?>">
		<?php } ?>
	</div>
	<div class="confsite-site-text-div">
		<p>관리주소:</p>
		<?php if(is_null($arrConfig)) {  ?>
		<input type="text" id="confsite-adminpage-input-id">
		<?php } else {?>
		<input type="text" id="confsite-adminpage-input-id" value="<?=$arrConfig[CONF_ADMINPAGE-1]->conf_content?>">
		<?php } ?>
	</div>
	<div class="confsite-site-text-div">
		<p>입금통장:</p>
		<?php if(is_null($arrConfig)) {  ?>
		<input type="text" placeholder="은행명" id="confsite-bankname-input-id">
		<input type="text" placeholder="예금주" id="confsite-bankown-input-id">
		<input type="text" placeholder="계좌번호" id="confsite-banknum-input-id">

		<?php } else {?>
		<input type="text" style="width:20%; margin-right:1px;" placeholder="은행명" id="confsite-bankname-input-id" value="<?=explode("#", trim($arrConfig[CONF_CHARGEINFO-1]->conf_content))[0]?>">
		<input type="text" style="width:25%; margin-right:1px;" placeholder="예금주" id="confsite-bankown-input-id" value="<?=explode("#", trim($arrConfig[CONF_CHARGEINFO-1]->conf_content))[1]?>">
		<input type="text" style="width:25%; " placeholder="계좌번호" id="confsite-banknum-input-id" value="<?=explode("#", trim($arrConfig[CONF_CHARGEINFO-1]->conf_content))[2]?>">
		<?php } ?>
	</div>
	
	<h4><i class="glyphicon glyphicon-hand-right"></i> 충환전안내 </h4>

	<div class="confsite-site-check-div">
		<?php if (is_null($arrConfig) || $arrConfig[CONF_NOTICE_BANK-1]->conf_active != 1) {?>
		<input type="checkbox" id="confsite-deposite-check-id">
		<?php } else {?>
		<input type="checkbox" id="confsite-deposite-check-id" checked>
		<?php }?>
		<label for="confsite-deposite-check-id"> 회원로그인시 충환전공지사항 현시</label>
	</div>

	<div class="width:100%; clear:both; ">
		<form method="post" style="width:80%; margin-left:20px; background-color:white;">
			<textarea id="confsite-deposite-text-id" name="editordata"><?php if(!is_null($arrConfig)) {  ?><?=$arrConfig[CONF_NOTICE_BANK-1]->conf_content?><?php } ?></textarea>
		</form>
	</div>

	<h4><i class="glyphicon glyphicon-hand-right"></i> 메인공지사항</h4>						
	<div class="confsite-site-check-div">
		<?php if(is_null($arrConfig) || $arrConfig[CONF_NOTICE_MAIN-1]->conf_active != 1) {  ?>
		<input type="checkbox" id="confsite-mainnotice-check-id">
		<?php } else {?>
		<input type="checkbox" id="confsite-mainnotice-check-id" checked>				
		<?php } ?>

		<label for="confsite-mainnotice-check-id"> 회원로그인시 메인공지사항 현시</label>			
	</div>
	<div class="confsite-site-text-div">
		<textarea rows="" id="confsite-mainnotice-text-id"><?php if(!is_null($arrConfig)) {  ?><?=$arrConfig[CONF_NOTICE_MAIN-1]->conf_content?><?php } ?></textarea>					
	</div>
	<!---->
	<h4><i class="glyphicon glyphicon-hand-right"></i> 긴급공지</h4>
	<div class="confsite-site-check-div">
		<?php if (is_null($arrConfig) || $arrConfig[CONF_NOTICE_URGENT-1]->conf_active != 1){ ?>
			<input type="checkbox" id="confsite-urgentnotice-check-id">
		<?php } else {?>
			<input type="checkbox" id="confsite-urgentnotice-check-id" checked>
		<?php }?>
		<label for="confsite-urgentnotice-check-id"> 회원로그인시 긴급공지사항 현시</label>
	</div>
	<div class="width:100%; clear:both; ">
		<form method="post" style="width:80%; margin-left:20px; background-color:white;">
			<textarea id="confsite-urgentnotice-text-id" name="editordata"><?php if(!is_null($arrConfig)) {  ?><?=$arrConfig[CONF_NOTICE_URGENT-1]->conf_content?><?php } ?></textarea>
		</form>
	</div>
	<!---->
	<h4><i class="glyphicon glyphicon-hand-right"></i> 계좌문의 매크로</h4>
	<div class="width:100%; clear:both; ">
		<form method="post" style="width:80%; margin-left:20px; background-color:white;">
			<textarea id="confsite-bankmacro-text-id" name="editordata"><?php if(!is_null($arrConfig)) {  ?><?=$arrConfig[CONF_CHARGEMACRO-1]->conf_content?><?php } ?></textarea>
		</form>
	</div>
	<h4><i class="glyphicon glyphicon-hand-right"></i> 회원가입정책</h4>
	<div class="confsite-site-check-div">
		<?php if (is_null($arrConfig) || $arrConfig[CONF_MULTI_LOGIN-1]->conf_active != 1){ ?>
			<input type="checkbox" id="confsite-multilog-check-id">
		<?php } else {?>
			<input type="checkbox" id="confsite-multilog-check-id" checked>
		<?php }?>
		<label for="confsite-multilog-check-id"> 회원가입시 중복로그인 허용</label>
	</div>
	
	<div class="confsite-button-group">
		<button class="confsite-cancel-button" id="confsite-cancel-btn-id">취소</button>
		<button class="confsite-ok-button" id="confsite-ok-btn-id">저장</button>
	</div>
</div>



<!--main_navbar.php-->
</div>

<?php if(array_key_exists("app.produce", $_ENV)) :?>
    <script src="<?php echo site_furl('/assets/js/confsite-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/confsite-script.js?v=1');?>"></script>
<?php endif ?>
<?= $this->endSection() ?>