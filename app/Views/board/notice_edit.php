<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	<script src="<?=$_ENV['app.furl']?>/assets/js/summernote-lite.js"></script>
	<script src="<?=$_ENV['app.furl']?>/assets/js/summernote-ko-KR.js"></script>

	<link rel="stylesheet" href="<?php echo site_furl('/assets/css/summernote-lite.css');?>">
  	<!--Sub Navbar-->
	<div class = "sub-navbar">
		<?php if(is_null($objNotice)) {  ?>
			<p><i class="glyphicon glyphicon-info-sign"></i> 공지사항 작성</p>
		<?php } else {?>
			<p><i class="glyphicon glyphicon-info-sign"></i> 공지사항 수정</p>
		<?php } ?>
		<?php if(is_null($objNotice)) {  ?>
			<p id="subnavbar-fid-p-id" hidden>0</p>
		<?php } else {?>
			<p id="subnavbar-fid-p-id" hidden><?=$objNotice->notice_fid?></p>
		<?php } ?>
	</div>

<!--Site Setting-->
	<div class="useredit-panel">
		<!---->
		<div class="useredit-text-div">
			<p>공개(비공개):</p>
			<?php if(is_null($objNotice) || $objNotice->notice_state_active == 0) {  ?>	
			<input type="checkbox" id="notice-state-check-id" style="width: 20px; padding-top: 5px;" name="public">
			<?php } else {?>
			<input type="checkbox" id="notice-state-check-id" style="width: 20px; padding-top: 5px;" checked name="public">
			<?php } ?>
			<label for="public">공개</label>
		</div>
		<div class="useredit-text-div">
			<p>공지사항제목:</p> 
			<?php if(is_null($objNotice) || is_null($objNotice->notice_title)) {  ?>	
			<input type = "text" id="notice-title-input-id" style="width:60%;">
			<?php } else {?>
			<input type = "text" id="notice-title-input-id" value="<?=$objNotice->notice_title?>" style="width:60%;">
			<?php } ?>
		</div>

		<div style="width:100%; clear:both;">
			<p style="width:180px; float:left; padding:5px;">공지사항내용:</p> 
			<form method="post" style="width:60%; float:left;background-color:white;">
			<textarea id="notice-content" name="editordata"><?php if(!is_null($objNotice)) {  ?><?=$objNotice->notice_content?><?php } ?></textarea>
			</form>	
		</div>
		<div class = "useredit-button-group">
			<button class="useredit-cancel-button" id="notice-cancel-btn-id">취소</button>
			<button class="useredit-ok-button"  id="notice-save-btn-id">저장</button>
		</div>
<!--main_navbar.php-->
</div>

<?php if(array_key_exists("app.produce", $_ENV)) :?>
    <script src="<?php echo site_furl('/assets/js/notice_edit-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/notice_edit-script.js?v=1');?>"></script>
<?php endif ?>
<?= $this->endSection() ?>