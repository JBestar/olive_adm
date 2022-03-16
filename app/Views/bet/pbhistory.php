	<!--Sub Navbar-->
	<div class = "sub-navbar">
		<p><i class="glyphicon glyphicon-book"></i> 배팅내역</p>
		<a href="<?php echo base_url().'bet/pbhistory';?>" class="sub-navbar-a active" >파워볼</a>
		<a href="<?php echo base_url().'bet/pshistory';?>" class="sub-navbar-a" >파워사다리</a>
		<a href="<?php echo base_url().'bet/kshistory';?>" class="sub-navbar-a" >키노사다리</a>
		<a href="<?php echo base_url().'bet/cshistory';?>" class="sub-navbar-a" >에볼루션</a>
		<a href="<?php echo base_url().'bet/bbhistory';?>" class="sub-navbar-a" >보글볼</a>
		<a href="<?php echo base_url().'bet/bshistory';?>" class="sub-navbar-a" >보글사다리</a>
	</div>

	<div class="bet-panel">
		<h4>파워볼 배팅내역</h4>
		
		<div class="pbresult-list-div">
			<label>기간</label>
			<input type="date" id="pbhistory-datestart-input-id"  value="<?php echo date('Y-m-')."01"; ?>">
            <label> ~ </label>
            <input type="date" id="pbhistory-dateend-input-id"  value="<?php echo date('Y-m-d'); ?>">
            <label>회차</label>
            <input type="text" class="pbresult-text-input" id="pbhistory-roundid-input-id" >
            <label>아이디</label>
            <input type="text" class="pbresult-text-input" id="pbhistory-userid-input-id" >

            <select class="pbresult-game-select" id="pbhistory-game-select-id">
				<option value="0">::게임선택::</option>
				<option value="1">파워볼 단폴</option>
				<option value="2">파워볼 조합</option>
				<option value="3">일반볼 대중소</option>
			</select>

			<select name="pbresult-number" class="pbresult-number-select" id="pbhistory-number-select-id">
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
					<th>매장</th>
					<th>총판</th>
					<th>부본사</th>
				</tr>
			</thead>
			<tbody id="pbbet-table-id">

			</tbody>
		</Table>
		<div class="pbresult-list-page-div">
			<?php if($mb_level >= LEVEL_ADMIN) {  ?>
			<p>배팅금: <span id="total-betmoney-id">0</span></p>
			<p>적중금: <span id="total-winmoney-id">0</span></p>
			<p>미적중금: <span id="total-lossmoney-id">0</span></p>
			<p>당첨금: <span id="total-benefit-id">0</span></p>
			<?php } ?>
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
<script src="<?php echo base_url('assets/js/pbhistory-script.js');?>"></script>