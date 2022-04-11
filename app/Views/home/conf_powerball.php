<?= $this->extend('header') ?>
<?= $this->section('content') ?>
  	<!--Sub Navbar-->
  	<div class="sub-navbar">
  		<p><i class="glyphicon glyphicon-play-circle"></i> 기본설정::게임설정</p>
  		<a href="<?php echo base_url().'home/conf_powerball';?>" class="sub-navbar-a <?=$active_pb?>">파워볼</a>
  		<a href="<?php echo base_url().'home/conf_powerladder';?>" class="sub-navbar-a">파워사다리</a>
  		<a href="<?php echo base_url().'home/conf_bogleball';?>" class="sub-navbar-a <?=$active_bb?>">보글파워볼</a>
  		<a href="<?php echo base_url().'home/conf_bogleladder';?>" class="sub-navbar-a">보글사다리</a>
  		<a href="<?php echo base_url().'home/conf_evol';?>" class="sub-navbar-a">에볼루션</a>
  		<a href="<?php echo base_url().'home/conf_slot_1';?>" class="sub-navbar-a">슬롯</a>
		<?php if($_ENV['app.name'] == APP_LUCKYONE) :?>
  		<a href="<?php echo base_url().'home/conf_slot_2';?>" class="sub-navbar-a">네츄럴슬롯</a>
		<?php endif ?>
  	</div>
  	<!--Site Setting-->
  	<div class="confsite-game-panel" id="<?=$game_id?>">
  		<!---->
  		<h4><i class="glyphicon glyphicon-hand-right"></i> <?=$game_name?> 게임관련 설정</h4>

  		<div class="confsite-game-text-div">
  			<p>유저 배팅승인:</p>
  			<input type="checkbox" id="confpb-bet-check-id"  style="zoom:120%; margin-top:4px;">
			  <label style="font-size:13px; font-weight:normal; padding-top:0px;"> 유저배팅승인</label>
  		</div>
  		<div class="confsite-game-text-div">
  			<p>배팅 마감시간:</p>
  			<input type="number" class="conf-seconds-input" id="confpb-endsec-input-id"><label> 초</label>
  		</div>
  		<div class="confsite-game-text-div">
  			<p>배팅 최소금액:</p>
  			<input type="number" class="conf-text-input" id="confpb-minmoney-input-id"><label> 원</label>
  		</div>
  		<div class="confsite-game-text-div">
  			<p>배팅 최대금액:</p>
  			<input type="number" class="conf-text-input" id="confpb-maxmoney-input-id"><label> 원</label>
  		</div>
		<div class="confsite-game-text-div">
  			<p>적중 최대금액:</p>
  			<input type="number" class="conf-text-input" id="confpb-winmoney-input-id"><label> 원</label>
  		</div>
		<div class="confsite-game-text-div">
			<p>단폴 누르기율:</p> 
			<input type = "number" class="conf-text-input"  id="confpb-percent-input-id"><label> %</label>
		</div>
		<div class="confsite-game-text-div">
			<p>조합 누르기율:</p> 
			<input type = "number" class="conf-text-input"  id="confpb-percent2-input-id"><label> %</label>
		</div>
		
  		<div class="confsite-game-text-div">
  			<p style="font-size: 16px; font-weight: bold;">파워볼단폴</p>
  		</div>
  		<div class="confsite-game-text-div">
  			<div>
  				<p>파워볼 홀 :: 짝 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio1-input-id">
  			</div>
  			<div>
  				<p>파워볼 언더 :: 오버 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio2-input-id">
  			</div>
  		</div>
  		<div class="confsite-game-text-div">
  			<div>
  				<p>일반볼 홀 :: 짝 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio3-input-id">
  			</div>
  			<div>
  				<p>일반볼 언더 :: 오버 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio4-input-id">
  			</div>
  		</div>
  		<div class="confsite-game-text-div">
  			<p style="font-size: 16px; font-weight: bold;">파워볼조합</p>

  		</div>
  		<div class="confsite-game-text-div">
  			<div>
  				<p>파워볼조합: 홀언더 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio5-input-id">
  			</div>
  			<div>
  				<p>파워볼조합: 홀오버 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio6-input-id">
  			</div>
  		</div>
  		<div class="confsite-game-text-div">
  			<div>
  				<p>파워볼조합: 짝언더 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio7-input-id">
  			</div>
  			<div>
  				<p>파워볼조합: 짝오버 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio8-input-id">
  			</div>
  		</div>
  		<div class="confsite-game-text-div">
  			<p style="font-size: 16px; font-weight: bold;">일반볼조합</p>

  		</div>
  		<div class="confsite-game-text-div">
  			<div>
  				<p>일반볼조합: 홀언더 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio9-input-id">
  			</div>
  			<div>
  				<p>일반볼조합: 홀오버 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio10-input-id">
  			</div>
  		</div>
  		<div class="confsite-game-text-div">
  			<div>
  				<p>일반볼조합: 짝언더 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio11-input-id">
  			</div>
  			<div>
  				<p>일반볼조합: 짝오버 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio12-input-id">
  			</div>
  		</div>
  		<div class="confsite-game-text-div">
  			<p style="font-size: 16px; font-weight: bold;">일반+파워조합</p>

  		</div>
  		<div class="confsite-game-text-div">
  			<div>
  				<p>일반+파워조합: 일반홀 파워홀 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio13-input-id">
  			</div>
  			<div>
  				<p>일반+파워조합: 일반홀 파워짝 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio14-input-id">
  			</div>
  		</div>
  		<div class="confsite-game-text-div">
  			<div>
  				<p>일반+파워조합: 일반짝 파워홀 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio15-input-id">
  			</div>
  			<div>
  				<p>일반+파워조합: 일반짝 파워짝 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio16-input-id">
  			</div>
  		</div>
  		<div class="confsite-game-text-div">
  			<div>
  				<p>일반+파워조합: 일반언더 파워언더 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio17-input-id">
  			</div>
  			<div>
  				<p>일반+파워조합: 일반언더 파워오버 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio18-input-id">
  			</div>
  		</div>
  		<div class="confsite-game-text-div">
  			<div>
  				<p>일반+파워조합: 일반오버 파워언더 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio19-input-id">
  			</div>
  			<div>
  				<p>일반+파워조합: 일반오버 파워오버 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio20-input-id">
  			</div>
  		</div>
  		<div class="confsite-game-text-div">
  			<p style="font-size: 16px; font-weight: bold;">일반볼 대중소</p>
  		</div>
  		<div class="confsite-game-text-div">
  			<div>
  				<p>일반볼대중소: 홀대 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio21-input-id">
  			</div>
  			<div>
  				<p>일반볼대중소: 홀중 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio22-input-id">
  			</div>
  		</div>
  		<div class="confsite-game-text-div">
  			<div>
  				<p>일반볼대중소: 홀소 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio23-input-id">
  			</div>
  			<div>
  				<p>일반볼대중소: 짝대 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio24-input-id">
  			</div>
  		</div>
  		<div class="confsite-game-text-div">
  			<div>
  				<p>일반볼대중소: 짝중 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio25-input-id">
  			</div>
  			<div>
  				<p>일반볼대중소: 짝소 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio26-input-id">
  			</div>
  		</div>
  		<div class="confsite-game-text-div">
  			<div>
  				<p>일반볼대중소: 대 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio27-input-id">
  			</div>
  			<div>
  				<p>일반볼대중소: 중 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio28-input-id">
  			</div>
  		</div>
		<div class="confsite-game-text-div">
  			<div>
  				<p>일반볼대중소: 소 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio29-input-id">
  			</div>
  		</div>
  		<div class="confsite-game-text-div">
  			<p style="font-size: 16px; font-weight: bold;">일반볼 조합+파워볼 홀짝</p>
  		</div>
		<div class="confsite-game-text-div">
  			<div>
  				<p>일반볼 조합+파워볼 홀짝: 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio31-input-id">
  			</div>
  		</div>
		<div class="confsite-game-text-div">
  			<p style="font-size: 16px; font-weight: bold;">파워볼 숫자</p>
  		</div>
		<div class="confsite-game-text-div">
  			<div>
  				<p>파워볼 숫자(0~9): 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio30-input-id">
  			</div>
  		</div>
		  <div class="confsite-button-group" style="margin-top:50px;">
  			<button class="confsite-cancel-button" id="confsite-cancel-btn-id">취소</button>
  			<button class="confsite-ok-button" id="confsite-ok-btn-id">저장</button>
  		</div>
  	</div>



  	<!--main_navbar.php-->
  	</div>

  	<script src="<?php echo base_url('assets/js/confpb-script.js?v=2');?>"></script>
	  <?= $this->endSection() ?>