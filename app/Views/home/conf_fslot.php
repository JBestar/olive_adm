<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	  <!--Sub Navbar-->
	<div class = "sub-navbar">
		<p><i class="glyphicon glyphicon-play-circle"></i> 기본설정::게임설정</p>
		<?php if(!$npg_deny) :?>
		<a href="<?php echo siteFurl().'home/conf_powerball';?>" class="sub-navbar-a" >파워볼</a>
		<a href="<?php echo siteFurl().'home/conf_powerladder';?>" class="sub-navbar-a " >파워사다리</a>
		<?php endif ?>   
    	<?php if(!$bpg_deny) :?>
		<a href="<?php echo siteFurl().'home/conf_bogleball';?>" class="sub-navbar-a" >보글파워볼</a>
		<a href="<?php echo siteFurl().'home/conf_bogleladder';?>" class="sub-navbar-a " >보글사다리</a>
		<?php endif ?>   
		<a href="<?php echo siteFurl().'home/conf_evol';?>" class="sub-navbar-a " >에볼루션</a>
		<?php if($_ENV['app.type'] != APPTYPE_2) :?>
  		<a href="<?php echo siteFurl().'home/conf_slot_1';?>" class="sub-navbar-a ">정품슬롯</a>
		<?php endif ?>
		<?php if($_ENV['app.type'] == APPTYPE_0 || $_ENV['app.type'] == APPTYPE_1) :?>
  		<a href="<?php echo siteFurl().'home/conf_slot_2';?>" class="sub-navbar-a active">네츄럴슬롯</a>
		  <?php elseif($_ENV['app.type'] == APPTYPE_2) :?>
		<a href="<?php echo siteFurl().'home/conf_slot_2';?>" class="sub-navbar-a active" >네츄럴슬롯</a>
		<?php endif ?>
	</div>
	<!--Site Setting-->
	<div class="confsite-game-panel"  id="<?=$game_id?>">
	<?php if($_ENV['app.type'] == APPTYPE_1) :?>
		<!---->
		<h4 style="font-size:16px; font-weight:bold;"><i class="glyphicon glyphicon-hand-right"></i> 게임별 설정</h4>	
		<div>
			<label>게임명</label>
			<input type="text" class="pbresult-text-input" id="confsite-game-input-id" style="width:150px; margin-right:0;">
			<select name="pbresult-number" class="pbresult-number-select" id="confsite-number-select-id">
				<option value="10">10개</option>
				<option value="20">20개</option>
				<option value="50">50개</option>
				<option value="100">100개</option>
			</select>
			<button class="pbresult-list-view-but" id="confsite-list-view-but-id">검색</button>  
		</div>
		
		<Table class="user-table" style="margin-top: 15px;">
			<thead>
				<tr>
					<th>번호</th>
					<th></th>
					<th>게임업체</th>
					<th>게임명(ko)</th>
					<th>게임명(en)</th>
					<th>게임코드</th>
					<th>로출상태</th>
					<th>운영상태</th>
					<th>API타입</th>
				</tr>
			</thead>
			<tbody  id="confsite-table-data">
				
			</tbody>

		</Table>

		<div class="pbresult-list-page-div">
			
			<div class="pagination" id="list-page" style="display:none">
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
		<?php endif ?>

		<!-- <p class="useredit-seperate-div"></p> -->

		<h4 style="font-size:16px; font-weight:bold;"><i class="glyphicon glyphicon-hand-right"></i> 게임 설정</h4>	
		<div class="confsite-game-text-div">
			<p>유저 게임승인:</p> 
			<input type="checkbox" id="confpb-bet-check-id" style="zoom:120%; margin-top:4px;">
			<label style="font-size:13px; font-weight:normal; padding-top:0px;"> 유저게임승인</label>
		</div>
		<div class="confsite-game-text-div">
			<p>에이젼트 코드:</p> 
			<input type = "text" class="conf-text-input" style="min-width:200px;"  id="confpb-agent-code-id" disabled>
		</div>
		<div class="confsite-game-text-div">
			<p>에이젼트 보유알:</p> 
			<input type = "text" class="conf-text-input" style="min-width:200px;" id="confpb-agent-egg-id"  disabled>
		</div>
		
		<div class="confsite-game-text-div">
			<p>입금 금액:</p> 
			<input type = "text" class="conf-text-input"  style="min-width:200px;" id="confpb-minmoney-input-id"><label> 원 / 회</label>
		</div>
		
		<div class = "confsite-button-group" style="margin-top:20px;">
			<button class="confsite-cancel-button" id="confsite-cancel-btn-id">취소</button>
			<button class="confsite-ok-button"  id="confsite-ok-btn-id">저장</button>
		</div>
		
	</div>

<!--main_navbar.php-->
</div>

<?php if(array_key_exists("app.produce", $_ENV)) :?>
    <script src="<?php echo site_furl('/assets/js/confcs-script.js?t='.time());?>"></script>
    <script src="<?php echo site_furl('/assets/js/page.js?t='.time());?>"></script>
    <script src="<?php echo site_furl('/assets/js/confsl-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/confcs-script.js?v=1');?>"></script>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
    <script src="<?php echo site_furl('/assets/js/confsl-script.js?v=1');?>"></script>
<?php endif ?>
<?= $this->endSection() ?>