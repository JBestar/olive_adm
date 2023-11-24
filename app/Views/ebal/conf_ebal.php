<?= $this->extend('/ebal/ebal_cont') ?>
<?= $this->section('ebal-active') ?><?=$game_name?><?= $this->endSection() ?>
<?= $this->section('eval_content') ?>
	<style>
		.confsite-game-text-div div{
			width:510px;			
		}
		.confsite-game-text-div p{
			width:150px;			
		}
		.confsite-game-text-div input[type="text"], .confsite-game-text-div input[type="number"]{
			min-width:200px;			
		}
	</style>
    <div class="confsite-site-panel">
    <h4><i class="glyphicon glyphicon-hand-right"></i> 에볼루션 보험계정</h4>
	
	<?php if(array_key_exists('app.ebal', $_ENV) && $_ENV['app.ebal'] > 0 ) :?>

		<div class="confsite-game-text-div">
			<div>
				<p>전체 보험배팅:</p> 
				<input type="checkbox" id="confev-bet-allcheck-id" style="zoom:120%; margin-top:0px;"><label style="font-size:14px; font-weight:normal; padding-top:0px;"> 배팅승인</label>
			</div>
			<div>
				<p> 배팅방식</p>			
				<select name="pbresult-number" class="" id="confev-betmode-select-id" style="padding:5px; width: 200px; margin-left:0px;">
					<option value="0" >기본배팅</option>
					<option value="1" >분산배팅</option>
				</select>
			</div>
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>최소배팅금액:</p> 
				<input type = "number" class="conf-text-input"  id="confev-betmin-input-id" min="0" step="1000"> 원
			</div>
			<div>
				<p>최대배팅금액:</p> 
				<input type = "number" class="conf-text-input"  id="confev-betmax-input-id" min="0" step="10000"> 원
				<?php if(array_key_exists('app.sess_act', $_ENV) && $_ENV['app.sess_act'] == 1) :?>
					<button class="confsite-cancel-button" style="margin-left:10px; padding:3px 10px; float:none;" id="confsite-betrange-reset-id">리셋</button>
				<?php endif ?>
			</div>
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>보험배팅마감:</p> 
				<input type = "number" class="conf-text-input"  id="confev-betend-input-id"> 초전
			</div>
			<div>
				<p>팅김방지 배팅간격:</p> 
				<input type = "number" class="conf-text-input" min="0" step="1" id="confev-conbet-input-id"> 분 (1~30)
			</div>
			
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>최대 접속자수:</p> 
				<input type = "number" class="conf-text-input" min="0" step="1" id="confev-maxuser-input-id"> 명 
				<span id="confev-conuser-input-id"> </span>
			</div>
			<?php if(array_key_exists('app.sess_act', $_ENV) && $_ENV['app.sess_act'] == 1) :?>
				<div>
					<p>전체 누르기:</p> 
					<button class="confsite-cancel-button" style="margin-left:0px; padding:3px 10px; float:none;" id="confsite-press-check-id">누르기체크</button>
					<button class="confsite-cancel-button" style="margin-left:10px; padding:3px 10px; float:none;" id="confsite-press-reset-id">누르기해제</button>
				</div>
			<?php endif ?>
		</div>

		<!-- 보험계정 1 -->
		<div class="confsite-game-text-div">
  			<p style="font-size: 16px; font-weight: bold;">보험계정 1</p>
  		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>보험배팅승인:</p> 
				<input type="checkbox" id="confev-bet-check-id" style="zoom:120%; margin-top:0px;"><label style="font-size:14px; font-weight:normal; padding-top:0px;"> 배팅승인</label>
			</div>
			<div>
				<p>팅김방지 배팅:</p> 
				<input type="checkbox" id="confev-conbet-check-id" style="zoom:120%; margin-top:5px;"><label style="font-size:14px; font-weight:normal; padding-top:0px;"> 배팅승인</label>
			</div>
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p> 보험타입</p>			
				<select name="pbresult-number" class="" id="confev-bettype-select-id" style="padding:5px; width: 200px; margin-left:0px;">
					<?php foreach($gamd_types as $type=>$name):?>
						<option value="<?=$type?>" ><?=$name?></option>
					<?php endforeach?>
				</select>
			</div>
			<div>
				<p>사이트 주소:</p> 
				<input type = "text" class="conf-text-input" id="confev-betsite-input-id">
			</div>
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>계정 아이디:</p> 
				<input type = "text" class="conf-text-input"  id="confev-userid-input-id">
			</div>
			<div>
				<p>계정 비밀번호:</p> 
				<input type = "text" class="conf-text-input"  id="confev-userpwd-input-id">
			</div>
		</div>
		
		<div class="confsite-game-text-div">
			<div>
				<p>보험알:</p> 
				<input type = "text" class="conf-text-input"  id="confev-balance-input-id" disabled> 
				<label style="font-size:14px; font-weight:normal; padding-top:0px; min-width:100px;"  id="confev-balance-label-id"></label>
			</div>
			<div>
				<p>계정타입:</p> 
				<input type="checkbox" id="confev-signal-check-id" style="zoom:120%; margin-top:5px;"><label style="font-size:14px; font-weight:normal; padding-top:0px;"> 신호값용</label>
				<input type="checkbox" id="confev-multiroom-check-id" style="zoom:120%; margin-top:5px; margin-left:30px;" <?=$mb_level<LEVEL_ADMIN+2?'hidden':''?>>
				<label style="font-size:14px; font-weight:normal; padding-top:0px;" <?=$mb_level < LEVEL_ADMIN+2?'hidden':''?>> 멀티방</label>
			</div>
		</div>
		
		<!-- 보험계정 2 -->
		<div class="confsite-game-text-div">
  			<p style="font-size: 16px; font-weight: bold;">보험계정 2</p>
  		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>보험배팅승인:</p> 
				<input type="checkbox" id="confev-bet-check-id2" style="zoom:120%; margin-top:0px;"><label style="font-size:14px; font-weight:normal; padding-top:0px;"> 배팅승인</label>
			</div>
			<div>
				<p>팅김방지 배팅:</p> 
				<input type="checkbox" id="confev-conbet-check-id2" style="zoom:120%; margin-top:5px;"><label style="font-size:14px; font-weight:normal; padding-top:0px;"> 배팅승인</label>
			</div>
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p> 보험타입</p>			
				<select name="pbresult-number" class="" id="confev-bettype-select-id2" style="padding:5px; width: 200px; margin-left:0px;">
					<?php foreach($gamd_types as $type=>$name):?>
						<option value="<?=$type?>" ><?=$name?></option>
					<?php endforeach?>
				</select>
			</div>
			<div>
				<p>사이트 주소:</p> 
				<input type = "text" class="conf-text-input" id="confev-betsite-input-id2">
			</div>
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>계정 아이디:</p> 
				<input type = "text" class="conf-text-input"  id="confev-userid-input-id2">
			</div>
			<div>
				<p>계정 비밀번호:</p> 
				<input type = "text" class="conf-text-input"  id="confev-userpwd-input-id2">
			</div>
		</div>
		
		<div class="confsite-game-text-div">
			<div>
				<p>보험알:</p> 
				<input type = "text" class="conf-text-input"  id="confev-balance-input-id2" disabled> 
				<label style="font-size:14px; font-weight:normal; padding-top:0px; min-width:100px;"  id="confev-balance-label-id2"></label>
			</div>
			<div>
				<p>계정타입:</p> 
				<input type="checkbox" id="confev-signal-check-id2" style="zoom:120%; margin-top:0px;"><label style="font-size:14px; font-weight:normal; padding-top:0px;"> 신호값용</label>
				<input type="checkbox" id="confev-multiroom-check-id2" style="zoom:120%; margin-top:5px; margin-left:30px;" <?=$mb_level<LEVEL_ADMIN+2?'hidden':''?>>
				<label style="font-size:14px; font-weight:normal; padding-top:0px;" <?=$mb_level<LEVEL_ADMIN+2?'hidden':''?>> 멀티방</label>
			</div>
		</div>
		<!-- 보험계정 3 -->
		<div class="confsite-game-text-div">
  			<p style="font-size: 16px; font-weight: bold;">보험계정 3</p>
  		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>보험배팅승인:</p> 
				<input type="checkbox" id="confev-bet-check-id3" style="zoom:120%; margin-top:0px;"><label style="font-size:14px; font-weight:normal; padding-top:0px;"> 배팅승인</label>
			</div>
			<div>
				<p>팅김방지 배팅:</p> 
				<input type="checkbox" id="confev-conbet-check-id3" style="zoom:120%; margin-top:5px;"><label style="font-size:14px; font-weight:normal; padding-top:0px;"> 배팅승인</label>
			</div>
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p> 보험타입</p>			
				<select name="pbresult-number" class="" id="confev-bettype-select-id3" style="padding:5px; width: 200px; margin-left:0px;">
					<?php foreach($gamd_types as $type=>$name):?>
						<option value="<?=$type?>" ><?=$name?></option>
					<?php endforeach?>
				</select>
			</div>
			<div>
				<p>사이트 주소:</p> 
				<input type = "text" class="conf-text-input" id="confev-betsite-input-id3">
			</div>
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>계정 아이디:</p> 
				<input type = "text" class="conf-text-input"  id="confev-userid-input-id3">
			</div>
			<div>
				<p>계정 비밀번호:</p> 
				<input type = "text" class="conf-text-input"  id="confev-userpwd-input-id3">
			</div>
		</div>
		
		<div class="confsite-game-text-div">
			<div>
				<p>보험알:</p> 
				<input type = "text" class="conf-text-input"  id="confev-balance-input-id3" disabled> 
				<label style="font-size:14px; font-weight:normal; padding-top:0px; min-width:100px;"  id="confev-balance-label-id3"></label>
			</div>
			<div>
				<p>계정타입:</p> 
				<input type="checkbox" id="confev-signal-check-id3" style="zoom:120%; margin-top:0px;"><label style="font-size:14px; font-weight:normal; padding-top:0px;"> 신호값용</label>
				<input type="checkbox" id="confev-multiroom-check-id3" style="zoom:120%; margin-top:5px; margin-left:30px;" <?=$mb_level<LEVEL_ADMIN+2?'hidden':''?>>
				<label style="font-size:14px; font-weight:normal; padding-top:0px;" <?=$mb_level<LEVEL_ADMIN+2?'hidden':''?>> 멀티방</label>
			</div>
		</div>
		<?php endif ?>

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
