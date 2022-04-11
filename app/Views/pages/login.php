<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title><?=$site_name?> 관리자</title>
    <?php if($_ENV['app.name'] == APP_LUCKYONE) :?>
    <link rel="shortcut icon" href="/favicon_l.ico">
    <?php else : ?>
    <link rel="shortcut icon" href="/favicon_o.ico">
    <?php endif ?>
	<!-- Bootstrap 3.3.7 -->
  	<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/pages.style.min.css');?>">
  	
  	<!-- JQuery 3.4.1 -->
	<script src="<?php echo base_url('assets/js/jquery-3.4.1.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/util.js'); ?>"></script>
</head>
<body>
	<div class="login-background-panel">
		<div class="login-modal-container">
			<div class="login-modal-panel" id="login-modal-panel-id">
				<h4>Login</h4>
				<p>환영합니다, 당신의 계정으로 로그인 부탁드립니다.</p>
				<div>
					<input type = "text" class="login-id-input" id="login-user-input-id" placeholder="ID">
				</div>
				<div>
					<input type = "password" class="login-password-input" id="login-pwd-input-id" placeholder="Password">
				</div>
				<div>					
					<button class="login-ok-button" id="login-okbtn-id">로그인</button>
				</div>
			</div>
		</div>
	</div>

	<script src="<?php echo base_url('assets/js/pages-script.js?v=1');?>"></script>
</body>
</html>