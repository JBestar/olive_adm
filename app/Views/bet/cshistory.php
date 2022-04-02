<?= $this->extend('/bet/history') ?>
<?= $this->section('history-active') ?>"에볼루션"<?= $this->endSection() ?>
<?= $this->section('history-title') ?>		
	에볼루션 배팅내역
<?= $this->endSection() ?>
<?= $this->section('history_game_options') ?>		
	<option value="0">바카라</option>
	<option value="1">용호</option>
	<option value="2">슈퍼 식보</option>
	<option value="3">룰렛</option>
<?= $this->endSection() ?>
<?= $this->section('history-bet-table-headers') ?>		
	<th>ID</th>
	<th>아이디</th>
	<th>닉네임</th>	
	<th>배팅시간</th>
	<th>구분</th>
	<th>게임방</th>
	<th>배팅금액</th>
	<th>배팅결과</th>
	<th>당첨금액</th>
	<th>포인트</th>
<?= $this->endSection() ?>
<?= $this->section('history_script') ?>
<script src="<?php echo base_url('assets/js/cshistory-script.js?v=2');?>"></script>
<?= $this->endSection() ?>