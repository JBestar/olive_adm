<?= $this->extend('/ebal/ebal_cont') ?>
<?= $this->section('ebal-active') ?><?=$game_name?><?= $this->endSection() ?>
<?= $this->section('eval_content') ?>
    <div class="confsite-site-panel">
    <h4><i class="glyphicon glyphicon-hand-right"></i> 에볼루션 보험계정</h4>
		<div class="confsite-game-text-div">
			<p>보험배팅승인:</p> 
			<input type="checkbox" id="confev-bet-check-id" style="zoom:120%; margin-top:0px;"><label style="font-size:14px; font-weight:normal; padding-top:0px;"> 배팅승인</label>
		</div>
		<div class="confsite-game-text-div">
			<p> 보험타입</p>			
			<select name="pbresult-number" class="" id="confev-bettype-select-id" style="padding:5px; width: 200px; margin-left:0px;">
				<option value="2" >AMAZON</option>
				<option value="3" >AIRLINE</option>
				<option value="1" >NINE</option>
				<option value="4" >NINEBAR</option>
				<option value="11" >LUCKY</option>
				<option value="12" >VEDA</option>
			</select>
		</div>
		<div class="confsite-game-text-div">
			<p>사이트 주소:</p> 
			<input type = "text" class="conf-text-input" style="min-width:200px;" id="confev-betsite-input-id">
		</div>
		<div class="confsite-game-text-div">
			<p>계정 아이디:</p> 
			<input type = "text" class="conf-text-input"  style="min-width:200px;" id="confev-userid-input-id">
		</div>
		
		<div class="confsite-game-text-div">
			<p id="conf-pw-label">계정 비밀번호:</p> 
			<input type = "text" class="conf-text-input"  style="min-width:200px;" id="confev-userpwd-input-id">
		</div>

		<div class="confsite-game-text-div">
			<p id="conf-pw-label">보험베팅시간:</p> 
			<input type = "number" class="conf-text-input"  style="min-width:200px;" id="confev-betend-input-id"> 초전
		</div>
		
		<div class="confsite-game-text-div">
			<p id="conf-pw-label">보험알:</p> 
			<input type = "text" class="conf-text-input"  style="min-width:200px;" id="confev-balance-input-id" disabled> 
			<label style="font-size:14px; font-weight:normal; padding-top:0px;"  id="confev-balance-label-id"></label>
		</div>
		<div class="confsite-game-text-div">
			<p>팅김방지 배팅:</p> 
			<input type="checkbox" id="confev-conbet-check-id" style="zoom:120%; margin-top:5px;"><label style="font-size:14px; font-weight:normal; padding-top:0px;"> 배팅승인</label>
		</div>
		<div class = "confsite-button-group">
			<button class="confsite-cancel-button"  id="confsite-cancel-btn-id">취소</button>
			<button class="confsite-ok-button" id="confsite-ok-btn-id">저장</button>
		</div>
    </div>
<!--main_navbar.php-main-container-->
</div>

<?php if(array_key_exists("app.produce", $_ENV)) :?>
    <script src="<?php echo site_furl('/assets/js/conf_ebal-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/conf_ebal-script.js?v=1');?>"></script>
<?php endif ?>

<?= $this->endSection() ?>
