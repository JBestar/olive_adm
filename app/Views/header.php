<html lang="en">
<head>
	<meta charset="utf-8">
	<!--<meta content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=yes" name="viewport">
	-->
	<title><?=$site_name?> 관리자</title>
    <?php if($_ENV['app.name'] == APP_ONESTAR) :?>
    <link rel="shortcut icon" href="/favicon_o.ico">
    <?php elseif($_ENV['app.name'] == APP_SKY) :?>
    <link rel="shortcut icon" href="/favicon_s.ico">
    <?php elseif($_ENV['app.name'] == APP_MSLOT) :?>
    <link rel="shortcut icon" href="/favicon_m.ico">
    <?php else : ?>
    <link rel="shortcut icon" href="/favicon_l.ico">
    <?php endif ?>
	<!-- Bootstrap 3.3.7 -->
  	<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/main.style.css?v=4');?>">

	<!-- JQuery 1.12.1 -->
	<link rel="stylesheet" href="<?php echo base_url('assets/js/jquery-ui-1.12.1.min.css'); ?>">
	<script src="<?php echo base_url('assets/js/jquery-1.12.4.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/jquery-ui-1.12.1.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/util.js?v=1'); ?>"></script>
	<script src="<?php echo base_url('assets/js/common.js?v=2'); ?>"></script>

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
<?= $this->include('include/sidebar')?>
<?= $this->include('include/main_navbar')?>
<?php $this->renderSection('content') ?>
<?= $this->include('footer')?>
