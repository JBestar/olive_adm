<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\ConfGame_model;
use App\Models\KsBet_model;
use App\Models\KsRound_model;
use App\Models\Member_Model;
use App\Models\MoneyHistory_model;

class KsApi extends BaseController {

	/**

	 */
	public function index()
	{
				
		if(is_login())
		{

		}
		else {
			$this->response->redirect( base_url().'pages/login');
		}	
		
	}


	//베팅결과를 Ajax로 전송
	public function result(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		
		if(is_login()) {

			//model
			$ksroundModel = new KsRound_model();
			$objResults = $ksroundModel->search($arrGetData);

			$arrResult['data'] = $objResults;
			$arrResult['status'] = "success";

			echo json_encode($arrResult);
		} else{
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		}

	}

	//베팅결과를 검색개수 Ajax로 전송
	public function resultcnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		
		if(is_login()) {

			//model
			$ksroundModel = new KsRound_model();
			$objCount = $ksroundModel->searchCount($arrGetData);
			
			$arrResult['data'] = $objCount;
			$arrResult['status'] = "success";

			echo json_encode($arrResult);
		} else{
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		}

	}


	//실시간베팅결과 합을 Ajax로 전송
	public function betrealtime(){ 
		if(is_login()) {
			$ksroundModel = new KsRound_model();
			$ksbetModel = new KsBet_model();
			$confgameModel = new ConfGame_model();
			

			$arrRoundInfo = getKsRoundInfo();
			$objConfKs = $confgameModel->getByIndex(GAME_KENO_LADDER);
			
			$arrBetSum = $ksbetModel->getBetSumByMode($arrRoundInfo, $objConfKs);
			
			$objData = new \StdClass;
			$objData->roundid = $arrRoundInfo['round_no'];
			$objData->betend = $arrRoundInfo['round_end'];
			$objData->betsums = $arrBetSum;
			$objData->config = $objConfKs;
			$bResult = true;

			if($bResult){
				$objResult = new \StdClass;
				$objResult->data = $objData;		
				$objResult->status = "success";
			
				echo json_encode($objResult);
			} else {
				$arrResult['status'] = "false";
				echo json_encode($arrResult);	
			}
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 
		
	}



	//베팅리력결과를 Ajax로 전송
	public function betlist(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		//var_dump($arrBetData);
		if(is_login()) {
			//model
			$ksbetModel = new KsBet_model();
			$memberModel  = new Member_Model();
			
			$strUid = $this->session->username;
			$objAdmin = $memberModel->getInfo($strUid);			
			$arrBetResults = $ksbetModel->search($objAdmin, $arrGetData);
			$arrBetAccount = null;
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				$arrBetAccount = $ksbetModel->getBetAccount($arrGetData);
			}
			
			$objResult = new \StdClass;
			$objResult->data = $arrBetResults;	
			$objResult->account = $arrBetAccount;		
			$objResult->status = "success";
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}



	//베팅리력결과 개수를 Ajax로 전송
	public function betlistcnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		//var_dump($arrBetData);
		if(is_login()) {
			//model
			$ksbetModel = new KsBet_model();
			$memberModel  = new Member_Model();
			
			$strUid = $this->session->username;
			$objAdmin = $memberModel->getInfo($strUid);			
			$objCount = $ksbetModel->searchCount($objAdmin, $arrGetData);
			
			$arrResult['data'] = $objCount;
			$arrResult['status'] = "success";
		
			echo json_encode($arrResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}
	
	//회차 결과 등록
	public function registerround(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		
		if(is_login()) {

			//model
			$memberModel  = new Member_Model();
			$ksroundModel = new KsRound_model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$iResult = 0;
			if($objUser->mb_level >=  LEVEL_ADMIN){
				if(isEnableRound($arrGetData, ROUND_5MIN)){
					
					$iResult = $ksroundModel->register($arrGetData);	
						
					//2: 이미 등록된 회차
				} else $iResult = 3;				//파라메터 오유
				
			} 
			if($iResult == 1)
				$arrResult['status'] = "success";
			else {
				$arrResult['status'] = "fail";
				$arrResult['data'] = $iResult;
			}
			

			echo json_encode($arrResult);
		} else{
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		}

	}
		//회차 결과 수정
	public function modifyround(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		
		if(is_login()) {

			//model
			$memberModel  = new Member_Model();
			$ksroundModel = new KsRound_model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$bResult = false;
			if($objUser->mb_level >=  LEVEL_ADMIN)
				$bResult = $ksroundModel->modify($arrGetData);

			$arrResult['status'] = $bResult?"success":"fail";

			echo json_encode($arrResult);
		} else{
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		}

	}



	//회차 무효 처리
	public function betignore(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		if(is_login()) {

			//model
			$memberModel  = new Member_Model();
			$ksbetModel = new KsBet_model();
			$moneyhistoryModel = new MoneyHistory_model();
			
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$bResult = false;
			if($objUser->mb_level >=  LEVEL_ADMIN){
				for($i = 0; $i < count($arrGetData); $i++) {
					$objBet = $ksbetModel->getByFid($arrGetData[$i]);
					if(!is_null($objBet) && $objBet->bet_state != 4){
						$bResult = $ksbetModel->ignore($objBet); 
						if($bResult){
							$objBetUser = $memberModel->getInfo($objBet->bet_mb_uid);
							if($objBet->bet_state == 2 || $objBet->bet_state == 1){		//미적중 혹은 대기중
								$dtMoney = $objBet->bet_money;
							}
							else if($objBet->bet_state == 3){	//적중
								$dtMoney = $objBet->bet_money - $objBet->bet_win_money;
							}
							$dtPoint = 0 - $objBet->point_amount;
							$bResult = $memberModel->moneyProc($objBetUser, $dtMoney, $dtPoint, 0, 0);
							if($bResult)
				            	$moneyhistoryModel->registerAccountBet($objBetUser, $objBet, $dtMoney, 12);

						} 	
					}
					
				}
				
			}

			$arrResult['status'] = $bResult?"success":"fail";

			echo json_encode($arrResult);
		} else{
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		}

	}	



		//회차 결과 처리
	public function betprocess(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		if(is_login()) {

			//model
			$memberModel  = new Member_Model();
			$ksroundModel = new KsRound_model();
			$ksbetModel = new KsBet_model();
			$moneyhistoryModel = new MoneyHistory_model();

			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$bResult = false;
			if($objUser->mb_level >=  LEVEL_ADMIN){
				for($i = 0; $i < count($arrGetData); $i++) {
					$objBet = $ksbetModel->getByFid($arrGetData[$i]);
					if(is_null($objBet))
						continue;
					if($objBet->bet_state == 1 ){
						$strDate = getKsRoundDate($objBet->bet_time);
						$objRoundInfo = $ksroundModel->getByDate($strDate, $objBet->bet_round_no);
						
						$bResult = $ksbetModel->updateBetRound($objRoundInfo, $objBet); 
						if($bResult){
							$objBetUser = $memberModel->getInfo($objBet->bet_mb_uid);
							if($objBet->bet_win_money > 0){		//적중
					      		$dtMoney = $objBet->bet_win_money;
					            //$objBetUser->mb_money_earn += ($objBet->bet_win_money - $objBet->bet_money) ;
					            $bResult = $memberModel->moneyProc($objBetUser, $dtMoney, 0, 0, 0);
					            if($bResult){
					            	$moneyhistoryModel->registerAccountBet($objBetUser, $objBet, $dtMoney, 12);
					            }
							}
						} 	

					} else if($objBet->bet_state == 4){
						$strDate = getKsRoundDate($objBet->bet_time);
						$objRoundInfo = $ksroundModel->getByDate($strDate, $objBet->bet_round_no);
						
						$bResult = $ksbetModel->updateBetRound($objRoundInfo, $objBet); 
						if($bResult){
							$objBetUser = $memberModel->getInfo($objBet->bet_mb_uid);
							if($objBet->bet_win_money > 0){		//적중
					      		$dtMoney = $objBet->bet_win_money - $objBet->bet_money;
							} else {
								$dtMoney = 0 - $objBet->bet_money;								
							}
							$dtPont = $objBet->point_amount;
							$bResult = $memberModel->moneyProc($objBetUser, $dtMoney, $dtPont, 0, 0);
							if($bResult){

					            $moneyhistoryModel->registerAccountBet($objBetUser, $objBet, $dtMoney, 12);
							}
						} 	
					}
					
				}
				
			}

			$arrResult['status'] = "success";

			echo json_encode($arrResult);
		} else{
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		}

	}	
}
