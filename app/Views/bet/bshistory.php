<?= $this->extend('/bet/history') ?>
<?= $this->section('history-active') ?>"보글사다리"<?= $this->endSection() ?>
<?= $this->section('history-title') ?>보글사다리 배팅내역<?= $this->endSection() ?>
<?= $this->section('history_game_options') ?>		
	<option value="1">좌우</option>
	<option value="2">줄수</option>
	<option value="3">홀짝</option>
	<option value="4">조합</option>
<?= $this->endSection() ?>
<?= $this->section('history-add-round-search') ?>
	<label>회차</label>
	<input type="text" class="pbresult-text-input" id="pbhistory-roundid-input-id" >
<?= $this->endSection() ?>
<?= $this->section('history-bet-table-headers') ?>
	<th>ID</th>
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
	<th>포인트</th>
<?= $this->endSection() ?>
<?= $this->section('history_script') ?>
	<script> var mPath = "bsapi"; </script>
	<script src="<?php echo base_url('assets/js/bshistory-script.js?v=2');?>"></script>
<?= $this->endSection() ?>