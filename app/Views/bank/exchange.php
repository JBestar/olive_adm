<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	<!--Sub Navbar-->
	<div class = "sub-navbar">
		<p><i class="glyphicon glyphicon-ok-sign"></i> 충환전관리::머니거래내역</p>
	</div>
	<!--Site Setting-->
	<div class="bank-panel">	
		
		<div class="pbresult-list-div">
			<label>기간</label>
			<input type="date" id="exchange-datestart-input-id" value="<?php echo date('Y-m')."-01";?>">
            <label> ~ </label>
            <input type="date" id="exchange-dateend-input-id" value="<?php echo date('Y-m-d'); ?>">
            <label>아이디</label>
            <input type="text" class="pbresult-text-input" id="exchange-userid-input-id" >

            <select class="pbresult-game-select" id="exchange-game-select-id">
				<option value="0">::분류::</option>
				<option value="1">충전</option>
				<option value="2">환전</option>
				<option value="3">포인트전환</option>
				<?php if(!$npg_deny) :?>
					<option value="4">파워볼배팅</option>				
					<option value="6">파워볼정산</option>
					<option value="7">파워사다리배팅</option>
					<option value="9">파워사다리정산</option>
				<?php endif ?>   
				<?php if(!$bpg_deny) :?>
					<option value="13">보글볼배팅</option>				
					<option value="15">보글볼정산</option>
					<option value="16">보글사다리배팅</option>
					<option value="18">보글사다리정산</option>
				<?php endif ?>   
				<option value="19">머니충전</option>
				<option value="20">머니이송</option>
			</select>

			<select name="pbresult-number" class="pbresult-number-select" id="exchange-number-select-id">
				<option value="10">10개</option>
				<option value="20">20개</option>
				<option value="50">50개</option>
				<option value="100">100개</option>
			</select>
			<button class="pbresult-list-view-but" id="exchange-list-view-but-id">검색</button>	  
		</div>


		<Table class="user-table" >
			<thead>
			<tr>
				<th>번호</th>
				<th>닉네임</th>
				<th>아이디</th>
				<th>현재금액</th>
				<th>거래금액</th>
				<th>거래전 머니</th>
				<th>거래후 머니</th>
				<th>일짜</th>
				<th>구분</th>
				<th>설명</th>
			</tr>
			</thead>
			<tbody id="bank-exchange-table-id">

			</tbody>
		</Table>
		<div class="pbresult-list-page-div">			
			<div class="pagination" id="list-page" style="display:none">
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
    <script src="<?php echo site_furl('/assets/js/exchange-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
    <script src="<?php echo site_furl('/assets/js/exchange-script.js?v=1');?>"></script>
<?php endif ?>
<?= $this->endSection() ?>