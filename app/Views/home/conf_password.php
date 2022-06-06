<?= $this->extend('header') ?>
<?= $this->section('content') ?>

<!--Sub Navbar-->
<div class="sub-navbar">
	<p><i class="glyphicon glyphicon-lock"></i> 기본설정::정보변경</p>
</div>
<!--Site Setting-->
<div class="confsite-password-panel">
	<?php if ($mb_level >= LEVEL_ADMIN) {  ?>
	<div class="confsite-password-text-div" style="margin-top:20px">
		<p>접속 아이피검사:</p>
		<input type="checkbox" id="confsite-ip-check-id" style="width:20px; zoom:120%;">
		<p style="width:200px;">로그인시 아이피 검사</p>
	</div>
	<div class="confsite-password-text-div">
		<p>접속 아이피:</p> <input type="text" id="confsite-ip-input-id" placeholder="X.X.X.X" pattern="^([0-9a-fA-F]{1,4}:){7}[0-9a-fA-F]{1,4}$">
		<p style="width:200px;" ></p>
	</div>
	<?php } ?>
	<div class="confsite-password-text-div" style="margin-top:20px">
		<p>현재 비밀번호:</p> <input type="password" id="confsite-password-input-id">
	</div>
	<div class="confsite-password-text-div">
		<p>새 비밀번호:</p> <input type="password" id="confsite-password-new-input-id">
	</div>
	<div class="confsite-password-text-div">
		<p>새 비밀번호 확인:</p> <input type="password" id="confsite-password-newok-input-id">
	</div>
	<div class="confsite-button-group">
		<button class="confsite-cancel-button" id="confsite-cancel-btn-id">취소</button>
		<button class="confsite-ok-button" id="confsite-ok-btn-id">저장</button>
	</div>
</div>



<!--main_navbar.php-->
</div>

<?php if(array_key_exists("app.produce", $_ENV)) :?>
    <script src="<?php echo site_furl('/assets/js/confpwd-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/confpwd-script.js?v=1');?>"></script>
<?php endif ?>
<?= $this->endSection() ?>