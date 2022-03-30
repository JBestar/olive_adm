<?= $this->extend('user/user_edit') ?>
<?= $this->section('user-edit-title') ?>회원<?= $this->endSection() ?>
<?= $this->section('user-edit-check-level') ?>
<?php if($mb_level > LEVEL_COMPANY) ?>
<?= $this->endSection() ?>
<?= $this->section('user-edit-form-section0') ?>
	<div class="useredit-text-div">
		<p>추천인:</p> 
		<?php if($mb_level > LEVEL_COMPANY) {  ?>
		<select type = "text" id="useredit-sort-select-id">
		<?php } else { ?>
		<select type = "text" id="useredit-sort-select-id" disabled>
		<?php } ?>

			<?php foreach ($arrEmpName as $objEmpName):?>
				<?php if(is_null($objMember) || ($objMember->mb_emp_fid != $objEmpName->mb_fid)) {  ?>
				<option value="<?=$objEmpName->mb_fid?>"><?=$objEmpName->mb_name?></option>
				<?php } else {?>
				<option value="<?=$objEmpName->mb_fid?>" selected><?=$objEmpName->mb_name?></option>
				<?php } ?>
			<?php endforeach;?>
		</select>
	</div>
<?= $this->endSection() ?>
<?= $this->section('user-edit-form-section1') ?>
	<div class="useredit-text-div">	
		<p>레벨:</p> 
		<?php if($mb_level > LEVEL_COMPANY) {  ?>
		<select type = "text" id="useredit-level-select-id">
		<?php } else { ?>
		<select type = "text" id="useredit-level-select-id" disabled>
		<?php } ?>

			<?php foreach (range(1, 6) as $useLevel):?>
				<?php if(is_null($objMember) || ($objMember->mb_level != $useLevel)) {  ?>
				<option value="<?=$useLevel?>"><?=$useLevel?>레벨</option>
				<?php } else {?>
				<option value="<?=$useLevel?>" selected><?=$useLevel?>레벨</option>
				<?php } ?>
			<?php endforeach;?>
		</select>
	</div>
<?= $this->endSection() ?>
<?= $this->section('user-edit-script') ?>
	<script src="<?php echo base_url('assets/js/member_edit-script.js?v=2');?>"></script>
<?= $this->endSection() ?>