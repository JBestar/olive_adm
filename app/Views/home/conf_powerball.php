  	<!--Sub Navbar-->
	<div class = "sub-navbar">
		<p><i class="glyphicon glyphicon-play-circle"></i> 기본설정::배팅설정</p>
		<a href="<?php echo base_url().'home/conf_powerball';?>" class="sub-navbar-a active" >파워볼</a>
		<a href="<?php echo base_url().'home/conf_powerladder';?>" class="sub-navbar-a" >파워사다리</a>
		<a href="<?php echo base_url().'home/conf_kenoladder';?>" class="sub-navbar-a" >키노사다리</a>
		<a href="<?php echo base_url().'home/conf_bogleball';?>" class="sub-navbar-a" >보글파워볼</a>
		<a href="<?php echo base_url().'home/conf_bogleladder';?>" class="sub-navbar-a" >보글사다리</a>
	</div>
	<!--Site Setting-->
	<div class="confsite-game-panel">
		<!---->
		<h4><i class="glyphicon glyphicon-hand-right"></i> 파워볼 게임관련 설정</h4>	
		
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
			<p>배팅마감시간:</p> 
			<input type = "text" class="conf-seconds-input" id="confpb-endsec-input-id"><label> 초</label>
		</div>
		<div class="confsite-game-text-div">
			<p>게임지연시간:</p> 
			<input type = "text" class="conf-seconds-input"  id="confpb-delaysec-input-id"><label> 초</label>
		</div>
		<div class="confsite-game-text-div">
			<p>최소배팅금액:</p> 
			<input type = "text" class="conf-text-input"  id="confpb-minmoney-input-id"><label> 원</label>
		</div>
		<div class="confsite-game-text-div">
			<p>최대배팅금액:</p> 
			<input type = "text" class="conf-text-input"  id="confpb-maxmoney-input-id"><label> 원</label>
		</div>
		<div class="confsite-game-text-div">
			<p>단폴 누르기율:</p> 
			<input type = "text" class="conf-text-input"  id="confpb-percent-input-id"><label> %</label>
		</div>
		<div class="confsite-game-text-div">
			<p>조합 누르기율:</p> 
			<input type = "text" class="conf-text-input"  id="confpb-percent2-input-id"><label> %</label>
		</div>
		<div class="confsite-game-text-div">
			<p style="font-size: 16px; font-weight: bold;">파워볼단폴</p> 
			
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>파워볼 홀 :: 짝 배당율</p> 
				<input type = "text" class="conf-text-input" id="confpb-ratio1-input-id">
			</div>
			<div>
				<p>파워볼 언더 :: 오버 배당율</p> 
				<input type = "text" class="conf-text-input" id="confpb-ratio2-input-id">
			</div>
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>일반볼 홀 :: 짝 배당율</p> 
				<input type = "text" class="conf-text-input"  id="confpb-ratio3-input-id">
			</div>
			<div>				
				<p>일반볼 언더 :: 오버 배당율</p> 
				<input type = "text" class="conf-text-input"  id="confpb-ratio4-input-id">
			</div>
		</div>
		<div class="confsite-game-text-div">
			<p style="font-size: 16px; font-weight: bold;">파워볼조합</p> 
			
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>파워볼조합: 홀언더 배당율</p> 
				<input type = "text" class="conf-text-input"  id="confpb-ratio5-input-id">
			</div>
			<div>				
				<p>파워볼조합: 홀오버 배당율</p> 
				<input type = "text" class="conf-text-input"  id="confpb-ratio6-input-id">
			</div>
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>파워볼조합: 짝언더 배당율</p> 
				<input type = "text" class="conf-text-input"  id="confpb-ratio7-input-id">
			</div>
			<div>				
				<p>파워볼조합: 짝오버 배당율</p> 
				<input type = "text" class="conf-text-input"  id="confpb-ratio8-input-id">
			</div>
		</div>
		<div class="confsite-game-text-div">
			<p style="font-size: 16px; font-weight: bold;">일반볼조합</p> 
			
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>일반볼조합: 홀언더 배당율</p> 
				<input type = "text" class="conf-text-input"  id="confpb-ratio9-input-id">
			</div>
			<div>				
				<p>일반볼조합: 홀오버 배당율</p> 
				<input type = "text" class="conf-text-input"  id="confpb-ratio10-input-id">
			</div>
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>일반볼조합: 짝언더 배당율</p> 
				<input type = "text" class="conf-text-input"  id="confpb-ratio11-input-id">
			</div>
			<div>				
				<p>일반볼조합: 짝오버 배당율</p> 
				<input type = "text" class="conf-text-input"  id="confpb-ratio12-input-id">
			</div>
		</div>
		<div class="confsite-game-text-div">
			<p style="font-size: 16px; font-weight: bold;">일반+파워조합</p> 
			
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>일반+파워조합: 일반홀 파워홀 배당율</p> 
				<input type = "text" class="conf-text-input"  id="confpb-ratio13-input-id">
			</div>
			<div>				
				<p>일반+파워조합: 일반홀 파워짝 배당율</p> 
				<input type = "text" class="conf-text-input"  id="confpb-ratio14-input-id">
			</div>
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>일반+파워조합: 일반짝 파워홀 배당율</p> 
				<input type = "text" class="conf-text-input"  id="confpb-ratio15-input-id">
			</div>
			<div>				
				<p>일반+파워조합: 일반짝 파워짝 배당율</p> 
				<input type = "text" class="conf-text-input"  id="confpb-ratio16-input-id">
			</div>
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>일반+파워조합: 일반언더 파워언더 배당율</p> 
				<input type = "text" class="conf-text-input"  id="confpb-ratio17-input-id">
			</div>
			<div>				
				<p>일반+파워조합: 일반언더 파워오버 배당율</p> 
				<input type = "text" class="conf-text-input"  id="confpb-ratio18-input-id">
			</div>
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>일반+파워조합: 일반오버 파워언더 배당율</p> 
				<input type = "text" class="conf-text-input"  id="confpb-ratio19-input-id">
			</div>
			<div>				
				<p>일반+파워조합: 일반오버 파워오버 배당율</p> 
				<input type = "text" class="conf-text-input"  id="confpb-ratio20-input-id">
			</div>
		</div>
		<div class="confsite-game-text-div">
			<p style="font-size: 16px; font-weight: bold;">일반볼 대중소</p> 
			
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>일반볼대중소: 홀대 배당율</p> 
				<input type = "text" class="conf-text-input"  id="confpb-ratio21-input-id">
			</div>
			<div>				
				<p>일반볼대중소: 홀중 배당율</p> 
				<input type = "text" class="conf-text-input"  id="confpb-ratio22-input-id">
			</div>
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>일반볼대중소: 홀소 배당율</p> 
				<input type = "text" class="conf-text-input"  id="confpb-ratio23-input-id">
			</div>
			<div>				
				<p>일반볼대중소: 짝대 배당율</p> 
				<input type = "text" class="conf-text-input"  id="confpb-ratio24-input-id">
			</div>
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>일반볼대중소: 짝중 배당율</p> 
				<input type = "text" class="conf-text-input"  id="confpb-ratio25-input-id">
			</div>
			<div>				
				<p>일반볼대중소: 짝소 배당율</p> 
				<input type = "text" class="conf-text-input"  id="confpb-ratio26-input-id">
			</div>
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

<script src="<?php echo base_url('assets/js/confpb-script.js');?>"></script>