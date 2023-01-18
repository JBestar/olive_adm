<?php namespace App\Controllers;

use App\Models\Notice_Model;
use App\Models\Charge_Model;
use App\Models\Clean_Model;
use App\Models\ConfGame_Model;
use App\Models\Exchange_Model;
use App\Models\MoneyHistory_Model;
use App\Models\Transfer_Model;
use App\Models\ConfSite_Model;
use App\Models\CsBet_Model;
use App\Models\SlBet_Model;
use App\Models\SlotGame_Model;
use App\Models\SlotPrd_Model;
use App\Models\SessLog_Model;
use App\Models\Block_Model;
use App\Models\Reward_Model;
use App\Models\CasPrd_Model;
use App\Models\CasGame_Model;
use App\Models\CasRoom_Model;
use App\Models\SessTry_Model;
use App\Models\EbalBet_Model;
use App\Models\Ebalance_Model;
use App\Models\Eorder_Model;

use App\Libraries\ApiCas_Lib;
use App\Libraries\ApiSlot_Lib;
use App\Libraries\ApiFslot_Lib;


class Api extends BaseController{
    public function index()
	{				
		if(is_login())
		{
			$this->response->redirect( $_ENV['app.furl']);
		}
		else {
			$this->response->redirect( $_ENV['app.furl'].'/pages/login');
		}	
	}
	/*
    //관리자 로그인
	public function login(){ 
		$jsonData = $_REQUEST['json_'];
		$arrLoginData = json_decode($jsonData, true);
		$ip = $this->request->getIPAddress();
		//model
		$modelBlock = new Block_Model();
		$modelConfsite = new ConfSite_Model();
		$modelSessTry = new SessTry_Model();

        $iResult = 0;

		$user_id = "";
		$user_pw = ""; 
		if(array_key_exists('username', $arrLoginData) && array_key_exists('password', $arrLoginData)){
			$user_id = trim($arrLoginData['username']);
			$user_pw = trim($arrLoginData['password']);
		} 

		$sessTry = $modelSessTry->getByIp($ip);
		$iTry = 5;
		if(!is_null($sessTry)){
			$iTry = time() - strtotime($sessTry->log_time);
		}

		$member = null;
		if(strlen($user_id) > 0 && strlen($user_pw) > 0){
			$member = $this->modelMember->login($user_id, $user_pw);
		}

		if($iTry < 3){
			$iResult = 0;
		}
		else if(is_null($member)){
			$iResult = 0;
			$modelSessTry->add($user_id, $user_pw, $ip, TRYLOG_FAIL);
		} else if($member->mb_level < LEVEL_ADMIN && !is_null($modelBlock->getByIp($ip, true))){
			$iResult = 2;
			$modelSessTry->add($user_id, $user_pw, $ip, TRYLOG_IPBLOCK);
		} else if($member->mb_level == LEVEL_ADMIN && $member->mb_state_view == STATE_ACTIVE &&
			$member->mb_ip_join !== $ip){
			$iResult = 3;
			$modelSessTry->add($user_id, $user_pw, $ip, TRYLOG_IPDENIED);
		} else if($member->mb_level < LEVEL_ADMIN && $modelConfsite->IsMaintain()){
			$iResult = 4;
			$modelSessTry->add($user_id, $user_pw, $ip, TRYLOG_MAINTAIN);
		}  else if(!$this->modelMember->isPermitMember((object)$member)){
			$iResult = 3;
			$modelSessTry->add($user_id, $user_pw, $ip, TRYLOG_IDBLOCK);
		}
		else
        {
			$this->modelSess->deleteLast();

			$sessId = $this->session->session_id;
			$sess = $this->modelSess->getByUid($member->mb_uid);

			if($member->mb_level < LEVEL_ADMIN && !$modelConfsite->IsMultiLogin() && !is_null($sess) && $sess->sess_id != $sessId ){
				$iResult = 4;
				$modelSessTry->add($user_id, $user_pw, $ip, TRYLOG_LOGINING);
            } else if ($member->mb_state_active == STATE_ACTIVE){
                $sessData = [
                    'user_id' => $member->mb_uid, 
                    'logged_in' => TRUE, 
                ];
				$this->session->set($sessData);
				$member->mb_ip_last = $ip;
				$this->modelMember->updateLogin($member);
                $iResult = 1;
				
				$this->modelSess->add($member, $sessId);
				$modelSessLog = new SessLog_Model();
				$modelSessLog->add($member);
				$modelSessTry->add($user_id, $user_pw, $ip, TRYLOG_SUCCESS);

            }
        }   
		//결과값 
 		if($iResult == 1){	
			$arrData = ['redirect' => '/'];

			$arrResult['data'] = $arrData;
			$arrResult['status'] = "success";
		}
		else{
			$arrResult['data'] = $iResult;
			$arrResult['status'] = "fail";

		}
        return $this->response->setJSON($arrResult);
	}
	*/
	public function logout()
	{
		$sess_id = $this->session->session_id;
		writeLog("[api] logout (".$sess_id.")");
		$this->sess_destroy();
		
		$arrResult['status'] = "success";
		echo json_encode($arrResult);
	}
	//게임설정 
	public function conf_game(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);
		
		$objResult = new \StdClass;

		if(is_login())
		{
			$gameId = intval($arrData['game_index']);
			//model
			$confgameModel = new ConfGame_Model();
			$confsiteModel = new ConfSite_Model();
			$objConfig = $confgameModel->getByIndex($gameId);
			
			$errMsg = "";
			$agConf = null;
			if($gameId == GAME_CASINO_EVOL){
				$arrResult = $this->libApicas->getAgentInfo();
				if($arrResult['status'] == 1){
					$confsiteModel->setConfActive(CONF_CASINO_EVOL, $arrResult['balance']);
					writeLog("<CASINO> Agent Egg = ".$arrResult['balance']);
				} else {
					if($arrResult['error'] == INVALID_ACCESS_TOKEN){
						$errMsg = "토큰불일치";
					} else if($arrResult['error'] == INVALID_PARAMETER){
						$errMsg = "파라메터오류";
					} else $errMsg = "접속불가";
				}
				$agConf = $confsiteModel->getConf(CONF_CASINO_EVOL);
			} else if($gameId == GAME_SLOT_1){
				$arrResult = $this->libApislot->getAgentInfo();
				if($arrResult['status'] == 1){
					$confsiteModel->setConfActive(CONF_SLOT_1, $arrResult['balance']);
					writeLog("<SLOT> Agent Egg = ".$arrResult['balance']);
				} else {
					if($arrResult['resultCode'] == SLOTCODE_IP_AUTH){
						$errMsg = "IP인증오류";
					} else if($arrResult['resultCode'] == SLOTCODE_API_FAIL){
						$errMsg = "요청실패";
					} else $errMsg = "접속불가";
				}
				$agConf = $confsiteModel->getConf(CONF_SLOT_1);
			} else if($gameId == GAME_SLOT_2){
				$arrResult = $this->libApifslot->getAgentInfo();
				if($arrResult['status'] == 1){
					$confsiteModel->setConfActive(CONF_SLOT_2, $arrResult['balance']);
					writeLog("<FSLOT> AGENT Egg = ".$arrResult['balance']);
				} else {
					if($arrResult['error'] == INVALID_ACCESS_TOKEN){
						$errMsg = "토큰불일치";
					} else if($arrResult['error'] == INVALID_PARAMETER){
						$errMsg = "파라메터오류";
					} else $errMsg = "접속불가";
				}
				$agConf = $confsiteModel->getConf(CONF_SLOT_2);
			} else if($gameId == GAME_SLOT_3){
				$arrResult = $this->libApigslot->getUserInfo();
				if($arrResult['status'] == 1){
					$confsiteModel->setConfActive(CONF_SLOT_3, $arrResult['balance']);
					writeLog("<GSLOT> AGENT Egg = ".$arrResult['balance']);
				} else {
					if($arrResult['msg'] == "INVALID_ACCESS_SECRETKEY"){
						$errMsg = "잘못된 보안키";
					} else if($arrResult['msg'] == "INVALID_AGENT" || $arrResult['msg'] == "BLOCKED_AGENT"){
						$errMsg = "에이젼트 오류";
					} else $errMsg = "접속불가";
					writeLog("<GSLOT> AGENT Egg Msg = ".$arrResult['msg']);

				}
				$agConf = $confsiteModel->getConf(CONF_SLOT_3);
			} else if($gameId == GAME_CASINO_KGON){
				$arrResult = $this->libApikgon->getAgentInfo();
				if($arrResult['status'] == 1){
					$confsiteModel->setConfActive(CONF_CASINO_KGON, $arrResult['balance']);
					writeLog("<KGON> AGENT Egg = ".$arrResult['balance']);
				} else {
					if(array_key_exists('msg', $arrResult)){
						$errMsg = $arrResult['msg'];
					} else $errMsg = "접속불가";
				}
				$agConf = $confsiteModel->getConf(CONF_CASINO_KGON);
			}
			
			$agInfo = null;
			if(!is_null($agConf)){
				$arrInfo = explode("#", $agConf->conf_content);
				if(count($arrInfo) >= 3){ //0-host, 1-ag_code, 2-ag_token
					$agInfo['code'] = $arrInfo[1];
					$agInfo['egg'] = $agConf->conf_active;
					$agInfo['useregg'] = $this->modelMember->calcGameEgg($gameId);
				}	
				
			}

			$objResult->data = $objConfig;
			$objResult->msg = $errMsg;
			$objResult->agent = $agInfo;
			$objResult->status = "success";
		}
		else {
			$objResult->status = "fail";			
		}
		echo json_encode($objResult);	
	}


	//본사설정 보관 
	public function saveconfsite(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);


		if(is_login())
		{
			$bPermit = false;
			
			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			
			if(!is_null($objUser))
			{				
				if($objUser->mb_level >= LEVEL_ADMIN )
					$bPermit = true;					
			}
			if($bPermit){
				//model
				$confsiteModel = new ConfSite_Model();
				$confsiteModel->saveData($arrData);
			
				$arrResult['status'] = "success";
			} else $arrResult['status'] = "nopermit";
		}
		else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	
	}

	
	//점검설정 보관 
	public function saveconfmaintain(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);
		
		if(is_login())
		{
			$bPermit = false;
			
			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			
			if(!is_null($objUser))
			{				
				if($objUser->mb_level >= LEVEL_ADMIN )
					$bPermit = true;					
			}
			if($bPermit){
				//model
				$confsiteModel = new ConfSite_Model();
				$bResult = $confsiteModel->saveMaintainConfig($arrData);
			
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


	//게임설정 보관 
	public function saveconfgame(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);		

		if(is_login())
		{
			$bPermit = false;
			
			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			
			if(!is_null($objUser))
			{				
				if($objUser->mb_level >= LEVEL_ADMIN )
					$bPermit = true;					
			}
			if($bPermit){
				//model
				$confgameModel = new ConfGame_Model();
                $query = "";
				if($confgameModel->saveData($arrData, $query)){
                    $this->modelModify->add($this->session->user_id, MOD_GM_CONF, $query, $this->request->getIPAddress());

				}
			
				$arrResult['status'] = "success";
			} else $arrResult['status'] = "nopermit";
		}
		else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	
	}

	public function getBetSite(){
		if(is_login())
		{
			
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level >= LEVEL_ADMIN){

				$arrResult['data'] = $confsiteModel->getBetSite();
				$arrResult['status'] = "success";
			} else $arrResult['status'] = "nopermit";

		} else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	

	}

	public function setBetSite(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);		
		if(is_login())
		{
			
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				
				$confsiteModel->setBetSite($arrData);
				$arrResult['status'] = "success";
			} else $arrResult['status'] = "nopermit";

		} else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	

	}

	public function getEvolSite(){
		if(is_login())
		{
			
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level >= LEVEL_ADMIN){

				$arrResult['data'] = $confsiteModel->getEvolSite();
				$arrResult['status'] = "success";
			} else $arrResult['status'] = "nopermit";

		} else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	

	}

	public function setEvolSite(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);		
		if(is_login())
		{
			
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				
				$confsiteModel->setEvolSite($arrData);
				$arrResult['status'] = "success";
			} else $arrResult['status'] = "nopermit";

		} else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	

	}

	public function getEvolState(){
		if(is_login())
		{
			
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				$state = false;
				if(array_key_exists('app.ebal', $_ENV) && $_ENV['app.ebal'] > 2 )
					$objConfig = $confsiteModel->getConf(CONF_AUTOAPPS);
				else $objConfig = $confsiteModel->getConf(CONF_EVOLRUN_1);
				if(!is_null($objConfig)){
					$state = $objConfig->conf_active;
				}
				$arrResult['data'] = $state;
				$arrResult['status'] = "success";
			} else $arrResult['status'] = "nopermit";

		} else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	

	}

	public function setEvolState(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);		
		if(is_login())
		{
			
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				$confId = CONF_EVOLRUN_1;
				if(array_key_exists('app.ebal', $_ENV) && $_ENV['app.ebal'] > 2 )
					$confId = CONF_AUTOAPPS;

				$confsiteModel->setConfActive($confId, $arrData['active_ev']);
				$arrResult['status'] = "success";
			} else $arrResult['status'] = "nopermit";

		} else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	

	}

	public function getsoundconf(){
		if(is_login())
		{
			
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level >= LEVEL_ADMIN){

				$arrResult['data'] = $confsiteModel->getSoundConf();
				$arrResult['status'] = "success";
			} else $arrResult['status'] = "nopermit";

		} else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	

	}

	public function savesoundconf(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);		

		if(is_login())
		{
			
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				$confsiteModel->saveSoundConf($arrData);				

				$arrResult['data'] = $confsiteModel->getSoundConf();
				$arrResult['status'] = "success";
			} else $arrResult['status'] = "nopermit";

		} else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	

	}

	//비번 변경  
	public function change_password(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);		

		if(is_login())
		{
			
			$strUid = $this->session->user_id;
			$query = "";
			$iResult = $this->modelMember->changepassword($strUid, $arrData, $query);
			
			if($iResult == 1){
				$this->modelModify->add($this->session->user_id, MOD_MB_PWD, $query, $this->request->getIPAddress());
				$arrResult['status'] = "success";
			}
			else if($iResult == 2)
				$arrResult['status'] = "mistake";
			else $arrResult['status'] = "fail";
			
		}
		else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	
	}

	//게임설정 보관 
	public function changealarmstate(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);		

		if(is_login())
		{
			
			$strUid = $this->session->user_id;
			$iResult = $this->modelMember->changeAlarmState($strUid, $arrData);
			
			if($iResult == 1)
				$arrResult['status'] = "success";
			else if($iResult == 2)
				$arrResult['status'] = "mistake";
			else $arrResult['status'] = "fail";
			
		}
		else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	
	}

	public function depositlist(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login())
		{
			
			$chargeModel = new Charge_Model();
			$strUid = $this->session->user_id;

			$arrData = $chargeModel->search($arrReqData);
			$nTotal = $chargeModel->calcAdminCharge($arrReqData);
				
			$arrResult['data'] = $arrData;
			$arrResult['total'] = $nTotal;
			$arrResult['status'] = "success";
		}
		else{
			$arrResult['status'] = "logout";
		}
		echo json_encode($arrResult);
	}

	public function depositlistcnt(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login())
		{
			$chargeModel = new Charge_Model();
			$objCount = $chargeModel->searchCount($arrReqData);

			$arrResult['data'] = $objCount;
			$arrResult['status'] = "success";
		}
		else{
			$arrResult['status'] = "logout";
		}
		echo json_encode($arrResult);
	}

	public function depositproc(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		if(is_login())
		{
			$strUid = $this->session->user_id;
			$chargeModel = new Charge_Model();
			
			$moneyhistoryModel = new MoneyHistory_Model();
			$objAdmin = $this->modelMember->getInfo($strUid);
			$objCharge = $chargeModel->get($arrReqData['charge_fid']);

			$bPermit = true;
			if(is_null($objAdmin) || $objAdmin->mb_level < LEVEL_ADMIN){
				$bPermit = false;
			} else if(is_null($objCharge)){
				$bPermit = false;
			} 
			
			$bResult = false;
			if(!$bPermit){
				$arrResult['status'] = "fail";
			} else {
				$objUser = $this->modelMember->getInfo($objCharge->charge_mb_uid);
				if(!is_null($objUser)){
					if($arrReqData['process'] == 0){//취소
						
					} else if($arrReqData['process'] == 1){//승인
						$confsiteModel = new ConfSite_Model();
						$confsiteModel->readMemConf();
			
						if($_ENV['mem.delay_play'] > 0 && $_ENV['mem.depodeny_play'] && diffDt(date('Y-m-d H:i:s'), $objUser->mb_time_bet) < $_ENV['mem.delay_play']){
							$arrResult['status'] = 'fail';
							$arrResult['msg'] = '회원이 게임플레이중이므로 충전승인 하실수 없습니다. '.intval(($_ENV['mem.delay_play']-diffDt(date('Y-m-d H:i:s'), $objUser->mb_time_bet))/60+1)."분후 다시 시도해주세요.";
						} else if($objCharge->charge_action_state == 1 || $objCharge->charge_action_state == 3 || $objCharge->charge_action_state == 4){ //대기이거나 거절된 상태에서만 진행
								
							$dtMoney = $objCharge->charge_money;
							$nCharge = $objCharge->charge_money;
							if($this->modelMember->moneyProc($objUser, $dtMoney, 0, $nCharge, 0)){
								//moneyhistory Table
								$moneyhistoryModel->registerCharge($objUser, $objCharge->charge_money);
								//charge Table 
								$objCharge->charge_action_state = 2;
								$objCharge->charge_action_uid = $strUid;
								$objCharge->charge_money_after = allMoney($objUser) + $dtMoney;
								$bResult = $chargeModel->permit($objCharge);
							}
						} 
					} else if($arrReqData['process'] == 2){	//거절
						if($objCharge->charge_action_state == 1 || $objCharge->charge_action_state == 4){//대기 상태에서만 진행
							//charge Table 
							$objCharge->charge_action_state = 3;
							$objCharge->charge_action_uid = $strUid;
							$objCharge->charge_money_after = allMoney($objUser);
							$bResult = $chargeModel->permit($objCharge);	
						}		
					}
						else if($arrReqData['process'] == 3){	//임시대기
						if($objCharge->charge_action_state == 1){//대기 상태에서만 진행
							//charge Table 
							$objCharge->charge_action_state = 4;
							$objCharge->charge_action_uid = $strUid;
							$objCharge->charge_money_after = allMoney($objUser);
							$bResult = $chargeModel->permit($objCharge);	
						}		
					}
				}
			} 

			$arrResult['status'] = $bResult?"success":"fail";
			echo json_encode($arrResult);
		}else {
			$arrResult['status'] = "logout";	
			echo json_encode($arrResult);			
		}

	}

public function withdrawlist(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		if(is_login())
		{
			$exchangeModel = new Exchange_Model();
			$arrData = $exchangeModel->search($arrReqData);
			$nTotal = $exchangeModel->calcAdminExchange($arrReqData);
			
			$arrResult['data'] = $arrData;
			$arrResult['total'] = $nTotal;
			$arrResult['status'] = "success";
		}
		else{
			$arrResult['status'] = "logout";
		}
		echo json_encode($arrResult);
	}

	public function withdrawlistcnt(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login())
		{
			$exchangeModel = new Exchange_Model();
			$objCount = $exchangeModel->searchCount($arrReqData);

			$arrResult['data'] = $objCount;
			$arrResult['status'] = "success";
		}
		else{
			$arrResult['status'] = "logout";
		}
		echo json_encode($arrResult);
	}

	public function withdrawproc(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		if(is_login())
		{
			$strUid = $this->session->user_id;
			$exchangeModel = new Exchange_Model();
			
			$moneyhistoryModel = new MoneyHistory_Model();
			
			$objAdmin = $this->modelMember->getInfo($strUid);
			$objExchange = $exchangeModel->get($arrReqData['exchange_fid']);

			$bPermit = true;
			if(is_null($objAdmin) || $objAdmin->mb_level < LEVEL_ADMIN){
				$bPermit = false;
			} else if(is_null($objExchange)){
				$bPermit = false;
			} 
			
			if(!$bPermit){
				$arrResult['status'] = "fail";
			} else {
				$bResult = false;
				$objUser = $this->modelMember->getInfo($objExchange->exchange_mb_uid);
				if(!is_null($objUser)){
					if($arrReqData['process'] == 0){	//취소
						
					}
					else if($arrReqData['process'] == 1){		//승인

						if($objExchange->exchange_action_state == 1 || $objExchange->exchange_action_state == 3  || $objExchange->exchange_action_state == 4){ //대기이거나 거절된 상태에서만 진행
							//moneyhistory Table
							$moneyhistoryModel->registerExchange($objUser, $objExchange);
							//exchange Table 
							$objExchange->exchange_action_state = 2;
							$objExchange->exchange_action_uid = $strUid;
							$bResult = $exchangeModel->permit($objExchange);
						}
					} else if($arrReqData['process'] == 2){					//거절
						if($objExchange->exchange_action_state == 1 || $objExchange->exchange_action_state == 4 ){		//대기상태에서만 진행
							//거절되면 신청머니를 보상
							if($this->modelMember->moneyProc($objUser, $objExchange->exchange_money)){
								//exchange Table 
								$objExchange->exchange_action_state = 3;
								$objExchange->exchange_action_uid = $strUid;
								$objExchange->exchange_money_after = $objExchange->exchange_money_before;
								$bResult = $exchangeModel->permit($objExchange);
							}
						}			
					} else if($arrReqData['process'] == 3){					//임시대기
						if($objExchange->exchange_action_state == 1 ){		//대기상태에서만 진행
							//charge Table 
							$objExchange->exchange_action_state = 4;
							$objExchange->exchange_action_uid = $strUid;
							// $objExchange->exchange_money_after = allMoney($objUser);
							$bResult = $exchangeModel->permit($objExchange);
						}			
					} 
				}
				$arrResult['status'] = $bResult?"success":"fail";
			}
			echo json_encode($arrResult);
		} else {
			$arrResult['status'] = "logout";	
			echo json_encode($arrResult);			
		}

	}



		//사용자 정보 변경  
	public function updateNotice(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);
		
		if(is_login())
		{
			$bPermit = false;
			$noticeModel = new Notice_Model();
			
			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			
			//현재 가입한 유저가 요청한 유저보다 레벨이 높은 경우에 변경이 가능하다.
			if(!is_null($objUser))
			{				
				if($objUser->mb_level >= LEVEL_ADMIN )
					$bPermit = true;					
			}
			

			if($bPermit){
				
				$bResult = $noticeModel->updateNoticeByFid(array($arrData));
				
				if($bResult) $noticeModel->updateNoticeByEmpFid($arrData);
				
				// if($bResult)
					$arrResult['status'] = "success";
				// else $arrResult['status'] = "fail";
			} else $arrResult['status'] = "nopermit";
		}
		else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	
	}


	//공지 정보 추가  
	public function addNotice(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);

		if(is_login())
		{
			$bPermit = false;
			$noticeModel = new Notice_Model();
			
			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			
			//현재 가입한 유저가 요청한 유저보다 레벨이 높은 경우에 변경이 가능하다.
			if(!is_null($objUser))
			{				
				if($objUser->mb_level >= LEVEL_ADMIN )
					$bPermit = true;					
			}
			

			if($bPermit){
				$arrData['notice_mb_uid'] = $objUser->mb_uid;
				$iResult = $noticeModel->addNotice($arrData);
					
				
				$arrResult['status'] = $iResult>0? "success":"fail";
				
			} else $arrResult['status'] = "nopermit";
		}
		else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	
	}



	//쪽지 정보 추가  
	public function addMessage(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);

		if(is_login())
		{
			$bPermit = false;
			$noticeModel = new Notice_Model();
			
			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			
			//현재 가입한 유저가 요청한 유저보다 레벨이 높은 경우에 변경이 가능하다.
			if(!is_null($objUser))
			{				
				if($objUser->mb_level >= LEVEL_ADMIN )
					$bPermit = true;					
			}			

			if($bPermit){
				$bResult = false;
				if($arrData['notice_mb_uid'] === '*'){
					$arrData['notice_read_count'] = 1;
					$iInsFid = $noticeModel->addNotice($arrData);
					if($iInsFid > 0){

						$arrMember = $this->modelMember->getMemberByLevel(LEVEL_ADMIN, true);
						//회원들에게 쪽지작성
						$arrData['notice_type'] = 4;
						$arrData['notice_emp_fid'] = $iInsFid;
						$arrData['notice_read_count'] = 0;
						$arrData['notice_time_create'] = date("Y-m-d H:i:s");
						$arrBatch = array();
						foreach ($arrMember as $objMember) {
							$arrData['notice_mb_uid'] = $objMember->mb_uid;
							array_push($arrBatch, $arrData);
						}
						$bResult = $noticeModel->addNoticeBatch($arrBatch);
					}
				}
				else {					
					$objMember = $this->modelMember->getInfo($arrData['notice_mb_uid']);		
					if(!is_null($objMember))
					{
						$bResult = $noticeModel->addNotice($arrData);
					}
				}
				
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


	//머니거래 리력
	public function moneyhistory(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		//var_dump($arrBetData);
		if(is_login()) {
			//model
			$moneyhistoryModel = new MoneyHistory_Model();
			
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);

			if(strlen($arrGetData['user']) > 0){
				$objUser = $this->modelMember->getInfo(trim($arrGetData['user']));
				if(!is_null($objUser))
					$arrGetData['user'] = $objUser->mb_fid;
				else $arrGetData['user'] = 0;
			}

			$arrBetResults = $moneyhistoryModel->search($objAdmin, $arrGetData);
		
			$objResult = new \StdClass;
			$objResult->data = $arrBetResults;			
			$objResult->status = "success";
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}



	//머니거래 리력 개수
	public function moneyhistorycnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		//var_dump($arrBetData);
		if(is_login()) {
			//model
			$moneyhistoryModel = new MoneyHistory_Model();
			
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			
			if(strlen($arrGetData['user']) > 0){
				$objUser = $this->modelMember->getInfo(trim($arrGetData['user']));
				if(!is_null($objUser))
					$arrGetData['user'] = $objUser->mb_fid;
				else $arrGetData['user'] = 0;
			}
			
			$objCount = $moneyhistoryModel->searchCount($objAdmin, $arrGetData);
			
			$arrResult['data'] = $objCount;
			$arrResult['status'] = "success";
		
			echo json_encode($arrResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}

	//머니이동 리력
	public function translist(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		//var_dump($arrBetData);
		if(is_login()) {
			//model
			$transferhistoryModel = new Transfer_Model();
			
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			
			$arrData = $transferhistoryModel->search($objAdmin, $arrGetData);
		
		
			$objResult = new \StdClass;
			$objResult->data = $arrData;			
			$objResult->status = "success";
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}



	//머니이동 리력 개수
	public function translistcnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		//var_dump($arrBetData);
		if(is_login()) {
			//model
			$transferhistoryModel = new Transfer_Model();
			
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			
			$objCount = $transferhistoryModel->searchCount($objAdmin, $arrGetData);
			
			$arrResult['data'] = $objCount;
			$arrResult['status'] = "success";
		
			echo json_encode($arrResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}


	//하부회워 정산리력 Ajax로 전송
	public function calculate(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		if(is_login()) {
			//model
			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			$arrResult = array();
			$arrEmp = array();
			//회원정보가 없다면
			$bResult = false;
			if($arrReqData['mb_fid'] == 0){	
				if($objUser->mb_level > LEVEL_COMPANY){//관리자이면 부본사정보를 반환
					$arrEmp = $this->modelMember->getMemberByLevel(LEVEL_COMPANY);
					$bResult = true;
					
				} else if($objUser->mb_level >= LEVEL_MIN){	// 가입한 성원자정보를 반환
					
					$arrEmp[0] = $objUser;
					$bResult = true;
				}

			} else {						//요청한 등급이하 정보를 반환
					$objReqEmp = $this->modelMember->getInfoByFid($arrReqData['mb_fid']);
					
					if(!is_null($objReqEmp)){
						if($objReqEmp->mb_level <= $objUser->mb_level){
							$arrEmp = $this->modelMember->getMemberByEmpFid($objReqEmp->mb_fid, $objReqEmp->mb_level-1);
							if(is_null($arrEmp))	$bResult = false;
							else $bResult = true; 
							
						}
					}
			}
			$arrData = array();
			
			if($bResult){
				$arrReqData['rw_blank'] = $objUser->mb_level >= LEVEL_ADMIN;
				if($arrReqData['type'] == 0){
					$confsiteModel = new ConfSite_Model();
					$siteConfs = $this->getSiteConf($confsiteModel);
					$this->modelMember->allGameRange($arrReqData, $siteConfs);
				} else 
					$this->modelMember->gameRange($arrReqData);

				foreach ($arrEmp as $objEmp) {
            		$objCalc['mb_fid'] = $objEmp->mb_fid;
		            $objCalc['mb_uid'] = $objEmp->mb_uid;
		            $objCalc['mb_nickname'] = $objEmp->mb_nickname;
		            $objCalc['mb_emp_fid'] = $objEmp->mb_emp_fid;
		            $objCalc['mb_level'] = $objEmp->mb_level;
					if($arrReqData['type'] == 0)
						$arrResult = $this->modelMember->calculate($objEmp, $arrReqData, $siteConfs);
					else $arrResult = $this->modelMember->calculateByGame($objEmp, $arrReqData);
					if(is_null($arrResult) || count($arrResult) != 7)
						continue;
					$objCalc['mb_money_all'] = $arrResult[0]->result_1 != null ? $arrResult[0]->result_1 : 0 ;		//관리자보유금;
					$objCalc['mb_money_single'] = $arrResult[0]->result_2 != null ? $arrResult[0]->result_2 : 0 ;	//관리자보유금;
					$objCalc['mb_point_all'] = $arrResult[1]->result_1 != null ? $arrResult[1]->result_1 : 0 ;		//관리자보유금;
					$objCalc['mb_point_single'] = $arrResult[1]->result_2 != null ? $arrResult[1]->result_2 : 0 ;	//관리자보유금;
					$objCalc['mb_user_cnt'] = $arrResult[2]->result_1 != null ? $arrResult[2]->result_1 : 0 ;		//관리자보유금;
		            $objCalc['mb_charge'] = $arrResult[3]->result_1 != null ? $arrResult[3]->result_1 : 0 ;         //충전금액합산
		            $objCalc['mb_exchange'] = $arrResult[4]->result_1 != null ? $arrResult[4]->result_1 : 0;     	//환전금액합산
		            $objCalc['mb_charge_benefit'] = $objCalc['mb_charge'] - $objCalc['mb_exchange'];  				//충환손익
			        $objCalc['mb_bet_money'] = $arrResult[5]->result_1 != null ? $arrResult[5]->result_1 : 0;		//배팅머니
					$objCalc['mb_bet_win_money'] = $arrResult[5]->result_2 != null ? $arrResult[5]->result_2 : 0;	//적중머니
         			$objCalc['mb_bet_benefit_money'] = $objCalc['mb_bet_money'] - $objCalc['mb_bet_win_money'];  	//배팅손익
         			$objCalc['mb_rate_all'] =   $arrResult[6]->result_1 != null ? $arrResult[6]->result_1 : 0;		//수수료 합산
         			$objCalc['mb_rate_single'] =   $arrResult[6]->result_2 != null ? $arrResult[6]->result_2 : 0;	//수수료 개별
         			$objCalc['mb_last_money'] = $objCalc['mb_bet_benefit_money'] - $objCalc['mb_rate_single'] ; 	//최종손익

		            $arrData[] = $objCalc;
            		
        		}
			}



			$objResult = new \StdClass;			
			$objResult->data = $arrData;
			$objResult->level = $objUser->mb_level;
			$objResult->status = "success";
		
			echo json_encode($objResult);
			
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}


	//하부회워 정산리력 Ajax로 전송
	public function calculategame(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		// return var_dump($arrReqData);
		if(is_login()) {
			//model
			
			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			$arrResult = array();
			$arrEmp = array();
			//회원정보가 없다면
			$bResult = false;
			if($arrReqData['mb_fid'] == 0){	
				if($objUser->mb_level > LEVEL_COMPANY){//관리자이면 부본사정보를 반환
					$arrEmp = $this->modelMember->getMemberByLevel(LEVEL_COMPANY);
					$bResult = true;
					
				} else if($objUser->mb_level >= LEVEL_MIN){	// 가입한 성원자정보를 반환
					
					$arrEmp[0] = $objUser;
					$bResult = true;
				}

			} else {						//요청한 등급이하 정보를 반환
					$objReqEmp = $this->modelMember->getInfoByFid($arrReqData['mb_fid']);
					if(!is_null($objReqEmp)){
						if($objReqEmp->mb_level <= $objUser->mb_level){
							$arrEmp = $this->modelMember->getMemberByEmpFid($objReqEmp->mb_fid, $objReqEmp->mb_level-1);
							if(is_null($arrEmp))	$bResult = false;
							else $bResult = true; 
						}
					}
			}
			$arrData = array();
			
			if($bResult){
				$arrReqData['rw_blank'] = $objUser->mb_level >= LEVEL_ADMIN;


				foreach ($arrEmp as $objEmp) {
            		$objCalc['mb_fid'] = $objEmp->mb_fid;
		            $objCalc['mb_uid'] = $objEmp->mb_uid;
		            $objCalc['mb_nickname'] = $objEmp->mb_nickname;
		            $objCalc['mb_emp_fid'] = $objEmp->mb_emp_fid;
		            $objCalc['mb_level'] = $objEmp->mb_level;
					$arrResult = $this->modelMember->calculateByGame($objEmp, $arrReqData);
					if(is_null($arrResult) || count($arrResult) != 7)
						continue;
					$objCalc['mb_money_all'] = $arrResult[0]->result_1 != null ? $arrResult[0]->result_1 : 0 ;		//관리자보유금;
					$objCalc['mb_money_single'] = $arrResult[0]->result_2 != null ? $arrResult[0]->result_2 : 0 ;	//관리자보유금;
					$objCalc['mb_point_all'] = $arrResult[1]->result_1 != null ? $arrResult[1]->result_1 : 0 ;		//관리자보유금;
					$objCalc['mb_point_single'] = $arrResult[1]->result_2 != null ? $arrResult[1]->result_2 : 0 ;	//관리자보유금;
					$objCalc['mb_user_cnt'] = $arrResult[2]->result_1 != null ? $arrResult[2]->result_1 : 0 ;		//관리자보유금;
					$objCalc['mb_charge'] = $arrResult[3]->result_1 != null ? $arrResult[3]->result_1 : 0 ;         //충전금액합산
					$objCalc['mb_exchange'] = $arrResult[4]->result_1 != null ? $arrResult[4]->result_1 : 0;     	//환전금액합산
					$objCalc['mb_charge_benefit'] = $objCalc['mb_charge'] - $objCalc['mb_exchange'];  				//충환손익
					$objCalc['mb_bet_money'] = $arrResult[5]->result_1 != null ? $arrResult[5]->result_1 : 0;		//배팅머니
					$objCalc['mb_bet_win_money'] = $arrResult[5]->result_2 != null ? $arrResult[5]->result_2 : 0;	//적중머니
					$objCalc['mb_bet_benefit_money'] = $objCalc['mb_bet_money'] - $objCalc['mb_bet_win_money'];  	//배팅손익
					$objCalc['mb_rate_all'] =   $arrResult[6]->result_1 != null ? $arrResult[6]->result_1 : 0;		//수수료 합산
					$objCalc['mb_rate_single'] =   $arrResult[6]->result_2 != null ? $arrResult[6]->result_2 : 0;	//수수료 개별
					$objCalc['mb_last_money'] = $objCalc['mb_bet_benefit_money'] - $objCalc['mb_rate_single'] ; 	//최종손익
	
		            $arrData[] = $objCalc;
        		}
			}

			$objResult = new \StdClass;			
			$objResult->data = $arrData;
			$objResult->level = $objUser->mb_level;
			$objResult->status = "success";
		
			echo json_encode($objResult);
			
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}



	//쪽지리스트를 Ajax로 전송
	public function getmessage(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		//var_dump($arrBetData);
		if(is_login()) {
			//model
			$noticeModel = new Notice_Model();
			$arrResult = $noticeModel->searchMessage($arrGetData);
		
			$objResult = new \StdClass;
			$objResult->data = $arrResult;			
			$objResult->status = "success";
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}



	//쪽지 개수를 Ajax로 전송
	public function getmessagecnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		//var_dump($arrBetData);
		if(is_login()) {
			//model
			$noticModel = new Notice_Model();
			$objCount = $noticModel->searchMessageCnt($arrGetData);
			
			$arrResult['data'] = $objCount;
			$arrResult['status'] = "success";
		
			echo json_encode($arrResult);
		}
		else{
		
			$arrResult['status'] = "logout";
			echo json_encode($arrResult);	
		} 		
	}



	//배팅리력결과를 Ajax로 전송
	public function csbetlist(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);

		if(is_login()) {
			//model
			if(isEBalMode()){
				$csbetModel = new EbalBet_Model();
				if(strlen($arrGetData['user']) > 0){
					$objUser = $this->modelMember->getInfo(trim($arrGetData['user']));
					if(!is_null($objUser))
						$arrGetData['user'] = $objUser->mb_fid;
					else $arrGetData['user'] = 0;
				}
			} else 
				$csbetModel = new CsBet_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);

			$arrGetData['type'] = GAME_CASINO_EVOL;
			$this->modelMember->gameRange($arrGetData, false);

			if($objAdmin->mb_level >= LEVEL_ADMIN && strlen(trim($arrGetData['emp'])) > 0){
				$objAdmin = $this->modelMember->getInfo(trim($arrGetData['emp']));
			} 
			$arrBetResults = $csbetModel->search($objAdmin, $arrGetData);
			
			$objResult = new \StdClass;
			$objResult->data = $arrBetResults;	
			$objResult->status = "success";
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}



	//배팅리력결과 개수를 Ajax로 전송
	public function csbetlistcnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);

		if(is_login()) {
			//model
			if(isEBalMode()){
				$csbetModel = new EbalBet_Model();
				if(strlen($arrGetData['user']) > 0){
					$objUser = $this->modelMember->getInfo(trim($arrGetData['user']));
					if(!is_null($objUser))
						$arrGetData['user'] = $objUser->mb_fid;
					else $arrGetData['user'] = 0;
				}
			} else 
				$csbetModel = new CsBet_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);

			$arrGetData['type'] = GAME_CASINO_EVOL;
			$this->modelMember->gameRange($arrGetData, false);
			
			$arrBetAccount = null;
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				if(strlen(trim($arrGetData['emp'])) > 0){
					$objAdmin = $this->modelMember->getInfo(trim($arrGetData['emp']));
				}
				else 	
					$arrBetAccount = $csbetModel->getBetAccount($arrGetData);
			}
			$objCount = $csbetModel->searchCount($objAdmin, $arrGetData);
			
			$arrResult['data'] = $objCount;
			$arrResult['account'] = $arrBetAccount;
			$arrResult['status'] = "success";
		
			echo json_encode($arrResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}

	//배팅리력결과를 Ajax로 전송
	public function ebalancelist(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);

		if(is_login()) {
			//model
			$ebetModel = new Ebalance_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);

			$arrBetResults = null;
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				$arrBetResults = $ebetModel->searchList($arrGetData);
			} 
			
			$objResult = new \StdClass;
			$objResult->data = $arrBetResults;	
			$objResult->status = "success";
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}



	//배팅리력결과 개수를 Ajax로 전송
	public function ebalancecnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);

		if(is_login()) {
			//model
			$ebetModel = new Ebalance_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			
			$objCount = null;
			$arrBetAccount = null;
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				$objCount = $ebetModel->searchCount($arrGetData);
				$arrBetAccount = $ebetModel->getBetAccount($arrGetData);
			}			

			$arrResult['data'] = $objCount;
			$arrResult['account'] = $arrBetAccount;
			$arrResult['status'] = "success";
		
			echo json_encode($arrResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}

	//배팅리력결과를 Ajax로 전송
	public function eorderlist(){ 

		if(is_login()) {
			//model
			$eorderModel = new Eorder_Model();
			$confsiteModel = new ConfSite_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);

			$stats = null;
			$agBalance = -1;
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				
				$stats = $eorderModel->getStatsByRound();	
			}

			$objResult = new \StdClass;
			$objResult->data = $stats;	
			$objResult->status = "success";
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}


	//배팅리력결과를 Ajax로 전송
	public function slbetlist(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);

		if(is_login()) {
			//model
			$slbetModel = new SlBet_Model();
			$confsiteModel = new ConfSite_Model();
			$confsiteModel->readBetConf();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level >= LEVEL_ADMIN && strlen(trim($arrGetData['emp'])) > 0){
				$objAdmin = $this->modelMember->getInfo(trim($arrGetData['emp']));
			}
			$arrBetResults = $slbetModel->search($objAdmin, $arrGetData);
			
			$objResult = new \StdClass;
			$objResult->data = $arrBetResults;	
			$objResult->status = "success";
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}



	//배팅리력결과 개수를 Ajax로 전송
	public function slbetlistcnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);

		if(is_login()) {
			//model
			$slbetModel = new SlBet_Model();
			$confsiteModel = new ConfSite_Model();
			$confsiteModel->readBetConf();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			
			$arrBetAccount = null;
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				if(strlen(trim($arrGetData['emp'])) > 0){
					$objAdmin = $this->modelMember->getInfo(trim($arrGetData['emp']));
				}
				else 	
					$arrBetAccount = $slbetModel->getBetAccount($arrGetData);
			} 
			$objCount = $slbetModel->searchCount($objAdmin, $arrGetData);
			
			$arrResult['data'] = $objCount;
			$arrResult['account'] = $arrBetAccount;	
			$arrResult['status'] = "success";
		
			echo json_encode($arrResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}

	public function fslotlist(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$objResult = new \StdClass;
		if(is_login()) {
			//model
			$slgameModel = new SlotGame_Model();
			
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level < LEVEL_ADMIN){
				$objResult->status = "fail";
			} else {
				$objResult->status = "success";
				$objResult->data = $slgameModel->search($arrReqData);
				$objResult->app = $_ENV['app.type'];
			}
		
		}
		else{
			$objResult->status = "logout";

		}
		echo json_encode($objResult);

	}

	public function fslotcnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$objResult = new \StdClass;
		if(is_login()) {
			//model
			$slgameModel = new SlotGame_Model();
			
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level < LEVEL_ADMIN){
				$objResult->status = "fail";
			} else {
				$objResult->status = "success";
				$objResult->data = $slgameModel->searchCount($arrReqData);
			}
		
		}
		else{
			$objResult->status = "logout";

		}
		echo json_encode($objResult);
	}

	public function fslotset(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$objResult = new \StdClass;
		if(is_login()) {
			//model
			$slgameModel = new SlotGame_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level < LEVEL_ADMIN){
				$objResult->status = "fail";
			} else {
				$slgameModel->changeAct($arrReqData);

				$objResult->status = "success";
			}
		
		}
		else{
			$objResult->status = "logout";

		}
		echo json_encode($objResult);
	}

	
	public function xslotlist(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$objResult = new \StdClass;
		if(is_login()) {
			//model
			$slprdModel = new SlotPrd_Model();
			
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level < LEVEL_ADMIN){
				$objResult->status = "fail";
			} else {
				$objResult->status = "success";
				$objResult->data = $slprdModel->search($arrReqData);
			}
		
		}
		else{
			$objResult->status = "logout";

		}
		echo json_encode($objResult);

	}

	public function xslotcnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$objResult = new \StdClass;
		if(is_login()) {
			//model
			$slprdModel = new SlotPrd_Model();
			
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level < LEVEL_ADMIN){
				$objResult->status = "fail";
			} else {
				$objResult->status = "success";
				$objResult->data = $slprdModel->searchCount($arrReqData);
			}
		
		}
		else{
			$objResult->status = "logout";

		}
		echo json_encode($objResult);
	}

	public function xslotset(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$objResult = new \StdClass;
		if(is_login()) {
			//model
			$slprdModel = new SlotPrd_Model();
			
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level < LEVEL_ADMIN){
				$objResult->status = "fail";
			} else {
				$slprdModel->changeAct($arrReqData);

				$objResult->status = "success";
			}
		
		}
		else{
			$objResult->status = "logout";

		}
		echo json_encode($objResult);
	}
	
	public function kgonlist(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$objResult = new \StdClass;
		if(is_login()) {
			//model
			$casprdModel = new CasPrd_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level < LEVEL_ADMIN){
				$objResult->status = "fail";
			} else {
				$objResult->status = "success";
				$objResult->data = $casprdModel->search($arrReqData);
			}
		
		}
		else{
			$objResult->status = "logout";

		}
		echo json_encode($objResult);

	}

	public function kgoncnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$objResult = new \StdClass;
		if(is_login()) {
			//model
			$casprdModel = new CasPrd_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level < LEVEL_ADMIN){
				$objResult->status = "fail";
			} else {
				$objResult->status = "success";
				$objResult->data = $casprdModel->searchCount($arrReqData);
			}
		
		}
		else{
			$objResult->status = "logout";

		}
		echo json_encode($objResult);
	}

	public function kgonset(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$objResult = new \StdClass;
		if(is_login()) {
			//model
			$casprdModel = new CasPrd_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level < LEVEL_ADMIN){
				$objResult->status = "fail";
			} else {
				$casprdModel->changeAct($arrReqData);

				$objResult->status = "success";
			}
		
		}
		else{
			$objResult->status = "logout";

		}
		echo json_encode($objResult);
	}
	

	//DB 정리
	public function cleanDb(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		if(is_login()) {
			
			$cleanModel = new Clean_Model();

			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);

			$iResult = 0;

			if($objAdmin->mb_level>LEVEL_MASTER){
				if($arrReqData['clean'] == 0){
					$iResult = $cleanModel->cleanDb();
					$this->modelModify->add($this->session->user_id, MOD_DB_DELETE, "Delete DB", $this->request->getIPAddress());

				} else if($arrReqData['clean'] == 1){
					$iResult = $cleanModel->initDb();
					$this->modelModify->add($this->session->user_id, MOD_DB_DELETE, "Clear DB", $this->request->getIPAddress());

				}
			} else {
				 $iResult = 2;
			}

			$arrResult['status'] = $iResult==1?"success":"fail";
			$arrResult['data'] = $iResult;

			echo json_encode($arrResult);	
		
		} else {
			$arrResult['status'] = "logout";
			echo json_encode($arrResult);	
		}
	}



}
    