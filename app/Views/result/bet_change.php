<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	<!--Sub Navbar-->
	<div class = "sub-navbar" value="<?= $this->renderSection('bet-change-title')?>">
		<p><i class="glyphicon glyphicon-tag"></i> 적중특례</p>
		<a href="<?php echo base_url().'result/pbbetchange/0/0';?>" class="sub-navbar-a" >파워볼</a>
		<a href="<?php echo base_url().'result/psbetchange/0/0';?>" class="sub-navbar-a" >파워사다리</a>
		<a href="<?php echo base_url().'result/bbbetchange/0/0';?>" class="sub-navbar-a" >보글볼</a>
		<a href="<?php echo base_url().'result/bsbetchange/0/0';?>" class="sub-navbar-a" >보글사다리</a>
	</div>


	<div class="bet-panel">
		<h4><?= $this->renderSection('bet-change-title')?> 적중특례</h4>
			<div class="pbresult-list-div">
				<?php if($strDate > 0) {  ?>
				<input type="date" id="pbbetchange-date-input-id"  value="<?=$strDate?>">
				<?php } else {?>
				<input type="date" id="pbbetchange-date-input-id">
				<?php } ?>
				<label> 일 </label>
				<?php if($strRoundNo > 0) {  ?>
				<input type="number" style="width:100px;" id="pbbetchange-round-input-id" value="<?=$strRoundNo?>">
	    		<?php } else {?>
				<input type="number" style="width:100px;" id="pbbetchange-round-input-id" >    		
				<?php } ?>
		        <label>회차</label>
		        <button class="list-view-but" id="pbbetchange-view-but-id">베팅보기</button>
		        <button class="list-view-but" id="pbbetchange-process-but-id">회차 결과처리</button>
		        <button class="list-view-but" id="pbbetchange-ignore-but-id">회차 무효처리</button>
    		</div>
			<Table class="bet-table">
				<thead>
					<tr>
                        <?= $this->renderSection('bet-change-table-header')?>
					</tr>
				</thead>
				<tbody id="pbbetchange-table-id">
				<tr><td colspan='13'>자료가 없습니다.</td></tr>
				</tbody>
			</Table>
		</div>


<!--main_navbar.php-main-container-->
</div>

<script src="<?php echo base_url('assets/js/page.js');?>"></script>
<?= $this->renderSection('bet-change-script')?>
<?= $this->endSection() ?>