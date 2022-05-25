<?php

namespace App\Controllers;

class User extends StdController
{

	/**

	 */
	private function user_edit_page($url, $activePage, $memberFid, $employeeLevel, $userLevel){
		if (is_login() === false){
			return $this->response->redirect( $_ENV['app.furl'].'/pages/login');
		}
		
		$strUid = $this->session->user_id;
		$objAdmin = $this->modelMember->getInfo($strUid);
		$objMember = null;
		if($memberFid > 0)
		{
			$objMember = $this->modelMember->getInfoByFid($memberFid, true);					
		}
		$empUid = '';
		$bChild = false;
		if ($objMember != null){
			$objEmpMember = $this->modelMember->find($objMember->mb_emp_fid);
			if ($objEmpMember != null)
				$empUid = $objEmpMember->mb_uid;
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
		$objEmp = $this->modelMember->find($strEmpFid);
		$strEmpUid = "";
		if ($objEmp != null){
			$strEmpUid = $objEmp->mb_uid;
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
	
	function member_ctrl($strEmpFid){
		if (is_login() === false){
			return $this->response->redirect($_ENV['app.furl'].'/pages/login');
		}
		$objEmp = $this->modelMember->find($strEmpFid);
		$strEmpUid = "";
		if ($objEmp != null){
			$strEmpUid = $objEmp->mb_uid;
		}
		$this->load_view_page(
			'user/member_ctrl', 
			'user_ctrl', 
			LEVEL_ADMIN, 
			['emp_uid' => $strEmpUid]);
	}
}