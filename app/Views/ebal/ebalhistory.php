<?= $this->extend('/ebal/ebal_cont') ?>
<?= $this->section('ebal-active') ?><?=$game_name?><?= $this->endSection() ?>
<?= $this->section('eval_content') ?>
	<div class="bet-panel">
		<div class="pbresult-list-div">
			<label>기간</label>
			<input type="datetime-local" id="pbhistory-datestart-input-id"  value="<?php echo date('Y-m-d')."T00:00"; ?>">
            <label> ~ </label>
            <input type="datetime-local" id="pbhistory-dateend-input-id"  value="<?php echo date('Y-m-d')."T23:59"; ?>" style="margin-right:10px">
            <input type="text" placeholder="     아이디" class="pbresult-text-input" id="pbhistory-userid-input-id" >

            <select name="pbresult-number" class="pbresult-number-select" id="pbhistory-number-select-id" style="width:70px; margin-left:10px;">
				<option value="10">10개</option>
				<option value="20">20개</option>
				<option value="50">50개</option>
				<option value="100">100개</option>
			</select>
			<button class="pbresult-list-view-but" id="pbhistory-list-view-but-id">검색</button>
		</div>
		<Table class="bet-table">
			<thead>
				<tr>
					<th>ID</th>
					<th>계정</th>
					<th>배팅시간</th>
					<th>게임종류</th>
					<th>게임방</th>
					<th>배팅금</th>
					<th>적중금</th>
					<th>배팅결과</th>
				</tr>
			</thead>
			<tbody id="pbbet-table-id">

			</tbody>
		</Table>
		<div class="pbresult-list-page-div">
			<div class="pagination"  id="list-page"  style="display:none">
				<button class="list-page-button" id="page-first"  onclick="firstPage()"><<</button>
				<button class="list-page-button" id="page-prev"  onclick="prevPage()"><</button>
				<div class="pagination-div" id="pagination-num">
					<button class="active">1</button>
					<button class="">2</button>						
				</div>
				<button class="list-page-button" id="page-next"  onclick="nextPage()">></button>
				<button class="list-page-button" id="page-last"  onclick="lastPage()">>></button>
			</div>			
	
		</div>
	</div>

<!--main_navbar.php-main-container-->
</div>

<?php if(array_key_exists("app.produce", $_ENV)) :?>
    <script src="<?php echo site_furl('/assets/js/page.js?t='.time());?>"></script>
    <script src="<?php echo site_furl('/assets/js/ebalhistory-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
    <script src="<?php echo site_furl('/assets/js/ebalhistory-script.js?v=1');?>"></script>
<?php endif ?>

<?= $this->endSection() ?>
