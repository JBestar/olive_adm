<?= $this->extend('bet/realtime') ?>
<?= $this->section('realtime-title') ?>보글사다리<?= $this->endSection() ?>
<?= $this->section('realtime-table-header') ?>
	<th>번호</th>
	<th>경기시간</th>
	<th>회차</th>
	<th>배팅</th>
	<th>배당율</th>
	<th>금액</th>
	<th>밸런스금액</th>		
<?= $this->endSection() ?>
<?= $this->section('realtime-script') ?>
	<script> var mPath = "bsapi"; </script>
	<script src="<?php echo base_url('assets/js/bsrealtime-script.js?v=3');?>"></script>
<?= $this->endSection() ?>
