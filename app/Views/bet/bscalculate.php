	<!--Sub Navbar-->
	<div class = "sub-navbar">
		<p><i class="glyphicon glyphicon-tag"></i> 정산</p>
		<a href="<?php echo base_url().'bet/allcalculate';?>" class="sub-navbar-a" >전체</a>
		<a href="<?php echo base_url().'bet/pbcalculate';?>" class="sub-navbar-a" >파워볼</a>
		<a href="<?php echo base_url().'bet/pscalculate';?>" class="sub-navbar-a" >파워사다리</a>
		<a href="<?php echo base_url().'bet/kscalculate';?>" class="sub-navbar-a" >키노사다리</a>
		<a href="<?php echo base_url().'bet/bbcalculate';?>" class="sub-navbar-a" >보글볼</a>
		<a href="<?php echo base_url().'bet/bscalculate';?>" class="sub-navbar-a active" >보글사다리</a>
		<a href="<?php echo base_url().'bet/cscalculate';?>" class="sub-navbar-a" >에볼루션</a>
	</div>

	<div class="bet-panel">
		<h4>보글사다리 정산내역</h4>
		
		<div class="pbresult-list-div">
			<label>기간</label>
			<input type="date" id="calculate-datestart-input-id" value="<?php echo date('Y-m-d'); ?>">
            <label> ~ </label>
            <input type="date" id="calculate-dateend-input-id" value="<?php echo date('Y-m-d'); ?>">
			<button class="pbresult-list-view-but" id="calculate-list-view-but-id">검색</button>
        </div>
		<Table class="calculate-table" id="calculate-table-id">
			<thead>
				<tr>
					<th></th>
					<th>ID</th>
					<th>닉네임</th>
					<th>본사구분</th>
					<th>충전</th>
					<th>환전</th>
					<th>충환손익</th>
					<th>관리자보유금<br>(하부합산)</th>
					<th>유저보유금</th>
					<th>배팅<br>(하부포함)</th>
					<th>적중<br>(하부포함)</th>
					<th>배팅손익</th>
					<th>수수료</th>
					<th>최종손익</th>				
				</tr>
			</thead>
			<tbody id="calculate-table-tbody-id">



			</tbody>
		</Table>

	</div>


<!--main_navbar.php-main-container-->
</div>



<script src="<?php echo base_url('assets/js/bscalculate-script.js');?>"></script>