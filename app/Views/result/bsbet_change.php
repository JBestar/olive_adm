<?= $this->extend('result/bet_change') ?>
<?= $this->section('bet-change-title') ?>보글사다리<?= $this->endSection() ?>
<?= $this->section('bet-change-table-header') ?>
	<th>번호</th>
	<th>배팅시간</th>
	<th>회차</th>
	<th>아이디</th>
	<th>구분</th>
	<th>배팅금액</th>
	<th>배당율</th>
	<th>배팅선택</th>
	<th>경기결과</th>
	<th>당첨금액</th>
	<th>배팅결과</th>
	<th>처리</th>
<?= $this->endSection() ?>
<?= $this->section('bet-change-script') ?>
	<script> var mPath = "/bsapi"; </script>
	<script src="<?php echo base_url('assets/js/psbet_change-script.js?v=2');?>"></script>
<?= $this->endSection() ?>