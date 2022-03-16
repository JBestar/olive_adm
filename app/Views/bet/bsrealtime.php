	<!--Sub Navbar-->
	<div class = "sub-navbar">
		<p><i class="glyphicon glyphicon-dashboard"></i> 실시간배팅</p>
		<a href="<?php echo base_url().'bet/pbrealtime';?>" class="sub-navbar-a" >파워볼</a>
		<a href="<?php echo base_url().'bet/psrealtime';?>" class="sub-navbar-a" >파워사다리</a>
		<a href="<?php echo base_url().'bet/ksrealtime';?>" class="sub-navbar-a" >키노사다리</a>
		<a href="<?php echo base_url().'bet/bbrealtime';?>" class="sub-navbar-a" >보글볼</a>
		<a href="<?php echo base_url().'bet/bsrealtime';?>" class="sub-navbar-a active" >보글사다리</a>
	</div>

	<div class="bet-panel">
		<h4>보글사다리 실시간배팅</h4>
		<Table class="bet-table" id="pbbet-table-id">
			<tr>
				<th>번호</th>
				<th>경기시간</th>
				<th>회차</th>
				<th>배팅</th>
				<th>배당율</th>
				<th>금액</th>
				<th>밸런스금액</th>												
			</tr>
			
		</Table>

	</div>


<!--main_navbar.php-main-container-->
</div>


<script src="<?php echo base_url('assets/js/bsrealtime-script.js');?>"></script>