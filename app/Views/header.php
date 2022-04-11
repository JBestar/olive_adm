<html lang="en">
<head>
	<meta charset="utf-8">
	<!--<meta content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=yes" name="viewport">
	-->
	<title><?=$site_name?> 관리자</title>

	<!-- Bootstrap 3.3.7 -->
  	<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/main.style.css?v=2');?>">

	<!-- JQuery 1.12.1 -->
	<link rel="stylesheet" href="<?php echo base_url('assets/js/jquery-ui-1.12.1.min.css'); ?>">
	<script src="<?php echo base_url('assets/js/jquery-1.12.4.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/jquery-ui-1.12.1.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/util.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/common.js?v=4'); ?>"></script>

</head>
<body>
<div class="main-navbar-dropdown-container" id="main-navbar-dropdown-container-id" style="display: none;">
  <div class="main-navbar-dropdown-div"> 
  <button id="main-navbar-dropdown-logout-id"><span class="glyphicon glyphicon-log-out"></span>&nbsp&nbsp로그아웃</button>
  </div>
</div>
<?= $this->include('include/sidebar')?>
<?= $this->include('include/main_navbar')?>
<?php $this->renderSection('content') ?>
<?= $this->include('footer')?>
