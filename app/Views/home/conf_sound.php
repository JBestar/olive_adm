<?= $this->extend('header') ?>
<?= $this->section('content') ?>
<!--Sub Navbar-->
	<div class = "sub-navbar">
		<p><i class="glyphicon glyphicon-user"></i> 기타설정::알람설정</p>
		<a href="<?php echo base_url().'home/conf_sound';?>" class="sub-navbar-a active" >알람설정</a>
		
	</div>
	<!--Site Setting-->
	<div class="confsite-game-panel">
		<!---->
		<h4><i class="glyphicon glyphicon-hand-right"></i> 알람설정</h4>
		
		<div class="confother-sound-div">
			<label>신규회원 알림음:</label> 
			<select name="pbresult-number" class="pbresult-number-select" id="confsound-alarm1-select-id" style="width: 150px; float: left;">
				<option value="sound1.mp3">알림음1</option>
				<option value="sound2.mp3">알림음2</option>
				<option value="sound3.mp3">알림음3</option>
				<option value="sound4.mp3">알림음4</option>
				<option value="sound5.mp3">알림음5</option>
				<option value="sound6.mp3">알림음6</option>
				<option value="sound7.mp3">알림음7</option>
				<option value="sound8.mp3">알림음8</option>
				<option value="sound9.mp3">알림음9</option>
				<option value="sound10.mp3">알림음10</option>
				<option value="sound11.mp3">알림음11</option>
				<option value="sound12.mp3">알림음12</option>
			</select>
			<audio id="confsound-alarm1-audio-id" controls>
			  <!--
			  <source src="" type="audio/ogg">
			  -->
			  <source id="confsound-alarm1-source-id" >
			  Your browser does not support the audio element.
			</audio>
			<!--
			<input type="range" id="confsound-volume-id" min="0" max="100" step="1" style="width: 100px;">
			
			<label>볼륨</label>
			<button><i class="glyphicon glyphicon-play"></i></button>
			
			<button><i class="glyphicon glyphicon-stop"></i></button>
			-->
			
		</div>
		
		<div class="confother-sound-div">
			<label>머니충전 알림음:</label> 
			<select name="pbresult-number" class="pbresult-number-select" id="confsound-alarm2-select-id" style="width: 150px; float: left;">
				<option value="sound1.mp3">알림음1</option>
				<option value="sound2.mp3">알림음2</option>
				<option value="sound3.mp3">알림음3</option>
				<option value="sound4.mp3">알림음4</option>
				<option value="sound5.mp3">알림음5</option>
				<option value="sound6.mp3">알림음6</option>
				<option value="sound7.mp3">알림음7</option>
				<option value="sound8.mp3">알림음8</option>
				<option value="sound9.mp3">알림음9</option>
				<option value="sound10.mp3">알림음10</option>
				<option value="sound11.mp3">알림음11</option>
				<option value="sound12.mp3">알림음12</option>
			</select>
			<audio id="confsound-alarm2-audio-id" controls>
			  
			  <source id="confsound-alarm2-source-id" >
			  Your browser does not support the audio element.
			</audio>
			
		</div>

		<div class="confother-sound-div">
			<label>머니환전 알림음:</label> 
			<select name="pbresult-number" class="pbresult-number-select" id="confsound-alarm3-select-id" style="width: 150px; float: left;">
				<option value="sound1.mp3">알림음1</option>
				<option value="sound2.mp3">알림음2</option>
				<option value="sound3.mp3">알림음3</option>
				<option value="sound4.mp3">알림음4</option>
				<option value="sound5.mp3">알림음5</option>
				<option value="sound6.mp3">알림음6</option>
				<option value="sound7.mp3">알림음7</option>
				<option value="sound8.mp3">알림음8</option>
				<option value="sound9.mp3">알림음9</option>
				<option value="sound10.mp3">알림음10</option>
				<option value="sound11.mp3">알림음11</option>
				<option value="sound12.mp3">알림음12</option>
			</select>
			<audio id="confsound-alarm3-audio-id" controls>
			  
			  <source id="confsound-alarm3-source-id" >
			  Your browser does not support the audio element.
			</audio>
			
		</div>

		<div class="confother-sound-div">
			<label>새쪽지 알림음:</label> 
			<select name="pbresult-number" class="pbresult-number-select" id="confsound-alarm4-select-id" style="width: 150px; float: left;">
				<option value="sound1.mp3">알림음1</option>
				<option value="sound2.mp3">알림음2</option>
				<option value="sound3.mp3">알림음3</option>
				<option value="sound4.mp3">알림음4</option>
				<option value="sound5.mp3">알림음5</option>
				<option value="sound6.mp3">알림음6</option>
				<option value="sound7.mp3">알림음7</option>
				<option value="sound8.mp3">알림음8</option>
				<option value="sound9.mp3">알림음9</option>
				<option value="sound10.mp3">알림음10</option>
				<option value="sound11.mp3">알림음11</option>
				<option value="sound12.mp3">알림음12</option>
			</select>
			<audio id="confsound-alarm4-audio-id" controls>
			  
			  <source id="confsound-alarm4-source-id" >
			  Your browser does not support the audio element.
			</audio>
			
		</div>

		<div class = "confsite-button-group">
			<button class="confsite-cancel-button"  id="confsite-cancel-btn-id">취소</button>
			<button class="confsite-ok-button" id="confsite-ok-btn-id">저장</button>
		</div>
	</div>



<!--main_navbar.php-->
</div>


<script src="<?php echo base_url('assets/js/confsound-script.js');?>"></script>
<?= $this->endSection() ?>