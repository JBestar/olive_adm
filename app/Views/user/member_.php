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
		<p><i class="glyphicon glyphicon-user"></i> 회원관리::회원</p>
	</div>
	<!--Site Setting-->
	<div class="user-panel">	
		<div>
			<?php if($mb_level >= LEVEL_ADMIN) {  ?>
			<a href="<?php echo base_url().'user/member_edit/0';?>" class="user-panel-add-a" >회원 등록</a>
			<?php } ?>
			<?php if ($mb_level >= LEVEL_ADMIN) {?>	
			<label>추천인</label>
            <input type="text" class="pbresult-text-input" id="userpanel-empid-input-id" value= "<?=$emp_uid ?>">
			<?php } ?>
			<label>아이디</label>
            <input type="text" class="pbresult-text-input" id="userpanel-userid-input-id" >
			
			<select name="pbresult-level" class="pbresult-number-select" id="userpanel-level-select-id">
				<option value="0">::레벨::</option>
				<option value="1"> 1레벨 </option>
				<option value="2"> 2레벨 </option>
				<option value="3"> 3레벨 </option>
				<option value="4"> 4레벨 </option>
				<option value="5"> 5레벨 </option>
				<option value="6"> 6레벨 </option>
				<option value="7"> 7레벨 </option>
				<option value="8"> 8레벨 </option>
				<option value="9"> 9레벨 </option>
				<option value="10"> 10레벨 </option>
			</select>
			<select name="pbresult-number" class="pbresult-number-select" id="userpanel-number-select-id">
				<option value="10">10개</option>
				<option value="20">20개</option>
				<option value="50">50개</option>
				<option value="100">100개</option>
			</select>
			<button class="pbresult-list-view-but" id="userpanel-list-view-but-id">검색</button>  
		</div>		
		<Table class="user-table" style="margin-top: 15px;">
			<thead>
				<tr>
					<th>번호</th>
					<th>추천인</th>
					<th>아이디</th>
					<th>닉네임</th>
					<th>레벨</th>
					<th>현재금액</th>
					<th>포인트</th>
					<th>게임사별<br>보유알</th>
					<th>배당율</th>
					<th>승인</th>
					<th>게임별설정</th>
				</tr>
			</thead>
			<tbody  id="user-member-table-id">
				
			</tbody>

		</Table>

		<div class="pbresult-list-page-div">
			
			<div class="pagination" id="list-page" style="display:none">
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

<script src="<?php echo base_url('assets/js/page.js');?>"></script>
<script src="<?php echo base_url('assets/js/member-script.js?v=4');?>"></script>
<?= $this->endSection() ?>