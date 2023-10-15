<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	<style>
		.user-table a.link-member {
			color: blue;
			border: none;
			background-color: transparent;
			box-shadow: none;
			font-weight: lighter;
		}

		.user-table a.link-member:hover {
			text-decoration: underline;
			color: blue;
		}
	</style>
	<!--Sub Navbar-->
	<div class = "sub-navbar">
		<p><i class="glyphicon glyphicon-user"></i> 회원관리::실시간접속</p>
		<a href="<?php echo siteFurl().'user/member_connect';?>" class="sub-navbar-a active" >실시간접속</a>
		<a href="<?php echo siteFurl().'user/member_log';?>" class="sub-navbar-a" >접속이력</a>
		<a href="<?php echo siteFurl().'user/member_try';?>" class="sub-navbar-a" >로그인이력</a>
	</div>
	<!--Site Setting-->
	<div class="user-panel">	
		<div>
			<?php if(array_key_exists('app.ebal', $_ENV) && $_ENV['app.ebal'] > 0 ) :?>
				<label>접속</label>
				<select name="pbresult-number" class="pbresult-number-select" id="userpanel-type-select-id" style="margin-left:0px">
					<option value="-1">전체</option>
					<option value="0">사이트</option>
					<option value="1">앱</option>
				</select>
			<?php endif ?>

			<label>아이디</label>
            <input type="text" class="pbresult-text-input" id="userpanel-userid-input-id" >
			
			<select name="pbresult-number" class="pbresult-number-select" id="userpanel-number-select-id">
				<?php foreach($select_nums as $num=>$select):?>
					<option value="<?=$num?>" <?=$select?>><?=$num?>개</option>
				<?php endforeach?>
			</select>
			<button class="pbresult-list-view-but" id="userpanel-list-view-but-id">검색</button>  
		</div>		
		<Table class="user-table" style="margin-top: 15px;">
			<thead>
				<tr>
					<th>번호</th>
					<th>아이디</th>
					<th>닉네임</th>
					<th>보유금</th>
					<th>포인트</th>
					<th>접속</th>
					<th>접속시간</th>
					<th>업뎃시간</th>
					<th>강제아웃</th>
				</tr>
			</thead>
			<tbody  id="user-member-table-id">
				
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

	</div>

	
<!--main_navbar.php-main-container-->
</div>
<?php if(array_key_exists("app.produce", $_ENV)) :?>
    <script src="<?php echo site_furl('/assets/js/page.js?t='.time());?>"></script>
    <script src="<?php echo site_furl('/assets/js/member_connect-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
    <script src="<?php echo site_furl('/assets/js/member_connect-script.js?v=1');?>"></script>
<?php endif ?>
<?= $this->endSection() ?>