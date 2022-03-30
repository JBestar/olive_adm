<?= $this->extend('header') ?>
<?= $this->section('content') ?>
<!--Sub Navbar-->
<div class="sub-navbar">
	<p><i class="glyphicon glyphicon-user"></i> 회원관리::<?= $this->renderSection('user-list-title') ?></p>
</div>
<!--Site Setting-->
<div class="user-panel">
	
	<?php if($mb_level >= LEVEL_ADMIN) {  ?>
	<div style="min-height:30px;">
		<?= $this->renderSection("user-list-new-reg")?>
	</div>
	<?php } ?>
	<?= $this->renderSection("user-list-table")?>
	<thead>
		<tr>
			<th rowspan='2'>번호</th>
			<th rowspan='2'>아이디</th>
			<th rowspan='2'>총판명</th>
			<th rowspan='2'>현재금액</th>
			<th rowspan='2'>포인트</th>
			<th rowspan='2'>카지노금</th>
			<th rowspan='2'>슬롯머니</th>
			<th colspan='4'>배당율</th>
			<th rowspan='2'>승인</th>
			<th rowspan='2'>게임별설정</th>
		</tr>
		<tr>
			<th>파워볼</th>
			<th>파워사</th>
			<th>에볼</th>
			<th>슬롯</th>
		</tr>
	</thead>
	<tbody>
		<?php $i=1; foreach ($arrMember as $objMember):?>
		<?php if(is_null($objMember->mb_color)) {  ?>
		<tr>
			<?php } else {?>
		<tr bgcolor="<?=$objMember->mb_color?>">
			<?php } ?>
			<td><?=$i++?></td>
			<td><?=$objMember->mb_uid?></td>
			<td><?=$objMember->mb_nickname?></td>
			<td><?=number_format($objMember->mb_money)?>원</td>
			<td><?=number_format($objMember->mb_point)?></td>
			<td><?=number_format($objMember->mb_live_money)?>원</td>
			<td><?=number_format($objMember->mb_slot_money)?>원</td>
			<td>
				<?=$objMember->mb_game_pb_ratio?> % / <?=$objMember->mb_game_pb2_ratio?> %
			</td>
			<td>
				<?=$objMember->mb_game_ps_ratio?> %
			</td>
			<td>
				<?=$objMember->mb_game_cs_ratio?> %
			</td>
			<td>
				<?=$objMember->mb_game_sl_ratio?> %
			</td>
			<td>
				<?php if($objMember->mb_state_active == 1) {  ?>
				<button name="<?=$objMember->mb_fid?>" class="button-active">승인</button>
				<?php } else if($objMember->mb_state_active == 2) {  ?>
				<button name="<?=$objMember->mb_fid?>">대기</button>
				<?php } else {?>
				<button name="<?=$objMember->mb_fid?>">차단</button>
				<?php } ?>
				<a href="<?php echo base_url().$editUrl.'/'.$objMember->mb_fid;?>">수정</a>
				<?php if($mb_level >= LEVEL_ADMIN) {  ?>
				<a href="/board/message_edit/0/<?=$objMember->mb_fid?>">쪽지</a>
				<button name="<?=$objMember->mb_fid?>">삭제</button>
				<?php } ?>
			</td>

			<td style="width:30%;">
				<?php if($objMember->mb_game_pb == 1) {  ?>
				<button name="<?=$objMember->mb_fid?>" class="button-active">파워볼</button>
				<?php } else {?>
				<button name="<?=$objMember->mb_fid?>">파워볼</button>
				<?php } ?>

				<?php if($objMember->mb_game_ps == 1) {  ?>
				<button name="<?=$objMember->mb_fid?>" class="button-active">파워사다리</button>
				<?php } else {?>
				<button name="<?=$objMember->mb_fid?>">파워사다리</button>
				<?php } ?>

				<?php if($objMember->mb_game_bb == 1) {  ?>
				<button name="<?=$objMember->mb_fid?>" class="button-active">보글볼</button>
				<?php } else {?>
				<button name="<?=$objMember->mb_fid?>">보글볼</button>
				<?php } ?>

				<?php if($objMember->mb_game_bs == 1) {  ?>
				<button name="<?=$objMember->mb_fid?>" class="button-active">보글사다리</button>
				<?php } else {?>
				<button name="<?=$objMember->mb_fid?>">보글사다리</button>
				<?php } ?>

				<?php if($objMember->mb_game_cs == 1) {  ?>
				<button name="<?=$objMember->mb_fid?>" class="button-active">카지노</button>
				<?php } else {?>
				<button name="<?=$objMember->mb_fid?>">카지노</button>
				<?php } ?>

				<?php if($objMember->mb_game_sl == 1) {  ?>
				<button name="<?=$objMember->mb_fid?>" class="button-active">슬롯</button>
				<?php } else {?>
				<button name="<?=$objMember->mb_fid?>">슬롯</button>
				<?php } ?>

			</td>
		</tr>

		<?php endforeach;?>
	</tbody>
	</Table>
</div>


<!--main_navbar.php-main-container-->
</div>

<?= $this->renderSection('user-list-script') ?>
<?= $this->endSection() ?>