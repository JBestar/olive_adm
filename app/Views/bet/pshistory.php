<?= $this->extend('/bet/history') ?>
<?= $this->section('history-active') ?>"파워사다리"<?= $this->endSection() ?>
<?= $this->section('history-title') ?>파워사다리 배팅내역<?= $this->endSection() ?>
<?= $this->section('history_game_options') ?>	
	<option value="0">::배팅선택::</option>	
	<option value="1">좌우</option>
	<option value="2">줄수</option>
	<option value="3">홀짝</option>
	<option value="4">조합</option>
<?= $this->endSection() ?>
<?= $this->section('history-add-round-search') ?>
	<label>회차</label>
	<input type="text" class="pbresult-text-input" id="pbhistory-roundid-input-id" style="width:70px;">
<?= $this->endSection() ?>
<?= $this->section('history-bet-table-headers') ?>
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
	<th>포인트</th>
<?= $this->endSection() ?>
<?= $this->section('history_script') ?>
	<script> var mPath = "/psapi"; </script>
	<?php if(array_key_exists("app.produce", $_ENV)) :?>
		<script src="<?php echo site_furl('/assets/js/pshistory-script.js?t='.time());?>"></script>
	<?php else : ?>
		<script src="<?php echo site_furl('/assets/js/pshistory-script.js?v=1');?>"></script>
	<?php endif ?>
<?= $this->endSection() ?>