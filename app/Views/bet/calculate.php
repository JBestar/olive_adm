<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	<!--Sub Navbar-->
	<div class = "sub-navbar" value="<?= $this->renderSection('calculate-active') ?>">
		<p><i class="glyphicon glyphicon-tag"></i> 정산</p>
		<a href="<?php echo site_furl('bet/allcalculate');?>" class="sub-navbar-a" >전체</a>
		<?php if(!$npg_deny) :?>
			<a href="<?php echo site_furl('bet/pbcalculate');?>" class="sub-navbar-a" >파워볼</a>
			<a href="<?php echo site_furl('bet/pscalculate');?>" class="sub-navbar-a" >파워사다리</a>
		<?php endif ?>   
    	<?php if(!$bpg_deny) :?>
			<a href="<?php echo site_furl('bet/bbcalculate');?>" class="sub-navbar-a" >보글볼</a>
			<a href="<?php echo site_furl('bet/bscalculate');?>" class="sub-navbar-a" >보글사다리</a>
   		<?php endif ?>   
		<?php if($eos5_enable) :?>
			<a href="<?php echo site_furl('bet/e5calculate');?>" class="sub-navbar-a" >EOS5분</a>
   		<?php endif ?>
		<?php if($eos3_enable) :?>
			<a href="<?php echo site_furl('bet/e3calculate');?>" class="sub-navbar-a" >EOS3분</a>
   		<?php endif ?>
		<?php if($coin5_enable) :?>
			<a href="<?php echo site_furl('bet/c5calculate');?>" class="sub-navbar-a" >코인5분</a>
   		<?php endif ?>
		<?php if($coin3_enable) :?>
			<a href="<?php echo site_furl('bet/c3calculate');?>" class="sub-navbar-a" >코인3분</a>
   		<?php endif ?>
		<?php if(!$cas_deny || $kgon_enable) :?>
		   <a href="<?php echo site_furl('bet/cscalculate');?>" class="sub-navbar-a" >카지노</a>
   		<?php endif ?>   
		<?php if(!$slot_deny) :?>
			<?php if($mb_level >= LEVEL_ADMIN) :  ?>
				<?php if($_ENV['app.type'] != APPTYPE_2) :?>
					<a href="<?php echo site_furl('bet/xslcalculate');?>" class="sub-navbar-a" >정품슬롯</a>
				<?php endif ?>
				<?php if($_ENV['app.type'] != APPTYPE_3) :?>
					<a href="<?php echo site_furl('bet/fslcalculate');?>" class="sub-navbar-a" >네츄럴슬롯</a>
				<?php endif ?>
				<?php if($_ENV['app.type'] == APPTYPE_1) :?>
					<a href="<?php echo site_furl('bet/slcalculate');?>" class="sub-navbar-a" >슬롯</a>
				<?php endif ?>
			<?php else: ?>
				<a href="<?php echo site_furl('bet/slcalculate');?>" class="sub-navbar-a" >슬롯</a>
			<?php endif ?>
		<?php endif ?>   

	</div>

	<div class="bet-panel">
		<h4>
			<?= $this->renderSection("calculate-title")?>
		</h4>
		
		<div class="pbresult-list-div">
			<label>기간</label>
			<input type="datetime-local" id="calculate-datestart-input-id"  value="<?php echo date('Y-m-d')."T00:00"; ?>">
            <label> ~ </label>
            <input type="datetime-local" id="calculate-dateend-input-id"  value="<?php echo date('Y-m-d')."T23:59"; ?>">
			<button class="pbresult-list-view-but" id="calculate-list-view-but-id">검색</button>
		</div>
		<Table class="calculate-table" id="calculate-table-id">
			<thead>
				<tr>
					<?= $this->renderSection("calculate-table-header")?>			
				</tr>
			</thead>
			<tbody id="calculate-table-tbody-id">

			</tbody>
		</Table>

	</div>


<!--main_navbar.php-main-container-->
</div>
<script> var mGameId = <?=$game_id?>; </script>
<?php if(array_key_exists("app.produce", $_ENV)) :?>
    <script src="<?php echo site_furl('/assets/js/page.js?t='.time());?>"></script>
	<script src="<?php echo site_furl('/assets/js/calculate-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
	<script src="<?php echo site_furl('/assets/js/calculate-script.js?v=1');?>"></script>
<?php endif ?>

<?= $this->renderSection("calculate-script")?>
<?= $this->endSection() ?>