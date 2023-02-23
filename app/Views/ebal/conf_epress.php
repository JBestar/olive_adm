<?= $this->extend('/ebal/ebal_cont') ?>
<?= $this->section('ebal-active') ?><?=$game_name?><?= $this->endSection() ?>
<?= $this->section('eval_content') ?>

	<div class="confsite-site-panel">
		<h4><i class="glyphicon glyphicon-hand-right"></i> 자동누르기 설정</h4>

		<div class="pbresult-list-div" style="padding-left:20px;">
			<label style="width:80px;">자동누르기:</label> 
			<input type="checkbox" id="confev-autopress-check-id" style="zoom:120%; margin-top:0px;"><label style="font-size:14px; font-weight:normal; padding-top:0px;"> 승인</label>
		
			<label style="margin-left:50px; width:70px;">배팅시간:</label> 
			<input type = "number" class="conf-text-input" style="width:80px;" id="confev-bettime-input-id" min="0" step="1">&nbsp;&nbsp;시간
		
			<label style="margin-left:50px; width:70px;">보유금액:</label> 
			<input type = "number" class="conf-text-input" style="width:80px;" id="confev-moneypercent-input-id" min="0" step="1">&nbsp;&nbsp;%이하
		
			<button class="pbresult-list-view-but" id="confsite-ok-btn-id">저장</button>
		</div>


		<h4><i class="glyphicon glyphicon-hand-right"></i> 누르기 변동내역</h4>
		<div class="pbresult-list-div" style="padding-left:20px;">
			<label>기간</label>
			<input type="datetime-local" id="pbhistory-datestart-input-id"  value="<?php echo date('Y-m-d')."T00:00"; ?>">
			<label> ~ </label>
			<input type="datetime-local" id="pbhistory-dateend-input-id"  value="<?php echo date('Y-m-d')."T23:59"; ?>" style="margin-right:10px">
			<input type="text" placeholder="     아이디" class="pbresult-text-input" id="pbhistory-userid-input-id" >
			<select name="pbresult-number" class="pbresult-number-select" id="pbhistory-number-select-id" style="width:70px; margin-left:0px;">
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
					<th>아이디</th>
					<th>변동상태</th>
					<th>변동타입</th>
					<th>변동시간</th>
					<th>설명</th>
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
    <script src="<?php echo site_furl('/assets/js/conf_epress-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
    <script src="<?php echo site_furl('/assets/js/conf_epress-script.js?v=1');?>"></script>
<?php endif ?>

<?= $this->endSection() ?>
