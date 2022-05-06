<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title><?=$site_name?> 관리자</title>
	<?php switch($_ENV['app.name']) { 
        case APP_ONESTAR:?>
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_re.ico');?>">
        <?php break; 
        case APP_SKY :?>
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_s.ico');?>">
        <?php break; 
        case APP_MSLOT :?>
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_m.ico');?>">
        <?php break; 
        case APP_KANGNUM :?>                        
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_k.ico');?>">
        <?php break; 
        case APP_MAX :?>
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_x.ico');?>">
        <?php break; 
        case APP_THUNDER :?>
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_t.ico');?>">
        <?php break; 
        case APP_WORLD :?>
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_w.ico');?>">
        <?php break; 
        case APP_ROYAL :?>
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_r.ico');?>">
        <?php break; 
        case APP_COD :?>
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_c.ico');?>">
        <?php break; 
        case APP_ORION :?>
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_or.ico');?>">
        <?php break; 
        case APP_MAJOR :?>
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_ma.ico');?>">
        <?php break; 
        case APP_CHANEL :?>
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_ch.ico');?>">
        <?php break; 
        case APP_APPLE :?>
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_a.ico');?>">
        <?php break; 
        case APP_BMW :?>
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_b.ico');?>">
        <?php break; 
        case APP_BIG :?>
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_bg.ico');?>">
        <?php break; 
        default :?>
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_l.ico');?>">
        <?php break; 
        } ?>
	<!-- Bootstrap 3.3.7 -->
  	<link rel="stylesheet" href="<?php echo site_furl('assets/bootstrap/css/bootstrap.min.css');?>">
	<link rel="stylesheet" href="<?php echo site_furl('assets/css/pages.style.min.css');?>">
  	
  	<!-- JQuery 3.4.1 -->
	<script src="<?php echo site_furl('assets/js/jquery-3.4.1.min.js'); ?>"></script>
	<script src="<?php echo site_furl('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
	<script>const FURL = "<?=$_ENV['app.furl']?>" </script>
	<script src="<?php echo site_furl('assets/js/util.js?v=1'); ?>"></script>
</head>
<body>
	<style>
		.login-background-panel{
			background-image: url('<?php echo siteFurl();?>/assets/image/register-bg.jpg');
			background-position: 50%;
			background-repeat: no-repeat;
			background-size: cover;
			width: 100% !important;
			height: 100vh !important;
			display: table;
			position: absolute;
		}
	</style>
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
	<?php if(array_key_exists("app.produce", $_ENV)) :?>
		<script src="<?php echo site_furl('/assets/js/pages-script.js?t='.time());?>"></script>
	<?php else : ?>
		<script src="<?php echo site_furl('/assets/js/pages-script.js?v=1');?>"></script>
	<?php endif ?>
</body>
</html>