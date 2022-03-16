	<!--Sub Navbar-->
	<div class = "sub-navbar">
		<p><i class="glyphicon glyphicon-book"></i> 게임결과</p>
		<a href="<?php echo base_url().'result/pbresult';?>" class="sub-navbar-a active" >파워볼</a>
		<a href="<?php echo base_url().'result/psresult';?>" class="sub-navbar-a" >파워사다리</a>
		<a href="<?php echo base_url().'result/ksresult';?>" class="sub-navbar-a" >키노사다리</a>
		<a href="<?php echo base_url().'result/bbresult';?>" class="sub-navbar-a" >보글볼</a>
		<a href="<?php echo base_url().'result/bsresult';?>" class="sub-navbar-a" >보글사다리</a>
	</div>

	<div class="pbresult-list-panel">
		<h4>파워볼 게임결과</h4>
		<div class="pbresult-list-div">
			<label>기간 선택</label>
			<input type="date" id="pbresult-datestart-input-id" >
            <label> ~ </label>
            <input type="date" id="pbresult-dateend-input-id" >
            <label>일회차</label>
            <input type="number" id="pbresult-round-input-id" style="width:100px;" min="1">
			<select name="pbresult-number" class="pbresult-number-select" id="pbresult-number-select-id">
				<option value="10">10개</option>
				<option value="20">20개</option>
				<option value="50">50개</option>
				<option value="100">100개</option>
			</select>
			<button class="pbresult-list-view-but" id="pbresult-list-view-but-id">검색</button>  
			<a href="<?php echo base_url().'result/pbresult_edit/0';?>" class="user-panel-add-a" >회차등록</a>
		</div>
		<Table class="user-table">
			<thead>
				<tr>
					<th>추첨일</th>
					<th>회차</th>
					<th>일회차</th>
					<th>파워볼</th>
					<th>홀짝</th>
					<th>언오버</th>
					<th>일반볼</th>
					<th>홀짝</th>
					<th>언오버</th>	
					<th>대중소</th>
					<th>게임관리</th>			
				</tr>
			</thead>
			<tbody id="pbresult-list-table-id">

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
<script src="<?php echo base_url('assets/js/pbresult-script.js');?>"></script>