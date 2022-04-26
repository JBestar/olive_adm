<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	<!--Sub Navbar-->
	<div class = "sub-navbar" value=<?= $this->renderSection('calculate-active') ?>>
		<p><i class="glyphicon glyphicon-tag"></i> 정산</p>
		<a href="<?php echo base_url().'bet/allcalculate';?>" class="sub-navbar-a" >전체</a>
		<a href="<?php echo base_url().'bet/pbcalculate';?>" class="sub-navbar-a" >파워볼</a>
		<a href="<?php echo base_url().'bet/pscalculate';?>" class="sub-navbar-a" >파워사다리</a>
		<a href="<?php echo base_url().'bet/bbcalculate';?>" class="sub-navbar-a" >보글볼</a>
		<a href="<?php echo base_url().'bet/bscalculate';?>" class="sub-navbar-a" >보글사다리</a>
		<a href="<?php echo base_url().'bet/cscalculate';?>" class="sub-navbar-a" >에볼루션</a>
		<?php if($_ENV['app.type'] != APPTYPE_2) :?>
		<a href="<?php echo base_url().'bet/slcalculate';?>" class="sub-navbar-a" >슬롯게임</a>
		<?php endif ?>
		<?php if($_ENV['app.type'] == APPTYPE_0 || $_ENV['app.type'] == APPTYPE_1) :?>
		<a href="<?php echo base_url().'bet/fslcalculate';?>" class="sub-navbar-a" >네츄럴슬롯</a>
		<?php elseif($_ENV['app.type'] == APPTYPE_2) :?>
		<a href="<?php echo base_url().'bet/fslcalculate';?>" class="sub-navbar-a" >네츄럴슬롯</a>
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
<?php if(array_key_exists("app.produce", $_ENV)) :?>
    <script src="<?php echo base_url('/assets/js/page.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo base_url('/assets/js/page.js?v=1');?>"></script>
<?php endif ?>
<?= $this->renderSection("calculate-script")?>
<?= $this->endSection() ?>