<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	<!--Sub Navbar-->
	<div class = "sub-navbar" value="<?= $this->renderSection('realtime-title')?>">
		<p><i class="glyphicon glyphicon-dashboard"></i> 실시간배팅</p>
		<?php if(!$npg_deny) :?>
			<a href="<?php echo site_furl('bet/pbrealtime');?>" class="sub-navbar-a" >파워볼</a>
			<a href="<?php echo site_furl('bet/psrealtime');?>" class="sub-navbar-a" >파워사다리</a>
		<?php endif ?>   
		<?php if(!$bpg_deny) :?>
			<a href="<?php echo site_furl('bet/bbrealtime');?>" class="sub-navbar-a" >보글볼</a>
			<a href="<?php echo site_furl('bet/bsrealtime');?>" class="sub-navbar-a" >보글사다리</a>
		<?php endif ?>   
	</div>

	<div class="bet-panel">
		<h4>
            <?php $this->renderSection('realtime-title')?> 실시간베팅
        </h4>
		<Table class="bet-table" id="pbbet-table-id">
			<tr>
                <?php $this->renderSection('realtime-table-header')?>										
			</tr>
			
		</Table>

	</div>


<!--main_navbar.php-main-container-->
</div>
<?php if(array_key_exists("app.produce", $_ENV)) :?>
    <script src="<?php echo site_furl('/assets/js/page.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
<?php endif ?>
<?php $this->renderSection('realtime-script')?>
<?= $this->endSection() ?>