<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	<!--Sub Navbar-->
	<div class = "sub-navbar" value="<?= $this->renderSection('ebal-active') ?>">
		<p><i class="glyphicon glyphicon-book"></i> 에볼밸런스</p>
        <a href="<?php echo site_furl('home/conf_ebal');?>" class="sub-navbar-a" >밸런스설정</a>
        <a href="<?php echo site_furl('bet/eordhistory');?>" class="sub-navbar-a" >실시간</a>
        <a href="<?php echo site_furl('bet/ebalhistory');?>" class="sub-navbar-a" >밸런스내역</a>
        <a href="<?php echo site_furl('bet/ebethistory');?>" class="sub-navbar-a" >배팅내역</a>
	</div>

    <?= $this->renderSection('eval_content') ?>
<?= $this->endSection() ?>