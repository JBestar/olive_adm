<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	<!--Sub Navbar-->
	<div class = "sub-navbar">
		<p><i class="glyphicon glyphicon-info-sign"></i> 게시판::쪽지 문의 관리</p>
	</div>

	<div class="user-panel">
		<div class="pbresult-list-div">
			<a href="<?php echo base_url().'board/message_edit/0/0';?>" class="user-panel-add-a" >쪽지 작성</a>

			<label>아이디</label>
			<input type="text" class="pbresult-text-input" id="message-userid-input-id" >

            <select class="pbresult-game-select" id="message-type-select-id">
				<option value="0">::분류::</option>
				<option value="1">고객문의</option>
				<option value="2">쪽지</option>
			</select>
            <select name="pbresult-number" class="pbresult-number-select" id="message-number-select-id">
				<option value="10">10개</option>
				<option value="20">20개</option>
				<option value="50">50개</option>
				<option value="100">100개</option>
			</select>
			<button class="pbresult-list-view-but" id="message-list-view-but-id">검색</button>  

			
		</div>
		<Table class="user-table" style="margin-top: 15px;">
			<thead>
				<tr>
					<th>번호</th>
					<th>분류</th>
					<th>발송</th>
					<th>제목</th>
					<th>등록일</th>
					<th>발송자</th>
					<th>처리</th>				
				</tr>
			</thead>
			<tbody id="notice-table-id">
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

<script src="<?php echo base_url('assets/js/page.js?v=1');?>"></script>
<script src="<?php echo base_url('assets/js/message-script.js?v=2');?>"></script>
<?= $this->endSection() ?>