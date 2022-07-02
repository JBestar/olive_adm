<?= $this->extend('header') ?>
<?= $this->section('content') ?>
  	<!--Sub Navbar-->
	<div class = "sub-navbar">
		<?php if(is_null($objMember)) {  ?>
		<p><i class="glyphicon glyphicon-user"></i> 회원관리::<?= $this->renderSection('user-edit-title') ?> 등록</p>
		<?php } else {?>
			<p><i class="glyphicon glyphicon-user"></i> 회원관리::<?= $this->renderSection('user-edit-title') ?> 수정</p>
		<?php } ?>
		<p id="subnavbar-emplevel-p-id" hidden><?=$mb_level?></p>
		<?php if(is_null($objMember)) {  ?>
			<p id="subnavbar-fid-p-id" hidden>0</p>
		<?php } else {?>
			<p id="subnavbar-fid-p-id" hidden><?=$objMember->mb_fid?></p>
		<?php } ?>
	</div>
	<!--Site Setting-->
	<div class="useredit-panel">
		<!---->
		<?= $this->renderSection('user-edit-form-section0') ?>		
		<!---->
		<div class="useredit-text-div">
			<p><?= $this->renderSection('user-edit-title') ?> 아이디:</p> 
			<?php if(is_null($objMember)) {  ?>	
			<input type = "text"   id="useredit-id-input-id" class="english_s">
			<?php } else {?>
			<input type = "text"   id="useredit-id-input-id" class="english_s" value="<?=$objMember->mb_uid ?>" disabled>
			<?php } ?>
			<label>(영문,숫자만 가능)</label>
		</div>
		<!---->
		<div class="useredit-text-div">
			<p><?= $this->renderSection('user-edit-title') ?> 닉네임:</p> 
			<?php if(is_null($objMember)) {  ?>	
			<input type = "text" id="useredit-nickname-input-id">
			<?php } else {?>
				<?php if($mb_level > LEVEL_COMPANY) {  ?>
				<input type = "text" id="useredit-nickname-input-id" value="<?=$objMember->mb_nickname?>" disabled>
				<?php } else {?>
				<input type = "text" id="useredit-nickname-input-id" value="<?=$objMember->mb_nickname?>" disabled>
				<?php } ?>		
			<?php } ?>
			<label>(길이 3~20)</label>
		</div>
		<!---->
        <?php $this->renderSection('user-edit-check-level')  ?>
		<?php if ($mb_level >= LEVEL_ADMIN) {?>
		<div class="useredit-text-div">
			<p>비밀번호:</p> 
			<?php if(is_null($objMember)) {  ?>	
			<input type = "text" id="useredit-pwd-input-id" class="english_s">
			<?php } else {?>
			<input type = "text" id="useredit-pwd-input-id" class="english_s" value="<?=$objMember->mb_pwd?>" >
			<?php } ?>
		</div>
		<?php }?>
		<!---->
        <?= $this->renderSection('user-edit-form-section1') ?>
		<!---->
		<?php if ($mb_level >= LEVEL_ADMIN) {?>
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
			<p>계좌번호:</p> 
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
		<?php }?>
        <?= $this->renderSection('user-edit-form-section2') ?>
		
		<?php if(!$npg_deny) :?>

			<div class="useredit-percent-div">
				<p>파워볼 단폴:</p> 
				
				<label> 배당율(%)</label>
				<?php if(is_null($objMember)) {  ?>
				<input type = "number" step="0.1" id="useredit-pbbetrate-input-id" value="0" >
				<?php } else {?>
				<input type = "number" step="0.1" id="useredit-pbbetrate-input-id" value="<?=$objMember->mb_game_pb_ratio?>">
				<?php } ?>

				<?php if(!$gameper_full) :?>
					<label> 누르기율(%)</label>
					<?php if(is_null($objMember)) {  ?>
					<input type = "number" step="1" id="useredit-pbbetpercent-input-id" value="100">
					<?php } else {?>
					<input type = "number" step="1" id="useredit-pbbetpercent-input-id" value="<?=$objMember->mb_game_pb_percent?>">
					<?php } ?>
				<?php endif?>
			</div>
			<div class="useredit-percent-div">
				<p>파워볼 조합:</p> 
				
				<label> 배당율(%)</label>
				<?php if(is_null($objMember)) {  ?>
				<input type = "number" step="0.1" id="useredit-pbbetrate2-input-id"  value="0">
				<?php } else {?>
				<input type = "number" step="0.1" id="useredit-pbbetrate2-input-id" value="<?=$objMember->mb_game_pb2_ratio?>">
				<?php } ?>

				<?php if(!$gameper_full) :?>
					<label> 누르기율(%)</label>
					<?php if(is_null($objMember)) {  ?>
					<input type = "number" step="1" id="useredit-pbbetpercent2-input-id" value="100">
					<?php } else {?>
					<input type = "number" step="1" id="useredit-pbbetpercent2-input-id" value="<?=$objMember->mb_game_pb2_percent?>">
					<?php } ?>
				<?php endif?>
			</div>
			<div class="useredit-percent-div">
				<p>파워사다리:</p> 
				
				<label> 배당율(%)</label>
				<?php if(is_null($objMember)) {  ?>
				<input type = "number" step="0.1" id="useredit-psbetrate-input-id" value="0" >
				<?php } else {?>
				<input type = "number" step="0.1" id="useredit-psbetrate-input-id" value="<?=$objMember->mb_game_ps_ratio?>">
				<?php } ?>

				<?php if(!$gameper_full) :?>
					<label> 누르기율(%)</label>
					<?php if(is_null($objMember)) {  ?>
					<input type = "number" step="1" id="useredit-psbetpercent-input-id" value="100">
					<?php } else {?>
					<input type = "number" step="1" id="useredit-psbetpercent-input-id" value="<?=$objMember->mb_game_ps_percent?>">
					<?php } ?>
				<?php endif?>
			</div>
		<?php endif ?>   

    	<?php if(!$bpg_deny) :?>
			<div class="useredit-percent-div">
				<p>보글볼 단폴:</p> 
				<label> 배당율(%)</label>
				<?php if(is_null($objMember)) {  ?>
				<input type = "number" min="0" step="0.1" id="useredit-bbbetrate-input-id" value="0" >
				<?php } else {?>
				<input type = "number" min="0" step="0.1" id="useredit-bbbetrate-input-id" value="<?=$objMember->mb_game_bb_ratio?>">
				<?php } ?>

				<?php if(!$gameper_full) :?>
					<label> 누르기율(%)</label>
					<?php if(is_null($objMember)) {  ?>
					<input type = "number" min="0" step="1" id="useredit-bbbetpercent-input-id" value="100">
					<?php } else {?>
					<input type = "number" min="0" step="1" id="useredit-bbbetpercent-input-id" value="<?=$objMember->mb_game_bb_percent?>">
					<?php } ?>
				<?php endif?>
			</div>
			<div class="useredit-percent-div">
				<p>보글볼 조합:</p> 
				
				<label> 배당율(%)</label>
				<?php if(is_null($objMember)) {  ?>
				<input type = "number" min="0" step="0.1" id="useredit-bbbetrate2-input-id" value="0">
				<?php } else {?>
				<input type = "number" min="0" step="0.1" id="useredit-bbbetrate2-input-id" value="<?=$objMember->mb_game_bb2_ratio?>">
				<?php } ?>

				<?php if(!$gameper_full) :?>
					<label> 누르기율(%)</label>
					<?php if(is_null($objMember)) {  ?>
					<input type = "number" min="0" step="1" id="useredit-bbbetpercent2-input-id" value="100">
					<?php } else {?>
					<input type = "number" min="0" step="1" id="useredit-bbbetpercent2-input-id" value="<?=$objMember->mb_game_bb2_percent?>">
					<?php } ?>
				<?php endif?>
			</div>
			<div class="useredit-percent-div">
				<p>보글사다리:</p> 
				<label> 배당율(%)</label>
				<?php if(is_null($objMember)) {  ?>
				<input type = "number" min="0" step="0.1" id="useredit-bsbetrate-input-id" value="0">
				<?php } else {?>
				<input type = "number" min="0" step="0.1" id="useredit-bsbetrate-input-id" value="<?=$objMember->mb_game_bs_ratio?>">
				<?php } ?>

				<?php if(!$gameper_full) :?>
					<label> 누르기율(%)</label>
					<?php if(is_null($objMember)) {  ?>
					<input type = "number" min="0" step="1" id="useredit-bsbetpercent-input-id" value="100">
					<?php } else {?>
					<input type = "number" min="0" step="1" id="useredit-bsbetpercent-input-id" value="<?=$objMember->mb_game_bs_percent?>">
					<?php } ?>
				<?php endif?>
			</div>	
		<?php endif ?>   
		<?php if($eos5_enable || $eos3_enable) :?>
			<div class="useredit-percent-div">
				<p>EOS 단폴:</p> 
				<label> 배당율(%)</label>
				<?php if(is_null($objMember)) {  ?>
				<input type = "number" min="0" step="0.1" id="useredit-eobetrate-input-id" value="0" >
				<?php } else {?>
				<input type = "number" min="0" step="0.1" id="useredit-eobetrate-input-id" value="<?=$objMember->mb_game_eo_ratio?>">
				<?php } ?>

				<?php if(!$gameper_full) :?>
					<label> 누르기율(%)</label>
					<?php if(is_null($objMember)) {  ?>
					<input type = "number" min="0" step="1" id="useredit-eobetpercent-input-id" value="100">
					<?php } else {?>
					<input type = "number" min="0" step="1" id="useredit-eobetpercent-input-id" value="<?=$objMember->mb_game_eo_percent?>">
					<?php } ?>
				<?php endif?>
			</div>
			<div class="useredit-percent-div">
				<p>EOS 조합:</p> 
				
				<label> 배당율(%)</label>
				<?php if(is_null($objMember)) {  ?>
				<input type = "number" min="0" step="0.1" id="useredit-eobetrate2-input-id" value="0">
				<?php } else {?>
				<input type = "number" min="0" step="0.1" id="useredit-eobetrate2-input-id" value="<?=$objMember->mb_game_eo2_ratio?>">
				<?php } ?>

				<?php if(!$gameper_full) :?>
					<label> 누르기율(%)</label>
					<?php if(is_null($objMember)) {  ?>
					<input type = "number" min="0" step="1" id="useredit-eobetpercent2-input-id" value="100">
					<?php } else {?>
					<input type = "number" min="0" step="1" id="useredit-eobetpercent2-input-id" value="<?=$objMember->mb_game_eo2_percent?>">
					<?php } ?>
				<?php endif?>
			</div>
		<?php endif ?>
		<?php if($coin5_enable || $coin3_enable) :?>
			<div class="useredit-percent-div">
				<p>코인 단폴:</p> 
				<label> 배당율(%)</label>
				<?php if(is_null($objMember)) {  ?>
				<input type = "number" min="0" step="0.1" id="useredit-cobetrate-input-id" value="0" >
				<?php } else {?>
				<input type = "number" min="0" step="0.1" id="useredit-cobetrate-input-id" value="<?=$objMember->mb_game_co_ratio?>">
				<?php } ?>

				<?php if(!$gameper_full) :?>
					<label> 누르기율(%)</label>
					<?php if(is_null($objMember)) {  ?>
					<input type = "number" min="0" step="1" id="useredit-cobetpercent-input-id" value="100">
					<?php } else {?>
					<input type = "number" min="0" step="1" id="useredit-cobetpercent-input-id" value="<?=$objMember->mb_game_co_percent?>">
					<?php } ?>
				<?php endif?>
			</div>
			<div class="useredit-percent-div">
				<p>코인 조합:</p> 
				
				<label> 배당율(%)</label>
				<?php if(is_null($objMember)) {  ?>
				<input type = "number" min="0" step="0.1" id="useredit-cobetrate2-input-id" value="0">
				<?php } else {?>
				<input type = "number" min="0" step="0.1" id="useredit-cobetrate2-input-id" value="<?=$objMember->mb_game_co2_ratio?>">
				<?php } ?>

				<?php if(!$gameper_full) :?>
					<label> 누르기율(%)</label>
					<?php if(is_null($objMember)) {  ?>
					<input type = "number" min="0" step="1" id="useredit-cobetpercent2-input-id" value="100">
					<?php } else {?>
					<input type = "number" min="0" step="1" id="useredit-cobetpercent2-input-id" value="<?=$objMember->mb_game_co2_percent?>">
					<?php } ?>
				<?php endif?>
			</div>
		<?php endif ?>   
		<?php if(!$cas_deny || $kgon_enable) :?>
			<div class="useredit-percent-div">
				<p>카지노:</p>			 
				<label> 배당율(%)</label>
				<?php if(is_null($objMember)) {  ?>
				<input type = "number" step="0.1" id="useredit-evbetrate-input-id" value="0" >
				<?php } else {?>
				<input type = "number" step="0.1" id="useredit-evbetrate-input-id" value="<?=$objMember->mb_game_cs_ratio?>">
				<?php } ?>
			</div>
		<?php endif ?>   
		<?php if(!$slot_deny) :?>
			<div class="useredit-percent-div">
				<p>슬롯:</p>			 
				<label> 배당율(%)</label>
				<?php if(is_null($objMember)) {  ?>
				<input type = "number" step="0.1" id="useredit-slbetrate-input-id" value="0" >
				<?php } else {?>
				<input type = "number" step="0.1" id="useredit-slbetrate-input-id" value="<?=$objMember->mb_game_sl_ratio?>">
				<?php } ?>
			</div>
		<?php endif ?>   
		
		<?php $this->renderSection('user-edit-check-level')  ?>
		<?php if ($mb_level >= LEVEL_ADMIN) { ?>
        
			<div class="useredit-text-div">
				<?php if(!is_null($objMember) && $objMember->mb_state_delete == 1) :  ?>
					<input type="checkbox" id="useredit-offline-check-id" style="zoom:140%; margin-top:4px; width:20px;" checked>
				<?php else :  ?>
					<input type="checkbox" id="useredit-offline-check-id" style="zoom:140%; margin-top:4px; width:20px;" >
				<?php endif ?>
				<p>오플라인 유저</p> 
			</div>

			<!---->
			<p class="useredit-seperate-div"></p>
					
			<div class="useredit-text-div">
				<p>보유금액:</p> 
				<?php if(is_null($objMember)) {  ?>	
				<input type = "text" id="useredit-money-input-id">
				<label>원</label>
				<?php } else {?>
				<input type = "text" id="useredit-money-input-id" value="<?=number_format(allMoney($objMember))?>" disabled>
				<label>원</label>
				<button class="pbresult-list-view-but" id="useredit-withdraw-money-id">회수</button>  
				<?php } ?>
			</div>
			<!---->
			<div class="useredit-text-div">
				<p>보유포인트:</p> 
				<?php if(is_null($objMember)) {  ?>	
				<input type = "text" id="useredit-point-input-id">
				<label>P</label>
				<?php } else {?>
				<input type = "text" id="useredit-point-input-id" value="<?=number_format($objMember->mb_point)?>" disabled>
				<label>P&nbsp;</label>
				<button class="pbresult-list-view-but" id="useredit-withdraw-point-id">회수</button>  
				<?php } ?>
				
			</div>

			<?php if(!is_null($objMember)) :  ?>	
				<div class="useredit-text-div">
					<p>충환전금액:</p> 
					<input type = "text" id="useredit-transfer-input-id" value="0" >
					<label>원</label>
					<button class="pbresult-money-but" id="money_1">1만원</button>
					<button class="pbresult-money-but" id="money_2">3만원</button>  
					<button class="pbresult-money-but" id="money_3">5만원</button>  
					<button class="pbresult-money-but" id="money_4">10만원</button>  
					<button class="pbresult-money-but" id="money_5">50만원</button>  
					<button class="pbresult-money-but" id="money_6">100만원</button>  
					<button class="pbresult-list-view-but" id="useredit-give-but-id" style="margin-right:0px;">직충전</button>  
					<button class="pbresult-list-view-but" id="useredit-withdraw-but-id"  style="margin-left:5px;">직환전</button>  

				</div>
			<?php endif ?>

		<?php } else {?>
		
			<?php if(!is_null($objMember)) {  ?>
				<p class="useredit-seperate-div">
				<div class="useredit-text-div">
					<p>보유금액:</p> 
					<input type = "text" id="useredit-money-input-id" value="<?=number_format(allMoney($objMember))?>"  disabled>
					<label>원</label>
				</div>
				<div class="useredit-text-div">
					<p>보유포인트:</p> 
					<input type = "text" id="useredit-point-input-id" value="<?=number_format($objMember->mb_point)?>" disabled>
					<label>P</label>
				</div>

				<?php if(!$_ENV['mem.trans_deny'] || !$_ENV['mem.return_deny']) :  ?>

				<div class="useredit-text-div">
					<p>이동금액:</p> 
					<input type = "text" id="useredit-transfer-input-id" value="0" >
					<label>원</label>
					<button class="pbresult-money-but" id="money_1">1만원</button>
					<button class="pbresult-money-but" id="money_2">3만원</button>  
					<button class="pbresult-money-but" id="money_3">5만원</button>  
					<button class="pbresult-money-but" id="money_4">10만원</button>  
					<button class="pbresult-money-but" id="money_5">50만원</button>  
					<button class="pbresult-money-but" id="money_6">100만원</button>  
				
					<?php if(!$_ENV['mem.trans_deny']) :  ?>
						<button class="pbresult-list-view-but" id="useredit-transfer-but-id" style="margin-right:0px;">이송</button>  
					<?php endif ?>

					<?php if(!$_ENV['mem.return_deny']) :  ?>
						<button class="pbresult-list-view-but" id="useredit-return-but-id" style="margin-left:5px;">환수</button>  
					<?php endif ?>
				</div>
				<?php endif ?>

			<?php } ?>
		<?php } ?>

		<div class = "useredit-button-group">
			<button class="useredit-cancel-button" id="useredit-cancel-btn-id">취소</button>
			<button class="useredit-ok-button"  id="useredit-save-btn-id">저장</button>
		</div>
	</div>



<!--main_navbar.php-->
</div>


<?= $this->renderSection('user-edit-script') ?>
<?= $this->endSection() ?>