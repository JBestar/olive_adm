<?= $this->extend('header') ?>
<?= $this->section('content') ?>
<!--Sub Navbar-->
	<div class = "sub-navbar">
		<p><i class="glyphicon glyphicon-plus-sign"></i> 충환전관리::충전</p>
	</div>
	<!--Site Setting-->
	<div class="bank-panel">
		
		<div class="pbresult-list-div">
			<label>아이디</label>
            <input type="text" class="pbresult-text-input" id="deposit-userid-input-id" >
			<label>요청일짜</label>
			<input type="date" id="deposit-datestart-input-id"  value="<?php echo date('Y-m-d'); ?>">
            <label> ~ </label>
            <input type="date" id="deposit-dateend-input-id"  value="<?php echo date('Y-m-d'); ?>">
            
			<select name="pbresult-number" class="pbresult-number-select" id="deposit-number-select-id">
				<option value="10">10개</option>
				<option value="20">20개</option>
				<option value="50">50개</option>
				<option value="100">100개</option>
			</select>
			<button class="pbresult-list-view-but" id="deposit-list-view-but-id">검색</button>
		</div>
		
		<Table class="user-table" >
			<thead>
				<tr>
					<th>번호</th>
					<th>닉네임</th>
					<th>아이디</th>
					<th>현재금액</th>
					<th>요청일짜</th>
					<th>충전금액</th>
					<th>전화번호</th>
					<th>상태</th>
					<th>승인일짜</th>
					<th>관리자</th>
					<th>설정</th>
				</tr>
			</thead>
			<tbody id="bank-deposit-table-id">
				
			</tbody>
		</Table>
		
		<div class="pbresult-list-page-div">			
			<p>충전금액 합계: <span id="bank-deposit-total-id"></span></p>
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

<script src="<?php echo base_url('assets/js/page.js?v=1');?>"></script>
<script src="<?php echo base_url('assets/js/deposit-script.js');?>"></script>
<?= $this->endSection() ?>