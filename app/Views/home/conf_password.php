  	<!--Sub Navbar-->
      <div class = "sub-navbar">
		<p><i class="glyphicon glyphicon-lock"></i> 기본설정::비밀번호변경</p>
	</div>
	<!--Site Setting-->
	<div class="confsite-password-panel">
		<!---->
		<div class="confsite-password-text-div">
			<p>이전비밀번호:</p> <input type = "password" id="confsite-password-input-id">
		</div>
		<div class="confsite-password-text-div">
			<p>새 비밀번호:</p> <input type = "password" id="confsite-password-new-input-id">
		</div>
		<div class="confsite-password-text-div">
			<p>새 비밀번호 확인:</p> <input type = "password" id="confsite-password-newok-input-id">
		</div>
		<div class = "confsite-button-group">
			<button class="confsite-cancel-button"  id="confsite-cancel-btn-id">취소</button>
			<button class="confsite-ok-button" id="confsite-ok-btn-id">저장</button>
		</div>
	</div>



<!--main_navbar.php-->
</div>


<script src="<?php echo base_url('assets/js/confpwd-script.js');?>"></script>