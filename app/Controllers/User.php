<?php

namespace App\Controllers;
use App\Models\Member_Model;

class User extends StdController
{

	/**

	 */
	private function user_edit_page($url, $activePage, $memberFid, $employeeLevel, $userLevel){
		if (is_login() === false){
			return $this->response->redirect( $_ENV['app.furl'].'/pages/login');
		}
		
		$memberModel = new Member_Model();
		$strUid = $this->session->user_id;
		$objAdmin = $memberModel->getInfo($strUid);
		$objMember = null;
		if($memberFid > 0)
		{
			$objMember = $memberModel->getMemberByFid($memberFid, true);					
		}
		$empUid = '';
		$bChild = false;
		if ($objMember != null){
			$arrEmpMember = $memberModel->find($objMember->mb_emp_fid);
			if ($arrEmpMember != null)
				$empUid = $arrEmpMember['mb_uid'];
			$bChild = $objMember->mb_emp_fid == $objAdmin->mb_fid;
			
		}
			
		$this->load_view_page(
			$url, 
			$activePage, 
			$userLevel, 
			[
				'objMember' => $objMember, 
				'emp_uid' => $empUid,
				'isChild' => $bChild,
		]);	
		
	}
	public function index()
	{
		if(is_login())
		{
			$this->response->redirect($_ENV['app.furl'].'/user/member/0');
		}
		else {
			$this->response->redirect($_ENV['app.furl'].'/pages/login');
		}	
		
	}

	function member_log(){
		
		$this->load_view_page(
			'user/member_log', 
			'user_log', 
			LEVEL_ADMIN);
	}

	function member_connect(){
		
		$this->load_view_page(
			'user/member_connect', 
			'user_log', 
			LEVEL_ADMIN);
	}
	
	function member_block(){
		
		$this->load_view_page(
			'user/member_block', 
			'user_block', 
			LEVEL_ADMIN);
	}

	function member($strEmpFid){
		if (is_login() === false){
			return $this->response->redirect($_ENV['app.furl'].'/pages/login');
		}
		$memberModel = new Member_Model();
		$objEmp = $memberModel->find($strEmpFid);
		$strEmpUid = "";
		if ($objEmp != null){
			$strEmpUid = $objEmp['mb_uid'];
		}
		$this->load_view_page(
			'user/member', 
			'user_member', 
			LEVEL_MIN, 
			['emp_uid' => $strEmpUid]);
	}

	public function member_edit($strMemberFid){
		$this->user_edit_page(
			'user/member_edit', 
			'user_member', 
			$strMemberFid, 
			LEVEL_MIN,
			LEVEL_MIN);		
	}
}