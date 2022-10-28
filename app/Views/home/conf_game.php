<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	  <!--Sub Navbar-->
	<div class = "sub-navbar" value="<?= $this->renderSection('confgame-active') ?>">
		<p><i class="glyphicon glyphicon-play-circle"></i> 게임설정</p>
		<?php if(!$npg_deny) :?>
			<a href="<?php echo siteFurl().'home/conf_powerball';?>" class="sub-navbar-a" >파워볼</a>
			<a href="<?php echo siteFurl().'home/conf_powerladder';?>" class="sub-navbar-a " >파워사다리</a>
		<?php endif ?>   
    	<?php if(!$bpg_deny) :?>
			<a href="<?php echo siteFurl().'home/conf_bogleball';?>" class="sub-navbar-a" >보글파워볼</a>
			<a href="<?php echo siteFurl().'home/conf_bogleladder';?>" class="sub-navbar-a " >보글사다리</a>
		<?php endif ?>   
		<?php if($eos5_enable) :?>
			<a href="<?php echo site_furl('home/conf_eos5ball');?>" class="sub-navbar-a" >EOS5분</a>
   		<?php endif ?>  
		<?php if($eos3_enable) :?>
			<a href="<?php echo site_furl('home/conf_eos3ball');?>" class="sub-navbar-a" >EOS3분</a>
   		<?php endif ?>  
		<?php if($coin5_enable) :?>
			<a href="<?php echo site_furl('home/conf_coin5ball');?>" class="sub-navbar-a" >코인5분</a>
   		<?php endif ?>  
		<?php if($coin3_enable) :?>
			<a href="<?php echo site_furl('home/conf_coin3ball');?>" class="sub-navbar-a" >코인3분</a>
   		<?php endif ?>  
		<?php if(!$cas_deny ) :?>
			<a href="<?php echo siteFurl().'home/conf_evol';?>" class="sub-navbar-a " >에볼루션</a>
		<?php endif ?>   
		<?php if($kgon_enable) :?>
			<a href="<?php echo siteFurl().'home/conf_casino';?>" class="sub-navbar-a" >호텔카지노</a>
		<?php endif ?>
		<?php if(!$slot_deny) :?>
			<?php if($_ENV['app.type'] != APPTYPE_2) :?>
				<a href="<?php echo siteFurl().'home/conf_slot_1';?>" class="sub-navbar-a">정품슬롯</a>
			<?php endif ?>
			<?php if($_ENV['app.type'] != APPTYPE_3) :?>
				<a href="<?php echo siteFurl().'home/conf_slot_2';?>" class="sub-navbar-a">네츄럴슬롯</a>
			<?php endif ?>
		<?php endif ?>   
	</div>
	<!--Site Setting-->
	<div class="confsite-game-panel"  id="<?=$game_id?>">
		<?= $this->renderSection('confgame-content') ?>
	</div>
<!--main_navbar.php-->
</div>

<?php if(array_key_exists("app.produce", $_ENV)) :?>
    <script src="<?php echo site_furl('/assets/js/page.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
<?php endif ?>
<?= $this->renderSection('confgame-script') ?>

<?= $this->endSection() ?>