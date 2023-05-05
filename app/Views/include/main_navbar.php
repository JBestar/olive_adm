<!--?= $this->extend('include/sidebar')?-->
<!--?= $this->section('MainNavBar')?-->
<div class="main-container" id="main-container-id">
	<div class="loading" style="display: none;">
		<div class="load lds-ellipsis"></div>
	</div>
	<div class="main-navbar" id="main-navbar-id">
		<h4 style="float: left; margin: 5px 20px 5px 10px;  font-weight: bold; color: #0090ff; font-size: 20px;"
			id="main-navbar-level-id"></h4>

		<Table class="main-navbar-info-table" id="main-navbar-table-id" style=" ">
			<tr>
				<td>보유금액 <span id="main-navbar-emp_money-id">0 원 </span></td>

				<?php if($mb_level < LEVEL_ADMIN) :  ?>
					<td>충전금액<span id="main-navbar-emp_charge-id">0 원</span></td>
					<?php if(!$bpg_deny) :?>
						<td>보글볼<span id="main-navbar-emp_bbrate-id">0 % | 0 %</span></td>
					<?php endif ?>  
					<?php if(!$hold_deny) :?>
						<td>홀덤<span id="main-navbar-emp_hlrate-id">0 %</span></td>
					<?php endif ?> 
					<?php if(!$evol_deny || !$cas_deny) :?>
						<td>카지노<span id="main-navbar-emp_evrate-id">0 %</span></td>
					<?php endif ?>
					<?php if(!$coin5_deny || !$coin3_deny) :?>
						<td>코인<span id="main-navbar-emp_corate-id">0 % | 0 %</span></td>
					<?php endif ?>
				<?php else : ?>
					<td>가입<a href="<?php echo site_furl('/user/member/0');?>" id="main-navbar-user_wait-id">0 신청</a></td>
				<?php endif ?>

			</tr>
			<tr>
				<td>보유포인트<span id="main-navbar-emp_point-id">0 P </span></td>
				<?php if($mb_level < LEVEL_ADMIN) :  ?>
					<td>환전금액<span id="main-navbar-emp_exchange-id">0 원</span></td>
					<?php if(!$bpg_deny) :?>
						<td>보글사다리<span id="main-navbar-emp_bsrate-id">0 % </span></td>
					<?php endif ?>
					<?php if(!$slot_deny) :?>
						<td>슬롯<span id="main-navbar-emp_slrate-id">0 %</span></td>
					<?php endif ?> 
					<?php if(!$eos5_deny || !$eos3_deny) :?>
						<td>EOS<span id="main-navbar-emp_eorate-id">0 % | 0 %</span></td>
					<?php endif ?>     
					<?php if(!$hpg_deny) :?>
						<td>해피볼<span id="main-navbar-emp_pbrate-id">0 % | 0 %</span></td>
					<?php endif ?>
				<?php else : ?>
					<td>문의<a href="<?php echo site_furl('/board/message');?>" id="main-navbar-newmessage-id">0 신청</a></td>
				<?php endif ?>
			</tr>

			<?php if($mb_level >= LEVEL_ADMIN) :  ?>
			<tr>
				<td>충전금액<span id="main-navbar-emp_charge-id">0 원</span></td>
				<td>충전<a href="<?php echo site_furl('/bank/deposit');?>" id="main-navbar-charge_wait-id">0 대기</a></td>
			</tr>
			<tr>
				<td>환전금액<span id="main-navbar-emp_exchange-id">0 원</span></td>
				<td>환전<a href="<?php echo site_furl('/bank/withdraw');?>" id="main-navbar-exchange_wait-id">0 대기</a></td>
			</tr>
			<?php endif ?>

		</Table>

		<?php if($mb_level >= LEVEL_ADMIN) :  ?>
			
			<?php if(!$evol_deny || !$cas_deny || !$slot_deny || !$hold_deny) :?>
			<Table class="main-navbar-betinfo-table" style="margin-left:20px; ">
				<?php if(!$hold_deny) :?>
				<tr>
					<td style="font-weight: bold; color: #0090ff; font-size: 14px;">홀덤게임:</td>
					<td>배팅<span id="main-navbar-hlbet-id">0 원</span></td>
					<td>적중<span id="main-navbar-hlbetearn-id">0 원</span></td>
					<td>보유알<span id="main-navbar-hluser-id">0 원</span><span>|</span><span id="main-navbar-hlegg-id">0 원</span></td>
				</tr>
    			<?php endif ?>  


					<?php if(!$evol_deny) :?>
					<tr>
						<td style="font-weight: bold; color: #0090ff; font-size: 14px;">에볼루션:</td>
						<td>배팅<span id="main-navbar-evbet-id">0 원</span></td>
						<td>적중<span id="main-navbar-evbetearn-id">0 원</span></td>
						<td>보유알<span id="main-navbar-evuser-id">0 원</span><span>|</span><span id="main-navbar-evegg-id">0 원</span></td>
					</tr>
					<?php endif ?>   
					
					<?php if(!$slot_deny) :?>
					<tr>
						<td style="font-weight: bold; color: #0090ff; font-size: 14px;">정품슬롯:</td>
						<td>배팅<span id="main-navbar-slbet-id">0 원</span></td>
						<td>적중<span id="main-navbar-slbetearn-id">0 원</span></td>
						<td>보유알<span id="main-navbar-sluser-id">0 원</span><span>|</span><span id="main-navbar-slegg-id">0 원</span></td>
					</tr>
					<tr>
						<td style="font-weight: bold; color: #0090ff; font-size: 14px;">네츌슬롯:</td>
						<td>배팅<span id="main-navbar-fslbet-id">0 원</span></td>
						<td>적중<span id="main-navbar-fslbetearn-id">0 원</span></td>
						<td>보유알<span id="main-navbar-fsluser-id">0 원</span><span>|</span><span id="main-navbar-fslegg-id">0 원</span></td>
					</tr>
					<?php endif ?>  
					<?php if(!isEBalMode() && !$cas_deny) :?>
					<tr>
						<td style="font-weight: bold; color: #0090ff; font-size: 14px;">카지노:</td>
						<td>배팅<span id="main-navbar-kgbet-id">0 원</span></td>
						<td>적중<span id="main-navbar-kgbetearn-id">0 원</span></td>
						<td>보유알<span id="main-navbar-kguser-id">0 원</span><span>|</span><span id="main-navbar-kgegg-id">0 원</span></td>
					</tr>
					<?php endif ?>  
					
					<?php if(array_key_exists('app.site', $_ENV) && $_ENV['app.site'] == 2 ) :?>
						<?php if(!$bpg_deny || !$hpg_deny || !$eos5_deny || !$eos3_deny || !$coin5_deny || !$coin3_deny) :?>
							<tr>
								<td style="font-weight: bold; color: #0090ff; font-size: 14px;">미니게임:</td>
								<td style="vertical-align:top;">배팅<span id="main-navbar-minibet-id">0 원</span></td>
								<td style="vertical-align:top;">적중<span id="main-navbar-minibetearn-id">0 원</span></td>
							</tr>
						<?php endif ?>  
					<?php endif ?>  
				
			</Table>
			<?php endif ?>     
			
				<?php if(!array_key_exists('app.site', $_ENV) || $_ENV['app.site'] != 2 ) :?>
					<?php if(!$bpg_deny || !$hpg_deny || !$eos5_deny || !$eos3_deny || !$coin5_deny || !$coin3_deny) :?>
						<Table class="main-navbar-betinfo-table" style="">
							<tr>
								<td style="font-weight: bold; color: #0090ff; font-size: 14px; vertical-align:top;">미니게임:</td>
								<td style="vertical-align:top;">배팅<span id="main-navbar-minibet-id">0 원</span></td>
								<td style="vertical-align:top;">적중<span id="main-navbar-minibetearn-id">0 원</span></td>
							</tr>
						</Table>
					<?php endif ?>  
				<?php endif ?>  

		<?php endif ?>

		<div class="main-navbar-user-div">
			<div>

				<label class="switch">
					<input class="switch-input" type="checkbox" id="main-navbar-alarm-check-id" />
					<span class="switch-label" data-on="켜기" data-off="끄기"></span>
					<span class="switch-handle"></span>
				</label>
				<p> <i class="glyphicon glyphicon-bell"></i></p>

			</div>
			<div style="text-align: center; cursor: pointer;" id="main-navbar-emp-div-id"></div>
			<div style="text-align: center; width:150px" id="main-navbar-ip-div-id"></div>
		</div>
		

	</div>


	<?php if(array_key_exists("app.produce", $_ENV)) :?>
		<script src="<?php echo site_furl('/assets/js/main-nav-script.js?t='.time());?>"></script>
	<?php else : ?>
		<script src="<?php echo site_furl('/assets/js/main-nav-script.js?v=1');?>"></script>
	<?php endif ?>