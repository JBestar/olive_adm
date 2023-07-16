<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\ConfGame_Model;
use App\Models\MoneyHistory_Model;
use App\Models\PbBet_Model;
use App\Models\PbRound_Model;
use App\Models\PsBet_Model;
use App\Models\CsBet_Model;
use App\Models\PsRound_Model;
use App\Models\EbalBet_Model;

class PbApi extends BaseController {

	/**

	 */
	public function index()
	{
				
		if(is_login())
		{

		}
		else {
			$this->response->redirect( $_ENV['app.furl'].'/pages/login');
		}	
		
	}

	//배팅결과를 Ajax로 전송
	public function result(){ 
		
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		if(is_login()) {
			$bResult = true;
			if($arrReqData['game'] == GAME_POWER_BALL || $arrReqData['game'] == GAME_HAPPY_BALL){
				$roundModel = new PbRound_Model();
				$roundModel->setType($arrReqData['game']);
				$arrResult = $roundModel->search($arrReqData);
			} else if($arrReqData['game'] == GAME_POWER_LADDER){
				$roundModel = new PsRound_Model();
				$roundModel->setType($arrReqData['game']);
				$arrResult = $roundModel->search($arrReqData);
			} else if($arrReqData['game'] == GAME_BOGLE_BALL){
				$roundModel = new PbRound_Model();
				$roundModel->setType($arrReqData['game']);
				$arrResult = $roundModel->search($arrReqData);
			} else if($arrReqData['game'] == GAME_BOGLE_LADDER){
				$roundModel = new PsRound_Model();
				$roundModel->setType($arrReqData['game']);
				$arrResult = $roundModel->search($arrReqData);
			}  else if($arrReqData['game'] >= GAME_EOS5_BALL && $arrReqData['game'] <= GAME_COIN3_BALL){
				$roundModel = new PbRound_Model();
				$roundModel->setType($arrReqData['game']);
				$arrResult = $roundModel->search($arrReqData);
			}  else $bResult = false;
				
			if($bResult){
				
				$objResult = new \StdClass;
				$objResult->data = $arrResult;		
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

	
	//배팅결과를 검색개수 Ajax로 전송
	public function resultcnt(){ 
			
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		if(is_login()) {
			$bResult = true;
			if($arrReqData['game'] == GAME_POWER_BALL || $arrReqData['game'] == GAME_HAPPY_BALL){
				$roundModel = new PbRound_Model();
				$roundModel->setType($arrReqData['game']);
				$objCount = $roundModel->searchCount($arrReqData);
			} else if($arrReqData['game'] == GAME_POWER_LADDER){
				$roundModel = new PsRound_Model();
				$roundModel->setType($arrReqData['game']);
				$objCount = $roundModel->searchCount($arrReqData);
			} else if($arrReqData['game'] == GAME_BOGLE_BALL){
				$roundModel = new PbRound_Model();
				$roundModel->setType($arrReqData['game']);
				$objCount = $roundModel->searchCount($arrReqData);
			} else if($arrReqData['game'] == GAME_BOGLE_LADDER){
				$roundModel = new PsRound_Model();
				$roundModel->setType($arrReqData['game']);
				$objCount = $roundModel->searchCount($arrReqData);
			}  else if($arrReqData['game'] >= GAME_EOS5_BALL && $arrReqData['game'] <= GAME_COIN3_BALL){
				$roundModel = new PbRound_Model();
				$roundModel->setType($arrReqData['game']);
				$objCount = $roundModel->searchCount($arrReqData);
			} else $bResult = false;
				
			if($bResult){
				
				$objResult = new \StdClass;
				$objResult->data = $objCount;		
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

	//배팅리력결과를 Ajax로 전송
	public function betlist(){ 
		
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		if(is_login()) {
			$bResult = true;
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);

			if($arrReqData['game'] == GAME_POWER_BALL || $arrReqData['game'] == GAME_BOGLE_BALL
				|| ($arrReqData['game'] >= GAME_EOS5_BALL && $arrReqData['game'] <= GAME_COIN3_BALL) 
				|| $arrReqData['game'] == GAME_HAPPY_BALL){

				$betModel = new PbBet_Model();
				$betModel->setType($arrReqData['game']);
			} else if($arrReqData['game'] == GAME_POWER_LADDER || $arrReqData['game'] == GAME_BOGLE_LADDER){

				$betModel = new PsBet_Model();
				$betModel->setType($arrReqData['game']);
			} else $bResult = false;
				
			if($bResult){
				
				if($objAdmin->mb_level >= LEVEL_ADMIN && strlen(trim($arrReqData['emp'])) > 0){
					$objAdmin = $this->modelMember->getInfo(trim($arrReqData['emp']));
				}
				$arrBetResults = $betModel->search($objAdmin, $arrReqData);
				
				$objResult = new \StdClass;
				$objResult->data = $arrBetResults;	
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



	//배팅리력결과 개수를 Ajax로 전송
	public function betlistcnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		if(is_login()) {
			//model
			$bResult = true;

			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);

			if($arrReqData['game'] == GAME_POWER_BALL || $arrReqData['game'] == GAME_BOGLE_BALL 
				|| ($arrReqData['game'] >= GAME_EOS5_BALL && $arrReqData['game'] <= GAME_COIN3_BALL) 
				|| $arrReqData['game'] == GAME_HAPPY_BALL){

				$betModel = new PbBet_Model();
				$betModel->setType($arrReqData['game']);
			} else if($arrReqData['game'] == GAME_POWER_LADDER || $arrReqData['game'] == GAME_BOGLE_LADDER){

				$betModel = new PsBet_Model();
				$betModel->setType($arrReqData['game']);
			}  else $bResult = false;
			
			if($bResult){

				$arrBetAccount = null;
				if($objAdmin->mb_level >= LEVEL_ADMIN){
					if(strlen(trim($arrReqData['emp'])) > 0){
						$objAdmin = $this->modelMember->getInfo(trim($arrReqData['emp']));
					}
				} 
				$arrBetAccount = $betModel->getBetAccount($objAdmin, $arrReqData);
				$objCount = $betModel->searchCount($objAdmin, $arrReqData);
				
				$arrResult['data'] = $objCount;
				$arrResult['account'] = $arrBetAccount;		
				$arrResult['status'] = "success";
			
				echo json_encode($arrResult);
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


	//실시간배팅결과 합을 Ajax로 전송
	public function betrealtime(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		if(is_login()) {
			$bResult = true;
			$confgameModel = new ConfGame_Model();

			$objConf = $confgameModel->getByIndex($arrReqData['game']);
			if($arrReqData['game'] == GAME_POWER_BALL){

				$betModel = new PbBet_Model();
				$betModel->setType($arrReqData['game']);
				$arrRoundInfo = getPbRoundInfo();
				$arrBetSum = $betModel->getBetSumByMode($arrRoundInfo, $objConf);
			} else if($arrReqData['game'] == GAME_POWER_LADDER){

				$betModel = new PsBet_Model();
				$betModel->setType($arrReqData['game']);
				$arrRoundInfo = getPbRoundInfo();
				$arrBetSum = $betModel->getBetSumByMode($arrRoundInfo, $objConf);
			} else if($arrReqData['game'] == GAME_BOGLE_BALL){

				$betModel = new PbBet_Model();
				$betModel->setType($arrReqData['game']);
				$arrRoundInfo = getBRoundInfo(ROUND_2MIN);
				$arrBetSum = $betModel->getBetSumByMode($arrRoundInfo, $objConf);
			} else if($arrReqData['game'] == GAME_BOGLE_LADDER){

				$betModel = new PsBet_Model();
				$betModel->setType($arrReqData['game']);
				$arrRoundInfo = getBRoundInfo(ROUND_3MIN);
				$arrBetSum = $betModel->getBetSumByMode($arrRoundInfo, $objConf);
			} else if($arrReqData['game'] >= GAME_EOS5_BALL && $arrReqData['game'] <= GAME_COIN3_BALL){

				$betModel = new PbBet_Model();
				$betModel->setType($arrReqData['game']);
				if($arrReqData['game'] == GAME_EOS5_BALL || $arrReqData['game'] == GAME_COIN5_BALL)
					$arrRoundInfo = getBRoundInfo(ROUND_5MIN);
				else $arrRoundInfo = getBRoundInfo(ROUND_3MIN);
				$arrBetSum = $betModel->getBetSumByMode($arrRoundInfo, $objConf);
			} else if($arrReqData['game'] == GAME_HAPPY_BALL){

				$betModel = new PbBet_Model();
				$betModel->setType($arrReqData['game']);
				$arrRoundInfo = getBRoundInfo(ROUND_5MIN);
				$arrBetSum = $betModel->getBetSumByMode($arrRoundInfo, $objConf);
			} else $bResult = false;
			
				
			if($bResult){
				
				$objData = new \StdClass;
				$objData->roundid = $arrRoundInfo['round_no'];
				$objData->betend = $arrRoundInfo['round_end'];
				$objData->betsums = $arrBetSum;
				$objData->config = $objConf;
				
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

	//회차 결과 등록
	public function registerround(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		
		if(is_login()) {
			$bResult = true;
			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			if($arrReqData['game'] == GAME_POWER_BALL || $arrReqData['game'] == GAME_HAPPY_BALL){

				$roundModel = new PbRound_Model();
			} else if($arrReqData['game'] == GAME_POWER_LADDER){

				$roundModel = new PsRound_Model();
			} else if($arrReqData['game'] == GAME_BOGLE_BALL){

				$roundModel = new PbRound_Model();
			} else if($arrReqData['game'] == GAME_BOGLE_LADDER){

				$roundModel = new PsRound_Model();
			}  else if($arrReqData['game'] >= GAME_EOS5_BALL && $arrReqData['game'] <= GAME_COIN3_BALL){

				$roundModel = new PbRound_Model();
			} else $bResult = false;
				
			if($bResult){
				$roundModel->setType($arrReqData['game']);
			
				$iResult = 0;
				if($objUser->mb_level >=  LEVEL_ADMIN){
					// if(isEnableRound($arrReqData, ROUND_5MIN)){
						$iResult = $roundModel->register($arrReqData);	
						//2: 이미 등록된 회차
					// } else $iResult = 3;				//파라메터 오류
					
				} 
				if($iResult == 1)
					$arrResult['status'] = "success";
				else {
					$arrResult['status'] = "fail";
					$arrResult['data'] = $iResult;
				}
			} else {
				$arrResult['status'] = "false";
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
		$arrReqData = json_decode($jsonData, true);
		
		if(is_login()) {

			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			
			$bResult = true;
			if($arrReqData['game'] == GAME_POWER_BALL || $arrReqData['game'] == GAME_HAPPY_BALL){

				$roundModel = new PbRound_Model();
			} else if($arrReqData['game'] == GAME_POWER_LADDER){

				$roundModel = new PsRound_Model();
			} else if($arrReqData['game'] == GAME_BOGLE_BALL){

				$roundModel = new PbRound_Model();
			} else if($arrReqData['game'] == GAME_BOGLE_LADDER){

				$roundModel = new PsRound_Model();
			}  else if($arrReqData['game'] >= GAME_EOS5_BALL && $arrReqData['game'] <= GAME_COIN3_BALL){

				$roundModel = new PbRound_Model();
			} else $bResult = false;
			
			if($bResult){
				$roundModel->setType($arrReqData['game']);

				$bResult = false;
				if($objUser->mb_level >=  LEVEL_ADMIN)
					$bResult = $roundModel->modify($arrReqData);

				$arrResult['status'] = $bResult?"success":"fail";
				echo json_encode($arrResult);
			} else {
				$arrResult['status'] = "false";
				echo json_encode($arrResult);	
			}
		} else{
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		}

	}

//회차 무효 처리
	public function betignore(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login()) {
			$bResult = true;

			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			//model
			$moneyhistoryModel = new MoneyHistory_Model();
			$iChangeType = 0;
			if($arrReqData['game'] == GAME_POWER_BALL || $arrReqData['game'] == GAME_HAPPY_BALL){
				$iChangeType = MONEYCHANGE_WIN_PB;

				$betModel = new PbBet_Model();
			} else if($arrReqData['game'] == GAME_POWER_LADDER){
				$iChangeType = MONEYCHANGE_WIN_PS;

				$betModel = new PsBet_Model();
			} else if($arrReqData['game'] == GAME_BOGLE_BALL){
				$iChangeType = MONEYCHANGE_WIN_BB;

				$betModel = new PbBet_Model();
			} else if($arrReqData['game'] == GAME_BOGLE_LADDER){
				$iChangeType = MONEYCHANGE_WIN_BS;

				$betModel = new PsBet_Model();
			}  else if($arrReqData['game'] == GAME_EOS5_BALL){
				$iChangeType = MONEYCHANGE_WIN_EO5;

				$betModel = new PbBet_Model();
			} else if($arrReqData['game'] == GAME_EOS3_BALL){
				$iChangeType = MONEYCHANGE_WIN_EO3;

				$betModel = new PbBet_Model();
			} else if($arrReqData['game'] == GAME_COIN5_BALL){
				$iChangeType = MONEYCHANGE_WIN_CO5;

				$betModel = new PbBet_Model();
			} else if($arrReqData['game'] == GAME_COIN3_BALL){
				$iChangeType = MONEYCHANGE_WIN_CO3;

				$betModel = new PbBet_Model();
			} else $bResult = false;
			
			if($bResult){
				$betModel->setType($arrReqData['game']);

				$bResult = false;
				if($objUser->mb_level >=  LEVEL_ADMIN){
					for($i = 0; $i < count($arrReqData['data']); $i++) {
						$objBet = $betModel->getByFid($arrReqData['data'][$i]);
						if(!is_null($objBet) && $objBet->bet_state != BET_STATE_TIE){
							$bResult = $betModel->ignore($objBet); 
							if($bResult){
								$objBetUser = $this->modelMember->getInfo($objBet->bet_mb_uid);
								if($objBet->bet_state == 2 || $objBet->bet_state == 1){		//미적중 혹은 대기중
									$dtMoney = $objBet->bet_money;
								}
								else if($objBet->bet_state == 3){	//적중
									$dtMoney = $objBet->bet_money - $objBet->bet_win_money;
								}
								$dtPoint = 0 - $objBet->point_amount;
								$bResult = $this->modelMember->moneyProc($objBetUser, $dtMoney, $dtPoint, 0, 0);
								if($bResult)
									$moneyhistoryModel->registerAccountBet($objBetUser, $objBet, $dtMoney, $iChangeType);

							} 	
						}
						
					}
					
				}

				$arrResult['status'] = $bResult?"success":"fail";
				echo json_encode($arrResult);
			} else {
				$arrResult['status'] = "false";
				echo json_encode($arrResult);	
			}
		} else{
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		}

	}	



	//회차 결과 처리
	public function betprocess(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login()) {
			$bResult = true;

			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			$iChangeType = 0;
			//model
			$moneyhistoryModel = new MoneyHistory_Model();
			if($arrReqData['game'] == GAME_POWER_BALL || $arrReqData['game'] == GAME_HAPPY_BALL){
				$iChangeType = MONEYCHANGE_WIN_PB;

				$roundModel = new PbRound_Model();
				$betModel = new PbBet_Model();
			} else if($arrReqData['game'] == GAME_POWER_LADDER){
				$iChangeType = MONEYCHANGE_WIN_PS;

				$roundModel = new PsRound_Model();
				$betModel = new PsBet_Model();
			} else if($arrReqData['game'] == GAME_BOGLE_BALL){
				$iChangeType = MONEYCHANGE_WIN_BB;

				$roundModel = new PbRound_Model();
				$betModel = new PbBet_Model();
			} else if($arrReqData['game'] == GAME_BOGLE_LADDER){
				$iChangeType = MONEYCHANGE_WIN_BS;

				$roundModel = new PsRound_Model();
				$betModel = new PsBet_Model();
			}  else if($arrReqData['game'] == GAME_EOS5_BALL || $arrReqData['game'] == GAME_EOS3_BALL){
				$iChangeType = $arrReqData['game'] == GAME_EOS5_BALL ? MONEYCHANGE_WIN_EO5:MONEYCHANGE_WIN_EO3;

				$roundModel = new PbRound_Model();
				$betModel = new PbBet_Model();
			}  else if($arrReqData['game'] == GAME_COIN5_BALL || $arrReqData['game'] == GAME_COIN3_BALL){
				$iChangeType = $arrReqData['game'] == GAME_COIN5_BALL ? MONEYCHANGE_WIN_CO5:MONEYCHANGE_WIN_CO3;

				$roundModel = new PbRound_Model();
				$betModel = new PbBet_Model();
			}  else $bResult = false;
				
			if($bResult){
				$roundModel->setType($arrReqData['game']);
				$betModel->setType($arrReqData['game']);

				$bResult = false;
				if($objUser->mb_level >=  LEVEL_ADMIN){
					for($i = 0; $i < count($arrReqData['data']); $i++) {
						$objBet = $betModel->getByFid($arrReqData['data'][$i]);
						if(is_null($objBet)) 
							continue;
						
						if($objBet->bet_state == 1){			//대기중이라면
							$strDate = getRoundDate($objBet->bet_time);
							$objRoundInfo = $roundModel->getByDate($strDate, $objBet->bet_round_no);
							$bResult = $betModel->updateBetRound($objRoundInfo, $objBet);
	
							if($bResult){
								$objBetUser = $this->modelMember->getInfo($objBet->bet_mb_uid);
								if($objBet->bet_win_money > 0){		//적중
									$dtMoney = $objBet->bet_win_money;
									$bResult = $this->modelMember->moneyProc($objBetUser, $dtMoney, 0, 0, 0);
									if($bResult){
										$moneyhistoryModel->registerAccountBet($objBetUser, $objBet, $dtMoney, $iChangeType);
									}
								}
							} 	
						} else if($objBet->bet_state == 4){			//무효라면 
							$strDate = getRoundDate($objBet->bet_time);
							$objRoundInfo = $roundModel->getByDate($strDate, $objBet->bet_round_no);
							$bResult = $betModel->updateBetRound($objRoundInfo, $objBet);

							if($bResult){
								$objBetUser = $this->modelMember->getInfo($objBet->bet_mb_uid);
								if($objBet->bet_win_money > 0){		//적중
									$dtMoney = $objBet->bet_win_money - $objBet->bet_money;
								} else {
									$dtMoney = 0 - $objBet->bet_money;								
								}
								$dtPont = $objBet->point_amount;
								$bResult = $this->modelMember->moneyProc($objBetUser, $dtMoney, $dtPont, 0, 0);
								if($bResult){
									$moneyhistoryModel->registerAccountBet($objBetUser, $objBet, $dtMoney, $iChangeType);
								}
							}
						}
						
					}
					
				}

				$arrResult['status'] = "success";
			} else {
				$arrResult['status'] = "false";
			}
			echo json_encode($arrResult);
		} else{
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		}

	}	


	public function csbetprocess(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login()) {
			$bResult = false;
			$moneyhistoryModel = new MoneyHistory_Model();
			$csbetModel = new EbalBet_Model();

			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			if(is_null($objUser) || $objUser->mb_level <  LEVEL_ADMIN){
				$bResult = false;
			} else {
				$state = intval($arrReqData['state']);
				for($i = 0; $i < count($arrReqData['data']); $i++) {
					$objBet = $csbetModel->find($arrReqData['data'][$i]);
					if(is_null($objBet)) 
						continue;

					if($objBet->point_amount == $state)
						continue;

					$winMoney = 0;
					if($state == BET_STATE_WIN){
						if($objBet->bet_choice == "Banker")
							$winMoney = $objBet->bet_money * RATE_BANKER;
						else if($objBet->bet_choice == "Tie")
							$winMoney = $objBet->bet_money * RATE_TIE;
						else
							$winMoney = $objBet->bet_money * RATE_PLAYER;
					} else if($state == BET_STATE_TIE){
						$winMoney = $objBet->bet_money;
					} else if($state == BET_STATE_LOSS){
						$winMoney = 0;
					} else continue;

					$dtMoney = 0;
					if($objBet->point_amount == 0){
						$dtMoney = $winMoney - $objBet->bet_money;
					} else if($objBet->point_amount == BET_STATE_LOSS){
						// $dtMoney = $objBet->bet_money + $winMoney - $objBet->bet_money;
						$dtMoney = $winMoney;
					} else if($objBet->point_amount == BET_STATE_WIN){
						// $dtMoney = $objBet->bet_money - $objBet->bet_win_money + $winMoney - $objBet->bet_money;
						$dtMoney = $winMoney - $objBet->bet_win_money;
					} else if($objBet->point_amount == BET_STATE_TIE){
						$dtMoney = $winMoney - $objBet->bet_money;
					} else continue;

					$objBetUser = $this->modelMember->getInfo($objBet->bet_mb_uid);
					if(is_null($objBetUser))
						continue;

					if($this->modelMember->moneyProc($objBetUser, $dtMoney)){
						
						$objBet->bet_win_money = $winMoney;
						$objBet->point_amount = $state;
						
						$moneyhistoryModel->registerAccountCsBet($objBetUser, $objBet, $dtMoney, MONEYCHANGE_DENY_EBAL);
						
						if(!$csbetModel->updateBet($objBet))
							continue;
					}

				}

				$bResult = true;
			}
			if($bResult)
				$arrResult['status'] = "success";
			else 
				$arrResult['status'] = "fail";

			echo json_encode($arrResult);
		} else {
			$arrResult['status'] = "logout";
			echo json_encode($arrResult);	
		}
	}



}