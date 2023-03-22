<?php

namespace App\Controllers;

use App\Models\ConfSite_Model;

class User extends StdController
{

	/**

	 */
	private function user_edit_page($url, $activePage, $mbFid, $userLevel){
		if (is_login() === false){
			return $this->response->redirect( $_ENV['app.furl'].'/pages/login');
		}
		$confsiteModel = new ConfSite_Model();
		$confsiteModel->readMemConf();

		$empUid = '';
		$bTrans = false;
		$bReturn = false;

		$objMember = null;
		$bPermit = false;
		
		if(!is_numeric($mbFid)){
			$bPermit = false;
		} else if($mbFid == 0){
			$bPermit = true;
		} else {
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);

			$arrMem = $this->modelMember->getMemberByEmpFid($objAdmin->mb_fid, $objAdmin->mb_level,  $objAdmin->mb_level, true, $mbFid);
			if(count($arrMem) > 0)
				$objMember = reset($arrMem);					
			
			if ($objMember != null){
				$objMember->mb_range_min = 0;
				$objMember->mb_range_max = 0;
				$objMember->mb_exc_amount = 0;
				$objMember->mb_exc_check = false;
				$range = explode(":", $objMember->mb_range_ev);
				if(count($range) >= 2){
					$objMember->mb_range_min = intval($range[0]);
					$objMember->mb_range_max = intval($range[1]);
					if(count($range) >= 4){
						$objMember->mb_exc_check = intval($range[2]) == STATE_ACTIVE;
						$objMember->mb_exc_amount = intval($range[3]) ;
					}
				}

				$objEmpMember = $this->modelMember->find($objMember->mb_emp_fid);
				if ($objEmpMember != null)
					$empUid = $objEmpMember->mb_uid;
				$bChild = $objMember->mb_emp_fid == $objAdmin->mb_fid;
				if($_ENV['mem.trans_deny'])				//이송 검사
					$bTrans = false;
				else if($_ENV['mem.trans_lv1'] && !$bChild)
					$bTrans = false;
				else
					$bTrans = true;

				if($_ENV['mem.return_deny'])				//환수 검사
					$bReturn = false;
				else if($_ENV['mem.return_lv1'] && !$bChild)
					$bReturn = false;
				else
					$bReturn = true;

				if(count($_ENV['mem.trans_lvs']) > 0 && !in_array(intval($objAdmin->mb_level), $_ENV['mem.trans_lvs']) ){
					$bTrans = false;
					$bReturn = false;
				}

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
				'trnas_en' => $bTrans, 
				'return_en' => $bReturn,]
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
		$strUid = $this->session->user_id;
		$objAdmin = $this->modelMember->getInfo($strUid);
		$admFid = 0;
		if($objAdmin->mb_level >= LEVEL_MASTER){
			$arrMem = $this->modelMember->getMemberByLevel(LEVEL_ADMIN);
			 if(count($arrMem) > 0){
				$objMember = reset($arrMem);	
				$admFid = $objMember->mb_fid;
			 }
				 
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
			['emp_uid' => $empUid, 'adm_fid' => $admFid]);
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

			$arrMem = $this->modelMember->getMemberByEmpFid($objAdmin->mb_fid, $objAdmin->mb_level,  $objAdmin->mb_level, true, $mbFid);
			if(count($arrMem) > 0)
				$objMember = reset($arrMem);					
			
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