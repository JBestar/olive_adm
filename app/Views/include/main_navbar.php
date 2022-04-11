<!--?= $this->extend('include/sidebar')?-->
<!--?= $this->section('MainNavBar')?-->
<div class="main-container" id="main-container-id" data-app="<?=$_ENV['app.name']?>">
	<div class="loading" style="display: none;">
		<div class="load lds-ellipsis"></div>
	</div>
	<div class="main-navbar" id="main-navbar-id">
		<h4 style="float: left; margin: 5px 40px 5px 10px;  font-weight: bold; color: #0090ff; font-size: 20px;"
			id="main-navbar-level-id"></h4>

		<Table class="main-navbar-info-table" id="main-navbar-table-id">
			<tr>
				<td>보유금액<span id="main-navbar-emp_money-id">0 원 </span></td>
				<td>충전금액<span id="main-navbar-emp_charge-id">0 원</span></td>

				<?php if($mb_level < LEVEL_ADMIN) {  ?>

				<td>파워볼<span id="main-navbar-emp_pbrate-id">0 % | 0 %</span></td>
				<td>보글볼<span id="main-navbar-emp_bbrate-id">0 % | 0 %</span></td>
				<td>카지노<span id="main-navbar-emp_evrate-id">0 %</span></td>
				<?php } else { ?>

				<td>새 문의&nbsp;<a href="/board/message" id="main-navbar-newmessage-id">0 통</a></td>
				<td>충전&nbsp;<a href="/bank/deposit" id="main-navbar-charge_wait-id">0 대기</a></td>

				<?php } ?>

			</tr>
			<tr>

				<td>보유포인트<span id="main-navbar-emp_point-id">0 P </span></td>
				<td>환전금액<span id="main-navbar-emp_exchange-id">0 원</span></td>

				<?php if($mb_level < LEVEL_ADMIN) {  ?>
				<td>파워사다리<span id="main-navbar-emp_psrate-id">0 % </span></td>
				<td>보글사다리<span id="main-navbar-emp_bsrate-id">0 % </span></td>
				<td>슬롯<span id="main-navbar-emp_slrate-id">0 %</span></td>
				<?php } else { ?>

				<td>가입신청&nbsp;<a href="/user/member/0" id="main-navbar-user_wait-id">0 명</span></td>
				<td>환전&nbsp;<a href="/bank/withdraw" id="main-navbar-exchange_wait-id">0 대기</span></td>

				<?php } ?>


			</tr>

		</Table>

		<?php if($mb_level >= LEVEL_ADMIN) {  ?>
		<Table class="main-navbar-betinfo-table">
			<tr>
				<td style="font-weight: bold; color: #0090ff; font-size: 14px;">파워볼단폴:</td>
				<td>배팅<span id="main-navbar-pbbet-id">0 원</span></td>
				<td>적중<span id="main-navbar-pbbetearn-id">0 원</span></td>
				<!--<td>누름<span id="main-navbar-pbbetpress-id">0 원</span></td>-->
			</tr>
			<tr>
				<td style="font-weight: bold; color: #0090ff; font-size: 14px;">파워볼조합:</td>
				<td>배팅<span id="main-navbar-pb2bet-id">0 원</span></td>
				<td>적중<span id="main-navbar-pb2betearn-id">0 원</span></td>
				<!--<td>누름<span id="main-navbar-pb2betpress-id">0 원</span></td>-->
			</tr>
			<tr>
				<td style="font-weight: bold; color: #0090ff; font-size: 14px;">파워사다리:</td>
				<td>배팅<span id="main-navbar-psbet-id">0 원</span></td>
				<td>적중<span id="main-navbar-psbetearn-id">0 원</span></td>
				<!--<td>누름<span id="main-navbar-psbetpress-id">0 원</span></td>-->
			</tr>

		</Table>
		<Table class="main-navbar-betinfo-table">
			<tr>
				<td style="font-weight: bold; color: #0090ff; font-size: 14px;">보글볼단폴:</td>
				<td>배팅<span id="main-navbar-bbbet-id">0 원</span></td>
				<td>적중<span id="main-navbar-bbbetearn-id">0 원</span></td>

			</tr>
			<tr>
				<td style="font-weight: bold; color: #0090ff; font-size: 14px;">보글볼조합:</td>
				<td>배팅<span id="main-navbar-bb2bet-id">0 원</span></td>
				<td>적중<span id="main-navbar-bb2betearn-id">0 원</span></td>

			</tr>
			<tr>
				<td style="font-weight: bold; color: #0090ff; font-size: 14px;">보글사다리:</td>
				<td>배팅<span id="main-navbar-bsbet-id">0 원</span></td>
				<td>적중<span id="main-navbar-bsbetearn-id">0 원</span></td>

			</tr>
		</Table>
		<?php } ?>

		<div class="main-navbar-user-div">
			<div>

				<label class="switch">
					<input class="switch-input" type="checkbox" id="main-navbar-alarm-check-id" />
					<span class="switch-label" data-on="켜기" data-off="끄기"></span>
					<span class="switch-handle"></span>
				</label>
				<p> <i class="glyphicon glyphicon-bell"></i></p>

			</div>
			<div style="text-align: center; cursor: pointer;" id="main-navbar-emp-div-id">

			</div>

		</div>

	</div>


  <!--?= $this->renderSection('content') ?-->
	<script src="<?php echo base_url('assets/js/main-nav-script.js?v=3');?>"></script>
	
	<!--?= $this->endSection() ?-->