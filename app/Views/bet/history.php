<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	<!--Sub Navbar-->
	<div class = "sub-navbar" value=<?= $this->renderSection('history-active') ?>>
		<p><i class="glyphicon glyphicon-book"></i> 배팅내역</p>
		<a href="<?php echo base_url().'bet/pbhistory';?>" class="sub-navbar-a" >파워볼</a>
		<a href="<?php echo base_url().'bet/pshistory';?>" class="sub-navbar-a" >파워사다리</a>
		<a href="<?php echo base_url().'bet/bbhistory';?>" class="sub-navbar-a" >보글볼</a>
		<a href="<?php echo base_url().'bet/bshistory';?>" class="sub-navbar-a" >보글사다리</a>
		<a href="<?php echo base_url().'bet/cshistory';?>" class="sub-navbar-a" >에볼루션</a>
        <a href="<?php echo base_url().'bet/slhistory';?>" class="sub-navbar-a" >슬롯</a>
	</div>

	<div class="bet-panel">
        <h4>
            <?= $this->renderSection('histroy-title') ?>
        </h4>
		
		<div class="pbresult-list-div">
			<label>기간</label>
			<input type="date" id="pbhistory-datestart-input-id"  value="<?php echo date('Y-m-')."01"; ?>">
            <label> ~ </label>
            <input type="date" id="pbhistory-dateend-input-id"  value="<?php echo date('Y-m-d'); ?>">
            <?= $this->renderSection('history-add-round-search') ?>
            <?php if($mb_level >= LEVEL_ADMIN) {  ?>
			<label>추천인</label>
            <input type="text" class="pbresult-text-input" id="pbhistory-empid-input-id" >
			<?php } ?>
			<label>아이디</label>
            <input type="text" class="pbresult-text-input" id="pbhistory-userid-input-id" >

            <select class="pbresult-game-select" id="pbhistory-game-select-id">
				<option value="0">::게임선택::</option>
                <?= $this->renderSection('history_game_options') ?>
			</select>

			<select name="pbresult-number" class="pbresult-number-select" id="pbhistory-number-select-id">
				<option value="10">10개</option>
				<option value="20">20개</option>
				<option value="50">50개</option>
				<option value="100">100개</option>
			</select>
			<button class="pbresult-list-view-but" id="pbhistory-list-view-but-id">검색</button>
		</div>
		<Table class="bet-table">
			<thead>
				<tr>
                    <?= $this->renderSection('history-bet-table-headers') ?>
				</tr>
			</thead>
			<tbody id="pbbet-table-id">

			</tbody>
		</Table>
		<div class="pbresult-list-page-div">
			<?php if($mb_level >= LEVEL_ADMIN) {  ?>
			<p>배팅금: <span id="total-betmoney-id">0</span></p>
			<p>적중금: <span id="total-winmoney-id">0</span></p>
			<p>미적중금: <span id="total-lossmoney-id">0</span></p>
			<p>당첨금: <span id="total-benefit-id">0</span></p>
			<?php } ?>
			<div class="pagination"  id="list-page"  style="display:none">
				<button class="list-page-button" id="page-prev"  onclick="prevPage()"><</button>
				<div class="pagination-div" id="pagination-num">
					<button class="active">1</button>
					<button class="">2</button>						
				</div>
				<button class="list-page-button" id="page-next"  onclick="nextPage()">></button>
			</div>			
	
		</div>
	</div>

	
<!--main_navbar.php-main-container-->
</div>
<script src="<?php echo base_url('assets/js/page.js?v=1');?>"></script>
<?= $this->renderSection('history_script') ?>
<?= $this->endSection() ?>