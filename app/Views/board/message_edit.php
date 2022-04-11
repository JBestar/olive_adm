<?= $this->extend('header') ?>
<?= $this->section('content') ?>
  	<!--Sub Navbar-->
	<div class = "sub-navbar">
		<?php if(is_null($objNotice)) {  ?>
			<p><i class="glyphicon glyphicon-info-sign"></i> 쪽지 작성</p>		
		<?php } else if($objNotice->notice_type == 3) {  ?>
			<p><i class="glyphicon glyphicon-info-sign"></i> 고객문의 회답</p>
		<?php } else {?>
			<p><i class="glyphicon glyphicon-info-sign"></i> 쪽지 수정</p>
		<?php } ?>
		
		<?php if(is_null($objNotice)) {  ?>
			<p id="subnavbar-fid-p-id" hidden>0</p>		
			<p id="subnavbar-type-p-id" hidden>0</p>		
		<?php } else {?>
			<p id="subnavbar-fid-p-id" hidden><?=$objNotice->notice_fid?></p>
			<p id="subnavbar-type-p-id" hidden><?=$objNotice->notice_type?></p>
		<?php } ?>
	</div>
	<style>
	.useredit-text-div input[type=checkbox] {
		zoom: 130%;
		margin-top: 5px;
		width:20px;
	}
	</style>
<!--Site Setting-->
	<div class="useredit-panel">
		<div class="useredit-text-div">
			<p >발송자아이디:</p>
			<label for="notice-mbuid-input-id">전체는 '*'로 입력하세요</label>
			<?php if(is_null($objNotice)) {  ?>
			<input type = "text" id="notice-mbuid-input-id" style="width:250px;" value="<?=$strUserId ?>">
			<?php } else {?>
			<input type = "text" id="notice-mbuid-input-id" style="width:250px;" value="<?=$objNotice->notice_mb_uid?>" disabled>
			<?php } ?>
		</div>
		<!---->
		<div class="useredit-text-div">
			<p>발송(대기):</p>
			<?php if(is_null($objNotice)) {  ?>
			<input type="checkbox" id="notice-state-check-id" name="public" checked>
			<?php } else if($objNotice->notice_type == 3) {  ?>	
			<input type="checkbox" id="notice-state-check-id" name="public" checked>
			<?php } else if($objNotice->notice_state_active == 0) {  ?>	
			<input type="checkbox" id="notice-state-check-id" name="public">
			<?php } else {?>
			<input type="checkbox" id="notice-state-check-id" checked name="public">
			<?php } ?>
			<label for="public" style="font-size:14px;">발송</label>
		</div>
		<div class="useredit-text-div">
			<p>쪽지제목:</p> 
			<?php if(is_null($objNotice) || is_null($objNotice->notice_title)) {  ?>	
			<input type = "text" id="notice-title-input-id" style="width:60%;">
			<?php } else {?>
			<input type = "text" id="notice-title-input-id" value="<?=$objNotice->notice_title?>" style="width:60%;">
			<?php } ?>
		</div>

		
		<?php if(!is_null($objNotice) && $objNotice->notice_type == 3) {  ?>
		<div class="useredit-text-div">
			<p>쪽지내용:</p> 
			<textarea rows="8" id="notice-content-text-id" style="width:60%;" disabled><?=$objNotice->notice_content?></textarea>	
		</div>
	
		<div class="useredit-text-div">
			<p>회답내용:</p> 
			<textarea rows="8" id="notice-answer-text-id" style="width:60%;"><?php if(!is_null($objNotice)) {  ?><?=$objNotice->notice_answer?><?php } ?></textarea>	
		</div>
		<?php } else {?>
		<div class="useredit-text-div">
			<p>쪽지내용:</p> 
			<textarea rows="8" id="notice-content-text-id" style="width:60%;"><?php if(!is_null($objNotice)) {  ?><?=$objNotice->notice_content?><?php } ?></textarea>	
		</div>

		<?php } ?>

		<div class = "useredit-button-group">
			<button class="useredit-cancel-button" id="notice-cancel-btn-id">취소</button>
			<?php if(is_null($objNotice) || $objNotice->notice_type==0) {  ?>
			<button class="useredit-ok-button"  id="notice-save-btn-id">발송</button>			
			<?php } else {?>
			<button class="useredit-ok-button"  id="notice-save-btn-id">회답</button>
			<?php } ?>
			
		</div>
	</div>
<!--main_navbar.php-->
</div>


<script src="<?php echo base_url('assets/js/message_edit-script.js');?>"></script>
<?= $this->endSection() ?>