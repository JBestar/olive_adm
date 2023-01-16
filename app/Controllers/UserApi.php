<?php

namespace App\Controllers;

use App\Models\Charge_Model;
use App\Models\ConfGame_Model;
use App\Models\ConfSite_Model;
use App\Models\Exchange_Model;
use App\Models\Notice_Model;
use App\Models\PbBet_Model;
use App\Models\PsBet_Model;
use App\Models\CsBet_Model;
use App\Models\SlBet_Model;
use App\Models\MoneyHistory_Model;
use App\Models\SessLog_Model;
use App\Models\Block_Model;

use App\Libraries\ApiCas_Lib;
use App\Libraries\ApiSlot_Lib;
use App\Libraries\ApiFslot_Lib;

class UserApi extends BaseController
{
    public function index()
    {
        if (is_login()) {
            $this->response->redirect($_ENV['app.furl']);
        } else {
            $this->response->redirect($_ENV['app.furl'].'/pages/login');
        }
    }

    // 사용자 정보 변경
    public function addmember()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);
        if (is_login()) {
            $bPermit = false;
            
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);
            $arrData['mb_level'] = LEVEL_COMPANY;
            $arrData['mb_emp_fid'] = 0;
            if (strlen($arrData['mb_emp_uid']) > 0){
                $objEmp = $this->modelMember->getInfo($arrData['mb_emp_uid']);
                $minLevel = LEVEL_MIN;
                // writeLog("Level" . intval($_ENV['app.level_limit']));
                if(array_key_exists('app.level_limit', $_ENV) && intval($_ENV['app.level_limit']) > 0 ){
                    $minLevel = LEVEL_MAX - intval($_ENV['app.level_limit']);
                }

                if ($objEmp == null || $objEmp->mb_level <= $minLevel){
                    $arrResult['status'] = 'employee_error';
                    echo json_encode($arrResult);
                    return;
                }
                $arrData['mb_emp_fid'] = $objEmp->mb_fid;
                $arrData['mb_level'] = $objEmp->mb_level - 1;                
            }
            // 현재 가입한 유저가 요청한 유저보다 레벨이 높은 경우에 변경이 가능하다.
            if (!is_null($objUser)) {
                if ($objUser->mb_level >= LEVEL_ADMIN) {
                    $bPermit = true;
                }
            }
            if ($bPermit) {
                $strError = '';
                $confsiteModel = new ConfSite_Model();
                $confsiteModel->readMemConf();

                $iResult = $this->modelMember->register($arrData, $strError);
                if ( $iResult == 1 ) {
                    $arrResult['status'] = 'success';
                } elseif ( $iResult == 4 || $iResult == 5 ) {
                    $arrResult['status'] = 'ratio_error';
                    $arrResult['error'] = $strError;
                } elseif ( $iResult == -1 ) {
                    $arrResult['status'] = 'val_error';
                    $arrResult['error'] = $strError;
                } else {
                    $arrResult['status'] = 'fail';
                    $arrResult['error'] = $iResult;
                }
            } else {
                $arrResult['status'] = 'nopermit';
            }
        } else {
            $arrResult['status'] = 'logout';
        }
        echo json_encode($arrResult);
    }

    // 사용자 정보 수정
    public function modifymember()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            $bPermit = false;
            
            $strUid = $this->session->user_id;
            $objAdmin = $this->modelMember->getInfo($strUid);
            $objReqUser = $this->modelMember->getInfoByFid($arrData['mb_fid']);
            $arrData['mb_emp_fid'] = 0;
            if ($objAdmin->mb_level >= LEVEL_ADMIN && strlen($arrData['mb_emp_uid']) > 0){
                $objEmp = $this->modelMember->getInfo($arrData['mb_emp_uid']);
                if ($objEmp == null){
                    $arrResult['status'] = 'employee_error';
                    echo json_encode($arrResult);
                    return;
                }
                $arrData['mb_emp_fid'] = $objEmp->mb_fid;
            }
            
            // 현재 가입한 유저가 요청한 유저보다 레벨이 높은 경우에 변경이 가능하다.
            if (!is_null($objAdmin) && !is_null($objReqUser)) {
                if ($objAdmin->mb_level > $objReqUser->mb_level) {
                    $bPermit = true;
                }
            }
            if ($bPermit) {
                $strError = '';
                $query = "";
                $iResult = 0;
                if ($objAdmin->mb_level >= LEVEL_ADMIN) {
                    $iResult = $this->modelMember->modifyMember($arrData, $strError, $query);
                } else {
                    $iResult = $this->modelMember->modifyMemberRatio($arrData, $strError,  $query);
                }

                if ($iResult == 1) {
				    $this->modelModify->add($this->session->user_id, MOD_MB_INFO, $query, $this->request->getIPAddress());
                    $arrResult['status'] = 'success';
                } elseif ( $iResult == 4 || $iResult == 5 ) {
                    $arrResult['status'] = 'ratio_error';
                    $arrResult['error'] = $strError;
                } elseif ( $iResult == -1 ) {
                    $arrResult['status'] = 'val_error';
                    $arrResult['error'] = $strError;
                } else {
                    $arrResult['status'] = 'fail';
                    $arrResult['error'] = $iResult;
                }
            } else {
                $arrResult['status'] = 'nopermit';
            }
        } else {
            $arrResult['status'] = 'logout';
        }
        echo json_encode($arrResult);
    }

    // 사용자 정보 변경
    public function updatemember()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            $bPermit = false;
            
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);
            $objReqUser = $this->modelMember->getInfoByFid($arrData['mb_fid']);

            // 현재 가입한 유저가 요청한 유저보다 레벨이 높은 경우에 변경이 가능하다.
            if (!is_null($objUser) && !is_null($objReqUser)) {
                if ($objUser->mb_level >= LEVEL_ADMIN) {
                    $bPermit = true;
                }
            }
            if ($bPermit) {
                $query = "";
                $bResult = $this->modelMember->updateMemberByFid($arrData, $query);
                if ($bResult) {
                    $this->modelModify->add($this->session->user_id, MOD_MB_STATE, $query, $this->request->getIPAddress());
                    $arrResult['status'] = 'success';
                } else {
                    $arrResult['status'] = 'fail';
                }
            } else {
                $arrResult['status'] = 'nopermit';
            }
        } else {
            $arrResult['status'] = 'logout';
        }
        echo json_encode($arrResult);
    }

    // 사용자 삭제
    public function deletemember()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            $bPermit = false;
            
            $strUid = $this->session->user_id;
            $objAdmin = $this->modelMember->getInfo($strUid);
            $objReqUser = $this->modelMember->getInfoByFid($arrData['mb_fid']);

            // 현재 가입한 유저가 요청한 유저보다 레벨이 높은 경우에 삭제가 가능하다.
            if (!is_null($objAdmin) && !is_null($objReqUser)) {
                if ($objAdmin->mb_level >= LEVEL_ADMIN) {
                    $bPermit = true;
                }
            }
            if ($bPermit) {
                $arrData['mb_state_active'] = PERMIT_DELETE;
                $arrData['mb_fids'] = [$objReqUser->mb_fid];
                $arrMem = $this->modelMember->getMemberByEmpFid($objReqUser->mb_fid, $objReqUser->mb_level,  $objReqUser->mb_level, true);
                foreach($arrMem as $objMem)
                    array_push($arrData['mb_fids'], $objMem->mb_fid);

                $query = '';
                $bResult = $this->modelMember->updateMemberByFids($arrData, $query);
                
                if ($bResult) {
                    $this->modelModify->add($this->session->user_id, MOD_MB_STATE, $query, $this->request->getIPAddress());
                    $arrResult['status'] = 'success';
                } else {
                    $arrResult['status'] = 'fail';
                }
            } else {
                $arrResult['status'] = 'nopermit';
            }
        } else {
            $arrResult['status'] = 'logout';
        }
        echo json_encode($arrResult);
    }

    // 사용자 강제아웃
    public function logoutmember()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            $bPermit = false;
            
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);
            $objReqUser = $this->modelMember->getInfoByFid($arrData['mb_fid']);

            // 현재 가입한 유저가 요청한 유저보다 레벨이 높은 경우에 삭제가 가능하다.
            if (!is_null($objUser) && !is_null($objReqUser)) {
                if ($objUser->mb_level >= LEVEL_ADMIN) {
                    $bPermit = true;
                }
            }
            if ($bPermit) {
                
                $bResult = $this->modelSess->deleteByMember($objReqUser->mb_fid);
                
                if ($bResult) {
                    $arrResult['status'] = 'success';
                } else {
                    $arrResult['status'] = 'fail';
                }
            } else {
                $arrResult['status'] = 'nopermit';
            }
        } else {
            $arrResult['status'] = 'logout';
        }
        echo json_encode($arrResult);
    }

    // 회원정보  대기 승인 라이브게임아이디 생성한다.
    public function wait_permit()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            

            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);
            $objReqUser = $this->modelMember->getInfoByFid($arrData['mb_fid']);

            $bPermit = false;
            $bResult = false;
            $iCreated = 0;
            // 현재 가입한 유저가 요청한 유저보다 레벨이 높은 경우에 변경이 가능하다.
            if (!is_null($objUser) && !is_null($objReqUser)) {
                if (2 != $objReqUser->mb_state_active) {
                    $bPermit = false;
                }
                elseif ($objUser->mb_level > $objReqUser->mb_level) {
                    $bPermit = true;
                }
            }

            if ($bPermit) {
                1 == $iCreated;
			    $query = "";
                $bResult = $this->modelMember->updateMemberByFid($arrData, $query);

                if ($bResult) {
                    $this->modelModify->add($this->session->user_id, MOD_MB_STATE, $query, $this->request->getIPAddress());

                    $arrResult['status'] = 'success';
                } elseif (0 == $iCreated) {
                    $arrResult['status'] = 'usererror';
                } else {
                    $arrResult['status'] = 'fail';
                }
            } else {
                $arrResult['status'] = 'nopermit';
            }
        } else {
            $arrResult['status'] = 'logout';
        }
        echo json_encode($arrResult);
    }

    // 사용자정보
    public function assets()
    {
        if (is_login()) {
            $strUid = $this->session->user_id;
            // model
            
            $modelConfsite = new ConfSite_Model();
            $objUser = $this->modelMember->getInfoByUid($strUid);
            $sess_id = $this->session->session_id;
			$this->modelSess->deleteLast();

            $objResult = new \stdClass();

            $bPermit = true;
            if (is_null($objUser)) {
				$bPermit = false;
                writeLog("[assets] user = null (".$strUid.")");
            }
            else if($objUser->mb_level < LEVEL_ADMIN && $modelConfsite->IsMaintain())
				$bPermit = false;
            else if( !$this->modelMember->isPermitMember($objUser) )
				$bPermit = false;
            else if( is_null($this->modelSess->getBySess($sess_id)) ){
                writeLog("[assets] session = null (".$sess_id.")");
				$bPermit = false;
            }
            
            if ($bPermit) {
        		// writeLog("[assets] ".$strUid." (".$sess_id.")");

				$this->modelSess->updateLast($sess_id);
                if($objUser->mb_level < LEVEL_ADMIN)
                    $this->modelMember->calcTransfer($objUser);
                
                $objResult->data = $objUser;
                $objResult->status = 'success';
            } else {
                writeLog("[assets] logout (".$sess_id.")");
				$this->sess_destroy();
                $objResult->status = 'logout';
            }

            echo json_encode($objResult);
        } else {
            $arrResult['status'] = 'logout';
            echo json_encode($arrResult);
        }
    }

    // 가입한 직원의 정보: 머니, 배당율
    public function empinfo()
    {
        if (is_login()) {
            $strUid = $this->session->user_id;
            // model
            
            $chargeModel = new Charge_Model();
            $exchangeModel = new Exchange_Model();
            $noticeModel = new Notice_Model();
            $confsiteModel = new ConfSite_Model();

            $objUser = $this->modelMember->getInfo($strUid);

            $objResult = new \stdClass();
            if ($objUser->mb_level >= LEVEL_ADMIN) {
                $arrEmpInfo = $this->modelMember->getEmpUserCnt($objUser);
                $arrEmpInfo['wait_charge'] = $chargeModel->getWaitCnt();
                $arrEmpInfo['moment_charge'] = $chargeModel->getMomentCnt();
                $arrEmpInfo['wait_exchange'] = $exchangeModel->getWaitCnt();
                $arrEmpInfo['moment_exchange'] = $exchangeModel->getMomentCnt();
                $arrEmpInfo['new_message'] = $noticeModel->getNewMessageCnt();
                $objAdminMoney = $this->modelMember->calcAdminMoney();
                $arrEmpInfo['emp_money'] = $objAdminMoney->emp_money;
                $arrEmpInfo['emp_point'] = $objAdminMoney->emp_point;

                $arrReqData['start'] = date('Y-m-d');
                $arrReqData['end'] = $arrReqData['start'];
                $arrEmpInfo['emp_money_charge'] = $chargeModel->calcAdminCharge($arrReqData);
                $arrEmpInfo['emp_money_exchange'] = $exchangeModel->calcAdminExchange($arrReqData);

                $arrSoundInfo = $confsiteModel->getSoundConf();

                $objResult->data = $arrEmpInfo;
                $objResult->sound = $arrSoundInfo;
                $objResult->status = 'success';
            } else {
                $objResult->status = 'fail';
            }

            echo json_encode($objResult);
        } else {
            $arrResult['status'] = 'logout';
            echo json_encode($arrResult);
        }
    }

    // 게임별 당일 총배팅금, 총당첨금, 누르기금
    public function empbetinfo()
    {
        if (is_login()) {
            $strUid = $this->session->user_id;
            // model
            
            $confsiteModel = new ConfSite_Model();
            $confgameModel = new ConfGame_Model();

            $objUser = $this->modelMember->getInfo($strUid);
            $siteConfs = $this->getSiteConf($confsiteModel);

            $objResult = new \stdClass();
            if ($objUser->mb_level >= LEVEL_ADMIN) {
                $strDate = date('Y-m-d');
                $arrReqData['start'] = $strDate.' 00:00:00';
                $arrReqData['end'] = $strDate.' 23:59:59';



                $arrSumData = [[0, 0], [0, 0], [0, 0], [0, 0], [0, 0], [0, 0], [0, 0], [0, 0], [0, 0], [0, 0], 
                    [0, 0], [0, 0], [0, 0], [0, 0], [0, 0], [0, 0], [0, 0], [0, 0] ];
                // if(!$siteConfs['npg_deny']){
                //     $betModel = new PbBet_Model();
                //     $betModel->setType(GAME_POWER_BALL);

                //     $objConfPb = $confgameModel->getByIndex(GAME_POWER_BALL);
                //     $arrSum = $betModel->getBetSumByDay($arrReqData, $objConfPb);
                //     $arrSumData[0] = $arrSum[0];
                //     $arrSumData[1] = $arrSum[1];
                    
                //     $betModel = new PsBet_Model();
                //     $betModel->setType(GAME_POWER_LADDER);
                //     $objConfPs = $confgameModel->getByIndex(GAME_POWER_LADDER);
                //     $sum = $betModel->getBetSumByDay($arrReqData, $objConfPs);
                //     $arrSumData[2] = $sum;
                // }
                if($siteConfs['hpg_enable']){
                    $betModel = new PbBet_Model();
                    $betModel->setType(GAME_HAPPY_BALL);

                    $objConfPb = $confgameModel->getByIndex(GAME_HAPPY_BALL);
                    $arrSum = $betModel->getBetSumByDay($arrReqData, $objConfPb);
                    $arrSumData[0] = $arrSum[0];
                    $arrSumData[1] = $arrSum[1];
                }
                if(!$siteConfs['bpg_deny']){
                    $betModel = new PbBet_Model();
                    $betModel->setType(GAME_BOGLE_BALL);

                    $objConfBb = $confgameModel->getByIndex(GAME_BOGLE_BALL);
                    $arrSum = $betModel->getBetSumByDay($arrReqData, $objConfBb);
                    $arrSumData[3] = $arrSum[0];
                    $arrSumData[4] = $arrSum[1];

                    $betModel = new PsBet_Model();
                    $betModel->setType(GAME_BOGLE_LADDER);
                    $objConfBs = $confgameModel->getByIndex(GAME_BOGLE_LADDER);
                    $arrSumData[5] = $betModel->getBetSumByDay($arrReqData, $objConfBs);
                }
                if($siteConfs['eos5_enable']){
                    $betModel = new PbBet_Model();
                    $betModel->setType(GAME_EOS5_BALL);

                    $objConfPb = $confgameModel->getByIndex(GAME_EOS5_BALL);
                    $arrSum = $betModel->getBetSumByDay($arrReqData, $objConfPb);
                    $arrSumData[6] = $arrSum[0];
                    $arrSumData[7] = $arrSum[1];
                }
                if($siteConfs['eos3_enable']){
                    $betModel = new PbBet_Model();
                    $betModel->setType(GAME_EOS3_BALL);

                    $objConfPb = $confgameModel->getByIndex(GAME_EOS3_BALL);
                    $arrSum = $betModel->getBetSumByDay($arrReqData, $objConfPb);
                    $arrSumData[8] = $arrSum[0];
                    $arrSumData[9] = $arrSum[1];
                }
                if($siteConfs['coin5_enable']){
                    $betModel = new PbBet_Model();
                    $betModel->setType(GAME_COIN5_BALL);

                    $objConfPb = $confgameModel->getByIndex(GAME_COIN5_BALL);
                    $arrSum = $betModel->getBetSumByDay($arrReqData, $objConfPb);
                    $arrSumData[10] = $arrSum[0];
                    $arrSumData[11] = $arrSum[1];
                }
                if($siteConfs['coin3_enable']){
                    $betModel = new PbBet_Model();
                    $betModel->setType(GAME_COIN3_BALL);

                    $objConfPb = $confgameModel->getByIndex(GAME_COIN3_BALL);
                    $arrSum = $betModel->getBetSumByDay($arrReqData, $objConfPb);
                    $arrSumData[12] = $arrSum[0];
                    $arrSumData[13] = $arrSum[1];
                }
                if(!$siteConfs['cas_deny']){
                    
                    $arrReqData['type'] = GAME_CASINO_EVOL;
                    $this->modelMember->gameRange($arrReqData);

                    $betModel = new CsBet_Model();

                    $objConfPb = $confgameModel->getByIndex(GAME_CASINO_EVOL);
                    $arrSumData[14] = $betModel->getBetSumByDay($arrReqData, $objConfPb);
                }
                if(!$siteConfs['slot_deny']){
                    $betModel = new SlBet_Model();

                    $objConfPb = $confgameModel->getByIndex(GAME_SLOT_1);
                    $arrSumData[15] = $betModel->getBetSumByDay($arrReqData, $objConfPb);
                    
                    if($_ENV['app.type'] == APPTYPE_4 || $_ENV['app.type'] == APPTYPE_5)
                        $objConfPb = $confgameModel->getByIndex(GAME_SLOT_3);
                    else 
                        $objConfPb = $confgameModel->getByIndex(GAME_SLOT_2);
                    $arrSumData[16] = $betModel->getBetSumByDay($arrReqData, $objConfPb);
                }
                if($siteConfs['kgon_enable']){
                    $betModel = new CsBet_Model();

                    $objConfPb = $confgameModel->getByIndex(GAME_CASINO_KGON);
                    $arrSumData[17] = $betModel->getBetSumByDay($arrReqData, $objConfPb);
                }
                $objResult->data = $arrSumData;
                $objResult->status = 'success';
            } else {
                $objResult->status = 'fail';
            }

            echo json_encode($objResult);
        } else {
            $arrResult['status'] = 'logout';
            echo json_encode($arrResult);
        }
    }

    // 유저의 충환전, 배팅총액
    public function userinfo()
    {
        $jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
        if (is_login()) {
            $strUid = $this->session->user_id;
            // model
            
            $chargeModel = new Charge_Model();
            $exchangeModel = new Exchange_Model();
            $confsiteModel = new ConfSite_Model();
            $siteConfs = $this->getSiteConf($confsiteModel);

            $objUser = $this->modelMember->getInfo($strUid);

            $objResult = new \stdClass();
            if ($objUser->mb_level >= LEVEL_ADMIN) {
                
                $arrInfo['charge_total'] = $chargeModel->calcAdminCharge($arrReqData);
                $arrInfo['discharge_total'] = $exchangeModel->calcAdminExchange($arrReqData);
                $arrBet = $this->modelMember->calcUserBet($arrReqData, $siteConfs);
                $arrInfo['bet_total'] = $arrBet['bet_money'];
                $arrInfo['win_total'] = $arrBet['bet_win_money'];

                $arrReqData['start'] = date('Y-m-d');
                $arrReqData['end'] = $arrReqData['start'];
                $arrInfo['charge_today'] = $chargeModel->calcAdminCharge($arrReqData);
                $arrInfo['discharge_today'] = $exchangeModel->calcAdminExchange($arrReqData);
                $arrBet = $this->modelMember->calcUserBet($arrReqData, $siteConfs);
                $arrInfo['bet_today'] = $arrBet['bet_money'];
                $arrInfo['win_today'] = $arrBet['bet_win_money'];

                $objResult->data = $arrInfo;
                $objResult->status = 'success';
            } else {
                $objResult->status = 'fail';
            }

            echo json_encode($objResult);
        } else {
            $arrResult['status'] = 'logout';
            echo json_encode($arrResult);
        }
    }

    
    // 유저의 충환전, 배팅총액
    public function userbet()
    {
        $jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
        if (is_login()) {
            $strUid = $this->session->user_id;
            // model
            $confsiteModel = new ConfSite_Model();
            $siteConfs = $this->getSiteConf($confsiteModel);

            $objUser = $this->modelMember->getInfo($strUid);

            $objResult = new \stdClass();
            if ($objUser->mb_level >= LEVEL_ADMIN) {
                
                $arrReqData['start'] = date('Y-m-d');
                $arrReqData['end'] = $arrReqData['start'];
                $objResult->date = $arrReqData['start'];
                $objResult->data = $this->modelMember->statistUserBet($arrReqData, $siteConfs);
                $objResult->status = 'success';
            } else {
                $objResult->status = 'fail';
            }

            echo json_encode($objResult);
        } else {
            $arrResult['status'] = 'logout';
            echo json_encode($arrResult);
        }
    }

    public function getmembers()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            // model
            
			$confsiteModel = new ConfSite_Model();

			$objResult = new \stdClass();
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);
			$empFid = 0;
            if (strlen($arrData['mb_emp_uid']) > 0){
                $objEmp = $this->modelMember->getInfo($arrData['mb_emp_uid']);
                if (!is_null($objEmp)){
                    $empFid = $objEmp->mb_fid;
                } else $empFid = -1;
            } 
            
            if($empFid >= 0){
                $arrMember = $this->modelMember->searchMemberByEmpFid($objUser, $arrData, $empFid);
                if (is_null($arrMember)) {
                    $arrMember = [];
                }
                foreach ($arrMember as $objMember) {
                    $objEmpInfo = $this->modelMember->find($objMember->mb_emp_fid);
                    if ($objEmpInfo != null){
                        $objMember->mb_empname = $objEmpInfo->mb_uid;
                    }
                    else {
                        $objMember->mb_empname = '';
                    }
                }
                
            } else {
                $arrMember = [];
            }

            $confs= $this->getSiteConf($confsiteModel);
            $confs['emp_level'] = $objUser->mb_level; 
            
            $objResult->status = 'success';
            $objResult->confs = $confs;
            $objResult->data = $arrMember;

            echo json_encode($objResult);
        } else {
            $arrResult['status'] = 'logout';
            echo json_encode($arrResult);
        }
    }

    public function getmembercnt()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            // model
            
			$objResult = new \stdClass();
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);
			$empFid = 0;
            if (strlen($arrData['mb_emp_uid']) > 0){
                $objEmp = $this->modelMember->getInfo($arrData['mb_emp_uid']);
                if (!is_null($objEmp)){
                    $empFid = $objEmp->mb_fid;
                } else $empFid = -1;
            } 
            if($empFid >= 0){
                $objCount = $this->modelMember->searchCountByEmpFid($objUser, $arrData, $empFid);
                $objResult->status = 'success';
                $objResult->data = $objCount;
            } else {
                $objCount = new \stdClass();
                $objCount->count = 0;
                $objResult->status = 'success';
                $objResult->data = $objCount;
            }
			
            echo json_encode($objResult);
        } else {
            $arrResult['status'] = 'logout';
            echo json_encode($arrResult);
        }
    }

    
    public function getmemberlist()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            // model
            
			$confsiteModel = new ConfSite_Model();
            $confs = $this->getSiteConf($confsiteModel);

			$objResult = new \stdClass();
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);
			$empFid = 0;
            if (strlen($arrData['mb_emp_uid']) > 0){
                $objEmp = $this->modelMember->getInfo($arrData['mb_emp_uid']);
                if (!is_null($objEmp)){
                    $empFid = $objEmp->mb_fid;
                } else $empFid = -1;
            } 
            
            if($empFid >= 0 && $objUser->mb_level >= LEVEL_ADMIN){
                $arrMember = $this->modelMember->searchUserByLevel($arrData, $empFid, $confs);
                if (is_null($arrMember)) {
                    $arrMember = [];
                }
                foreach ($arrMember as $objMember) {
                    $objEmpInfo = $this->modelMember->find($objMember->mb_emp_fid);
                    if ($objEmpInfo != null){
                        $objMember->mb_empname = $objEmpInfo->mb_uid;
                    }
                    else {
                        $objMember->mb_empname = '';
                    }
                }
                
            } else {
                $arrMember = [];
            }
            
            $confs['emp_level'] = $objUser->mb_level; 
            $objResult->status = 'success';
            $objResult->confs = $confs;
            $objResult->data = $arrMember;

            echo json_encode($objResult);
        } else {
            $arrResult['status'] = 'logout';
            echo json_encode($arrResult);
        }
    }

    public function allmember()
    {
        if (is_login()) {
            // model
            

            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);

            $objResult = new \stdClass();

            if ($objUser->mb_level >= LEVEL_ADMIN) {
                $arrMember = $this->modelMember->getMemberByLevel(LEVEL_ADMIN, true);

                if (is_null($arrMember)) {
                    $arrMember = [];
                }

                $objResult->status = 'success';
                $objResult->data = $arrMember;
            } else {
                $objResult->status = 'fail';
            }

            echo json_encode($objResult);
        } else {
            $arrResult['status'] = 'logout';
            echo json_encode($arrResult);
        }
    }

    
	public function loglist(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login())
		{
            

            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);

            if($objUser->mb_level  >= LEVEL_ADMIN) {
                $modelSesslog = new SessLog_Model();

                $arrData = $modelSesslog->search($arrReqData, $objUser->mb_level);
                    
                $arrResult['data'] = $arrData;
                $arrResult['status'] = "success";
            } else{
                $arrResult['status'] = "fail";
            }

		}
		else{
			$arrResult['status'] = "logout";
		}
		echo json_encode($arrResult);
	}

	public function logcnt(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login())
		{
            
         
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);

            if($objUser->mb_level  >= LEVEL_ADMIN) {
                $modelSesslog = new SessLog_Model();
                $objCount = $modelSesslog->searchCount($arrReqData, $objUser->mb_level);

                $arrResult['data'] = $objCount;
                $arrResult['status'] = "success";
            } else {
                $arrResult['status'] = "fail";
            }
		}
		else{
			$arrResult['status'] = "logout";
		}
		echo json_encode($arrResult);
	}

    public function conlist(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login())
		{

            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);

            if($objUser->mb_level  >= LEVEL_ADMIN) {

                $arrData = $this->modelSess->search($arrReqData, $objUser->mb_level);
                    
                $arrResult['data'] = $arrData;
                $arrResult['status'] = "success";
            } else{
                $arrResult['status'] = "fail";
            }

		}
		else{
			$arrResult['status'] = "logout";
		}
		echo json_encode($arrResult);
	}

	public function concnt(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login())
		{
         
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);

            if($objUser->mb_level  >= LEVEL_ADMIN) {
                $objCount = $this->modelSess->searchCount($arrReqData, $objUser->mb_level);

                $arrResult['data'] = $objCount;
                $arrResult['status'] = "success";
            } else {
                $arrResult['status'] = "fail";
            }
		}
		else{
			$arrResult['status'] = "logout";
		}
		echo json_encode($arrResult);
	}

	public function blocklist(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login())
		{
            
            
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);

            if($objUser->mb_level  >= LEVEL_ADMIN) {

                $modelBlock = new Block_Model();
                $arrData = $modelBlock->search($arrReqData);
                    
                $arrResult['data'] = $arrData;
                $arrResult['status'] = "success";
            } else{
                $arrResult['status'] = "fail";
            }

		}
		else{
			$arrResult['status'] = "logout";
		}
		echo json_encode($arrResult);
	}

	public function blockcnt(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login())
		{
            

            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);

            if($objUser->mb_level  >= LEVEL_ADMIN) {

                $modelBlock = new Block_Model();
                $objCount = $modelBlock->searchCount($arrReqData);

                $arrResult['data'] = $objCount;
                $arrResult['status'] = "success";
            } else {
                $arrResult['status'] = "fail";
            }
		}
		else{
			$arrResult['status'] = "logout";
		}
		echo json_encode($arrResult);
	}

    
    // 블록아이피 추가
    public function add_block()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            $bPermit = false;
            
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);

            if($objUser->mb_level  >= LEVEL_ADMIN) {
                $modelBlock = new Block_Model();
                // $arrData['block_state'] = 1;
                $arrData['block_updated'] = date("Y-m-d H:i:s");

                $bResult = $modelBlock->saveByIp($arrData);

                if ($bResult) {
                    $arrResult['status'] = 'success';
                } else {
                    $arrResult['status'] = 'fail';
                }
            } else {
                $arrResult['status'] = 'nopermit';
            }
        } else {
            $arrResult['status'] = 'logout';
        }
        echo json_encode($arrResult);
    }

    // 블록아이피 변경
    public function update_block()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            $bPermit = false;
            
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);

            if($objUser->mb_level  >= LEVEL_ADMIN) {
                $modelBlock = new Block_Model();

                $bResult = $modelBlock->updateByFid($arrData);

                if ($bResult) {
                    $arrResult['status'] = 'success';
                } else {
                    $arrResult['status'] = 'fail';
                }
            } else {
                $arrResult['status'] = 'nopermit';
            }
        } else {
            $arrResult['status'] = 'logout';
        }
        echo json_encode($arrResult);
    }

    // 블록아이피 삭제
    public function delete_block()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            $bPermit = false;
            
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);

            if($objUser->mb_level  >= LEVEL_ADMIN) {
                $modelBlock = new Block_Model();

                $bResult = $modelBlock->deleteByFid($arrData['block_fid']);

                if ($bResult) {
                    $arrResult['status'] = 'success';
                } else {
                    $arrResult['status'] = 'fail';
                }
            } else {
                $arrResult['status'] = 'nopermit';
            }
        } else {
            $arrResult['status'] = 'logout';
        }
        echo json_encode($arrResult);
    }

    public function transfer()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            // model
            
            $moneyhistoryModel = new MoneyHistory_Model();
            $confsiteModel = new ConfSite_Model();
            $confsiteModel->readMemConf();

            $strUid = $this->session->user_id;
            $objEmp = $this->modelMember->getInfo($strUid);
            $objMember = $this->modelMember->getInfoByFid($arrData['mb_fid'], true);

            $objResult = new \stdClass();

            if(is_null($objMember) || is_null($objEmp)){
                $objResult->status = 'fail';
            } else if($arrData['amount'] <= 0 ){
                $objResult->status = 'fail';
                $objResult->msg = '금액을 정확히 입력해주세요.';
            } else if($objEmp->mb_level >= LEVEL_ADMIN) {
                $objResult->status = 'fail';
                
                if($arrData['type'] == 0){          //직충전
                    $iResult = 1;
                    if($_ENV['mem.delay_play'] > 0 && $_ENV['mem.depodeny_play'] &&  diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                        $iResult = 2;
                    } 

                    if($iResult == 1){
                        if($this->modelMember->moneyProc($objMember, $arrData['amount']))
                        {
                            $chargeModel = new Charge_Model();

                            $data =[
                                'charge_emp_fid' => $objMember->mb_emp_fid,
                                'charge_mb_uid' => $objMember->mb_uid,
                                'charge_mb_realname' => $objMember->mb_bank_own,
                                'charge_mb_phone' => $objMember->mb_phone,
                                'charge_money' => $arrData['amount'],
                                'charge_time_require' => date("Y-m-d H:i:s"),
                                'charge_action_state' => 5,
                                'charge_action_uid' => $objEmp->mb_uid,
                                'charge_time_process' => date("Y-m-d H:i:s"),
                                'charge_money_before' => allMoney($objMember),
                                'charge_money_after' => allMoney($objMember) + $arrData['amount'],
                            ];
                            $chargeModel->register($data);

                            $moneyhistoryModel->registerTransfer($objMember, $objEmp->mb_uid, $arrData['amount'], MONEYCHANGE_INC);
                            $objResult->status = 'success';
                        }
                    } else if($iResult == 2) {
                        $objResult->status = 'fail';
                        $objResult->msg = '회원이 게임플레이중이므로 충전 하실수 없습니다. '.intval(($_ENV['mem.delay_play']-diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet))/60+1)."분후 다시 시도해주세요.";
                    }
                } else if($arrData['type'] == 1){           //직환전
                    $iResult = 1;
                    if(intval($objMember->mb_money) < $arrData['amount']){
                        if($_ENV['mem.delay_play'] > 0 && $_ENV['mem.withdeny_play'] && diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                            $iResult = 2;
                        } 
                        else 
                            $iResult = $this->alltoGame($objMember); 
                    } 
                    
                    if($iResult == 1){
                        
                        if(intval($objMember->mb_money) < $arrData['amount']){
                            $arrData['amount'] = $objMember->mb_money;
                        }
                        if($arrData['amount'] > 0 && $this->modelMember->moneyProc($objMember, 0-$arrData['amount']))
                        {
                            $exchangeModel = new Exchange_Model();

                            $data =[
                                'exchange_emp_fid' => $objMember->mb_emp_fid,
                                'exchange_mb_uid' => $objMember->mb_uid,
                                'exchange_mb_phone' => $objMember->mb_phone,
                                'exchange_money' => $arrData['amount'],
                                'exchange_time_require' => date("Y-m-d H:i:s"),
                                'exchange_action_state' => 5,
                                'exchange_action_uid' => $objEmp->mb_uid,
                                'exchange_time_process' => date("Y-m-d H:i:s"),
                                'exchange_bank_name' => $objMember->mb_bank_name,
                                'exchange_bank_account' => $objMember->mb_bank_own,
                                'exchange_bank_serial' => $objMember->mb_bank_num,
                                'exchange_money_before' => allMoney($objMember),
                                'exchange_money_after' => allMoney($objMember)-$arrData['amount']

                            ];
                            $exchangeModel->register($data);

                            $moneyhistoryModel->registerTransfer($objMember, $objEmp->mb_uid, 0-$arrData['amount'], MONEYCHANGE_DEC);
                            $objResult->status = 'success';
                        }
                    } else if($iResult == 2) {
                        $objResult->status = 'fail';
                        $objResult->msg = '회원이 게임플레이중이므로 환전 하실수 없습니다. '.intval(($_ENV['mem.delay_play']-diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet))/60+1)."분후 다시 시도해주세요.";
                    } else {
                        $objResult->status = 'fail';
                        $objResult->msg = '게임서버가 응답하지 않습니다. 잠시후 다시 시도해주세요..';

                    }
                    
                } 
                 
            } else {
                
                $objChMember = null;            //하부회원찾기
                $arrEmp = $this->modelMember->getMemberByEmpFid($objEmp->mb_fid, $objEmp->mb_level,  $objEmp->mb_level, true, $objMember->mb_fid);
                if(count($arrEmp) > 0)
                    $objChMember = reset($arrEmp);

                $objResult->status = 'fail';
                
                if(is_null($objChMember)){
                    $objResult->status = 'fail';
                }else if($arrData['type'] == 2){                      //이송
                    
                    if($_ENV['mem.trans_deny']){
                        $objResult->msg = '거절되었습니다.';
                    } else if($_ENV['mem.trans_lv1'] && $objChMember->mb_emp_fid !== $objEmp->mb_fid){
                        $objResult->msg = '거절되었습니다.';
                    } else if(count($_ENV['mem.trans_lvs']) > 0 && !in_array(intval($objEmp->mb_level), $_ENV['mem.trans_lvs']) ){ 
                        $objResult->msg = '거절되었습니다.';
                    } else {
                        $iResult = 1;
                        if($arrData['amount'] > $objEmp->mb_money){
                            if($_ENV['mem.delay_play'] > 0 && $_ENV['mem.depodeny_play'] && diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                                $iResult = 2;
                            } 
                            else $iResult = $this->alltoGame($objEmp);
                        } 
    
                        if($iResult == 1){
                            if($arrData['amount'] > $objEmp->mb_money){
                                $objResult->msg = '이동금액이 보유금액을 초과하셧습니다.';
                            } else if($this->modelMember->trasferMoney($objEmp, $objMember, $arrData['amount'])){
    
                                $moneyhistoryModel->registerTransfer($objEmp, $objMember->mb_uid, 0-$arrData['amount'], MONEYCHANGE_TRANS_DEC);
                                $moneyhistoryModel->registerTransfer($objMember, $objEmp->mb_uid, $arrData['amount'], MONEYCHANGE_TRANS_INC);
                                $objResult->status = 'success';
                            } 
                        } else if($iResult == 2) {
                            $objResult->status = 'fail';
                            $objResult->msg = '회원이 게임플레이중이므로 이동 하실수 없습니다. '.intval(($_ENV['mem.delay_play']-diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet))/60+1)."분후 다시 시도해주세요.";
                        } else {
                            $objResult->status = 'fail';
                            $objResult->msg = '게임서버가 응답하지 않습니다. 잠시후 다시 시도해주세요..';
                        }
                    }
                    
                } else if($arrData['type'] == 3){               //환수

                    if($_ENV['mem.return_deny']){
                        $objResult->msg = '거절되었습니다.';
                    } else if($_ENV['mem.return_lv1'] && $objChMember->mb_emp_fid !== $objEmp->mb_fid){
                        $objResult->msg = '거절되었습니다.';
                    } else if(count($_ENV['mem.trans_lvs']) > 0 && !in_array(intval($objEmp->mb_level), $_ENV['mem.trans_lvs']) ){ 
                        $objResult->msg = '거절되었습니다.';
                    } else {
                        $iResult = 1;
                        if($arrData['amount'] > $objMember->mb_money){
                            if($_ENV['mem.delay_play'] > 0 && $_ENV['mem.withdeny_play'] && diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                                $iResult = 2;
                            } 
                            else $iResult = $this->alltoGame($objMember);
                        } 

                        if($iResult == 1){
                            if($arrData['amount'] > $objMember->mb_money){
                                $arrData['amount'] = $objMember->mb_money;
                            }
                            if($arrData['amount'] > 0 && $this->modelMember->trasferMoney($objMember, $objEmp, $arrData['amount'])){

                                $moneyhistoryModel->registerTransfer($objEmp, $objMember->mb_uid, $arrData['amount'], MONEYCHANGE_EXCHANGE_INC);
                                $moneyhistoryModel->registerTransfer($objMember, $objEmp->mb_uid, 0-$arrData['amount'], MONEYCHANGE_EXCHANGE_DEC);
                                $objResult->status = 'success';
                            } 
                        } else if($iResult == 2) {
                            $objResult->status = 'fail';
                            $objResult->msg = '회원이 게임플레이중이므로 환수 하실수 없습니다. '.intval(($_ENV['mem.delay_play']-diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet))/60+1)."분후 다시 시도해주세요.";
                        } else {
                            $objResult->status = 'fail';
                            $objResult->msg = '게임서버가 응답하지 않습니다. 잠시후 다시 시도해주세요..';
                        }
                    }
                }
                
            }

            echo json_encode($objResult);
        } else {
            $arrResult['status'] = 'logout';
            echo json_encode($arrResult);
        }
    }

    //머니, 포인트 회수
    public function withdraw()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            // model
            
            $moneyhistoryModel = new MoneyHistory_Model();
            $confsiteModel = new ConfSite_Model();
            $confsiteModel->readMemConf();

            $strUid = $this->session->user_id;
            $objEmp = $this->modelMember->getInfo($strUid);
            $objMember = $this->modelMember->getInfoByFid($arrData['mb_fid'], true);

            $objResult = new \stdClass();

            if(is_null($objMember) || is_null($objEmp)){
                $objResult->status = 'fail';
            } else if($objEmp->mb_level < LEVEL_ADMIN) {
                $objResult->status = 'fail';
            } else {
                if($arrData['type'] == 0){              //머니회수
                    if($_ENV['mem.delay_play'] > 0 && $_ENV['mem.withdeny_play'] && diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                        $iResult = 2;
                    } 
                    else 
                        $iResult = $this->alltoGame($objMember);

                    if($iResult == 1){
                        $nAmount = 0-$objMember->mb_money;
                        if($nAmount == 0){
                            $objResult->status = 'success';
                        } else if( $this->modelMember->moneyProc($objMember, $nAmount)){
                            $moneyhistoryModel->registerWithdraw($objMember, $objEmp->mb_uid, $nAmount, MONEYCHANGE_WITHDRAW);
                            $objResult->status = 'success';
                        }
                    } else if($iResult == 2) {
                        $objResult->status = 'fail';
                        $objResult->msg = '회원이 게임플레이중이므로 회수 하실수 없습니다. '.intval(($_ENV['mem.delay_play']-diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet))/60+1)."분후 다시 시도해주세요.";
                    } else {
                        $objResult->status = 'fail';
                        $objResult->msg = '게임서버가 응답하지 않습니다. 잠시후 다시 시도해주세요..';
                    }
                    
                }
                else if($arrData['type'] == 1){ //포인트회수
                    $nAmount = 0-$objMember->mb_point;
                    if($nAmount == 0){
                        $objResult->status = 'success';
                    } 
                    else if($this->modelMember->moneyProc($objMember, 0, $nAmount)){
                        $moneyhistoryModel->registerWithdraw($objMember, $objEmp->mb_uid, $nAmount, POINTHANGE_WITHDRAW);
                        $objResult->status = 'success';
                    }
                } else {
                    $objResult->status = 'fail';
                }

            } 

            echo json_encode($objResult);
        } else {
            $arrResult['status'] = 'logout';
            echo json_encode($arrResult);
        }
    }

    public function empIp()
    {
        if (is_login()) {
            // model
            $strUid = $this->session->user_id;
            $objEmp = $this->modelMember->getInfo($strUid);

            if(is_null($objEmp) || $objEmp->mb_level < LEVEL_ADMIN){
                $arrResult['status'] = 'fail';
            } else{
                $arrInfo = [
                    'ip_addr'  => $objEmp->mb_ip_join,
                    'ip_check' => $objEmp->mb_state_view > 0 ? 1:0,
                    'fid' => $objEmp->mb_fid,
                    'money' => allMoney($objEmp),
                    'egg' => $objEmp->mb_live_money + $objEmp->mb_slot_money + $objEmp->mb_fslot_money + $objEmp->mb_kgon_money + $objEmp->mb_gslot_money,
                ];
                $arrResult['data'] = $arrInfo;
                $arrResult['status'] = 'success';
            }

        } else {
            $arrResult['status'] = 'logout';
            
        }
        echo json_encode($arrResult);
    }

      
	public function egginfo(){
        $jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$result = new \StdClass;
		if(!is_login())
		{
            $result->status = STATUS_LOGOUT;
		} else {

            $objMember = $this->modelMember->getInfoByFid($arrReqData['mb_fid']);
			if(!is_null($objMember)){
				$this->allEgg($objMember);
                $result->money = allMoney($objMember);
                $result->point = $objMember->mb_point;
                $result->egg = $objMember->mb_live_money + $objMember->mb_slot_money + $objMember->mb_fslot_money + $objMember->mb_kgon_money + $objMember->mb_gslot_money;
                $result->status = STATUS_SUCCESS;
    
            } else {
                $result->status = STATUS_FAIL;

            }


		}
		echo json_encode($result);

	}
     
	public function eggcollect(){
        $jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$result = new \StdClass;
		if(!is_login())
		{
            $result->status = STATUS_LOGOUT;
		} else {
            $confsiteModel = new ConfSite_Model();
            
            $strUid = $this->session->user_id;
            $objEmp = $this->modelMember->getInfo($strUid);
			$bSelf = false;
            if(array_key_exists('self', $arrReqData) && $arrReqData['self'] == 1)
    			$bSelf = true;
            if($bSelf)
                $objMember = $objEmp;
            else
                $objMember = $this->modelMember->getInfoByFid($arrReqData['mb_fid']);


            if(!is_null($objMember) && ($objEmp->mb_level >= LEVEL_ADMIN || $bSelf)){
                $confsiteModel->readMemConf();

                if(!$bSelf && diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                    $iResult = 2;
                } else $iResult = $this->alltoGame($objMember);

                if($iResult == 1){
                    $result->money = allMoney($objMember);
                    $result->egg = $objMember->mb_live_money + $objMember->mb_slot_money + $objMember->mb_fslot_money + $objMember->mb_kgon_money + $objMember->mb_gslot_money;
                    $result->status = STATUS_SUCCESS;
                } else if($iResult == 2) {
                    $result->status = STATUS_FAIL;
                    $result->msg = '회원이 게임플레이중이므로 회수 하실수 없습니다. '.intval(($_ENV['mem.delay_play']-diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet))/60+1)."분후 다시 시도해주세요.";
                } else 
                    $result->status = STATUS_FAIL;
    
            } else {
                $result->status = STATUS_FAIL;

            }

		}
		echo json_encode($result);

	}

    public function eggrecovery(){
        $jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$result = new \StdClass;
		if(!is_login())
		{
            $result->status = STATUS_LOGOUT;
		} else {
            $confsiteModel = new ConfSite_Model();
            
            $strUid = $this->session->user_id;
            $objEmp = $this->modelMember->getInfo($strUid);
			$gameId = intval($arrReqData['game_index']);
			
            if($objEmp->mb_level >= LEVEL_ADMIN){
                $confsiteModel->readMemConf();
                $arrMember = $this->modelMember->findAll();

                $iResult = 1;
                $balance = 0;
                if($gameId == GAME_CASINO_EVOL){

                    foreach($arrMember as $objMember){
                        if($objMember->mb_live_id > 0 && $objMember->mb_live_money > 0 ){
                            writeLog("<CASINO> Recovery Uid=".$objMember->mb_uid." Balance=".$objMember->mb_live_money);
                            if(diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                                $iResult = 2;
                            } else $iResult = $this->evtoMb($objMember);
                            if($iResult == 0)
                                break;
                            else usleep(500000);
                        } 
                    }

                    $arrResult = $this->libApicas->getAgentInfo();
                    if($arrResult['status'] == 1){
                        $confsiteModel->setConfActive(CONF_CASINO_EVOL, $arrResult['balance']);
                        writeLog("<CASINO> Agent Egg = ".$arrResult['balance']);
                        $balance = $arrResult['balance'];
                    }
                } else if($gameId == GAME_SLOT_1){
                    foreach($arrMember as $objMember){
                        if($objMember->mb_slot_uid != "" && $objMember->mb_slot_money > 0 ){
                            writeLog("<SLOT> Recovery Uid=".$objMember->mb_uid." Balance=".$objMember->mb_slot_money);
                            if(diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                                $iResult = 2;
                            } else $iResult = $this->sltoMb($objMember);
                            if($iResult == 0)
                                break;
                            else usleep(500000);
                        } 
                    }

                    $arrResult = $this->libApislot->getAgentInfo();
                    if($arrResult['status'] == 1){
                        $confsiteModel->setConfActive(CONF_SLOT_1, $arrResult['balance']);
                        writeLog("<SLOT> Agent Egg = ".$arrResult['balance']);
                        $balance = $arrResult['balance'];
                    }
    
                } else if($gameId == GAME_SLOT_2){
                    foreach($arrMember as $objMember){
                        if($objMember->mb_fslot_id > 0 && $objMember->mb_fslot_money > 0 ){
                            writeLog("<FSLOT> Recovery Uid=".$objMember->mb_uid." Balance=".$objMember->mb_fslot_money);
                            if(diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                                $iResult = 2;
                            } else $iResult = $this->fsltoMb($objMember);
                            if($iResult == 0)
                                break;
                            else usleep(500000);
                        } 
                    }

                    $arrResult = $this->libApifslot->getAgentInfo();
                    if($arrResult['status'] == 1){
                        $confsiteModel->setConfActive(CONF_SLOT_2, $arrResult['balance']);
                        writeLog("<FSLOT> AGENT Egg = ".$arrResult['balance']);
                        $balance = $arrResult['balance'];
                    }
    
                } else if($gameId == GAME_SLOT_3){
                    foreach($arrMember as $objMember){
                        if($objMember->mb_gslot_uid != "" && $objMember->mb_gslot_money > 0 ) {
                            writeLog("<GSLOT> Recovery Uid=".$objMember->mb_uid." Balance=".$objMember->mb_gslot_money);
                            if(diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                                $iResult = 2;
                            } else $iResult = $this->gsltoMb($objMember);
                            if($iResult == 0)
                                break;
                            else usleep(500000);
                        } 
                    }

                    $arrResult = $this->libApigslot->getUserInfo();
                    if($arrResult['status'] == 1){
                        $confsiteModel->setConfActive(CONF_SLOT_3, $arrResult['balance']);
                        writeLog("<SLOT> Agent Egg = ".$arrResult['balance']);
                        $balance = $arrResult['balance'];
                    }
                } else if($gameId == GAME_CASINO_KGON){
                    foreach($arrMember as $objMember){
                        if($objMember->mb_kgon_id > 0 && $objMember->mb_kgon_money > 0 ){
                            writeLog("<KGON> Recovery Uid=".$objMember->mb_uid." Balance=".$objMember->mb_kgon_money);
                            if(diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                                $iResult = 2;
                            } else $iResult = $this->kgtoMb($objMember);
                            if($iResult == 0)
                                break;
                            else usleep(500000);
                        } 
                    }
                    
                    $arrResult = $this->libApikgon->getAgentInfo();
                    if($arrResult['status'] == 1){
                        $confsiteModel->setConfActive(CONF_CASINO_KGON, $arrResult['balance']);
                        writeLog("<KGON> AGENT Egg = ".$arrResult['balance']);
                        $balance = $arrResult['balance'];
                    }
                }

                $result->status = STATUS_SUCCESS;
			    $result->egg = $balance;
			    $result->useregg = $this->modelMember->calcGameEgg($gameId);
    
            } else {
                $result->status = STATUS_FAIL;

            }

		}
		echo json_encode($result);

	}

}
