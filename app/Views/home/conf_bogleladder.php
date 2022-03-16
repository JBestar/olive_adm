  	<!--Sub Navbar-->
	<div class = "sub-navbar">
		<p><i class="glyphicon glyphicon-play-circle"></i> 기본설정::배팅설정</p>
		<a href="<?php echo base_url().'home/conf_powerball';?>" class="sub-navbar-a" >파워볼</a>
		<a href="<?php echo base_url().'home/conf_powerladder';?>" class="sub-navbar-a" >파워사다리</a>
		<a href="<?php echo base_url().'home/conf_kenoladder';?>" class="sub-navbar-a" >키노사다리</a>
		<a href="<?php echo base_url().'home/conf_bogleball';?>" class="sub-navbar-a" >보글파워볼</a>
		<a href="<?php echo base_url().'home/conf_bogleladder';?>" class="sub-navbar-a active" >보글사다리</a>
	</div>
	<!--Site Setting-->
	<div class="confsite-game-panel">
		<!---->
		<h4><i class="glyphicon glyphicon-hand-right"></i> 보글사다리 게임관련 설정</h4>	
		
		<div class="confsite-game-text-div">
			<p>유저 배팅차단:</p> 
			<input type="checkbox" id="confpb-bet-check-id"><label> 유저배팅승인</label>
		</div>
		<!--
		<div class="confsite-game-text-div">
			<p>유저 자동배팅차단:</p> 
			<input type="checkbox" id="confpb-autobet-check-id"><label> 유저자동배팅승인</label>
		</div>
		-->
		<div class="confsite-game-text-div">
			<p>베팅마감시간:</p> 
			<input type = "text" class="conf-seconds-input" id="confpb-endsec-input-id"><label> 초</label>
		</div>
		<div class="confsite-game-text-div">
			<p>게임지연시간:</p> 
			<input type = "text" class="conf-seconds-input"  id="confpb-delaysec-input-id"><label> 초</label>
		</div>
		<div class="confsite-game-text-div">
			<p>최소베팅금액:</p> 
			<input type = "text" class="conf-text-input"  id="confpb-minmoney-input-id"><label> 원</label>
		</div>
		<div class="confsite-game-text-div">
			<p>최대베팅금액:</p> 
			<input type = "text" class="conf-text-input"  id="confpb-maxmoney-input-id"><label> 원</label>
		</div>
		<div class="confsite-game-text-div">
			<p>누르기율:</p> 
			<input type = "text" class="conf-text-input"  id="confpb-percent-input-id"><label> %</label>
		</div>
		<div class="confsite-game-text-div">
			<p>파워사다리 좌 :: 우 배당율</p> 
			<input type = "text" class="conf-text-input" id="confpb-ratio1-input-id">
		</div>
		<div class="confsite-game-text-div">
			<p>파워사디리 3줄 :: 4줄 배당율</p> 
			<input type = "text" class="conf-text-input" id="confpb-ratio2-input-id">
		</div>
		<div class="confsite-game-text-div">
			<p>파워사다리 홀 :: 짝 배당율</p> 
			<input type = "text" class="conf-text-input"  id="confpb-ratio3-input-id">
		</div>
		<!--
		<div class="confsite-game-check-div">			 
			<input type="checkbox" id="confpb-event-check-id"><label> 이벤트적용</label><br>
			<input type="checkbox" id="confpb-multibet-check-id"><label> 양방배팅가능</label>
		</div>
		-->
		<div class = "confsite-button-group" style="margin-top:50px;">
			<button class="confsite-cancel-button" id="confsite-cancel-btn-id">취소</button>
			<button class="confsite-ok-button"  id="confsite-ok-btn-id">저장</button>
		</div>
	</div>



<!--main_navbar.php-->
</div>

<script src="<?php echo base_url('assets/js/confbs-script.js');?>"></script>