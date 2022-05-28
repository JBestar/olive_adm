<?php

namespace App\Controllers;

class User extends StdController
{

	/**

	 */
	private function user_edit_page($url, $activePage, $mbFid, $userLevel){
		if (is_login() === false){
			return $this->response->redirect( $_ENV['app.furl'].'/pages/login');
		}

		$objMember = null;
		$bPermit = false;
		
		if(!is_numeric($mbFid)){
			$bPermit = false;
		} else if($mbFid == 0){
			$bPermit = true;
		} else {
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);

			$arrEmp = $this->modelMember->getMemberByEmpFid($objAdmin->mb_fid, $objAdmin->mb_level,  $objAdmin->mb_level, true, $mbFid);
			if(count($arrEmp) > 0)
				$objMember = reset($arrEmp);					
			
			$empUid = '';
			$bChild = false;
			if ($objMember != null){
				$objEmpMember = $this->modelMember->find($objMember->mb_emp_fid);
				if ($objEmpMember != null)
					$empUid = $objEmpMember->mb_uid;
				$bPermit = true;
			}
		}
		
		if (!$bPermit){
			$this->response->redirect( $_ENV['app.furl'].'/pages/nopermit');
		}
		else {
			$this->load_view_page(
				$url, 
				$activePage, 
				$userLevel, 
				[ 'objMember' => $objMember,
				'emp_uid' => $empUid,
				'isChild' => $bChild, ]
			);  
		}
		
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

	function member($empFid){
		if (is_login() === false){
			return $this->response->redirect($_ENV['app.furl'].'/pages/login');
		}
		$objEmp = $this->modelMember->find($empFid);
		$empUid = "";
		if ($objEmp != null){
			$empUid = $objEmp->mb_uid;
		}
		$this->load_view_page(
			'user/member', 
			'user_member', 
			LEVEL_MIN, 
			['emp_uid' => $empUid]);
	}

	public function member_edit($mbFid){
		$this->user_edit_page(
			'user/member_edit', 
			'user_member', 
			$mbFid,
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

	public function member_detail($mbFid){
			
		$objMember = null;
		$bPermit = false;
		
		if(!is_numeric($mbFid)){
			$bPermit = false;
		} else {
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);

			$arrEmp = $this->modelMember->getMemberByEmpFid($objAdmin->mb_fid, $objAdmin->mb_level,  $objAdmin->mb_level, true, $mbFid);
			if(count($arrEmp) > 0)
				$objMember = reset($arrEmp);					
			
			$empUid = '';
			$bChild = false;
			if ($objMember != null) {
				$objEmpMember = $this->modelMember->find($objMember->mb_emp_fid);
				$objMember->mb_emp_uid = "";
				if ($objEmpMember != null)
					$objMember->mb_emp_uid = $objEmpMember->mb_uid;
				$bPermit = true;
			}
		}
		
		if (!$bPermit){
			$this->response->redirect( $_ENV['app.furl'].'/pages/nopermit');
		}
		else {
			$this->load_view_page(
				'user/member_detail', 
				'user_ctrl',
				LEVEL_MIN, 
				[
					'objMember' => $objMember, 
					
			]);  
		}
	}
}