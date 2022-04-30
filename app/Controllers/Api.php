<?php namespace App\Controllers;

use App\Models\Member_Model;
use App\Models\Notice_Model;
use App\Models\Charge_Model;
use App\Models\Clean_model;
use App\Models\ConfGame_model;
use App\Models\Exchange_Model;
use App\Models\MoneyHistory_Model;
use App\Models\Transfer_Model;
use App\Models\ConfSite_Model;
use App\Models\CsBet_model;
use App\Models\SlBet_model;
use App\Models\SlotGame_Model;
use App\Models\SlotPrd_Model;
use App\Models\SessLog_Model;
use App\Models\Block_Model;
use App\Models\Reward_Model;



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
    //관리자 로그인
	public function login(){ 
		$jsonData = $_REQUEST['json_'];
		$arrLoginData = json_decode($jsonData, true);
		//model
        $modelMember = new Member_Model();
        $userData = $modelMember->where('mb_uid', $arrLoginData['username'])->first();
        $iResult = 0;
		$ip = $this->request->getIPAddress();

		$modelBlock = new Block_Model();
		if(is_null($userData)){
			$iResult = 0;
		} else if($userData['mb_level'] < LEVEL_ADMIN && !is_null($modelBlock->getByIp($ip, true))){
			$iResult = 2;
		} else if($userData['mb_level'] == LEVEL_ADMIN && $userData['mb_state_view'] == STATE_ACTIVE &&
			$userData['mb_ip_join'] !== $ip){
			$iResult = 3;
		}
		else if ( $userData['mb_pwd'] === $arrLoginData['password'])
        {
            if ($userData['mb_level'] >= LEVEL_MIN && $userData['mb_state_active'] == STATE_ACTIVE){
                $sessData = [
                    'user_id' => $userData['mb_uid'], 
                    'logged_in' => TRUE, 
                ];
				$this->session->set($sessData);
				$userData['mb_ip_last'] = $ip;
				$modelMember->updateLogin($userData);
                $iResult = 1;
				
				if($userData['mb_level'] <= LEVEL_ADMIN){
					$modelSessLog = new SessLog_Model();
					$modelSessLog->add($userData);
				}
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

	public function logout()
	{
		$this->session->destroy();
		
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
			$confgameModel = new ConfGame_model();
			$confsiteModel = new ConfSite_Model();
			$objConfig = $confgameModel->getByIndex($gameId);
			
			$agConf = null;
			if($gameId == GAME_CASINO_EVOL){
				$agConf = $confsiteModel->getConf(CONF_CASINO_EVOL);
			} else if($gameId == GAME_SLOT_1){
				$agConf = $confsiteModel->getConf(CONF_SLOT_1);
			} else if($gameId == GAME_SLOT_2){
				$agConf = $confsiteModel->getConf(CONF_SLOT_2);
			}
			
			$agInfo = null;
			if(!is_null($agConf)){
				$arrInfo = explode("#", $agConf['conf_content']);
				if(count($arrInfo) >= 3){ //0-host, 1-ag_code, 2-ag_token
					$agInfo['code'] = $arrInfo[1];
					$agInfo['egg'] = $agConf['conf_active'];
				}	
				
			}

			$objResult->data = $objConfig;
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
			$memberModel  = new Member_Model();
			$strUid = $this->session->user_id;
			$objUser = $memberModel->getInfo($strUid);
			
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
			$memberModel  = new Member_Model();
			$strUid = $this->session->user_id;
			$objUser = $memberModel->getInfo($strUid);
			
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
			$memberModel  = new Member_Model();
			$strUid = $this->session->user_id;
			$objUser = $memberModel->getInfo($strUid);
			
			if(!is_null($objUser))
			{				
				if($objUser->mb_level >= LEVEL_ADMIN )
					$bPermit = true;					
			}
			if($bPermit){
				//model
				$confgameModel = new ConfGame_model();
				$confgameModel->saveData($arrData);
			
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
			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->user_id;
			$objAdmin = $memberModel->getInfo($strUid);
			if($objAdmin->mb_level >= LEVEL_ADMIN){

				$arrResult['data'] = $confsiteModel->getBetSite($objAdmin->mb_level);
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
			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->user_id;
			$objAdmin = $memberModel->getInfo($strUid);
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				
				$confsiteModel->setBetSite($objAdmin->mb_level, $arrData);
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
			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->user_id;
			$objAdmin = $memberModel->getInfo($strUid);
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
			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->user_id;
			$objAdmin = $memberModel->getInfo($strUid);
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
			$memberModel  = new Member_Model();
			$strUid = $this->session->user_id;
			$iResult = $memberModel->changepassword($strUid, $arrData);
			
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

	//게임설정 보관 
	public function changealarmstate(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);		

		if(is_login())
		{
			$memberModel  = new Member_Model();
			$strUid = $this->session->user_id;
			$iResult = $memberModel->changeAlarmState($strUid, $arrData);
			
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
			$memberModel  = new Member_Model();
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
			$memberModel  = new Member_Model();
			$moneyhistoryModel = new MoneyHistory_Model();
			$bResult = false;
			$objAdmin = $memberModel->getInfo($strUid);

			if($objAdmin->mb_level >= LEVEL_ADMIN) {
				$objCharge = $chargeModel->get($arrReqData['charge_fid']);
				if(!is_null($objCharge)){
					$objUser = $memberModel->getInfo($objCharge->charge_mb_uid);
					if(!is_null($objUser)){
						if($arrReqData['process'] == 0){//취소
							if($objCharge->charge_action_state == 2){//승인된 상태에서만 진행
								//member Table

								if($objUser->mb_money >= $objCharge->charge_money){
									$dtMoney = 0-$objCharge->charge_money;
									$nCharge = 0-$objCharge->charge_money;
									$bResult = $memberModel->moneyProc($objUser, $dtMoney, 0, $nCharge, 0);
									if($bResult){
										//moneyhistory Table
										$moneyhistoryModel->cancelCharge($objUser, $objCharge->charge_money);

										//charge Table 
										$objCharge->charge_action_state = 3;
										$objCharge->charge_action_uid = $strUid;
										$objCharge->charge_money_after = $objUser->mb_money + $dtMoney;
										$bResult = $chargeModel->permit($objCharge);
									}
								}
							}
						} else if($arrReqData['process'] == 1){//승인
							if($objCharge->charge_action_state == 1 || $objCharge->charge_action_state == 3 || $objCharge->charge_action_state == 4){ //대기이거나 거절된 상태에서만 진행
								
								$dtMoney = $objCharge->charge_money;
								$nCharge = $objCharge->charge_money;
								$bResult = $memberModel->moneyProc($objUser, $dtMoney, 0, $nCharge, 0);
								if($bResult){
									//moneyhistory Table
									$moneyhistoryModel->registerCharge($objUser, $objCharge->charge_money);
									//charge Table 
									$objCharge->charge_action_state = 2;
									$objCharge->charge_action_uid = $strUid;
									$objCharge->charge_money_after = $objUser->mb_money + $dtMoney;
									$bResult = $chargeModel->permit($objCharge);
								}
							}
						} else if($arrReqData['process'] == 2){	//거절
							if($objCharge->charge_action_state == 1 || $objCharge->charge_action_state == 4){//대기 상태에서만 진행
								//charge Table 
								$objCharge->charge_action_state = 3;
								$objCharge->charge_action_uid = $strUid;
								$objCharge->charge_money_after = $objUser->mb_money;
								$bResult = $chargeModel->permit($objCharge);	
							}		
						}
						 else if($arrReqData['process'] == 3){	//임시대기
							if($objCharge->charge_action_state == 1){//대기 상태에서만 진행
								//charge Table 
								$objCharge->charge_action_state = 4;
								$objCharge->charge_action_uid = $strUid;
								$objCharge->charge_money_after = $objUser->mb_money;
								$bResult = $chargeModel->permit($objCharge);	
							}		
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
			$memberModel  = new Member_Model();
			$moneyhistoryModel = new MoneyHistory_Model();
			$bResult = false;
			
			$objAdmin = $memberModel->getInfo($strUid);

			if($objAdmin->mb_level >= LEVEL_ADMIN) {
				$objExchange = $exchangeModel->get($arrReqData['exchange_fid']);
				if(!is_null($objExchange)){
					$objUser = $memberModel->getInfo($objExchange->exchange_mb_uid);
					if(!is_null($objUser)){
						if($arrReqData['process'] == 0){	//취소
							if($objExchange->exchange_action_state == 2){	//승인된 상태에서만 진행
								
								//member Table
								$dtMoney = $objExchange->exchange_money;
								$nExchange = 0-$objExchange->exchange_money;
								$bResult = $memberModel->moneyProc($objUser, $dtMoney, 0, 0, $nExchange);
								if($bResult){
									//moneyhistory Table
									$moneyhistoryModel->cancelExchange($objUser, $objExchange->exchange_money);
									//exchange Table 
									$objExchange->exchange_action_state = 3;
									$objExchange->exchange_action_uid = $strUid;
									$objExchange->exchange_money_after = $objUser->mb_money + $dtMoney;
									$bResult = $exchangeModel->permit($objExchange);
								}
							}
						}
						else if($arrReqData['process'] == 1){		//승인

							if($objExchange->exchange_action_state == 1 || $objExchange->exchange_action_state == 3  || $objExchange->exchange_action_state == 4){ //대기이거나 거절된 상태에서만 진행
								
								if($objUser->mb_money >= $objExchange->exchange_money){
									$dtMoney = 0-$objExchange->exchange_money;
									$nExchange = $objExchange->exchange_money;
									$bResult = $memberModel->moneyProc($objUser, $dtMoney, 0, 0, $nExchange);
									if($bResult){
										//moneyhistory Table
										$moneyhistoryModel->registerExchange($objUser, $objExchange->exchange_money);
										//exchange Table 
										$objExchange->exchange_action_state = 2;
										$objExchange->exchange_action_uid = $strUid;
										$objExchange->exchange_money_after = $objUser->mb_money + $dtMoney;
										$bResult = $exchangeModel->permit($objExchange);
									}
								}
							}
						} else if($arrReqData['process'] == 2){					//거절
							if($objExchange->exchange_action_state == 1 || $objExchange->exchange_action_state == 4 ){		//대기상태에서만 진행
								//charge Table 
								$objExchange->exchange_action_state = 3;
								$objExchange->exchange_action_uid = $strUid;
								$objExchange->exchange_money_after = $objUser->mb_money;
								$bResult = $exchangeModel->permit($objExchange);
							}			
						} else if($arrReqData['process'] == 3){					//임시대기
							if($objExchange->exchange_action_state == 1 ){		//대기상태에서만 진행
								//charge Table 
								$objExchange->exchange_action_state = 4;
								$objExchange->exchange_action_uid = $strUid;
								$objExchange->exchange_money_after = $objUser->mb_money;
								$bResult = $exchangeModel->permit($objExchange);
							}			
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



		//사용자 정보 변경  
	public function updateNotice(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);
		
		if(is_login())
		{
			$bPermit = false;
			$noticeModel = new Notice_Model();
			$memberModel  = new Member_Model();
			$strUid = $this->session->user_id;
			$objUser = $memberModel->getInfo($strUid);
			
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
			$memberModel  = new Member_Model();
			$strUid = $this->session->user_id;
			$objUser = $memberModel->getInfo($strUid);
			
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
			$memberModel  = new Member_Model();
			$strUid = $this->session->user_id;
			$objUser = $memberModel->getInfo($strUid);
			
			//현재 가입한 유저가 요청한 유저보다 레벨이 높은 경우에 변경이 가능하다.
			if(!is_null($objUser))
			{				
				if($objUser->mb_level >= LEVEL_ADMIN )
					$bPermit = true;					
			}			

			if($bPermit){
				if($arrData['notice_mb_uid'] === '*'){
					$arrData['notice_read_count'] = 1;
					$iInsFid = $noticeModel->addNotice($arrData);
					if($iInsFid > 0){
						//date_default_timezone_set('Asia/Seoul');

						$arrMember = $memberModel->getMemberByLevel(LEVEL_ADMIN, true);
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
					$objMember = $memberModel->getInfo($arrData['notice_mb_uid']);		
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
			$memberModel  = new Member_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $memberModel->getInfo($strUid);
			
			$arrBetResults = $moneyhistoryModel->search($objAdmin, $arrGetData);
		
			//var_dump($arrBetHistory);
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
			$memberModel  = new Member_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $memberModel->getInfo($strUid);
			
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
			$memberModel  = new Member_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $memberModel->getInfo($strUid);
			
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
			$memberModel  = new Member_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $memberModel->getInfo($strUid);
			
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
		//var_dump($arrBetData);
		if(is_login()) {
			//model
			$memberModel  = new Member_Model();
			$strUid = $this->session->user_id;
			$objUser = $memberModel->getInfo($strUid);
			$arrResult = array();
			$arrEmp = array();
			//회원정보가 없다면
			$bResult = false;
			if($arrReqData['mb_fid'] == 0){	
				if($objUser->mb_level > LEVEL_COMPANY){//관리자이면 부본사정보를 반환
					$arrEmp = $memberModel->getMemberByLevel(LEVEL_COMPANY);
					$bResult = true;
					
				} else if($objUser->mb_level >= LEVEL_MIN){	// 가입한 성원자정보를 반환
					
					$arrEmp[0] = $objUser;
					$bResult = true;
				}

			} else {						//요청한 등급이하 정보를 반환
					$objReqEmp = $memberModel->getInfoByFid($arrReqData['mb_fid']);
					
					if(!is_null($objReqEmp)){
						if($objReqEmp->mb_level <= $objUser->mb_level){
							$arrEmp = $memberModel->getMemberByEmpFid($objReqEmp->mb_fid, $objReqEmp->mb_level-1);
							if(is_null($arrEmp))	$bResult = false;
							else $bResult = true; 
							
						}
					}
			}
			$arrData = array();
			
			if($bResult){
				
				$chargeModel = new Charge_Model();
				$exchangeModel = new Exchange_Model();
		        $rewardModel = new Reward_Model();
				$confsiteModel = new ConfSite_Model();

				$siteConfs = $this->getSiteConf($confsiteModel);
				foreach ($arrEmp as $objEmp) {
            		$objCalc['mb_fid'] = $objEmp->mb_fid;
		            $objCalc['mb_uid'] = $objEmp->mb_uid;
		            $objCalc['mb_nickname'] = $objEmp->mb_nickname;
		            $objCalc['mb_emp_fid'] = $objEmp->mb_emp_fid;
		            $objCalc['mb_level'] = $objEmp->mb_level;
		            $objCalc['mb_charge'] = $chargeModel->calcChargeMoney($objEmp, $arrReqData);         	//충전금액합산
		            $objCalc['mb_exchange'] = $exchangeModel->calcExchangeMoney($objEmp, $arrReqData);     	//환전금액합산
		            $objCalc['mb_charge_benefit'] = $objCalc['mb_charge'] - $objCalc['mb_exchange'];  		//충환손익
		            $arrEmpMoney = $memberModel->calcEmpMoney($objEmp);
		            $arrUserMoney = $memberModel->calcUserMoney($objEmp->mb_fid);
	            	$objCalc['mb_emp_money'] =  $arrEmpMoney[0];                        					//관리자보유금;
	            	$objCalc['mb_user_money'] = $arrUserMoney[0];											//유저보유금;
		            $arrBetData = $memberModel->calcBetMoneys($objEmp, $arrReqData, $siteConfs);
			        $objCalc['mb_bet_money'] = $arrBetData['bet_money'] ;          							//베팅머니
					$objCalc['mb_bet_win_money'] = $arrBetData['bet_win_money'] ;      						//적중머니
         			$objCalc['mb_bet_benefit_money'] = $arrBetData['bet_benefit_money'];  					//베팅손익
         			$objCalc['mb_rate_money'] = $rewardModel->calcPoint($objEmp, $arrReqData) ;   			//수수료
         			$objCalc['mb_last_money'] = $arrBetData['bet_benefit_money'] - $objCalc['mb_rate_money'] ; //최종손익

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
			$memberModel  = new Member_Model();
			$strUid = $this->session->user_id;
			$objUser = $memberModel->getInfo($strUid);
			$arrResult = array();
			$arrEmp = array();
			//회원정보가 없다면
			$bResult = false;
			if($arrReqData['mb_fid'] == 0){	
				if($objUser->mb_level > LEVEL_COMPANY){//관리자이면 부본사정보를 반환
					$arrEmp = $memberModel->getMemberByLevel(LEVEL_COMPANY);
					$bResult = true;
					
				} else if($objUser->mb_level >= LEVEL_MIN){	// 가입한 성원자정보를 반환
					
					$arrEmp[0] = $objUser;
					$bResult = true;
				}

			} else {						//요청한 등급이하 정보를 반환
					$objReqEmp = $memberModel->getInfoByFid($arrReqData['mb_fid']);
					if(!is_null($objReqEmp)){
						if($objReqEmp->mb_level <= $objUser->mb_level){
							$arrEmp = $memberModel->getMemberByEmpFid($objReqEmp->mb_fid, $objReqEmp->mb_level-1);
							if(is_null($arrEmp))	$bResult = false;
							else $bResult = true; 
						}
					}
			}
			$arrData = array();
			
			if($bResult){
				
				$chargeModel = new Charge_Model();
				$exchangeModel = new Exchange_Model();
				$rewardModel = new Reward_Model();

				foreach ($arrEmp as $objEmp) {
            		$objCalc['mb_fid'] = $objEmp->mb_fid;
		            $objCalc['mb_uid'] = $objEmp->mb_uid;
		            $objCalc['mb_nickname'] = $objEmp->mb_nickname;
		            $objCalc['mb_emp_fid'] = $objEmp->mb_emp_fid;
		            $objCalc['mb_level'] = $objEmp->mb_level;
		            $objCalc['mb_charge'] = $chargeModel->calcChargeMoney($objEmp, $arrReqData);         //충전금액합산
		            $objCalc['mb_exchange'] = $exchangeModel->calcExchangeMoney($objEmp, $arrReqData);     //환전금액합산
		            $objCalc['mb_charge_benefit'] = $objCalc['mb_charge'] - $objCalc['mb_exchange'];  //충환손익
		            $arrEmpMoney = $memberModel->calcEmpMoney($objEmp);
		            $arrUserMoney = $memberModel->calcUserMoney($objEmp->mb_fid);
					switch($arrReqData['type']){
						case GAME_CASINO_EVOL:
							$objCalc['mb_emp_money'] =  $arrEmpMoney[1];                        //관리자보유금;
		            		$objCalc['mb_user_money'] = $arrUserMoney[1];						//유저보유금;
							break;
						case GAME_SLOT_1:
							$objCalc['mb_emp_money'] =  $arrEmpMoney[2];                        //관리자보유금;
		            		$objCalc['mb_user_money'] = $arrUserMoney[2];						//유저보유금;
							break;
						case GAME_SLOT_2:
							$objCalc['mb_emp_money'] =  $arrEmpMoney[3];                        //관리자보유금;
							$objCalc['mb_user_money'] = $arrUserMoney[3];						//유저보유금;
							break;
						default:
							$objCalc['mb_emp_money'] =  $arrEmpMoney[0];                        //관리자보유금;
							$objCalc['mb_user_money'] = $arrUserMoney[0];						//유저보유금;
							break;
					}
		            $arrBetData = $memberModel->calcBetMoneysByGame($objEmp, $arrReqData);
					
		            if(is_null($arrBetData))	break;
			        $objCalc['mb_bet_money'] = $arrBetData['bet_money'] ;          //베팅머니
					$objCalc['mb_bet_win_money'] = $arrBetData['bet_win_money'] ;      //적중머니
         			$objCalc['mb_bet_benefit_money'] = $arrBetData['bet_benefit_money'];  //베팅손익
         			$objCalc['mb_rate_money'] = $rewardModel->calcPoint($objEmp, $arrReqData, $arrReqData['type']) ;          //수수료
         			$objCalc['mb_last_money'] = $arrBetData['bet_benefit_money'] - $objCalc['mb_rate_money'] ; //최종손익

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
		
			//var_dump($arrBetHistory);
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



	//베팅리력결과를 Ajax로 전송
	public function csbetlist(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);

		if(is_login()) {
			//model
			$csbetModel = new CsBet_model();
			$memberModel  = new Member_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $memberModel->getInfo($strUid);

			$bPoint = false;
			$arrBetAccount = null;
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				if(strlen(trim($arrGetData['emp'])) > 0){
					$objAdmin = $memberModel->getInfo(trim($arrGetData['emp']));
					$bPoint = true;
				}
				else 	
					$arrBetAccount = $csbetModel->getBetAccount($arrGetData);
			} else {
				$bPoint = true;
			}

			$arrBetResults = $csbetModel->search($objAdmin, $arrGetData);
			
			$objResult = new \StdClass;
			$objResult->data = $arrBetResults;	
			$objResult->account = $arrBetAccount;	
			$objResult->point = $bPoint?1:0;	
			$objResult->status = "success";
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}



	//베팅리력결과 개수를 Ajax로 전송
	public function csbetlistcnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);

		if(is_login()) {
			//model
			$csbetModel = new CsBet_model();
			$memberModel  = new Member_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $memberModel->getInfo($strUid);
			if($objAdmin->mb_level >= LEVEL_ADMIN && strlen(trim($arrGetData['emp'])) > 0){
				$objAdmin = $memberModel->getInfo(trim($arrGetData['emp']));
			}
			$objCount = $csbetModel->searchCount($objAdmin, $arrGetData);
			
			$arrResult['data'] = $objCount;
			$arrResult['status'] = "success";
		
			echo json_encode($arrResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}

	//베팅리력결과를 Ajax로 전송
	public function slbetlist(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);

		if(is_login()) {
			//model
			$slbetModel = new SlBet_model();
			$memberModel  = new Member_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $memberModel->getInfo($strUid);
			$bPoint = false;
			$arrBetAccount = null;
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				if(strlen(trim($arrGetData['emp'])) > 0){
					$objAdmin = $memberModel->getInfo(trim($arrGetData['emp']));
					$bPoint = true;
				}
				else 	
					$arrBetAccount = $slbetModel->getBetAccount($arrGetData);
			} else {
				$bPoint = true;
			}

			$arrBetResults = $slbetModel->search($objAdmin, $arrGetData);
			
			$objResult = new \StdClass;
			$objResult->data = $arrBetResults;	
			$objResult->account = $arrBetAccount;	
			$objResult->point = $bPoint?1:0;	
			$objResult->status = "success";
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}



	//베팅리력결과 개수를 Ajax로 전송
	public function slbetlistcnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);

		if(is_login()) {
			//model
			$slbetModel = new SlBet_model();
			$memberModel  = new Member_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $memberModel->getInfo($strUid);
			if($objAdmin->mb_level >= LEVEL_ADMIN && strlen(trim($arrGetData['emp'])) > 0){
				$objAdmin = $memberModel->getInfo(trim($arrGetData['emp']));
			}
			$objCount = $slbetModel->searchCount($objAdmin, $arrGetData);
			
			$arrResult['data'] = $objCount;
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
			$memberModel  = new Member_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $memberModel->getInfo($strUid);
			if($objAdmin->mb_level < LEVEL_ADMIN){
				$objResult->status = "fail";
			} else {
				$objResult->status = "success";
				$objResult->data = $slgameModel->search($arrReqData);
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
			$memberModel  = new Member_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $memberModel->getInfo($strUid);
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
			$memberModel  = new Member_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $memberModel->getInfo($strUid);
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

	
	//DB 정리
	public function cleanDb(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		if(is_login()) {
			$memberModel  = new Member_Model();
			$cleanModel = new Clean_model();

			$strUid = $this->session->user_id;
			$objAdmin = $memberModel->getInfo($strUid);

			$iResult = 0;

			if($objAdmin->mb_level>LEVEL_ADMIN){
				if($arrReqData['clean'] == 0){
					$iResult = $cleanModel->cleanDb();
				} else if($arrReqData['clean'] == 1){
					$iResult = $cleanModel->initDb();
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
    