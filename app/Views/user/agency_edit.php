  	<!--Sub Navbar-->
	<div class = "sub-navbar">
	<?php if(is_null($objMember)) {  ?>
		<p><i class="glyphicon glyphicon-user"></i> 회원관리::총판 등록</p>
	<?php } else {?>
		<p><i class="glyphicon glyphicon-user"></i> 회원관리::총판 수정</p>
	<?php } ?>		
	<p id="subnavbar-emplevel-p-id" hidden><?=$nAdminLevel?></p>
	<?php if(is_null($objMember)) {  ?>
		<p id="subnavbar-fid-p-id" hidden>0</p>
	<?php } else {?>
		<p id="subnavbar-fid-p-id" hidden><?=$objMember->mb_fid?></p>
	<?php } ?>
	</div>
	<!--Site Setting-->
	<div class="useredit-panel">
		<!---->
		<div class="useredit-text-div">
			<p>분류:</p> 
			<?php if($nAdminLevel > LEVEL_COMPANY) {  ?>
			<select type = "text" id="useredit-sort-select-id">
			<?php } else { ?>
			<select type = "text" id="useredit-sort-select-id" disabled>
			<?php } ?>
				<?php foreach ($arrEmpName as $objEmpName):?>
				<?php if(is_null($objMember) || ($objMember->mb_emp_fid != $objEmpName->mb_fid)) {  ?>
				<option value="<?=$objEmpName->mb_fid?>"><?=$objEmpName->mb_name?></option>
				<?php } else {?>
				<option value="<?=$objEmpName->mb_fid?>" selected><?=$objEmpName->mb_name?></option>
				<?php } ?>
				<?php endforeach;?>
			</select>
		</div>
		<!---->
		<div class="useredit-text-div">
			<p>총판 아이디:</p> 
			<?php if(is_null($objMember)) {  ?>	
			<input type = "text"   id="useredit-id-input-id">
			<?php } else {?>
			<input type = "text"   id="useredit-id-input-id" value="<?=$objMember->mb_uid?>" disabled>
			<?php } ?>
			<label>(영문,숫자만 가능)</label>
		</div>
		<!---->
		<div class="useredit-text-div">
			<p>총판 닉네임:</p> 
			<?php if(is_null($objMember)) {  ?>	
			<input type = "text" id="useredit-nickname-input-id">
			<?php } else {?>
				<?php if($nAdminLevel > LEVEL_COMPANY) {  ?>
				<input type = "text" id="useredit-nickname-input-id" value="<?=$objMember->mb_nickname?>" disabled>
				<?php } else {?>
				<input type = "text" id="useredit-nickname-input-id" value="<?=$objMember->mb_nickname?>" disabled>
				<?php } ?>
			<?php } ?>
			<label>(길이 3~20)</label>
		</div>

		<!---->
		<?php if($nAdminLevel > LEVEL_COMPANY) {  ?>
		<div class="useredit-text-div">
			<p>비밀번호:</p> 
			<?php if(is_null($objMember)) {  ?>	
			<input type = "text" id="useredit-pwd-input-id">
			<?php } else {?>
			<input type = "text" id="useredit-pwd-input-id" value="<?=$objMember->mb_pwd?>" >
			<?php } ?>
		</div>
		
		<!---->
		<div class="useredit-text-div">
			<p>핸드폰번호:</p> 
			<?php if(is_null($objMember)) {  ?>			
			<input type = "text" id="useredit-phone-input-id">
			<?php } else {?>
			<input type = "text" id="useredit-phone-input-id" value="<?=$objMember->mb_phone?>" >
			<?php } ?>
			<label>(-없이 숫자만입력)</label>
		</div>
		<!---->
		<div class="useredit-text-div">
			<p>은행명:</p> 
			<?php if(is_null($objMember)) {  ?>			
			<input type = "text" id="useredit-bankname-input-id">
			<?php } else if(is_null($objMember->mb_bank_name)) {  ?>
			<input type = "text" id="useredit-bankname-input-id">
			<?php } else {?>
			<input type = "text" id="useredit-bankname-input-id" value="<?=$objMember->mb_bank_name?>" >
			<?php } ?>
		</div>
		<!---->
		<div class="useredit-text-div">
			<p>예금주:</p> 
			<?php if(is_null($objMember)) {  ?>			
			<input type = "text" id="useredit-bankaccount-input-id">
			<?php } else if(is_null($objMember->mb_bank_own)) {  ?>
			<input type = "text" id="useredit-bankaccount-input-id">
			<?php } else {?>
			<input type = "text" id="useredit-bankaccount-input-id" value="<?=$objMember->mb_bank_own?>" >
			<?php } ?>
		</div>
		<!---->
		<div class="useredit-text-div">
			<p>은행번호:</p> 
			<?php if(is_null($objMember) || is_null($objMember->mb_bank_num)) {  ?>						
			<input type = "text" id="useredit-bankserial-input-id">
			<?php } else {?>
			<input type = "text" id="useredit-bankserial-input-id" value="<?=$objMember->mb_bank_num?>" >
			<?php } ?>
			<label>(-없이 숫자만입력)</label>
		</div>
		<!---->
		<div class="useredit-text-div">
			<p>출금비번:</p> 
			<?php if(is_null($objMember) || is_null($objMember->mb_bank_pwd)) {  ?>	
			<input type = "text" id="useredit-bankpwd-input-id">
			<?php } else {?>
			<input type = "text" id="useredit-bankpwd-input-id" value="<?=$objMember->mb_bank_pwd?>">
			<?php } ?>
		</div>
		<!---->
		<div class="useredit-text-div">
			<p>색깔:</p> 
			<?php if(is_null($objMember) || is_null($objMember->mb_color)) {  ?>
			<input type="color" value="#ffffff" id="useredit-color-input-id">
			<?php } else {?>
			<input type="color" value="<?=$objMember->mb_color?>" id="useredit-color-input-id" >
			<?php } ?>
		</div>
		<div class="useredit-check-div">			 
			<?php if(is_null($objMember) || $objMember->mb_emp_permit == 0) {  ?>	
			<input type="checkbox" id="useredit-modify-check-id">
			<?php } else {?>
			<input type="checkbox" id="useredit-modify-check-id" checked>
			<?php } ?>
			<label> 하부매장회원정보수정</label>
		</div>
	
		<?php }  ?>
		<!---->
		<div class="useredit-percent-div">
			<p>파워볼단폴:</p> 
			<label> 배당율(%)</label>
			<?php if(is_null($objMember)) {  ?>
			<input type = "number" step="0.1" id="useredit-pbbetrate-input-id" value="0" >
			<?php } else {?>
			<input type = "number" step="0.1" id="useredit-pbbetrate-input-id" value="<?=$objMember->mb_game_pb_ratio?>">
			<?php } ?>
			<label> 누르기율(%)</label>
			<?php if(is_null($objMember)) {  ?>
			<input type = "number" step="1" id="useredit-pbbetpercent-input-id" value="100">
			<?php } else {?>
			<input type = "number" step="1" id="useredit-pbbetpercent-input-id" value="<?=$objMember->mb_game_pb_percent?>">
			<?php } ?>
		</div>
		<div class="useredit-percent-div">
			<p>파워볼조합:</p> 
			<label> 배당율(%)</label>
			<?php if(is_null($objMember)) {  ?>
			<input type = "number" step="0.1" id="useredit-pbbetrate2-input-id" value="0" >
			<?php } else {?>
			<input type = "number" step="0.1" id="useredit-pbbetrate2-input-id" value="<?=$objMember->mb_game_pb2_ratio?>">
			<?php } ?>
			<label> 누르기율(%)</label>
			<?php if(is_null($objMember)) {  ?>
			<input type = "number" step="1" id="useredit-pbbetpercent2-input-id" value="100">
			<?php } else {?>
			<input type = "number" step="1" id="useredit-pbbetpercent2-input-id" value="<?=$objMember->mb_game_pb2_percent?>">
			<?php } ?>
		</div>
		<div class="useredit-percent-div">
			<p>파워사다리:</p> 
			<label> 배당율(%)</label>
			<?php if(is_null($objMember)) {  ?>
			<input type = "number" step="0.1" id="useredit-psbetrate-input-id" value="0" >
			<?php } else {?>
			<input type = "number" step="0.1" id="useredit-psbetrate-input-id" value="<?=$objMember->mb_game_ps_ratio?>">
			<?php } ?>
			<label> 누르기율(%)</label>
			<?php if(is_null($objMember)) {  ?>
			<input type = "number" step="1" id="useredit-psbetpercent-input-id" value="100">
			<?php } else {?>
			<input type = "number" step="1" id="useredit-psbetpercent-input-id" value="<?=$objMember->mb_game_ps_percent?>">
			<?php } ?>
		</div>
		<div class="useredit-percent-div">
			<p>키노사다리:</p>			 
			<label> 배당율(%)</label>
			<?php if(is_null($objMember)) {  ?>
			<input type = "number" step="0.1" id="useredit-ksbetrate-input-id" value="0" >
			<?php } else {?>
			<input type = "number" step="0.1" id="useredit-ksbetrate-input-id" value="<?=$objMember->mb_game_ks_ratio?>">
			<?php } ?>

			<label> 누르기율(%)</label>
			<?php if(is_null($objMember)) {  ?>
			<input type = "number" step="1" id="useredit-ksbetpercent-input-id" value="100">
			<?php } else {?>
			<input type = "number" step="1" id="useredit-ksbetpercent-input-id" value="<?=$objMember->mb_game_ks_percent?>">
			<?php } ?>
		</div>	
		<div class="useredit-percent-div">
			<p>보글볼단폴:</p> 
			<label> 배당율(%)</label>
			<?php if(is_null($objMember)) {  ?>
			<input type = "number" min="0" step="0.1" id="useredit-bbbetrate-input-id" value="0" >
			<?php } else {?>
			<input type = "number" min="0" step="0.1" id="useredit-bbbetrate-input-id" value="<?=$objMember->mb_game_bb_ratio?>">
			<?php } ?>

			<label> 누르기율(%)</label>
			<?php if(is_null($objMember)) {  ?>
			<input type = "number" min="0" step="1" id="useredit-bbbetpercent-input-id" value="100">
			<?php } else {?>
			<input type = "number" min="0" step="1" id="useredit-bbbetpercent-input-id" value="<?=$objMember->mb_game_bb_percent?>">
			<?php } ?>
		</div>
		<div class="useredit-percent-div">
			<p>보글볼조합:</p> 
			
			<label> 배당율(%)</label>
			<?php if(is_null($objMember)) {  ?>
			<input type = "number" min="0" step="0.1" id="useredit-bbbetrate2-input-id" value="0">
			<?php } else {?>
			<input type = "number" min="0" step="0.1" id="useredit-bbbetrate2-input-id" value="<?=$objMember->mb_game_bb2_ratio?>">
			<?php } ?>

			<label> 누르기율(%)</label>
			<?php if(is_null($objMember)) {  ?>
			<input type = "number" min="0" step="1" id="useredit-bbbetpercent2-input-id" value="100">
			<?php } else {?>
			<input type = "number" min="0" step="1" id="useredit-bbbetpercent2-input-id" value="<?=$objMember->mb_game_bb2_percent?>">
			<?php } ?>
		</div>
		<div class="useredit-percent-div">
			<p>보글사다리:</p> 
			<label> 배당율(%)</label>
			<?php if(is_null($objMember)) {  ?>
			<input type = "number" min="0" step="0.1" id="useredit-bsbetrate-input-id" value="0">
			<?php } else {?>
			<input type = "number" min="0" step="0.1" id="useredit-bsbetrate-input-id" value="<?=$objMember->mb_game_bs_ratio?>">
			<?php } ?>

			<label> 누르기율(%)</label>
			<?php if(is_null($objMember)) {  ?>
			<input type = "number" min="0" step="1" id="useredit-bsbetpercent-input-id" value="100">
			<?php } else {?>
			<input type = "number" min="0" step="1" id="useredit-bsbetpercent-input-id" value="<?=$objMember->mb_game_bs_percent?>">
			<?php } ?>
		</div>	
		<div class="useredit-percent-div">
			<p>에볼루션:</p>			 
			<label> 배당율(%)</label>
			<?php if(is_null($objMember)) {  ?>
			<input type = "number" step="0.1" id="useredit-evbetrate-input-id" value="0" >
			<?php } else {?>
			<input type = "number" step="0.1" id="useredit-evbetrate-input-id" value="<?=$objMember->mb_game_ev_ratio?>">
			<?php } ?>

		</div>
		
		<?php if($nAdminLevel > LEVEL_COMPANY) {  ?>
		<p class="useredit-seperate-div">
		</p>
		<!---->
		<?php if(!is_null($objMember)) {  ?>
		<div class="useredit-text-div">
			<p>충전금액:</p> 
			<?php if(is_null($objMember)) {  ?>		
			<input type = "text" id="useredit-charge-input-id">
			<?php } else {?>
			<input type = "text" id="useredit-charge-input-id" value="<?=number_format($objMember->mb_money_charge)?>" disabled>
			<?php } ?>
			<label>원</label>
		</div>
		<!---->
		<div class="useredit-text-div">
			<p>환전금액:</p> 
			<?php if(is_null($objMember)) {  ?>		
			<input type = "text" id="useredit-exchange-input-id">
			<?php } else {?>
			<input type = "text" id="useredit-exchange-input-id" value="<?=number_format($objMember->mb_money_exchange)?>" disabled>
			<?php } ?>
			<label>원</label>
		</div>
		<?php } ?>
		<!---->
		<div class="useredit-text-div">
			<p>현재금액:</p> 
			<?php if(is_null($objMember)) {  ?>	
			<input type = "number" id="useredit-money-input-id">
			<?php } else {?>
			<input type = "number" id="useredit-money-input-id" value="<?=$objMember->mb_money?>">
			<?php } ?>
			<label>원</label>
		</div>
		<!---->
		<div class="useredit-text-div">
			<p>현재포인트:</p> 
			<?php if(is_null($objMember)) {  ?>	
			<input type = "number" id="useredit-point-input-id">
			<?php } else {?>
			<input type = "number" id="useredit-point-input-id" value="<?=$objMember->mb_point?>">
			<?php } ?>
			<label>원</label>
		</div>
		<?php } ?>

		<div class = "useredit-button-group">
			<button class="useredit-cancel-button" id="useredit-cancel-btn-id">취소</button>
			<button class="useredit-ok-button"  id="useredit-save-btn-id">저장</button>
		</div>
	</div>



<!--main_navbar.php-->
</div>


<script src="<?php echo base_url('assets/js/agency_edit-script.js?v=1');?>"></script>
