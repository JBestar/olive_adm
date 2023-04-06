<?= $this->extend('/ebal/ebal_cont') ?>
<?= $this->section('ebal-active') ?><?=$game_name?><?= $this->endSection() ?>
<?= $this->section('eval_content') ?>

	<style>
		.user-table button {
			width:70px;
		}
	</style>
	<div class="bet-panel">
		<div class="pbresult-list-div">
			
			<label style="font-size:18px; font-weight:normal">
				에볼루션 방설정
			</label>
		</div>
		<Table class="user-table">
			<thead>
				<tr>
					<th>번호</th>
					<th>방</th>
					<th>상태</th>
					<th style="border-right:2px solid #aaa">설정</th>
					<th>번호</th>
					<th>방</th>
					<th>상태</th>
					<th>설정</th>
				</tr>
			</thead>
			<tbody id="pbbet-table-id">

			</tbody>
		</Table>
		
	</div>

<!--main_navbar.php-main-container-->
</div>

<?php if(array_key_exists("app.produce", $_ENV)) :?>
    <script src="<?php echo site_furl('/assets/js/conf_eroom-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/conf_eroom-script.js?v=1');?>"></script>
<?php endif ?>

<?= $this->endSection() ?>
