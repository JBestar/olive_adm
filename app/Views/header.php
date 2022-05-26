<html lang="en">
<head>
	<meta charset="utf-8">
	<!--<meta content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=yes" name="viewport">
	-->
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
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_x.ico?v=1');?>">
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
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_a.ico?v=1');?>">
        <?php break; 
        case APP_BMW :?>
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_b.ico');?>">
        <?php break; 
        case APP_BIG :?>
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_bg.ico');?>">
        <?php break; 
        case APP_DREAM :?>
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_d.ico');?>">
        <?php break; 
        case APP_EMPEROR :?>
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_e.ico');?>">
        <?php break; 
        case APP_GOLD :?>
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_g.ico');?>">
        <?php break; 
        case APP_FOXWOOD :?>
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_f.ico');?>">
        <?php break; 
        case APP_ORANGE :?>
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_org.ico?v=2');?>">
        <?php break; 
        case APP_CT :?>
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_ct.ico');?>">
        <?php break; 
        case APP_HI :?>
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_hi.ico?v=1');?>">
        <?php break; 
        default :?>
            <link rel="shortcut icon" href="<?php echo site_furl('/favicon_l.ico');?>">
        <?php break; 
        } ?>
	<!-- Bootstrap 3.3.7 -->
  	<link rel="stylesheet" href="<?php echo site_furl('assets/bootstrap/css/bootstrap.min.css');?>">
	<link rel="stylesheet" href="<?php echo site_furl('assets/css/main.style.css?v=7');?>">

	<!-- JQuery 1.12.1 -->
	<link rel="stylesheet" href="<?php echo site_furl('assets/js/jquery-ui-1.12.1.min.css'); ?>">
	<script src="<?php echo site_furl('assets/js/jquery-1.12.4.min.js'); ?>"></script>
	<script src="<?php echo site_furl('assets/js/jquery-ui-1.12.1.min.js'); ?>"></script>
	<script src="<?php echo site_furl('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
	<script>const FURL = "<?=$_ENV['app.furl']?>" </script>
	<script src="<?php echo site_furl('assets/js/util.js?v=1'); ?>"></script>
	<script src="<?php echo site_furl('assets/js/common.js?v=3'); ?>"></script>

</head>
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
<body oncontextmenu="return false" ondragstart="return true" onselectstart="return true">
<?php else : ?>
<body>
<?php endif ?>
<div class="main-navbar-dropdown-container" id="main-navbar-dropdown-container-id" style="display: none;">
  <div class="main-navbar-dropdown-div"> 
  <button id="main-navbar-dropdown-logout-id"><span class="glyphicon glyphicon-log-out"></span>&nbsp&nbsp로그아웃</button>
  </div>
</div>
<style>
		.user-table a.link-member {
			color: blue;
			border: none;
			background-color: transparent;
			box-shadow: none;
			font-weight: lighter;
		}

		.user-table a.link-member:hover {
			text-decoration: underline;
			color: blue;
		}
				
		button.refresh_btn {
			border:0;
			padding:0;
			box-shadow:none;
			width: 18px;
			height: 18px;
			vertical-align: top;
			margin-left: 10px;
			background: url(<?php echo site_furl('/assets/image/refresh_btn.png');?>) no-repeat left top;
		}
		button.refresh_btn:hover{
			border:1px;

			box-shadow: 1px 1px 1px 0px rgb(0 0 0 / 50%);
		}

		button.refresh_btn.refresh{
			background: url(<?php echo site_furl('/assets/image/refresh_btn.gif');?>) no-repeat left top;
		}
	</style>
<?= $this->include('include/sidebar')?>
<?= $this->include('include/main_navbar')?>
<?php $this->renderSection('content') ?>
<?= $this->include('footer')?>
