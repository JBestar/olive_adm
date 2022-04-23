<?= $this->extend('/bet/history') ?>
<?= $this->section('history-active') ?>"<?=$game_name?>"<?= $this->endSection() ?>
<?= $this->section('history-title') ?><?=$game_name?> 배팅내역<?= $this->endSection() ?>
<?= $this->section('history_game_options') ?>	
	<?php foreach ($prds as $prd):?>
		<option value="<?=$prd->code?>"><?=$prd->name?></option>
	<?php endforeach;?>
<?= $this->endSection() ?>
<?= $this->section('history-bet-table-headers') ?>		
	<th>번호</th>
	<th>아이디</th>
	<th>닉네임</th>	
	<th>배팅시간</th>
	<th>구분</th>
	<th>게임종류</th>
	<th>배팅금액</th>
	<th>배팅결과</th>
	<th>당첨금액</th>
	<th>포인트</th>
<?= $this->endSection() ?>
<?= $this->section('history_script') ?>
<script>var mGameId = <?=$game_id?></script>
<script src="<?php echo base_url('assets/js/slhistory-script.js?v=2');?>"></script>
<?= $this->endSection() ?>