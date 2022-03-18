<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	<!--Sub Navbar
	<i class="glyphicon glyphicon-resize-horizontal"></i>
	-->
	<div class = "sub-navbar">
		<p> 사이트 <i class="glyphicon glyphicon-transfer"></i> 카지노 머니이동내역</p>
	</div>
	<!--Site Setting-->
	<div class="bank-panel">	
		
		<div class="pbresult-list-div">
			<label>기간</label>
			<input type="date" id="transfer-datestart-input-id" >
            <label> ~ </label>
            <input type="date" id="transfer-dateend-input-id" >
            <label>아이디</label>
            <input type="text" class="pbresult-text-input" id="transfer-userid-input-id" >

            <select class="pbresult-game-select" id="transfer-type-select-id">
				<option value="0">::분류::</option>
				<option value="1">카지노로 이동</option>
				<option value="2">사이트로 이동</option>
				
			</select>

			<select name="pbresult-number" class="pbresult-number-select" id="transfer-number-select-id">
				<option value="10">10개</option>
				<option value="20">20개</option>
				<option value="50">50개</option>
				<option value="100">100개</option>
			</select>
			<button class="pbresult-list-view-but" id="transfer-list-view-but-id">검색</button>
		</div>


		<Table class="user-table">
			<thead>
				<tr>
					<th>번호</th>
					<th>닉네임</th>
					<th>아이디</th>
					<th>현재금액</th>
					<th>카지노금</th>
					<th>구분</th>
					<th>이동금액</th>
					<th>충전전 사이트머니</th>
					<th>충전후 사이트머니</th>
					<th>충전전 카지노머니</th>
					<th>충전후 카지노머니</th>
					<th>일짜</th>
					
				</tr>
			</thead >
			<tbody  id="transfer-table-id">
				
			</tbody>
		</Table>
		<div class="pbresult-list-page-div">			
			<div class="pagination"  id="list-page"  style="display:none">
				<button class="list-page-button" id="page-prev"  onclick="prevPage()"><</button>
				<div class="pagination-div" id="pagination-num">
					<button class="active">1</button>
					<button class="">2</button>						
				</div>
				<button class="list-page-button" id="page-next"  onclick="nextPage()">></button>
			</div>			
	
		</div>
	</div>

	
<!--main_navbar.php-main-container-->
</div>

<script src="<?php echo base_url('assets/js/page.js');?>"></script>
<script src="<?php echo base_url('assets/js/transfer-script.js');?>"></script>
<?= $this->endSection() ?>