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
				$objMember->mb_point = floor($objMember->mb_point);
				if($_ENV['app.ebal'] > 0 && $objAdmin->mb_level >= LEVEL_ADMIN){
					$objMember->mb_range_min = 0;
					$objMember->mb_range_max = 0;
					$info = explode(":", $objMember->mb_range_ev);
					if(count($info) >= 2){
						$objMember->mb_range_min = intval($info[0]);
						$objMember->mb_range_max = intval($info[1]);
					}
					$objMember->mb_press_active = false;
					$objMember->mb_press_amount = 0;
					$info = explode(":", $objMember->mb_press_ev);
					if(count($info) >= 2){
						$objMember->mb_press_active = intval($info[0]) == STATE_ACTIVE;
						$objMember->mb_press_amount = intval($info[1]) ;
					}
					$objMember->mb_follow_active = false;
					$objMember->mb_follow_id = "";
					$info = explode(":", $objMember->mb_follow_ev);
					if(count($info) >= 2){
						$objMember->mb_follow_active = intval($info[0]);
						$objMember->mb_follow_id = trim($info[1]);
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
			// $this->response->redirect( $_ENV['app.furl'].'/pages/nopermit');
			print "<script language=javascript> alert('존재하지 않는 회원입니다.'); self.close(); </script>";
		}
		else {
			$follow_en = false;
			$press_en = 0;
			$confFollow = $confsiteModel->getConf(CONF_EVOLFOLLOW);
			if($confFollow != null){
				$follow_en = intval($confFollow->conf_active) == STATE_ACTIVE ;
				$info = explode('#', $confFollow->conf_idx);
				if(count($info) >= 1){
					$press_en = intval($info[0]);
				}
			}


			$this->load_view_page(
				$url, 
				$activePage, 
				$userLevel, 
				[ 'objMember' => $objMember,
					'emp_uid' => $empUid,
					'trnas_en' => $bTrans, 
					'return_en' => $bReturn,
					'follow_en' => $follow_en,
					'press_en' => $press_en,
				]
			);  
		}
		
	}

	public function index()
	{
		if(is_login())
		{
			if(array_key_exists('app.hold', $_ENV) && $_ENV['app.hold'] == 1)
				$this->response->redirect($_ENV['app.furl'].'/user/member_list/0');
			else 
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

	function member_list($empFid){
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
			'user/member_list', 
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

	public function member_uid($mbUid){
		
		$objMember = $this->modelMember->getInfo($mbUid);
		if(is_null($objMember))
			print "<script language=javascript> alert('존재하지 않는 회원입니다.'); self.close(); </script>";
		else if($objMember->mb_level > LEVEL_ADMIN)
			print "<script language=javascript> alert('접근권한이 없습니다.'); self.close(); </script>";
		else 
			$this->user_edit_page(
				'user/member_edit', 
				'user_member', 
				$objMember->mb_fid,
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

	function member_class($strEmpFid){
		if (is_login() === false){
			return $this->response->redirect($_ENV['app.furl'].'/pages/login');
		}
		$objEmp = $this->modelMember->find($strEmpFid);
		$strEmpUid = "";
		if ($objEmp != null){
			$strEmpUid = $objEmp->mb_uid;
		}
		$this->load_view_page(
			'user/member_class', 
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
			// $this->response->redirect( $_ENV['app.furl'].'/pages/nopermit');
			print "<script language=javascript> alert('존재하지 않는 회원입니다.'); self.close(); </script>";
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