<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	<!--Sub Navbar-->
	<div class = "sub-navbar" value=<?= $this->renderSection('history-active') ?>>
		<p><i class="glyphicon glyphicon-book"></i> 배팅내역</p>
		<?php if(!$npg_deny) :?>
			<a href="<?php echo site_furl('bet/pbhistory');?>" class="sub-navbar-a" >파워볼</a>
			<a href="<?php echo site_furl('bet/pshistory');?>" class="sub-navbar-a" >파워사다리</a>
		<?php endif ?>   
    	<?php if(!$bpg_deny) :?>
			<a href="<?php echo site_furl('bet/bbhistory');?>" class="sub-navbar-a" >보글볼</a>
			<a href="<?php echo site_furl('bet/bshistory');?>" class="sub-navbar-a" >보글사다리</a>
   		<?php endif ?>   
		<?php if(!$cas_deny) :?>
			<a href="<?php echo site_furl('bet/cshistory');?>" class="sub-navbar-a" >카지노</a>
		<?php endif ?>
		<?php if(!$slot_deny) :?>
			<?php if($mb_level >= LEVEL_ADMIN) :  ?>
				<?php if($_ENV['app.type'] != APPTYPE_2) :?>
					<a href="<?php echo site_furl('bet/xslhistory');?>" class="sub-navbar-a" >정품슬롯</a>
				<?php endif ?>
				<a href="<?php echo site_furl('bet/fslhistory');?>" class="sub-navbar-a" >네츄럴슬롯</a>
				<?php if($_ENV['app.type'] != APPTYPE_2) :?>
					<a href="<?php echo site_furl('bet/slhistory');?>" class="sub-navbar-a" >슬롯</a>
				<?php endif ?>
			<?php else: ?>
				<a href="<?php echo site_furl('bet/slhistory');?>" class="sub-navbar-a" >슬롯</a>
			<?php endif ?>
		<?php endif ?>   
	</div>

	<div class="bet-panel">
        <h4>
            <?= $this->renderSection('histroy-title') ?>
        </h4>
		
		<div class="pbresult-list-div">
			<label>기간</label>
			<input type="datetime-local" id="pbhistory-datestart-input-id"  value="<?php echo date('Y-m')."-01T00:00"; ?>">
            <label> ~ </label>
            <input type="datetime-local" id="pbhistory-dateend-input-id"  value="<?php echo date('Y-m-d')."T23:59"; ?>">
            <?= $this->renderSection('history-add-round-search') ?>
            <?php if($mb_level >= LEVEL_ADMIN) {  ?>
			<label>추천인</label>
            <input type="text" class="pbresult-text-input" id="pbhistory-empid-input-id" >
			<?php } ?>
			<label>아이디</label>
            <input type="text" class="pbresult-text-input" id="pbhistory-userid-input-id" >

            <select class="pbresult-game-select" id="pbhistory-game-select-id">
                <?= $this->renderSection('history_game_options') ?>
			</select>

			<select name="pbresult-number" class="pbresult-number-select" id="pbhistory-number-select-id" style="width:70px;">
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
				<button class="list-page-button" id="page-first"  onclick="firstPage()"><<</button>
				<button class="list-page-button" id="page-prev"  onclick="prevPage()"><</button>
				<div class="pagination-div" id="pagination-num">
					<button class="active">1</button>
					<button class="">2</button>						
				</div>
				<button class="list-page-button" id="page-next"  onclick="nextPage()">></button>
				<button class="list-page-button" id="page-last"  onclick="lastPage()">>></button>
			</div>			
	
		</div>
	</div>

	
<!--main_navbar.php-main-container-->
</div>
<?php if(array_key_exists("app.produce", $_ENV)) :?>
    <script src="<?php echo site_furl('/assets/js/page.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
<?php endif ?>

<?= $this->renderSection('history_script') ?>
<?= $this->endSection() ?>