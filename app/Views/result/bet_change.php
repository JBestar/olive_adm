<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	<!--Sub Navbar-->
	<div class = "sub-navbar" value="<?= $this->renderSection('bet-change-title')?>">
		<p><i class="glyphicon glyphicon-tag"></i> 적중특례</p>
		<?php if($hpg_enable) :?>
			<a href="<?php echo siteFurl().'result/pbbetchange/0/0';?>" class="sub-navbar-a" >해피볼</a>
			<!-- <a href="<?php echo siteFurl().'result/psbetchange/0/0';?>" class="sub-navbar-a" >파워사다리</a> -->
		<?php endif ?>   
		<?php if(!$bpg_deny) :?>
			<a href="<?php echo siteFurl().'result/bbbetchange/0/0';?>" class="sub-navbar-a" >보글볼</a>
			<a href="<?php echo siteFurl().'result/bsbetchange/0/0';?>" class="sub-navbar-a" >보글사다리</a>
		<?php endif ?>   
		<?php if($eos5_enable) :?>
			<a href="<?php echo siteFurl().'result/e5betchange/0/0';?>" class="sub-navbar-a" >EOS5분</a>
		<?php endif ?>   
		<?php if($eos3_enable) :?>
			<a href="<?php echo siteFurl().'result/e3betchange/0/0';?>" class="sub-navbar-a" >EOS3분</a>
		<?php endif ?>   
		<?php if($coin5_enable) :?>
			<a href="<?php echo siteFurl().'result/c5betchange/0/0';?>" class="sub-navbar-a" >코인5분</a>
		<?php endif ?>   
		<?php if($coin3_enable) :?>
			<a href="<?php echo siteFurl().'result/c3betchange/0/0';?>" class="sub-navbar-a" >코인3분</a>
		<?php endif ?>   
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
		        <button class="list-view-but" id="pbbetchange-view-but-id">배팅보기</button>
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
<script> var mGameId = <?=$game_id?>; </script>
<?php if(array_key_exists("app.produce", $_ENV)) :?>
    <script src="<?php echo site_furl('/assets/js/page.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
<?php endif ?>
<?= $this->renderSection('bet-change-script')?>
<?= $this->endSection() ?>