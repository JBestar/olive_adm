<?= $this->extend('/bet/calculate') ?>
<?= $this->section('calculate-active') ?>
"전체"
<?= $this->endSection() ?>
<?= $this->section('calculate-title') ?>
전체 정산내역
<?= $this->endSection() ?>
<?= $this->section('calculate-table-header') ?>
	<th></th>
	<th>ID</th>
	<th>닉네임</th>
	<th>구분</th>
	<th>충전</th>
	<th>환전</th>
	<th>충환손익</th>
	<th>관리자보유금<br>(하부합산)</th>
	<th>유저보유금</th>
	<th>배팅<br>(하부포함)</th>
	<th>적중<br>(하부포함)</th>
	<th>배팅손익</th>
	<th>수수료</th>
	<th>최종손익</th>				
<?= $this->endSection() ?>
<?= $this->section('calculate-script') ?>
	<script src="<?php echo base_url('assets/js/allcalculate-script.js?v=3');?>"></script>
<?= $this->endSection() ?>
