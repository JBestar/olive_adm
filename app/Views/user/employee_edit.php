<?php echo $this->extend('user/user_edit'); ?>
<?php echo $this->section('user-edit-title'); ?>매장<?php echo $this->endSection(); ?>
<?php echo $this->section('user-edit-check-level-open'); ?>
<?php if ($mb_level > LEVEL_COMPANY) { ?>
<?php echo $this->endSection(); ?>
<?php echo $this->section('user-edit-check-level-close'); ?>
<?php }?>
<?php echo $this->endSection(); ?>

<?php echo $this->section('user-edit-form-section0'); ?>
	<div class="useredit-text-div">
		<p>추천인:</p> 
		<?php if (is_null($objMember)) {  ?>
		<input type = "text" id="useredit-sort-select-id" value="<?php echo $emp_uid; ?>">
		<?php } else { ?>
		<input type = "text" id="useredit-sort-select-id" value="<?php echo $emp_uid; ?>" disabled>
		<?php } ?>
	</div>
<?php echo $this->endSection(); ?>
<?php echo $this->section('user-edit-form-section2'); ?>
		<div class="useredit-text-div">
			<p>색깔:</p> 
			<?php if (is_null($objMember) || is_null($objMember->mb_color)) {  ?>
			<input type="color" value="#ffffff" id="useredit-color-input-id">
			<?php } else {?>
			<input type="color" value="<?php echo $objMember->mb_color; ?>" id="useredit-color-input-id" >
			<?php } ?>
		</div>
		<div class="useredit-check-div">			 
			<?php if (is_null($objMember) || 0 == $objMember->mb_emp_permit) {  ?>	
			<input type="checkbox" id="useredit-modify-check-id">
			<?php } else {?>
			<input type="checkbox" id="useredit-modify-check-id" checked>
			<?php } ?>
			<label> 하부매장회원정보수정</label>
		</div>
<?php echo $this->endSection(); ?>
<?php echo $this->section('user-edit-script'); ?>
	<script src="<?php echo base_url('assets/js/employee_edit-script.js?v=2'); ?>"></script>
<?php echo $this->endSection(); ?>