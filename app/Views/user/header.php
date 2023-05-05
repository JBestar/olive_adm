<html lang="en">
<head>
	<meta charset="utf-8">
	<!--<meta content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=yes" name="viewport">
	-->
	<title><?=$site_name?> 관리자</title>
    
	<?php if(array_key_exists('app.logo', $_ENV)) :?> 
        <link rel="shortcut icon" href="<?php echo site_furl('/favicon_'.$_ENV['app.logo'].'.ico?v=1');?>">
    <?php else: ?>
        <link rel="shortcut icon" href="<?php echo site_furl('/favicon_am.ico?v=1');?>">
    <?php endif ?>
	<!-- Bootstrap 3.3.7 -->
  	<link rel="stylesheet" href="<?php echo site_furl('assets/bootstrap/css/bootstrap.min.css');?>">
	<link rel="stylesheet" href="<?php echo site_furl('assets/css/main.style.css?v=14');?>">

	<!-- JQuery 1.12.1 -->
	<link rel="stylesheet" href="<?php echo site_furl('assets/js/jquery-ui-1.12.1.min.css'); ?>">
	<script src="<?php echo site_furl('assets/js/jquery-1.12.4.min.js'); ?>"></script>
	<script src="<?php echo site_furl('assets/js/jquery-ui-1.12.1.min.js'); ?>"></script>
	<script src="<?php echo site_furl('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
	<script>
        const FURL = "<?=$_ENV['app.furl']?>" 
        var mLevelType = 0;
        <?php if(array_key_exists('app.level_type', $_ENV) && $_ENV['app.level_type'] > 0) :  ?>
            mLevelType = <?=$_ENV['app.level_type']?>;
        <?php endif ?>
    </script>
    <script src="<?php echo site_furl('assets/js/sweetalert2.all.min.js?v=1'); ?>"></script>
    <script src="<?php echo site_furl('assets/js/common.js?t=').time(); ?>"></script>

</head>
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
<body oncontextmenu="return false" ondragstart="return true" onselectstart="return true">
<?php else : ?>
<body>
<?php endif ?>

<style>
	.useredit-panel{
		min-height:660px;
	}
</style>
<div class="main-container" style="margin-left:0px; padding:0px; min-width:800px;" id="main-container-id">
<div class="loading" style="display: none;">
	<div class="load lds-ellipsis"></div>
</div>
<?php $this->renderSection('content') ?>
<?= $this->include('footer')?>
