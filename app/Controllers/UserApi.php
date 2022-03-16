<?php namespace App\Controllers;

use App\Models\BbBet_model;
use App\Models\BsBet_model;
use App\Models\Member_Model;
use App\Models\ConfSite_Model;
use App\Models\Charge_Model;
use App\Models\ConfGame_model;
use App\Models\Exchange_model;
use App\Models\KsBet_model;
use App\Models\Notice_Model;
use App\Models\PbBet_model;
use App\Models\PsBet_model;
use stdClass;

class UserApi extends BaseController{
    public function index()
	{
				
		if(is_login())
		{
			$this->response->redirect( base_url());
		}
		else {
			$this->response->redirect( base_url().'pages/login');
		}	
		
	}

	//사용자 정보 변경  
	public function addmember(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);

		if(is_login())
		{
			$bPermit = false;
			$memberModel = new Member_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			
			//현재 가입한 유저가 요청한 유저보다 레벨이 높은 경우에 변경이 가능하다.
			if(!is_null($objUser))
			{				
				if($objUser->mb_level >= LEVEL_ADMIN )
					$bPermit = true;					
			}
			if($bPermit){
				$strError = "";

				$iResult = $memberModel->register($arrData, $strError);
				
				if($iResult == 1)
					$arrResult['status'] = "success";
				else if($iResult == 4) {
					$arrResult['status'] = "pb_ratio_error";
					$arrResult['error'] = $strError;
				} else if($iResult == 5) {
					$arrResult['status'] = "ps_ratio_error";
					$arrResult['error'] = $strError;
				} else if($iResult == 6) {
					$arrResult['status'] = "ks_ratio_error";
					$arrResult['error'] = $strError;				
				} else if($iResult == 7) {
					$arrResult['status'] = "ev_ratio_error";
					$arrResult['error'] = $strError;				
				} else if($iResult == 9) {
					$arrResult['status'] = "bb_ratio_error";
					$arrResult['error'] = $strError;				
				} else if($iResult == 10) {
					$arrResult['status'] = "bs_ratio_error";
					$arrResult['error'] = $strError;				
				} else {				
					$arrResult['status'] = "fail";
					$arrResult['error'] = $iResult;	
				}

			} else $arrResult['status'] = "nopermit";
		}
		else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	
	}

	//사용자 정보 수정  
	public function modifymember(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);

		if(is_login())
		{
			$bPermit = false;
			$memberModel = new Member_Model();
			$strUid = $this->session->username;
			$objAdmin = $memberModel->getInfo($strUid);
			$objReqUser = $memberModel->getInfoByFid($arrData['mb_fid']);
			
			//현재 가입한 유저가 요청한 유저보다 레벨이 높은 경우에 변경이 가능하다.
			if(!is_null($objAdmin) && !is_null($objReqUser))
			{				
				if($objAdmin->mb_level > $objReqUser->mb_level )
					$bPermit = true;					
			}
			if($bPermit){
				$strError = "";
				$iResult = 0;
				if($objAdmin->mb_level >= LEVEL_ADMIN)
					$iResult = $memberModel->modifyMember($arrData, $strError);
				else
					$iResult = $memberModel->modifyMemberRatio($arrData, $strError);

				if($iResult == 1)
					$arrResult['status'] = "success";
				else if($iResult == 4) {
					$arrResult['status'] = "pb_ratio_error";
					$arrResult['error'] = $strError;
				} else if($iResult == 5) {
					$arrResult['status'] = "ps_ratio_error";
					$arrResult['error'] = $strError;
				} else if($iResult == 6) {
					$arrResult['status'] = "ks_ratio_error";
					$arrResult['error'] = $strError;				
				} else if($iResult == 7) {
					$arrResult['status'] = "ev_ratio_error";
					$arrResult['error'] = $strError;				
				} else if($iResult == 9) {
					$arrResult['status'] = "bb_ratio_error";
					$arrResult['error'] = $strError;				
				} else if($iResult == 10) {
					$arrResult['status'] = "bs_ratio_error";
					$arrResult['error'] = $strError;				
				} else {
					$arrResult['status'] = "fail";
					$arrResult['error'] = $iResult;	
				}

			} else $arrResult['status'] = "nopermit";
		}
		else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	
	}

	//사용자 정보 변경  
	public function updatemember(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);

		if(is_login())
		{
			$bPermit = false;
			$memberModel = new Member_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$objReqUser = $memberModel->getInfoByFid($arrData['mb_fid']);
			
			//현재 가입한 유저가 요청한 유저보다 레벨이 높은 경우에 변경이 가능하다.
			if(!is_null($objUser) && !is_null($objReqUser))
			{				
				if($objUser->mb_level > $objReqUser->mb_level )
					$bPermit = true;					
			}
			if($bPermit){
				$bResult = $memberModel->updateMemberByFid($arrData);
				
				if($bResult)
					$arrResult['status'] = "success";
				else $arrResult['status'] = "fail";
			} else $arrResult['status'] = "nopermit";
		}
		else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	
	}

	//사용자 삭제  
	public function deletemember(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);

		if(is_login())
		{
			$bPermit = false;
			$memberModel = new Member_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$objReqUser = $memberModel->getInfoByFid($arrData['mb_fid']);
			
			//현재 가입한 유저가 요청한 유저보다 레벨이 높은 경우에 삭제가 가능하다.
			if(!is_null($objUser) && !is_null($objReqUser))
			{				
				if($objUser->mb_level >= LEVEL_ADMIN )
					$bPermit = true;					
			}
			if($bPermit){
				$bResult = $memberModel->deleteMemberByFid($arrData);
				
				if($bResult)
					$arrResult['status'] = "success";
				else $arrResult['status'] = "fail";
			} else $arrResult['status'] = "nopermit";
		}
		else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	
	}

	//회원정보  대기 승인 라이브게임아이디 생성한다.
	public function wait_permit(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);
		
		if(is_login())
		{
			$memberModel = new Member_Model();

			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$objReqUser = $memberModel->getMemberByFid($arrData['mb_fid']);
			
			$bPermit = false;
			$bResult = false;
			$iCreated = 0;
			//현재 가입한 유저가 요청한 유저보다 레벨이 높은 경우에 변경이 가능하다.
			if(!is_null($objUser) && !is_null($objReqUser))
			{				
				if($objReqUser->mb_state_active != 2){
					$bPermit = false;
				}
				//if($objUser['mb_level'] >= LEVEL_ADMIN )
				else if($objUser->mb_level > $objReqUser->mb_level )
					$bPermit = true;					
			}

			if($bPermit){
				$iCreated == 1;
				$bResult = $memberModel->updateMemberByFid($arrData);

				if($bResult)
					$arrResult['status'] = "success";
				else if($iCreated == 0)
					$arrResult['status'] = "usererror";
				else $arrResult['status'] = "fail";
			} else $arrResult['status'] = "nopermit";
			
		}
		else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	
	}


	
	//사용자정보
	public function assets(){ 
		
		if(is_login())
		{
			
			$strUid = $this->session->username;
			//model
			$memberModel = new Member_Model();

			$objUser = $memberModel->getInfo($strUid);
			
			$objResult = new \StdClass; 
			if($objUser != NULL)
            {	
				$objResult->data = $objUser;
				$objResult->status = "success";
			}
			else
				$objResult->status = "fail";

			echo json_encode($objResult);
		}
		else {
			$arrResult['status'] = "logout";
			echo json_encode($arrResult);	
		}
	}
	//가입한 직원의 정보: 머니, 배당율
	public function empinfo(){ 
		
		if(is_login())
		{
			
			$strUid = $this->session->username;
			//model
			$memberModel = new Member_Model();
			$chargeModel = new Charge_Model();
			$exchangeModel = new Exchange_Model();
			$noticeModel = new Notice_Model();
			$confsiteModel = new ConfSite_Model();

			$objUser = $memberModel->getInfo($strUid);
			
			$objResult = new \StdClass; 
			if($objUser->mb_level >= LEVEL_ADMIN){	
	 			
				$arrEmpInfo = $memberModel->getEmpUserCnt($objUser);
				$arrEmpInfo['waitcharge'] =  $chargeModel->getWaitCnt();
				$arrEmpInfo['waitexchange'] =  $exchangeModel->getWaitCnt();
				$arrEmpInfo['newmessage'] =  $noticeModel->getNewMessageCnt();
				$objAdminMoney = $memberModel->calcAdminMoney();
				$arrEmpInfo['emp_money'] = $objAdminMoney->emp_money;
				$arrEmpInfo['emp_point'] = $objAdminMoney->emp_point;

				//date_default_timezone_set('Asia/Seoul');
        		$arrReqData['start'] = date("Y-m-d"); 
        		$arrReqData['end'] = $arrReqData['start']; 
				$arrEmpInfo['emp_money_charge'] = $chargeModel->calcAdminCharge($arrReqData);
				$arrEmpInfo['emp_money_exchange'] = $exchangeModel->calcAdminExchange($arrReqData);
				
				$arrSoundInfo = $confsiteModel->getSoundConf();

				$objResult->data = $arrEmpInfo;
				$objResult->sound = $arrSoundInfo;
				$objResult->status = "success";
			}
			else
				$objResult->status = "fail";

			echo json_encode($objResult);
		}
		else {
			$arrResult['status'] = "logout";
			echo json_encode($arrResult);	
		}
	}

	//게임별 당일 총배팅금, 총당첨금, 누르기금
	public function empbetinfo(){ 
		
		if(is_login())
		{
			
			$strUid = $this->session->username;
			//model
			$memberModel = new Member_Model();
            $confgameModel = new ConfGame_model();
            $pbbetModel = new PbBet_model();       
            $psbetModel = new PsBet_model();
            $ksbetModel = new KsBet_model();
            $bbbetModel = new BbBet_model();
            $bsbetModel = new BsBet_model();
			
			$objUser = $memberModel->getInfo($strUid);
			
			$objResult = new \StdClass; 
			if($objUser->mb_level >= LEVEL_ADMIN){	
	 			//date_default_timezone_set('Asia/Seoul');
	 			$strDate = date( 'Y-m-d');
	 			$arrReqData['start'] = $strDate." 00:00:00";
	 			$arrReqData['end'] = $strDate." 23:59:59";

	 			$objConfPb = $confgameModel->getByIndex(GAME_POWER_BALL);
				$arrSumData = $pbbetModel->getBetSumByDay($arrReqData, $objConfPb);

				$objConfPs = $confgameModel->getByIndex(GAME_POWER_LADDER);
				$arrSumData[2] = $psbetModel->getBetSumByDay($arrReqData, $objConfPs);
				
				$objConfKs = $confgameModel->getByIndex(GAME_KENO_LADDER);
				$arrSumData[3] = $ksbetModel->getBetSumByDay($arrReqData, $objConfKs);
				 
				$objConfBb = $confgameModel->getByIndex(GAME_BOGLE_BALL);
				$arrSum = $bbbetModel->getBetSumByDay($arrReqData, $objConfBb);
				$arrSumData[4] = $arrSum[0];
				$arrSumData[5] = $arrSum[1];

				$objConfBs = $confgameModel->getByIndex(GAME_BOGLE_LADDER);
				$arrSumData[6] = $bsbetModel->getBetSumByDay($arrReqData, $objConfBs);
				 
				$objResult->data = $arrSumData;
				$objResult->status = "success";
			}
			else
				$objResult->status = "fail";

			echo json_encode($objResult);
		}
		else {
			$arrResult['status'] = "logout";
			echo json_encode($arrResult);	
		}
	}

	public function getmembers(){
        
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);
        
		if(is_login())
		{
			//model
			$memberModel = new Member_Model();

			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			
			$objResult = new \StdClass;
			if($objUser->mb_level > $arrData['mb_level'] ){
				
				$arrMember = $memberModel->searchMemberByEmpFid($objUser->mb_fid, $objUser->mb_level, $arrData);
				if(is_null($arrMember))
					$arrMember = array();
				foreach($arrMember as $objMember){
					$objMember->mb_empname = $memberModel->getFullName($objMember);
				}
				
				$objResult->status = "success";
				$objResult->level = $objUser->mb_level;
				$objResult->data = $arrMember;
			}
			else
				$objResult->status = "fail";

			echo json_encode($objResult);
		} 
		else {
			$arrResult['status'] = "logout";
			echo json_encode($arrResult);	
		}

	} 



	public function getmembercnt(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);

		if(is_login())
		{
			//model
			$memberModel = new Member_Model();

			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			
			$objResult = new \StdClass;
			if($objUser->mb_level > $arrData['mb_level'] ){
				$objCount = $memberModel->searchCountByEmpFid($objUser->mb_fid, $objUser->mb_level, $arrData);
				
				$objResult->status = "success";
				$objResult->data = $objCount;
			}
			else
				$objResult->status = "fail";

			echo json_encode($objResult);
		} 
		else {
			$arrResult['status'] = "logout";
			echo json_encode($arrResult);	
		}

	} 



	public function allmember(){
		
		if(is_login())
		{
			//model
			$memberModel = new Member_Model();

			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			
			$objResult = new \StdClass;
			
			if($objUser->mb_level >= LEVEL_ADMIN ){
				$arrMember = $memberModel->getMemberByLevel(LEVEL_ADMIN, true);

				if(is_null($arrMember))
					$arrMember = array();
				
				$objResult->status = "success";
				$objResult->data = $arrMember;
			}
			else
				$objResult->status = "fail";

			echo json_encode($objResult);
		} 
		else {
			$arrResult['status'] = "logout";
			echo json_encode($arrResult);	
		}
	} 
}