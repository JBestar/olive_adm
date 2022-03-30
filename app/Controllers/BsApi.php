<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\BsBet_model;
use App\Models\BsRound_model;
use App\Models\ConfGame_model;
use App\Models\Member_Model;
use App\Models\MoneyHistory_model;

class BsApi extends BaseController {

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
			$bsroundModel = new BsRound_model();
			$objResults = $bsroundModel->search($arrGetData);

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
			$bsroundModel = new BsRound_model();
			$objCount = $bsroundModel->searchCount($arrGetData);
			
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
			$bsbetModel = new BsBet_model();
			$confgameModel = new ConfGame_model();
			

			$arrRoundInfo = getBRoundInfo(ROUND_3MIN);
			$objConfBs = $confgameModel->getByIndex(GAME_BOGLE_LADDER);

			$arrBetSum = $bsbetModel->getBetSumByMode($arrRoundInfo, $objConfBs);
					
			$objData = new \StdClass;
			$objData->roundid = $arrRoundInfo['round_no'];
			$objData->betend = $arrRoundInfo['round_end'];
			$objData->betsums = $arrBetSum;
			$objData->config = $objConfBs;
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
			$bsbetModel = new BsBet_model();
			$memberModel  = new Member_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $memberModel->getInfo($strUid);
			$arrBetResults = $bsbetModel->search($objAdmin, $arrGetData);
			$arrBetAccount = null;
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				$arrBetAccount = $bsbetModel->getBetAccount($arrGetData);
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
			$bsbetModel = new BsBet_model();
			$memberModel  = new Member_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $memberModel->getInfo($strUid);
			$objCount = $bsbetModel->searchCount($objAdmin, $arrGetData);
			
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
			$bsroundModel = new BsRound_model();
			$strUid = $this->session->user_id;
			$objUser = $memberModel->getInfo($strUid);
			$iResult = 0;
			if($objUser->mb_level >=  LEVEL_ADMIN){
				if(isEnableRound($arrGetData, ROUND_3MIN)){
					
					$iResult = $bsroundModel->register($arrGetData);	
						
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
			$bsroundModel = new BsRound_model();
			$strUid = $this->session->user_id;
			$objUser = $memberModel->getInfo($strUid);
			$bResult = false;
			if($objUser->mb_level >=  LEVEL_ADMIN)
				$bResult = $bsroundModel->modify($arrGetData);

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
			$bsbetModel = new BsBet_model();
			$moneyhistoryModel = new MoneyHistory_model();
			
			$strUid = $this->session->user_id;
			$objUser = $memberModel->getInfo($strUid);
			$bResult = false;
			if($objUser->mb_level >=  LEVEL_ADMIN){
				for($i = 0; $i < count($arrGetData); $i++) {
					$objBet = $bsbetModel->getByFid($arrGetData[$i]);
					if(!is_null($objBet) && $objBet->bet_state != 4){
						$bResult = $bsbetModel->ignore($objBet); 
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
				            	$moneyhistoryModel->registerAccountBet($objBetUser, $objBet, $dtMoney, MONEYCHANGE_WIN_BS);

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
			$bsroundModel = new BsRound_model();
			$bsbetModel = new BsBet_model();
			$moneyhistoryModel = new MoneyHistory_model();

			$strUid = $this->session->user_id;
			$objUser = $memberModel->getInfo($strUid);
			$bResult = false;
			if($objUser->mb_level >=  LEVEL_ADMIN){
				for($i = 0; $i < count($arrGetData); $i++) {
					$objBet = $bsbetModel->getByFid($arrGetData[$i]);
					if(is_null($objBet))
						continue;
					if($objBet->bet_state == 1 ){
						$strDate = getPbRoundDate($objBet->bet_time);
						$objRoundInfo = $bsroundModel->getByDate($strDate, $objBet->bet_round_no);
						
						$bResult = $bsbetModel->updateBetRound($objRoundInfo, $objBet); 
						if($bResult){
							$objBetUser = $memberModel->getInfo($objBet->bet_mb_uid);
							if($objBet->bet_win_money > 0){		//적중
					      		$dtMoney = $objBet->bet_win_money;
					            //$objBetUser->mb_money_earn += ($objBet->bet_win_money - $objBet->bet_money) ;
					            $bResult = $memberModel->moneyProc($objBetUser, $dtMoney, 0, 0, 0);
					            if($bResult){
					            	$moneyhistoryModel->registerAccountBet($objBetUser, $objBet, $dtMoney, MONEYCHANGE_WIN_BS);
					            }
							}
						} 	

					} else if($objBet->bet_state == 4){
						$strDate = getPbRoundDate($objBet->bet_time);
						$objRoundInfo = $bsroundModel->getByDate($strDate, $objBet->bet_round_no);
						
						$bResult = $bsbetModel->updateBetRound($objRoundInfo, $objBet); 
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

					            $moneyhistoryModel->registerAccountBet($objBetUser, $objBet, $dtMoney, MONEYCHANGE_WIN_BS);
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

