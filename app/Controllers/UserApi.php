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
use App\Models\HlBet_Model;
use App\Models\EbalBet_Model;
use App\Models\EbalLog_Model;
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

            if (!is_null($objUser)) {
                if ($objUser->mb_level >= LEVEL_ADMIN) {
                    $bPermit = true;
                }
            }
            if ($bPermit) {
                $strError = '';
                $confsiteModel = new ConfSite_Model();
                $confsiteModel->readMemConf();

                $checkOk = validUserId($arrData['mb_uid']);
                if(!$checkOk){
                    $strError  = ["아이디는 4자~16자, 영문 또는 숫자만 사용 가능합니다."];
                } else {
                    $checkOk = validUserPw($arrData['mb_pwd']);
        
                    if(!$checkOk)
                        $strError  = ["비밀번호는 8자~20자, 특수문자 한개 이상 입력하셔야 합니다."];
                }

                if($checkOk)
                    $iResult = $this->modelMember->register($arrData, $strError);
                else $iResult = -1;

                if ( $iResult == 1 ) {
                    $objReqUser = $this->modelMember->getInfo(trim($arrData['mb_uid']));

                    if(!is_null($objReqUser) && array_key_exists('app.ebal', $_ENV) && $_ENV['app.ebal'] > 0 && array_key_exists('mb_state_view', $arrData) ){
                        $ebalLogModel = new EbalLog_Model();

                        $data =[
                            'log_mb_fid' => $objReqUser->mb_fid,
                            'log_mb_uid' => $objReqUser->mb_uid,
                            'log_data' => $arrData['mb_state_view'] == 1 ? "누르기":"넘기기",
                            'log_type' => EBAL_LOGTYPE_PRESSMANUAL,
                            'log_memo' => $objUser->mb_uid,
                            'log_time' => date("Y-m-d H:i:s"),
                        ];
                        $ebalLogModel->register($data);
                    }

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
            $objReqUser = $this->modelMember->getInfoByFid($arrData['mb_fid'], true);
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

                    $checkOk = true;
                    if($arrData['mb_pwd'] !== $objReqUser->mb_pwd){
                        $checkOk = validUserPw($arrData['mb_pwd']);
            
                        if(!$checkOk)
                            $strError  = ["비밀번호는 8자~20자, 특수문자 한개 이상 입력하셔야 합니다."];
                    }

                    if($checkOk)
                        $iResult = $this->modelMember->modifyMember($objReqUser, $arrData, $strError, $query);
                    else $iResult = -1;

                    if($iResult==1){

                        if(array_key_exists('app.ebal', $_ENV) && $_ENV['app.ebal'] > 0 && array_key_exists('mb_state_view', $arrData) ){

                            if($objReqUser->mb_state_view != $arrData['mb_state_view']){
                                $ebalLogModel = new EbalLog_Model();
    
                                $data =[
                                    'log_mb_fid' => $objReqUser->mb_fid,
                                    'log_mb_uid' => $objReqUser->mb_uid,
                                    'log_data' => $arrData['mb_state_view'] == 1 ? "누르기":"넘기기",
                                    'log_type' => EBAL_LOGTYPE_PRESSMANUAL,
                                    'log_memo' => $objAdmin->mb_uid,
                                    'log_time' => date("Y-m-d H:i:s"),
                                ];
                                $ebalLogModel->register($data);
                            }
                        }
                    }

                } else {
                    $iResult = $this->modelMember->modifyMemberRatio($objReqUser, $arrData, $strError,  $query);
                }

                if ($iResult == 1) {
				    $this->modelModify->add($this->session->user_id, MOD_MB_INFO, $query, $this->request->getIPAddress());
                    $arrResult['status'] = 'success';
                } elseif ( $iResult == 4 || $iResult == 5 ) {
                    $arrResult['status'] = 'ratio_error';
                    $arrResult['error'] = $strError;
                } elseif ( $iResult == 7 ) {
                    $arrResult['status'] = 'level_error';
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
    public function updatemembers()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            $bPermit = false;
            
            $strUid = $this->session->user_id;
            $objAdmin = $this->modelMember->getInfo($strUid);

            if (!is_null($objAdmin)) {
                if ($objAdmin->mb_level >= LEVEL_ADMIN) {
                    $bPermit = true;
                }
            }
            if ($bPermit) {
                $arrData['mb_fids'] = [];

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
            $confsiteModel = new ConfSite_Model();
            
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);
            $objReqUser = $this->modelMember->getInfoByFid($arrData['mb_fid']);

            if (!is_null($objUser) && !is_null($objReqUser)) {
                if ($objUser->mb_level >= LEVEL_ADMIN) {
                    $bPermit = true;
                }
            }
            if ($bPermit) {
                $siteConfs = $this->getSiteConf($confsiteModel);
                
                if(!$siteConfs['hold_deny']){
                    $this->libApiHold->logout($objReqUser->mb_hold_uid);
                }

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
                if($objUser->mb_level < LEVEL_ADMIN){
                    $this->modelMember->calcTransfer($objUser);
                    $objUser->mb_money_all = allMoney($objUser);
                    $objUser->mb_point = floor($objUser->mb_point);
                }
                else{
				    $objSess = $this->modelSess->getBySess($sess_id);
                    if($objSess != null)
                        $objUser->mb_ip_login = $objSess->sess_ip;
                }
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
            $confsiteModel = new ConfSite_Model();

            $objUser = $this->modelMember->getInfo($strUid);
            $objResult = new \stdClass();
            if ($objUser->mb_level >= LEVEL_ADMIN) {
                $arrReqData['start'] = date('Y-m-d');
                $arrReqData['end'] = $arrReqData['start'];

                $arrEmpInfo = null;
                $arrResult = $this->modelMember->getEmpInfo($objUser, $arrReqData);
                if(!is_null($arrResult) && count($arrResult) == 8){
                    $arrEmpInfo['wait_user'] = $arrResult[0]->result_1 != null ? $arrResult[0]->result_1 : 0 ;		    //대기
                    $arrEmpInfo['wait_charge'] = $arrResult[1]->result_1 != null ? $arrResult[1]->result_1 : 0 ;		//충전대기
                    $arrEmpInfo['moment_charge'] = $arrResult[1]->result_2 != null ? $arrResult[1]->result_2 : 0 ;		//충전대기
                    $arrEmpInfo['wait_exchange'] = $arrResult[2]->result_1 != null ? $arrResult[2]->result_1 : 0 ;		//환전대기
                    $arrEmpInfo['moment_exchange'] = $arrResult[2]->result_2 != null ? $arrResult[2]->result_2 : 0 ;   //환전대기
                    $arrEmpInfo['new_message'] = $arrResult[3]->result_1 != null ? $arrResult[3]->result_1 : 0 ;		//문의대기
                    $arrEmpInfo['emp_money'] = $arrResult[4]->result_1 != null ? intval($arrResult[4]->result_1) : 0 ;		//보유머니
                    $arrEmpInfo['emp_point'] = $arrResult[4]->result_2 != null ? intval($arrResult[4]->result_2) : 0 ;		//포유포인트
                    $arrEmpInfo['emp_money_charge'] = $arrResult[5]->result_1 != null ? $arrResult[5]->result_1 : 0 ;     //충전금액
                    $arrEmpInfo['emp_money_exchange'] = $arrResult[6]->result_1 != null ? $arrResult[6]->result_1 : 0 ;		//환전금액
                    $arrEmpInfo['emp_money_give'] = $arrResult[7]->result_1 != null ? $arrResult[7]->result_1 : 0 ;     //지급
                    $arrEmpInfo['emp_money_withdraw'] = $arrResult[7]->result_2 != null ? $arrResult[7]->result_2 : 0 ;		//회수
                }

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
                // $arrReqData['end'] = $strDate.' 23:59:00';
                $eggs = $this->modelMember->calcGameEgg();

                $arrSumData = [[0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0] ];
                
                $tmNow = microtime(true) * 1000;
                // writeLog("empbetinfo");

                if(!$siteConfs['hpg_deny']){
                    $betModel = new PbBet_Model();
                    $betModel->setType(GAME_HAPPY_BALL);

                    // $objConfPb = $confgameModel->getByIndex(GAME_HAPPY_BALL);
                    $arrSum = $betModel->getBetSumByDay($arrReqData);
                    $arrSumData[0][0] += $arrSum[0][0] + $arrSum[1][0];
                    $arrSumData[0][1] += $arrSum[0][1] + $arrSum[1][1];
                    // writeLog("hpg_deny");
                }
                if(!$siteConfs['bpg_deny']){
                    $betModel = new PbBet_Model();
                    $betModel->setType(GAME_BOGLE_BALL);

                    $arrSum = $betModel->getBetSumByDay($arrReqData);
                    $arrSumData[0][0] += $arrSum[0][0] + $arrSum[1][0];
                    $arrSumData[0][1] += $arrSum[0][1] + $arrSum[1][1];

                    $betModel = new PsBet_Model();
                    $betModel->setType(GAME_BOGLE_LADDER);
                    $arrSum = $betModel->getBetSumByDay($arrReqData);
                    $arrSumData[0][0] += $arrSum[0];
                    $arrSumData[0][1] += $arrSum[1];
                    // writeLog("bpg_deny");
                }
                if(!$siteConfs['eos5_deny']){
                    $betModel = new PbBet_Model();
                    $betModel->setType(GAME_EOS5_BALL);

                    $arrSum = $betModel->getBetSumByDay($arrReqData);
                    $arrSumData[0][0] += $arrSum[0][0] + $arrSum[1][0];
                    $arrSumData[0][1] += $arrSum[0][1] + $arrSum[1][1];
                    // writeLog("eos5_deny");

                }
                if(!$siteConfs['eos3_deny']){
                    $betModel = new PbBet_Model();
                    $betModel->setType(GAME_EOS3_BALL);

                    $arrSum = $betModel->getBetSumByDay($arrReqData);
                    $arrSumData[0][0] += $arrSum[0][0] + $arrSum[1][0];
                    $arrSumData[0][1] += $arrSum[0][1] + $arrSum[1][1];
                    // writeLog("eos3_deny");

                }
                if(!$siteConfs['coin5_deny']){
                    $betModel = new PbBet_Model();
                    $betModel->setType(GAME_COIN5_BALL);

                    $arrSum = $betModel->getBetSumByDay($arrReqData);
                    $arrSumData[0][0] += $arrSum[0][0] + $arrSum[1][0];
                    $arrSumData[0][1] += $arrSum[0][1] + $arrSum[1][1];
                    // writeLog("coin5_deny");

                }
                if(!$siteConfs['coin3_deny']){
                    $betModel = new PbBet_Model();
                    $betModel->setType(GAME_COIN3_BALL);

                    $arrSum = $betModel->getBetSumByDay($arrReqData);
                    $arrSumData[0][0] += $arrSum[0][0] + $arrSum[1][0];
                    $arrSumData[0][1] += $arrSum[0][1] + $arrSum[1][1];
                    // writeLog("coin3_enable");

                }
                if(!$siteConfs['evol_deny']){

                    $arrReqData['type'] = GAME_CASINO_EVOL;
                    if(isEBalMode()){
                        $betModel = new EbalBet_Model();
                        $arrSum = $betModel->getBetSumByDay($arrReqData);
                        $arrSumData[1][0] = $arrSum[0];
                        $arrSumData[1][1] = $arrSum[1];
                    } else {
                        $betModel = new CsBet_Model();
                        $arrReqData['gm_range'] = $this->modelMember->getBetMinId($arrReqData, $betModel->table);
                        $objConfPb = $confgameModel->getByIndex($arrReqData['type']);
                        $arrSum = $betModel->getBetSumByDay($arrReqData, $objConfPb);
                        $arrSumData[1][0] = $arrSum[0];
                        $arrSumData[1][1] = $arrSum[1];
                        $confId = CONF_API_HPPLAY;
                        $agConf = $confsiteModel->getConf($confId);
                        if($agConf != null)
                            $arrSumData[1][2] = $agConf->conf_active;
                        $arrSumData[1][3] = $eggs->mb_live_money;
                    }
                }
                if(!$siteConfs['slot_deny']){
                    $betModel = new SlBet_Model();

                    if($_ENV['app.type'] == APP_TYPE_1 || $_ENV['app.type'] = APP_TYPE_3){

                        $gameId = GAME_SLOT_THEPLUS;
                        $confId = CONF_API_THEPLUS;
                        $arrSumData[2][3] = $eggs->mb_slot_money;
                        if($_ENV['app.slot'] == APP_SLOT_KGON){
                            $gameId = GAME_SLOT_KGON;
                            $confId = CONF_API_KGON;
                            $arrSumData[2][3] = $eggs->mb_kgon_money;
                        } else if($_ENV['app.slot'] == APP_SLOT_STAR){
                            $gameId = GAME_SLOT_STAR;
                            $confId = CONF_API_STAR;
                            $arrSumData[2][3] = $eggs->mb_hslot_money;
                        }

                        $objConfPb = $confgameModel->getByIndex($gameId);
                        $arrSum = $betModel->getBetSumByDay($arrReqData, $objConfPb);
                        $arrSumData[2][0] = $arrSum[0];
                        $arrSumData[2][1] = $arrSum[1];

                        $agConf = $confsiteModel->getConf($confId);
                        if($agConf != null)
                            $arrSumData[2][2] = $agConf->conf_active;
                    }

                    if($_ENV['app.type'] == APP_TYPE_1 || $_ENV['app.type'] == APP_TYPE_2){
                        $gameId = GAME_SLOT_GSPLAY;
                        $confId = CONF_API_GSPLAY;
                        $arrSumData[3][3] = $eggs->mb_fslot_money;
                        if($_ENV['app.fslot'] == APP_FSLOT_GOLD){
                            $gameId = GAME_SLOT_GOLD;
                            $confId = CONF_API_GOLD;
                            $arrSumData[3][3] = $eggs->mb_gslot_money;
                        }
                        $objConfPb = $confgameModel->getByIndex($gameId);
                        $arrSum = $betModel->getBetSumByDay($arrReqData, $objConfPb);
                        $arrSumData[3][0] = $arrSum[0];
                        $arrSumData[3][1] = $arrSum[1];
                        
                        $agConf = $confsiteModel->getConf($confId);
                        if($agConf != null)
                            $arrSumData[3][2] = $agConf->conf_active;
                    }
                    // writeLog("slot_deny");
                }
                if(!isEBalMode() && !$siteConfs['cas_deny']){
                    $betModel = new CsBet_Model();

                    $objConfPb = $confgameModel->getByIndex(GAME_CASINO_KGON);
                    $arrSum = $betModel->getBetSumByDay($arrReqData, $objConfPb);
                    $arrSumData[4][0] = $arrSum[0];
                    $arrSumData[4][1] = $arrSum[1];

                    $confId = CONF_API_KGON;
                    if($_ENV['app.casino'] == APP_CASINO_STAR)
                        $confId = CONF_API_STAR;
                    $agConf = $confsiteModel->getConf($confId);
                    if($agConf != null)
                        $arrSumData[4][2] = $agConf->conf_active;
                    $arrSumData[4][3] = $eggs->mb_kgon_money;

                }
                if(!$siteConfs['hold_deny']){
                    $betModel = new HlBet_Model();

                    $objConfPb = $confgameModel->getByIndex(GAME_HOLD_CMS);
                    $arrSum = $betModel->getBetSumByDay($arrReqData, $objConfPb);
                    $arrSumData[5][0] = $arrSum[0];
                    $arrSumData[5][1] = $arrSum[1];
				    $agConf = $confsiteModel->getConf(CONF_API_HOLD);
                    if($agConf != null)
                        $arrSumData[5][2] = $agConf->conf_active;
                    $arrSumData[5][3] = $eggs->mb_hold_money;

                }
                // writeLog("empbetinfo end duration = ".(microtime(true) * 1000 - $tmNow));

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
                    $objMember->mb_money_all = allMoney($objMember);
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

    
    public function getmembertree()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            // model
			$confsiteModel = new ConfSite_Model();

			$objResult = new \stdClass();
            $strUid = $this->session->user_id;
            $objAdmin = $this->modelMember->getInfoByUid($strUid);
			$mbFid = 0;
            $arrData['search'] = trim($arrData['search']);
            $objUser = null;
            if (strlen($arrData['search']) > 0){
                if($arrData['mode'] == 0){
                    if($arrData['type'] == 1){
                        $objUser = $this->modelMember->getByNickname($arrData['search']);
                    } else if($arrData['type'] == 2){
                        $objUser = $this->modelMember->getInfoByFid($arrData['search']);
                    } else if($arrData['type'] == 3){
                        $objUser = $this->modelMember->getByBankOwner($arrData['search']);
                    } else {
                        $objUser = $this->modelMember->getInfo($arrData['search']);
                    }
                    
                    if(is_null($objUser)){
                        $mbFid = -1;
                    } else {
                        $arrMem = $this->modelMember->getMemberByEmpFid($objAdmin->mb_fid, $objAdmin->mb_level,  $objAdmin->mb_level, true, $objUser->mb_fid);
                        if(count($arrMem) > 0 || ($objAdmin->mb_level < LEVEL_ADMIN && $objAdmin->mb_fid == $objUser->mb_fid ) )
                            $mbFid = $objUser->mb_fid;
                        else $mbFid = -1;
                    }
                } else $mbFid = -2;
            } 
            
            $bTree = false;
            $arrUsers = [];
            if($mbFid >= 0){
                $bTree = true;

                $arrMember = $this->modelMember->searchMemberTree($objAdmin, $arrData, $mbFid);
                if(is_null($arrMember))
                    $arrMember = [];

                if($objAdmin->mb_level < LEVEL_ADMIN){
                    $objAdmin->mb_self = 1;
                    array_unshift($arrMember, $objAdmin);
                }

                foreach ($arrMember as $objMember) {
                    if($objAdmin->mb_level < LEVEL_ADMIN && !is_null($objUser) && $objUser->mb_fid != $objMember->mb_fid)
                        continue;

                    $objMember->mb_money_all = allMoney($objMember);
                    $objMember->mb_point = floor($objMember->mb_point);
                    $objEmpInfo = $this->modelMember->find($objMember->mb_emp_fid);
                    if ($objEmpInfo != null){
                        $objMember->mb_empname = $objEmpInfo->mb_uid;
                    }
                    else {
                        $objMember->mb_empname = '';
                    }
                    array_push($arrUsers, $objMember);
                }
                
            } else if($mbFid = -2){
                $empFid = 0;
                $arrData['mb_uid'] = "";
                $arrData['mb_grade'] = "";
                $arrData['mb_state'] = -1;
                $arrData['page'] = 1;
                $arrData['count'] = 1000;
                if($arrData['type'] == 1){
                    $arrData['mb_nickname'] = $arrData['search'];
                } else if($arrData['type'] == 2){
                    $arrData['mb_fid'] = $arrData['search'];
                } else if($arrData['type'] == 3){
                    $arrData['mb_bank_own'] = $arrData['search'];
                } else {
                    $arrData['mb_uid'] = $arrData['search'];
                }

                $arrMember = $this->modelMember->searchMemberByEmpFid($objAdmin, $arrData, $empFid);
                if (is_null($arrMember)) {
                    $arrMember = [];
                }
                foreach ($arrMember as $objMember) {
                    $objMember->mb_money_all = allMoney($objMember);
                    $objMember->mb_point = floor($objMember->mb_point);
                    $objEmpInfo = $this->modelMember->find($objMember->mb_emp_fid);
                    if ($objEmpInfo != null){
                        $objMember->mb_empname = $objEmpInfo->mb_uid;
                    }
                    else {
                        $objMember->mb_empname = '';
                    }
                    array_push($arrUsers, $objMember);
                }
            }

            $confs= $this->getSiteConf($confsiteModel);
            $confs['emp_level'] = $objAdmin->mb_level; 
            
            $objResult->status = 'success';
            $objResult->confs = $confs;
            $objResult->data = $arrUsers;
            $objResult->tree = $bTree;


            echo json_encode($objResult);
        } else {
            $arrResult['status'] = 'logout';
            echo json_encode($arrResult);
        }
    }
    
    public function getmemberclass()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            // model
			$confsiteModel = new ConfSite_Model();
            $confs= $this->getSiteConf($confsiteModel);

			$objResult = new \stdClass();
            $strUid = $this->session->user_id;
            $objAdmin = $this->modelMember->getInfo($strUid);
			$mbFid = 0;
            $arrData['search'] = trim($arrData['search']);
            if (strlen($arrData['search']) > 0){
                if($arrData['mode'] == 0){
                    if($arrData['type'] == 1){
                        $objUser = $this->modelMember->getByNickname($arrData['search']);
                    } else if($arrData['type'] == 2){
                        $objUser = $this->modelMember->getInfoByFid($arrData['search']);
                    } else if($arrData['type'] == 3){
                        $objUser = $this->modelMember->getByBankOwner($arrData['search']);
                    } else 
                        $objUser = $this->modelMember->getInfo($arrData['search']);
                
                    if(is_null($objUser)){
                        $mbFid = -1;
                    } else {
                        $arrMem = $this->modelMember->getMemberByEmpFid($objAdmin->mb_fid, $objAdmin->mb_level,  $objAdmin->mb_level, true, $objUser->mb_fid);
                        if(count($arrMem) < 1)
                            $mbFid = -1;
                        else $mbFid = $objUser->mb_fid;
                    }
                } else $mbFid = -2;
                    
            } 
            
            $bTree = true;
            if($mbFid >= 0){
                $arrMember = $this->modelMember->searchMemberClass($objAdmin, $arrData, $mbFid, $confs);
                if (is_null($arrMember)) {
                    $arrMember = [];
                }
                foreach ($arrMember as $objMember) {
                    $objMember->mb_money = floor($objMember->mb_money);
                    $objMember->mb_point = floor($objMember->mb_point);
                    $objEmpInfo = $this->modelMember->find($objMember->mb_emp_fid);
                    if ($objEmpInfo != null){
                        $objMember->mb_empname = $objEmpInfo->mb_uid;
                    }
                    else {
                        $objMember->mb_empname = '';
                    }
                }
                
            } else if($mbFid == -2){
                $bTree = false;

                $empFid = 0;
                $arrData['mb_uid'] = "";
                $arrData['mb_grade'] = -1;
                $arrData['mb_state'] = -1;
                $arrData['order'] = "";
                $arrData['dir'] = "";
                $arrData['page'] = 1;
                $arrData['count'] = 1000;
                if($arrData['type'] == 1){
                    $arrData['mb_nickname'] = $arrData['search'];
                } else if($arrData['type'] == 2){
                    $arrData['mb_fid'] = $arrData['search'];
                } else if($arrData['type'] == 3){
                    $arrData['mb_bank_own'] = $arrData['search'];
                } else {
                    $arrData['mb_uid'] = $arrData['search'];
                }

                $arrMember = $this->modelMember->searchUserByLevel($arrData, $empFid, $confs);
                if (is_null($arrMember)) {
                    $arrMember = [];
                }
                foreach ($arrMember as $objMember) {
                    $objMember->mb_money = floor($objMember->mb_money);
                    $objMember->mb_point = floor($objMember->mb_point);
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

            $confs['emp_level'] = $objAdmin->mb_level; 
            
            $objResult->status = 'success';
            $objResult->confs = $confs;
            $objResult->data = $arrMember;
            $objResult->tree = $bTree;

            echo json_encode($objResult);
        } else {
            $arrResult['status'] = 'logout';
            echo json_encode($arrResult);
        }
    }

    public function memberIds()
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

                $ids = [];
                foreach ($arrMember as $objMember) {
                    array_push($ids, $objMember->mb_uid);
                }

                $objResult->status = 'success';
                $objResult->data = $ids;
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
                
                if($arrData['type'] == 0 || $arrData['type'] == 4){          //Deposit or Payment
                    $iResult = 1;
                    if($_ENV['mem.delay_play'] > 0 && $_ENV['mem.depodeny_play'] &&  diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                        $iResult = 2;
                    } 

                    if($iResult == 1){
                        if($this->modelMember->moneyProc($objMember, $arrData['amount']))
                        {
                            if($arrData['type'] == 0){
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
                                $iChangeType = MONEYCHANGE_INC;
                            } else 
                                $iChangeType = MONEYCHANGE_GIVE;
                            
                            $moneyhistoryModel->registerTransfer($objMember, $objEmp->mb_uid, $arrData['amount'], $iChangeType);
                            $objResult->status = 'success';
                        }
                    } else if($iResult == 2) {
                        $objResult->status = 'fail';
                        $objResult->msg = '회원이 게임플레이중이므로 충전 하실수 없습니다. '.intval(($_ENV['mem.delay_play']-diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet))/60+1)."분후 다시 시도해주세요.";
                    }
                } else if($arrData['type'] == 1 || $arrData['type'] == 5){           //Withdraw OR 
                    $iResult = 1;
                    if(floatval($objMember->mb_money) < $arrData['amount']){
                        if($_ENV['mem.delay_play'] > 0 && $_ENV['mem.withdeny_play'] && diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                            $iResult = 2;
                        } 
                        else 
                            $iResult = $this->alltoGame($objMember); 
                    } 
                    
                    if($iResult == 1){
                        
                        if(floatval($objMember->mb_money) < $arrData['amount']){
                            $arrData['amount'] = $objMember->mb_money;
                        }
                        if($arrData['amount'] > 0 && $this->modelMember->moneyProc($objMember, 0-$arrData['amount']))
                        {
                            if($arrData['type'] == 1){
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
    
                                $iChangeType = MONEYCHANGE_DEC;
                            } else $iChangeType = MONEYCHANGE_WITHDRAW;
                            
                            $moneyhistoryModel->registerTransfer($objMember, $objEmp->mb_uid, 0-$arrData['amount'], $iChangeType);
                            $objResult->status = 'success';
                        }
                    } else if($iResult == 2) {
                        $objResult->status = 'fail';
                        $objResult->msg = '회원이 게임플레이중이므로 환전 하실수 없습니다. '.intval(($_ENV['mem.delay_play']-diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet))/60+1)."분후 다시 시도해주세요.";
                    } else {
                        $objResult->status = 'fail';
                        $objResult->msg = '게임서버가 응답하지 않습니다. 잠시후 다시 시도해주세요..';

                    }
                    
                } else if($arrData['type'] == 6){          //Change Money to Point
                    $iResult = 1;
                    if(floatval($objMember->mb_money) < $arrData['amount']){
                        if($_ENV['mem.delay_play'] > 0 && $_ENV['mem.withdeny_play'] && diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                            $iResult = 2;
                        } 
                        else 
                            $iResult = $this->alltoGame($objMember); 
                    } 
                    
                    if($iResult == 1){
                        
                        if(floatval($objMember->mb_money) < $arrData['amount']){
                            $arrData['amount'] = $objMember->mb_money;
                        }
                        if($arrData['amount'] > 0 && $this->modelMember->moneyProc($objMember, 0-$arrData['amount'], $arrData['amount']))
                        {
                            $moneyhistoryModel->registerPointToMoney($objMember, 0-$arrData['amount'], $objEmp->mb_uid, MONEYCHANGE_CONVERT);
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
                if($arrData['type'] == 0){              //collect all money
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
                    
                } else if($arrData['type'] == 1){ //포인트회수
                    $nAmount = 0-$objMember->mb_point;
                    if($nAmount == 0){
                        $objResult->status = 'success';
                    } 
                    else if($this->modelMember->moneyProc($objMember, 0, $nAmount)){
                        $moneyhistoryModel->registerWithdraw($objMember, $objEmp->mb_uid, $nAmount, POINTHANGE_WITHDRAW);
                        $objResult->status = 'success';
                    }
                } else if($arrData['type'] == 2){ //포인트전환
                    $nAmount = floor($objMember->mb_point);
                    if($nAmount < 1){
                        $objResult->status = 'success';
                    } 
                    else if($this->modelMember->moneyProc($objMember, $nAmount, 0-$nAmount)){
                        $moneyhistoryModel->registerPointToMoney($objMember, $nAmount, $objEmp->mb_uid, POINTCHANGE_EXCHANGE);
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
                    'egg' => allEgg($objEmp),
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
                $result->egg = allEgg($objMember);
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
                    $result->egg = allEgg($objMember);
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
                            writeLog("<EVOL> Recovery Uid=".$objMember->mb_uid." Balance=".$objMember->mb_live_money);
                            if(diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                                $iResult = 2;
                            } else $iResult = $this->evtoMb($objMember);
                            if($iResult == 0)
                                break;
                            else usleep(500000);
                        } 
                    }

                    $arrResult = $this->libApiCas->getAgentInfo();
                    if($arrResult['status'] == 1){
                        $confsiteModel->setConfActive(CONF_API_HPPLAY, $arrResult['balance']);
                        writeLog("<EVOL> Agent Egg = ".$arrResult['balance']);
                        $balance = $arrResult['balance'];
                    }
                } else if($gameId == GAME_SLOT_THEPLUS){
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

                    $arrResult = $this->libApiSlot->getAgentInfo();
                    if($arrResult['status'] == 1){
                        $confsiteModel->setConfActive(CONF_API_THEPLUS, $arrResult['balance']);
                        writeLog("<SLOT> Agent Egg = ".$arrResult['balance']);
                        $balance = $arrResult['balance'];
                    }
    
                } else if($gameId == GAME_SLOT_GSPLAY){
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

                    $arrResult = $this->libApiFslot->getAgentInfo();
                    if($arrResult['status'] == 1){
                        $confsiteModel->setConfActive(CONF_API_GSPLAY, $arrResult['balance']);
                        writeLog("<FSLOT> AGENT Egg = ".$arrResult['balance']);
                        $balance = $arrResult['balance'];
                    }
    
                } else if($gameId == GAME_SLOT_GOLD){
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

                    $arrResult = $this->libApiGslot->getUserInfo();
                    if($arrResult['status'] == 1){
                        $confsiteModel->setConfActive(CONF_API_GOLD, $arrResult['balance']);
                        writeLog("<GSLOT> Agent Egg = ".$arrResult['balance']);
                        $balance = $arrResult['balance'];
                    }
                } else if($gameId == GAME_CASINO_KGON || $gameId == GAME_SLOT_KGON){
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
                    
                    $arrResult = $this->libApiKgon->getAgentInfo();
                    if($arrResult['status'] == 1){
                        $confsiteModel->setConfActive(CONF_API_KGON, $arrResult['balance']);
                        writeLog("<KGON> AGENT Egg = ".$arrResult['balance']);
                        $balance = $arrResult['balance'];
                    }
                } else if($gameId == GAME_SLOT_STAR){
                    foreach($arrMember as $objMember){
                        if($objMember->mb_hslot_token != "" && $objMember->mb_hslot_money > 0 ) {
                            writeLog("<HSLOT> Recovery Uid=".$objMember->mb_uid." Balance=".$objMember->mb_hslot_money);
                            if(diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                                $iResult = 2;
                            } else $iResult = $this->hsltoMb($objMember);
                            if($iResult == 0)
                                break;
                            else usleep(500000);
                        } 
                    }

                    $arrResult = $this->libApiHslot->getAgentInfo();
                    if($arrResult['status'] == 1){
                        $confsiteModel->setConfActive(CONF_API_STAR, $arrResult['balance']);
                        writeLog("<HSLOT> Agent Egg = ".$arrResult['balance']);
                        $balance = $arrResult['balance'];
                    }
                }  else if($gameId == GAME_HOLD_CMS){
                    foreach($arrMember as $objMember){
                        if($objMember->mb_hold_uid != "" && $objMember->mb_hold_money > 0 ) {
                            writeLog("<HOLDEM> Recovery Uid=".$objMember->mb_uid." Balance=".$objMember->mb_hold_money);
                            if(diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                                $iResult = 2;
                            } else $iResult = $this->holtoMb($objMember);
                            if($iResult == 0)
                                break;
                            else usleep(500000);
                        } 
                    }

                    $arrResult = $this->libApiHold->getUserInfo();
                    if($arrResult['status'] == 1){
                        $confsiteModel->setConfActive(CONF_API_HOLD, $arrResult['balance']);
                        writeLog("<HOLDEM> Agent Egg = ".$arrResult['balance']);
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

    // function massMember(){
    //     $infos = array (
    //         array( 'jpm9743','제이피엠','0000','0000','ssk ','jw8188','케이뱅크','100160278688','김종권','01099914051','4780','6301','0'),
    //         array( '278011','해물1','123456','123456','mvp ','Ok1004','농협','71704456021763','최성대','01028332783','0','8812','0'),
    //         array( 'ho1godme','김치맨','wltjd123','wltjd123','mvp','mvp','신한은행','1.10332E+11','김지성','01029334910','0','0','600000'),
    //         array( 'byun1566','성안','bjk5636','bjk0458','kyu1','cn7777','기업은행','39206741001018','변재근','01043580458','0','2000','0'),
    //         array( 'Illmin','푸하하','ohring','ohring','bbc ','abc1 ','카카오뱅크 ','3333-06-9408370','이민섭','01022636483 ','0','0','500,000'),
    //         array( 'byebye222','공이공이님','dmsgk7787.','dmsgk7787.','mvp','beastkal77','농협','80116052073055','김은하','01051597379','0','4000','0'),
    //         array( 'romanshy','마카오도선생','h@6245768','h@6245768','abc ','4885 ','농협','3560459184693','황현수','01082787687','0','75','0'),
    //         array( 'bonedra1','본드라','aa12151210','aa12151210','mvp ','bonedra77','국민은행','27380204158349','조우현','01080256021','0','1582','4040'),
    //         array( 'choo8489','야쿠르트좋아','321250','321250','kyu1 ','jmt9999','카카오뱅크','3333084591730','이현주','01094309728','0','0','0'),
    //         array( 'filmlove21','시라소니','991060','991060','mvp ','po1128','카카오뱅크','3333066095541','이상준','01020492923','0','10000','0'),
    //         array( 'lh9393','마포쌥쌥이','lh0224','lh0224','mvp ','mvp48 ','신한은행 ','110314649050','이훈 ','01064199394','0','15631','170000'),
    //         array( 'doskr0315','홀덤킹','t2123@*3','04170417','mvp','okok100 ','농협','3560697232093','김종호','01025295243','0','4114','0'),
    //         array( 'pangpang','팡팡','0023','0023','abc','soffice ','카카오뱅크','3333 10 5392206','김효정','01088880898','0','0','0'),
    //         array( 'ngo1','한국인','zaq1234','zaq1234','kyu1 ','ng365 ','카카오뱅크','3333246860621','박분자','01081710165','0','2000','0'),
    //         array( 'obong77','지존무상','dkagh7399@','dkagh7399@','collabo1','obong','신한은행','110356406361','하연우','01055455050','0','11436','0'),
    //         array( 'sun22','sun22','1004','1004','kyu1 ','sun33','하나은행','34391055331107','한동선','01071528863','5806','0','0'),
    //         array( 'ddancer7','믹스믹스','kjh7325','kjh7325','mvp','sky777 ','카카오뱅크','3333186768664','김재학','01067782747','0','4000','0'),
    //         array( 'ksh1818','비전','k1818','k1818','collabo1','king1234','케이뱅크','100102410137','김성환 ','01041141818','0','0','0'),
    //         array( 'vhfptmxm00','난곡111','mouse5837','mouse5837','mvp','danawa00','카카오뱅크','3333203047293','김진웅','01038178311','0','3000','0'),
    //         array( 'min746','프로짱깨','1011746','1011746','dandy','dandy ','우리은행','1002 964 037256','정민규','01047617048','-200,000','0','0'),
    //         array( 'Mangchi12','망치','Qwer1234','Qwer1234','mvp','soonyang','카카오뱅크','3333070888092','이종현','0100000000','0','1647','0'),
    //         array( 'kjo01','판도라','yu1017215~','yu1017215','ssk ','ssk ','부산은행','1012054212506','김진욱','1111','0','0','0'),
    //         array( 'doll0702','생활도박꾼1','a13137','a13137','mvp','beastkal77','케이뱅크','100102725526','백다훈','01037809292','0','0','0'),
    //         array( 'aa999','애플99','zaq12','zaq12','kyu1','cn7777 ','하나은행','33491018854207','김형률','01076108425','0','4000','0'),
    //         array( 'vcom0325','브이','cdshek0917','cdshek0917','mvp ','po1128','카카오뱅크','3333056296583','최대성','01042763034','0','0','0'),
    //         array( 'youngkal11','youngkal11','a1122','a1122','mvp','youngka11','부산은행','031120769184','황성태','01063648296','0','0','0'),
    //         array( 'jehuen','공간스테이','dlswjfal1!','dlswjfal1!','abc','soffice','국민은행','35060204003481','김현송','01097813355','4044','2000','82'),
    //         array( 'romanshy2','마카오현이','h@6245768','h@6245768','abc','4885','농협','3560459184693','황현수','01082787687','0','2539','0'),
    //         array( 'dgh9641','월곡동꿀주먹','byungsoo1!','byungsoo1!@','mvp','beastkal77','국민은행','06590204214424','김병수','01050189641','0','0','0'),
    //         array( 'Jkb4000','스카이','807788','807788','mvp','mvp48','우리은행','45303821702001','배진국','01074794000','0','4000','0'),
    //         array( 'Kopajio','코파지요','9648','9648','abc ','abc','농협','3522100938443 ','김효정','01036660893','0','4000','0'),
    //         array( 'blue','뫼팁','4z5z6z7z','4z5z6z7z','mvp','beastkal77','케이뱅크','100116627923','원선재','01041408125','0','4000','0'),
    //         array( 'jaehyun7151','팡팡팡팡팡','kjh8500','kjh8500','mvp ','sw5500','농협','3510291507283','김재현','01090797151','0','18038','0'),
    //         array( 'psy79cho','고니','h6k8r4','h6k8r4','mvp ','wegari ','카카오뱅크 ','3333197591136','홍미정','01077623669','0','3798','0'),
    //         array( 'Fuckme18','뻑미18','Boyboy22','Boyboy22','mvp','sky777 ','카카오뱅크','3333031125813','강윤석','01068793641','0','4000','0'),
    //         array( '95kimes','은서','rladmstj1','rladmstj1','mvp','WOW114','카카오뱅크','3333069945725','김은서','01058090357','0','0','0'),
    //         array( 'ok2706','대박이1','0923','0923','kyu1','kk5191','새마을금고','9002187370566','김현석','01051912706','0','2000','0'),
    //         array( 'kyoung97377','블랙타이거10','ghdejr12','ghdejr12','mvp ','beastkal77','카카오뱅크','3333129838620','홍덕','01095654008','8334','3000','0'),
    //         array( 'DODO1234','도도도도','ahoo3254','ahoo2916','mvp','wook0021 ','새마을금고','9003277903405','이정은','01049004448','0','11960','3000'),
    //         array( 'kimcode','흑우','edbag#1321','edbag#1321','mvp','k520075 ','신한은행','110383246924','김성환','01087416803','0','3000','0'),
    //         array( 'mgcharm','명불허전','m36453645!@','m36453645!@','abc ','4885','기업은행','38909514701010','이명건','01030242337','0','6000','0'),
    //         array( 'sense678','당구시대','s08171982','s08171982','mvp','okok100','우체국','70057502187651','정상희','01024264441','614','400','0'),
    //         array( 'kti5656','불수시개','ite8913','ite8913','collabo1','korea4848','우리은행','29523180202001','강태일','01085345656','0','0','0'),
    //         array( 'bull019','가자으','72947366','1140','ssk ','jw8188','신한은행','110443575703','한대진','01084474969','0','0','0'),
    //         array( 'ko2929','조자룡','skrkwk12!@','skrkwk12!@','mvp','jj77','카카오뱅크','3333244477019','권용선','01086181206','0','0','0'),
    //         array( 'basyo','바쇼1988','1988!!@@','1988!!@@','mvp','mvp0','마닐라','1111','바쇼1988','11111','0','0','0'),
    //         array( 'Skvv911','동동동동','Sj0916','Sj0916','mvp ','po1127 ','신한은행','110447500872','정성조','01086818365','0','2000','129'),
    //         array( 'baepro72','배프로','pos9425!','pos9425!','mvp','jj77','카카오뱅크','3333111154547','배민기','01039136339','0','0','0'),
    //         array( 'mp01','엠피01','123123@#','123123','mvp ','mm01 ','농','12341','헝','010','0','64055','0'),
    //         array( 'neone4912','가을들녘11','1q2w3e4r5t','1q2w3e4r5t','mvp','shs4578','새마을금고','9002166760534','박종일','01039554585','0','4484','0'),
    //         array( 'ningure','카이도해적무','4862115z','4862115z','bbc ','abc1 ','농협','3528972357393','이무방','01089723573','0','2000','200000'),
    //         array( 'yingying2','읭읭','STar5445^^','STar5445^^','mvp ','sky777','카카오뱅크','3333058230879 ','이승하','01054266446','0','2000','0'),
    //         array( 'bum77','범777','w3053','w3053','kyu1','bum88 ','농협','3510800396933','김성범','01088277088','0','4000','0'),
    //         array( 'antka0215','새복이','@@antka','a1234','mvp','okok100','케이뱅크 ','100132470225','이진우','01090086162','1','3110','0'),
    //         array( 'poopooking','홀덤왕이태혁','po0809!','po0809!','abc','4885 ','경남은행','2210048288404','정문재','01066551182','0','0','0'),
    //         array( 'koko88','논논다다','jjs88','jjs88','mvp ','AK88','신한은행','110425749788','전백민','01088889999','0','0','0'),
    //         array( 'andy0365','앤뒤','147200','147200','kyu1','sun33','국민은행','805210841901','박상율','01073687003','6000','0','0'),
    //         array( 'jungmadam','정마담님','ch123456','ch123456','mvp ','jang ','국민은행','10140204248352','정아윤','01074256222','5684','5333','0'),
    //         array( 'Sakula77','아리아','132962cz@','132962cz@','mvp','wook0021','카카오뱅크','3333192069493','신진섭','01089885900','0','0','29999'),
    //         array( 'qkrwogh','홀행님','park2187','park2187','bbc ','abc1','국민은행','20390204263187','박재호','01096444447','0','0','0'),
    //         array( 'yu4008','타짜왕1','song80000@','song80000','collabo1 ','song700','기업은행','41209962801013','양인호','01033983112','0','0','700000'),
    //         array( 'polo0277','너쿠자화이팅','1234!@#','1234!@#','total ','total ','카카오뱅크','3333086910329','원형숙','01055554433','6000','0','0'),
    //         array( 'TIGER','타이거','yu1017215','111','ssk ','ssk ','농협','3561014649803','신유지','1040','0','0','0'),
    //         array( 'Al3102','자미성','al3102##','al3102 ','ssk','ssk','경남은행 ','707210054570 ','김민규 ','1111','0','0','0'),
    //         array( 'facemoi','탄이타니','1212','1212','mvp ','lyh1414','신협','01077643866','홍석현','01077643866','0','0','0'),
    //         array( 'cjseogns11','백마왕','A4160dhc','A4160dhc','mvp','po1128','기업은행','22409640701013','천대훈','01067877091','0','0','0'),
    //         array( 'topclass','승부중','12345@','12345@','top','top','우리은행','1002342782209','김현명','01089324026','0','0','0'),
    //         array( 'saka28','토니','1234@','1730','collabo1 ','saka25 ','국민은행','81810201094700','조윤경','01065995757','0','0','0'),
    //         array( 'bigbet124','빅홀','jh0327','jh0327','abc','bigbet1248','카카오뱅크','3333032836619','정재현','01091162727','0','0','0'),
    //         array( 'ghdrl1303','지금이','ksb135790@','ksb135790@','kyu1 ','fnx999','카카오뱅크','3333011714625','김홍기','01082278226','9753','0','0'),
    //         array( '2hana2','기리링','asdasd12','asdasd12','kyu1 ','fnx999','농협','3024642767681','김성진','01046117686','0','0','0'),
    //         array( 'goldriver','깡다구','1234@@','1234@@','collabo1','allin ','카카오뱅크','3333268797538 ','남기옥','','0','2000','0'),
    //         array( 'song80000','타짜왕11','song80000@','song80000','collabo1','song700','농협','79812207033','양인호','01033983112','0','0','0'),
    //         array( 'dorocy12','룰루랄라','dorocy0113','dorocy0113','ssk','jw8188 ','국민은행','60640104491175','김예옥','01099133213','0','0','0'),
    //         array( 'WOW111','와우1','wow1','wow1','mvp ','WOW114','카카오뱅크','3333118855667','홍성표','01010101','0','2000','0'),
    //         array( 'y1090','이랑제이','1q2w3e4r5t@@','1q2w3e4r5t@@','mvp','shs4578','새마을금고','9003243545051','윤석현','01059410315','7728','0','0'),
    //         array( 'okok1000','다마','@@1234','@@123123','mvp','okok100','농협','71704256036765','오상준','01028170503','2333','60','0'),
    //         array( 'saywon807','권구','0311','0311','collabo1','saka25 ','카카오뱅크','3333049338001','박현정','01082622700','0','0','0'),
    //         array( 'jp11233','산도령1','54321','54321','kyu1','jp1123','sc제일','21620161210','천재필','01036490649','0','4000','0'),
    //         array( 'sale777','오레오레오','1233','1233','mvp','good1004 ','국민은행','13150104418388','김규동','01028663769','20000','2000','0'),
    //         array( 'dog3538','십새','aa0808!','aa0808!','mvp ','sw5500','신한은행','110418507273','정래준','01066633331','0','1106','0'),
    //         array( 'Ck9047ck','독불장군','@@ck9047','@@ck9047','mvp ','khg5879','카카오뱅크','3333082619192','김창기','01022722199','0','0','0'),
    //         array( 'milk11','우유','1212qa1212','1212qa1212','mvp ','popo01','국민은행','12630204100837','정숙','01095628745','9,989','4960','0'),
    //         array( 'pxg1212','골프왕','1212qa1212','1212qa1212','mvp','popo01','농협','3561554755723','강경민','01012121212','6384','0','0'),
    //         array( 'ymg8455','아재8455','dustjdrnr12','dustjdrnr12','dandy ','dandy ','신한은행','110520978470','연성국','01059238455','0','0','0'),
    //         array( 'ssjj777','잘해봅시다','qwqw1212!!','qwqw1212!!','bbc','abc1','대구은행','508118092252','김동희','01091705412','0','4000','0'),
    //         array( 'po1177','법돌이1','love800520!','love800520!','mvp ','po1127','국민은행','65810101385803','이호철','01090039555','0','4000','0'),
    //         array( 'cn777','비젼77','zaq12','zaq12','kyu1 ','sun33','국민은행','85180101515407','추설희','01052570130','0','0','0'),
    //         array( 'Coupe00','한라산1','Asas0909','Asas0909','collabo1','twotwo','새마을금고 ','9003241109410','박재성','01046924006','0','2173','0'),
    //         array( 'malss9','방지','1234','6281','abc ','soffice','농협','3021753883621','김효정','01076553331','0','4000','0'),
    //         array( 'wnsgur1560','알브딩','q10474502!','q10474502!','abc','abc ','토스뱅크','100033911004','김준일','01096380417','18','0','0'),
    //         array( 'bsk007','율하','bank1057@','bank1057','ssk ','bsk','수협','001088341057','백선길','01088341057','0','0','0'),
    //         array( 'nightgod0505','전주좆빱','@Dudgh2132','@Dudgh2132','mvp','zxc0445','sc제일은행','70120498257','이효현','01088391331','0','2000','0'),
    //         array( 'soo3','새로이','@@123123','@@123123','mvp ','soo200 ','새마을금고','5228090014208','박세혁','01046223484','0','9623','0'),
    //         array( 'tntll1225','가나다라','12345@','12345@','collabo1 ','twotwo ','케이뱅크','100192350232','윤진희','01058185067','9631','0','0'),
    //         array( 'min1234','재재아','min2104','min2104','bbc ','abc1','국민은행 ','82170101489695','이호재','01035304875','0','0','0'),
    //         array( 'Kjh1432','오아시스','jy1332xx','jy1332xx','mvp ','bsh1432 ','카카오뱅크','3333029985228','김지혁','01035122642','0','0','0'),
    //         array( 'aa1234','지안파더','Qaz1234','Qaz1234','mvp','hk0930 ','우리은행','1002563135384','홍학기','01083353229','983','2829','0'),
    //         array( 'lg01','구원자','lg01','lg01','mvp','in6006','카카오뱅크','3333220721872','최인','010679500900','0','2000','0'),
    //         array( 'ssakgawa','가와','cc12!@','cc12!@','mvp ','mvp0 ','','','','','184908','0','2000000'),
    //         array( 'onepunch','원펀','asd123!@','asd123!@','mvp ','mvp0 ','','','','','0','2000','0'),
    //         array( 'gkstjs2','써니텐','qweqwe','qweqwe','kyu1 ','jmt9999','국민은행','78490101475364','노한선','01091854500','0','0','0'),
    //         array( 'zz0w0','못말리는뚱이','ekfkadl1!','ekfkadl1!','ssk ','bsk','국민은행','12210104181923','조다람','01022111047','1','0','0'),
    //         array( 'madryu','홀린이','kidb2007','kidb2007','mvp ','soonyang','우리은행','1002859500180','유성훈','01055135044','0','0','0'),
    //         array( 'samsam01','삼삼삼','samsam01@!@','samsam01@!@','mvp ','sam01','국민은행','42240101586049','이동수','','0','0','0'),
    //         array( 'vvip888','불빠따','7922aa7922','7922aa7922','mvp ','a0001','기업은행','52003638001013','이지승','01052627754','0','0','0'),
    //         array( '1357als','허니','qpscm500^!^','qpscm500^!^','mvp ','jang','하나은행','25391008417007','조성은','01093913907','0','5000','0'),
    //         array( 'sksk11','난나난','qwer1111','qwer1111','mvp ','jin01','국민은행','61610204235373','이진아','01039374743','7423','0','0'),
    //         array( 'jjj0301','파이터','qwzx111222','qwzx111222','mvp ','one001','케이뱅크','100177250122','정의승','01095739928','7117','0','0'),
    //         array( 'kkk5325','마도로스','kkk444','kkk444','mvp','ak77','우체국','20001402532503','이용한','01067305325','0','0','0'),
    //         array( 'tjsgh01','선호야01','opop9090@','opop9090@','mvp ','ace002','신협','132094863907','이덕진','01067369099','335','0','0'),
    //         array( 'pdh1203','pdh1203','120300@','120300','','bbangbbang ','','','','','310,599','50','2575395'),
    //         array( 'qwe123','달달','1344','1344','mvp','wook0021 ','케이뱅크','100130050248','윤소영','01076058417','0','2430','0'),
    //         array( 'qqww9518','올킬맨1','9518','9518','ssk ','ssk ','농협','3021046166681','김종령','01043428235','0','2260','0'),
    //         array( '7981','보니퍄쇼','7981@','hyuk2219','collabo1 ','-asd1234','국민은행','61580104039610','신정길','01030589800','0','4764','0'),
    //         array( 'mydbslzhs7','말미말비','mydbslzhs7','mydbslzhs7','mvp ','pubg','카카오뱅크','3333117061177','임동연','01098675113','0','2000','0'),
    //         array( 'carrlos1','까미오리1','whgdkdy1','whgdkdy1','abc','4885 ','카카오뱅크','3333042396125','박태웅','010230377620','0','2000','0'),
    //         array( 'hazzuzzuba','천둥에이','godsla12','godsla12','mvp ','zxcv1212','농협','3021629762251','백광준','01048016363','0','1526','0'),
    //         array( 'song90000','song90000','song90000@','song90000','collabo1 ','song700','농협','79812207033','양인호','01033983112','0','2000','0'),
    //         array( 'kbs1212','강남곰돌이','kbs6486','kbs6486','mvp','mvp57 ','카카오뱅크','3333165183335','김병성','01096060025','0','0','0'),
    //         array( 'kkk7792','마카티121','qwe7792','qwe7792','mvp ','ak77','농협','3021328380011','이강혁','01075805288','0','0','0'),
    //         array( 'in0708','인생한방','1446ju','1446ju','mvp','mvp27 ','농협','3560922603733','김유진','01050606561','0','12,853','0'),
    //         array( 'rkdgur7909','마이다스','t2123@*3','7909','collabo1 ','add12 ','농협','3021397996911','이강혁','01075805284','0','300','0'),
    //         array( 'prime1987','프라임','1987!!@@','1987!!@@','mvp','mvp0','마닐라','11111','프라임','','0','10000','0'),
    //         array( 'kkk1234','시애틀','kkk123','kkk123','abc ','la1248','카카오뱅크','3333252213345','전미령','01096738233','0','2000','0'),
    //         array( 'ssba7942','도박중독자','a9676761','a9676761','abc','abc','토스뱅크','100047259117','송인보','01084903042','0','3937','0'),
    //         array( 'gokije','고기제이','11111@','11111@','ssk','ssk ','농협','3518840440213','신승연','0000','0','0','0'),
    //         array( 'mm0909','엠공구','usa9231@@','asas12!@','mvp ','mm01','농협','3021410954501','오형근','01021213333','0','137','0'),
    //         array( 'in0090','강릉1번','in6006','in6006','mvp','in6006 ','카카오뱅크','3333220721872','최인','01067950090','0','9109','0'),
    //         array( 'dlwlsdn4762','타조','@4762','4762','mvp ','Ok1004','새마을금고','9003234879521','이진우','01090086163','5000','4483','0'),
    //         array( 'qw02','윈터','1234@','1234','collabo1 ','-asd1234','우리은행','1002749203010','윤영호','01031513363','655','60','0'),
    //         array( 'song70000','타짜','song70000@','song70000','collabo1','song700 ','농협','79812207033','양인호','01033983112','0','2000','0'),
    //         array( 'KDJ','라이언','1111@','1111','ssk','ssk','부산은행','1122241197801','김대종','01080785452','0','0','0'),
    //         array( 'soo1','고래아빠','@@123123','@@123123','mvp','soo200','신한은행','110 506 315113','김현수','01092091661','0','0','0'),
    //         array( 'tytyl777','망언터진스님','asdf1008','asdf1008','mvp','danawa00 ','케이뱅크','100199440024','정현대','01088645285','0','0','0'),
    //         array( 'jakal425','정회장님','asas3240','asas3240','abc ','4885 ','토스뱅크','100033397303','정재식','01031568234','0','0','0'),
    //         array( 'gon1928','사짜','54070410ss!','54070410ss!','ssk','bsk','농협','3561496158693','한창곤','01039537515','0','4000','0'),
    //         array( 'sb30125','마광일','k96321','k96321','mvp','psy123','토스뱅크','100035225353','원동범','01048940506','15246','0','0'),
    //         array( 'woghxx','읽어라','qweqwe11','qweqwe11','abc','abc','카카오','3333 11 4708107','김재호','01056240872','2081','0','0'),
    //         array( 'chows','배송지시서','asertv2','asertv2','bbc','tough222','대구은행','508102766838','조우성','01041318766 ','0','0','0'),
    //         array( 'jhon1616','jhon1616','song90000@','song90000@','collabo1','song700','기업','412 099628 01 013','양인호','01033983112','186642','0','6000'),
    //         array( 'hoya777','배짱이','nice2500','nice2500','collabo1','carry','농협','3561395133313','김순희','01096412552','','1000',''),
    //         array( 'fomnajana','난다날아','dmstnr1109','dmstnr1109','mvp','Ok1004 ','하나','52391024650807','남정우','01052461205','','1844',''),
    //         array( 'freemanpill','뜨면먹지','qpscm500^!^','qpscm500^!^','mvp','jang ','부산은행','054121218218','장승필','01035582000','117417','440',''),
    //         array( 'kialswn','두만쓰','tprh0076','tprh0076','mvp','p222','국민','20130204083994','심민주','01092276594 ','','',''),
    //         array( 'ad770225','효기','qz3199qzqz','qz3199qzqz','mvp','bm9999','카카오뱅크','3333 07 1930122  ','최효진','01039092687','','2000',''),
    //         array( 'minu3714','킁킁이','tkdals12','tkdals12','dandy','dandy','광주은행','1121027364912','박상민','01064613714','','373',''),
    //         array( 'jack1233','모모모','12331233','12331233','mvp ','cho','기업은행','04508122701048','이야순','12345678','','2530',''),
    //         array( 'sdsknk12','도로꼬','ZXC1213','ZXC1213','mvp ','ggame001 ','케이뱅크','100150743921','정해정','01088975575','','',''),
    //         array( 'Qaz741','오아시스1','jy1332xx','1234','mvp','bsh1432 ','카카오뱅크','3333029985228','김지혁','01035122642','','',''),
    //         array( 'as11','턴리버','aa112233','aa112233','collabo1','acadia','카카오뱅크','3333243749105','최세병 ','01076807242','','',''),
    //         array( 'mklove8836','럭키맨','pk1234@','pk1234@','mvp','shs4578 ','농협','3511007024993','문학기','01044488836','','20507',''),
    //         array( 'jhe0320','넣고기도','gusdlr0320','0320','mvp','soonyang','카카오뱅크','3333050619954','정현익','01098029904','','',''),
    //         array( 'tvortoo','셔틀출신','090909','090909','abc','4885','농협','3021615771171','김보성','01073158254','','1000',''),
    //         array( 'sktk11','에코프로','zaq12','zaq12','kyu1','cn7777 ','기업은행','60102118002010','추설희','01042609852','','',''),
    //         array( 'qkrzna','캘빈쿰','gus1349','gus1349','kyu1 ','fox','카카오뱅크','3333083061185','이현석','01037431349','','',''),
    //         array( 'q1q1w1w1','오박사','qqq111','qqq111','mvp','mvp46 ','하나은행','72491023539607','조인석','01047575922','','22754',''),
    //         array( 'moda','오태식','moda1370','moda1370','abc','soffice','기업','45706612201017','이재원','01026469155','6275','2000',''),
    //         array( 'mjmj4885','에이스프로','opop1212!','opop1212!','mvp ','a0001','카카오뱅크','3333027927976','황명진','01048798333','','25217',''),
    //         array( 'mebu82','문재앙','13q12q12','13q12q12','dandy','dandy','국민','42400201285839','박설민','01075423920','','',''),
    //         array( 'jjang9193','카라도마','jjang9193','jjang9193','mvp ','shs4578 ','농협','33301652142110','최성형','01096469193','5077','5947',''),
    //         array( 'choi','한강한강','0206choi','0206choi','mvp ','mvp51','대구은행','0115265555','최한광','01035265555','5560','1019',''),
    //         array( 'chleoen','덱스터','1234','1234','collabo1 ','a25000 ','카카오','3333095974030','최두영','01083848562','1385','',''),
    //         array( 'ming9255','망구랑','rkskek123','rkskek123','kyu1 ','pood364','카카오뱅크','3333108455386','전민규','01074159255','','',''),
    //         array( 'boss12345','수유리지후님','as1400','as1400','mvp ','boss1234','국민','69470101528048','손석태','01055221400','','2218',''),
    //         array( 'Sunghyu8024','라페라리','Sunghyun8024','Sunghyun8024','mvp','psy123','농협','3561465332053','박성현 ','01026532693','','4243',''),
    //         array( 'lbc001','한방','aaaaa123','aaaaa123','mvp ','mvp07','새마을금고','9003 2380 2486 1','김정화','09994892999','186827','9460',''),
    //         array( 'kdj1711','수박','1711','1711','mvp ','po1128','국민은행','99440201019414','김대중','01023747983','','',''),
    //         array( 'dbong01','봉아저씨1','yesmall25','yesmall25','collabo1','propark','대구은행','184080128298','박영진','01045167931','9670','',''),
    //         array( 'zus120','만두상','dl3357037','dl3357037','kyu1 ','fnx999','카카오뱅크','3333144656289','이형민','01057107037','3437','',''),
    //         array( 'elfinston','짹필79','3293','3293','bbc','abc1','수협','103011133941','박상필','01099856066','','2000',''),
    //         array( 'bongjua1','1기동1','bongjua12','bongjua12','mvp ','RED123','Sc제일은행','72820025130','김봉주','01024201122','','9377',''),
    //         array( 'dkansk231','지티오','skrmsp1','skrmsp1','ssk ','bsk','카카오뱅크 ','3333062438538','최성욱','01062819951','','1920',''),
    //         array( 'sbsmbc12','두야두야','skfksk12','skfksk12','abc ','4885','농협','3560423504063','서창수','01084667681','','',''),
    //         array( 'qkdel12','88렉카','zxc8668','zxc8668','ssk','jw8188 ','농협','3010212246101','박영환','01081202257','','21000',''),
    //         array( 'olol66','강한송불리','sjh0085a!','sjh0085a!','mvp ','WOW114','농협','3511026304133','송정화','01038059077','','',''),
    //         array( 'live0127','산수','dkdnel11','dkdnel11','bbc ','na49511','sbl상호저축은행','02810 13 6573081','김정훈','01022068716','','6000',''),
    //         array( 'JunKay','준케이','abc123','dkaghek1','mvp','chuck ','우체국','70421302155737','전기채','01057814187','','2814',''),
    //         array( 'Benz','리시크','asd1212','asd1212','mvp','wook0021 ','농협','3021264753181','김명섭','01058820969','','6361',''),
    //         array( 'bio11','걷어들여','a123123!@','123400','mvp','mvp41','토스뱅크','100051355217','송민형','01075183595','5202','2000',''),
    //         array( 'jxzizon','승승승','akfqhfn12','akfqhfn12','mvp','shs4578','농협','33309456033409','오대균','01020470513','','2000',''),
    //         array( 'gumdo119','도다리','gustjr772883','gustjr772883','bbc','0909op ','카카오뱅크','3333048147472','김현석','01051838356','','',''),
    //         array( 'panda','곰돌이1','aa112233','aa112233','collabo1','acadia ','새마을금고','9003232186900','심재진','01074449449','8','4000',''),
    //         array( 'spd0604','엠제이제이','spd123','spd123','mvp ','WOW114','카카오뱅크','3333068846230','정민재','01080815492','3962','2000',''),
    //         array( 'gg1004gg','뜨거운심장','735702','735702','mvp','psy123','국민은행','757301 000 444 66 ','곽순상','01027447448','','5576',''),
    //         array( 'danawa5837','신림','mouse5837','mouse5837','mvp','mvp48 ','카카오뱅크','3333164195832','허준','01038089330','','',''),
    //         array( 'avia9007','덕팔이','avia9007!!','123123','mvp ','mvp40','농협','3021591771441','정덕용','01031819007','40000','8158',''),
    //         array( 'mnbv1234','사용중','890108j','890108j','mvp','boss1234','카카오뱅크','3333156414786 ','임재민','01091756667','2275','5895',''),
    //         array( 'xodn1023','샤크','xodn9159','xodn9159','bbc','airbag','신한','110206614006','김태우','01091842733','','',''),
    //         array( 'qkrgmlwls','총총','okh0308','okh0308','mvp ','soonyang','카카오뱅크','3333073471227','이경민','01075897378','','9142',''),
    //         array( 'ktg5129','탈북자','q1w2e3r4t5!','q1w2e3r4t5!','abc','4885','부산은행','087120916435','김태균','01045888154','','',''),
    //         array( 'gauriy','싹쓸이','123123qw','123123qw','bbc','abc1 ','농협','17735756083034','이재민','01075368087','','',''),
    //         array( 'dkrak','안봤다올인','1324','1324','dandy ','dandy ','카카오뱅크','3333078164867','김민성','01029290874','','',''),
    //         array( 'a2657716','레드마스터','A1055','A1055','mvp','mvp23','카카오뱅크','3333239191337 ','이귀훈','01083752003','853','1520',''),
    //         array( 'cjs4782','미리미터맨','cjs4782','cjs4782','collabo1','king1234','새마을 금고','2001100037682','천종수 ','01098958254','','',''),
    //         array( 'chang4189','코몽','wkdtmddn123','wkdtmddn123','mvp','psy123','토스뱅크','1000-0299-4542','장승우','01047154198','','10226',''),
    //         array( 'antk3333','무사시시','ejrrn3333','ejrrn3333','mvp','okok100','농협','352  1747  779  423','황현희','01093712000','623','',''),
    //         array( 'Bowon0972','뽀탱이','bowon0972','bowon0972','abc ','8282','카카오','3333239766628','이보원','01098980972','','',''),
    //         array( 'ujhcho1','감성돔','a1234','a1234','mvp','ujhcho ','우리은행','1002431500700','조대섭','01075263074','134','1000',''),
    //         array( 'hwang3218','타짜1','song70000@','song70000@','collabo1 ','song700','기업은행','412 099628 01 013','양인호','01033983112','','4000',''),
    //         array( 'tjstn9329','올인인생','t2123@*3','루루0403','collabo1','tjstn18','케이뱅크','100134592371','정아란','01022745289','9552','',''),
    //         array( 'teddy1','희동이','3949','3949','mvp','mvp42 ','농협','352-1537-5369-53 ','김민성','01027936535','22959','',''),
    //         array( 'sldoqlek2','짜구','whddhks12@','whddhks12@','mvp','Jace777','우리','1002157475233','신종완','01047331268','','4000',''),
    //         array( 'Shin5022','엘에스','Shin9868','Shin9868','mvp','shs4578','농협','0103371986808','신승균','01033719868','22140','11397',''),
    //         array( 'manhoi1','피앤만두','9747','9747','mvp','mvp57','카카오','3333016079404','황만회','01077558222','','',''),
    //         array( 'helloys','씩씩22','c123789','c123789','kyu1','fox ','카카오뱅크','3333124253982','천윤식','01033950381','','',''),
    //         array( 'goranism1','노루','popo1004@','popo1004@','collabo1 ','goranism ','카카오','3333030318136','김수영','01056582266','750','',''),
    //         array( 'dv04','dv04','t2123@*3','1111','collabo1','dv999 ','','','','','','33903',''),
    //         array( 'dv01','dv01','t2123@*3','1111','collabo1','dv999 ','','','','','60000','',''),
    //         array( 'chwhekd','허브킹','rowjddn1','rowjddn1','top ','top ','국민','853802 04 151584','이진영','85510250010','1000','',''),
    //         array( '4599ai','갈지마왕','4599hoja','4599hoja','mvp','psy123','하나은행 ','79391040315507','홍순용','01044717732','8731','3479',''),
    //         array( 'hulk001','헐크헐크','sk7520@','sk7520 ','terry7520','terry7520','기업은행 ','53004447301015','전경진 ','01088524003','','',''),
    //         array( 'slow3423','ㅇㅇㅇ','salsa100','salsa100','ssk','bsk ','카카오뱅크','3333199561632','이용우','01065326351','','7312',''),
    //         array( 'jewelry','쥬얼리','1234','1234','collabo1','acadia','카카오','3333036394048','고보석','01082499565','','4000',''),
    //         array( 'nsciel','동키','adpower12!','adpower12!','bbc','0909op','저축은행','06501132625559','이동율','01055378402','','34000',''),
    //         array( 'leeltj','마카오리','100815','100815','abc','4885','우리은행','1002633419185','이태정','01044445562','','',''),
    //         array( 'kimsg29','리버역전','7415623a','7415623a','mvp ','WOW114','농협','3511033340603','윤민현','01099618222','8200','',''),
    //         array( 'kim5682','호치민사이공포커','qntks5106','qntks5106','mvp','soonyang','카카오뱅크','3333064613198','김상욱','0702117000','','',''),
    //         array( 'kf511400','와이즈런','kdj005342','kdj005342','collabo1 ','cherry1','카카오뱅크','3333053312496','김도준','01023536304','','20000',''),
    //         array( 'kdhjy','콰트로팬더','1577','1577','mvp ','yy777','하나은행','01092583618607','김동환','01092583618','','2000',''),
    //         array( 'jason1216','후루룩','!@abcd1234','!dhjh1216','vip','vipdir','신협','132123990920','이정학','01096526310','','2966',''),
    //         array( 'hid7242','산까치','hid7242','hid7242','mvp','ujhcho','국민은행','99162724260','경규하','01091627242','','8000',''),
    //         array( 'a9919','예림이','t2123@*3','a9919','mvp ','mvp13','기업','47906105101013','양시유','01033259116','802','11534',''),
    //         array( '7rlawlgns7','하다부','qazokm79!','qazokm79!','mvp','wook0021 ','카카오뱅크','3333033385129','김지훈','01067381112','','',''),
    //         array( 'ghkfkd4684','탑홀덜','nbyxcmf168','4684','mvp','yms1027','농협','3521418827753','김화랑','01080794684','2078','70',''),
    //         array( 'kk1047','kk1047','q10474502!','1234','abc','abc','토스뱅크','100033911004','김준일','0105711455','','8534',''),
    //         array( 'wheotjq','다쥐질래','a12345','a12345','mvp','ujhcho','카카오뱅크','3333070627948','조대섭','01058753074','','',''),
    //         array( 'aidrockcrew','나2254','9635gusdn274','9635gusdn274','abc','4885','새마을금고','9003270678911','이현우','01022549635','','',''),
    //         array( 'song49788','당근','884185','884185','collabo1 ','korea4848 ','우리','1002261703718','송병섭','01083848642','','2000',''),
    //         array( 'coco9459','에이스하이','aa1106aa','aa1106aa','dandy','dandy','카카오뱅크','3333017768837','홍기봉','01099749459','','',''),
    //         array( 'Pataya791508','따봉','sjk3864','sjk3864','mvp ','shs4578','농협','3020498786881','심재경','01087909105','8780','47000',''),
    //         array( 'nike9987','니케9987','asas1212!@','asas1212!@','collabo1','saka25','케이뱅크','100154444654','차동현','01020680089','','2000',''),
    //         array( 'gmlgnsdl','후니사마','gnsl00^^','gnsl00^^','mvp','yy777 ','기업','24206901401013','방희훈','01091918528','','2000',''),
    //         array( 'jin700327','조낸꼬라박','0327','0327','mvp','mvp48 ','농협','21105356205490','김용환','01084974200','','2000',''),
    //         array( 'hanbang49','펭귄대왕','1240','1240','mvp ','wegari ','카카오뱅크 ','3333114291504','조성현','01075858041','','',''),
    //         array( 'msse486','후다다닥','aa0709','aa0709','mvp','mvp57','카카오뱅크','3333091614960','김훈','01092230002','','',''),
    //         array( 'dkxmzl','아울링','aa7845120','aa7845120','abc','4885','카카오뱅크','3333043472973','임현택','01039513317','','',''),
    //         array( 'winn333','아드리아티코','park1chul','park1chul','abc','4885','토스뱅크','100032969081','박철우','01080108028','','',''),
    //         array( 'tjrgns1275','디네로타임','1234','1234','abc','4885','농협','3529838127543','고석훈','01023701276','','',''),
    //         array( 'seho7788','강릉독사','3535','3535','mvp','shs4578','하나은행','01098547788007','김세호','01098547878','','',''),
    //         array( 'a3917a','정우아빠','qkqh0514','qkqh0514','mvp ','red01','신한은행','110412072710','배현석','09563362841','','',''),
    //         array( 's012','공일이','zaq12','zaq12','kyu1','aa01','새마을금고','6217100206850','진영일','01092986482','','',''),
    //         array( 'tata9959','후후히히','tata2524','tata2524','mvp ','shs4578','기업은행','37815253701010','최시웅','01093621840','','27122',''),
    //         array( 'huyk11','만복아빠','Qkrdntjr1!','Qkrdntjr1!','abc','4885','기업','51401563602023 ','박우석','01042162555','','2000',''),
    //         array( 'Q2080','빨래는피존','7288qq','7288qq','mvp','WOW114','K뱅크','','','','','',''),
    //         array( 'kk01','kk01','kim5454@','kim5454@','collabo1','kk135790','농협','1500  20  5215  3670','김종섭','01065405454','520','4200',''),
    //         array( 'ghfjdkslap1','나이스핸','jw8188','qwepqwep123','ssk ','ssk ','기업은행','01031049184','김정준','01077886787','','2389',''),
    //         array( 'enjoyroom18','fuusbwjai','miya1773','miya1773','mvp','youngka11','우리','1002847934834','김한석','01073752213','','4000',''),
    //         array( 'dlwognsaa','화려한플레이','gkgk22','gkgk22','abc ','abc ','국민','93770201006684','이재훈','01093475232','','',''),
    //         array( 'gpqls00','신림동여사장','gpqls11','gpqls11','mvp','yy777 ','신한은행','110 460 5327 76','최혜빈','01064725214','','2000',''),
    //         array( 'yjyjjy','응구탱구','tlqkf1sus!','tlqkf1sus!','ssk ','bsk','카카오뱅크','3333010412369','김여진','01023335799','','1892',''),
    //         array( 'sas9999','필리핀초','dong8911','dong8911','mvp','mvp31 ','국민','121210428563','손대목','01029362400','','7904',''),
    //         array( 'com6278','에어라인','*ysj950103','a950103','mvp','mvp23 ','농협','3520990169773','양선준','01093699873','','',''),
    //         array( 'bebebibi','비비','bibi1004','bibi1004','mvp','k520075','국민은행','26310104443704','윤화섭','01050824615','','2000',''),
    //         array( 'aa4884','삼성아파','aa4884','aa4884','mvp','ujhcho','농협','3510375943463','김종화','01020404884','','2000',''),
    //         array( 'soho2636','소유','qjawns01','qjawns01','mvp','mb7000','신한은행','110505149578','김경식','01021014768','271','2000',''),
    //         array( 'city1333','원투','asdf1323','asdf1323','mvp','mvp63','카카오','3333074422229','민영민','01032490503','','32000',''),
    //         array( 'jyb0036','베르나르','ss0908','ss0908','mvp','bm9999 ','카카오뱅크','3333037195984','심형보','01087703843','','',''),
    //         array( 'tjswns0609','가나조팝','7789','7789','kyu1','pood364','카카오뱅크','3333139710606','김선준','01075245156','','',''),
    //         array( 'marshell79','한빵','31663166','31663166','collabo1 ','collabo1 ','농협','3521680652253','안태현','01048316921','','',''),
    //         array( 'qaz5832','백타짜','wsx7113','wsx7113','dandy','5555','신협','132106854565','백진만','01040015832','','','10000'),
    //         array( 'dudcjfek1','플레이','wlaks123','wlaks123','mvp ','WOW114 ','카카오뱅크','3333176580266','최지만','01025554111','','',''),
    //         array( 'ttttt1','차무식8','ttttt1','ttttt1','kyu1','sun33','농협','3560984551783','오충열','01093855710','','',''),
    //         array( 'sanga3986','울산바위','1207@!','1207@!','mvp','mvp58 ','국민은행','55860104053865','이건호','01063528191','','2000',''),
    //         array( 'cash','조커','qwe123123@@','qwe123123@@','ssk','jw8188','하나은행','26891014279407','봉유남','01073108460','','',''),
    //         array( 'dns01','해적왕루피','sd1213','sd1213','mvp','sky00','국민 ','45960101571601','김운','01088826167','358760','',''),
    //         array( 'tlthth','고니고니고니','th667799!!','th667799!!','mvp ','shs4578 ','신한은행','110505506838','권혁이','01092887086','','4600',''),
    //         array( 'softguy00','더더더홀릭','qweqwe1212','qweqwe1212','bbc','kk33 ','카카오뱅크','3333080023113','박원일','01077226633','','',''),
    //         array( 'roseazz','코난','6492278r','6492278r','coco17','coco17','우리은행','1002 534 314006','안길수','01024169944','','',''),
    //         array( 'kgd4178','스텔스','rlarleo','rlarleo','bbc','abc1','카카오뱅크','3333025176939','김기대','01047374178','','',''),
    //         array( 'aowlr58','장군','1234','1234','abc /','4885','국민','70210104227409','양장군','01052533437','','',''),
    //         array( 'sh993','프랭이','sa88ss','sa88ss','mvp','1588','카카오뱅크','3333018421764','송사랑','01049449370','3092','4807',''),
    //         array( 'yassssss','코카콜라','qwe123','qwe123','mvp','WOW114','카카오뱅크','3333058747737','이설송','01051534090','','',''),
    //         array( 'amie2000','알레오','qw980723','qw980723','mvp ','RED123','농협','3561546293993','김상진','01082003932','','',''),
    //         array( 'lg02','구원자2','lg02','lg02','mvp ','in6006','카카오뱅크','333322072187200','최인','01067950090','','',''),
    //         array( 'yong0468','용갈불패','@@1485','@@1485','mvp','okok100','농협','71704252107191','최용석','01035160468','','23999',''),
    //         array( 'a123456','대박','khs1245','khs1245','mvp','cave1123 ','신한','110227143188','권혁선','01085619033','5547','1360',''),
    //         array( 'popo00','악동','qwqw12','qwqw12','dandy','dandy','케이뱅크','100103609282','박창호','01054444455','','667',''),
    //         array( 'reside23','므명이','dkssud1!','dkssud1!','bbc','ngfg40','국민은행','00990204228551','이창호','01050014100','','',''),
    //         array( 'ktk87','거제월클','qwqw1212','qwqw1212','bbc','win7 ','새마을금고 ','9003239318577','김태광 ','01097830109','','',''),
    //         array( 'hd01','먹어보자','t2123@*3','a123a123','collabo1','collabo1','농협','0102712200209','한해동','01027122002','','',''),
    //         array( 'cc0001','가프로','qwqw1212!@','asas1212','mvp','bb0001 ','카카오뱅크','3333 15 6810621','이희용','01013445698','','2234',''),
    //         array( 'llw1004','맹춘이','audgnsdl1!','audgnsdl1!','mvp','k520075 ','카카오뱅크','3333183634682','권명훈','01024878511','','2000',''),
    //         array( 'ikingikingi','돈을불러온다','dudgml12','dudgml12','abc','bjk ','새마을금고','9003273202568','백정기','01026257656','','',''),
    //         array( 'hokul123','띠까보자','rlago137','rlago137','abc','4885','카카오뱅크','3333-12-9867184','홍대왕','01077453551','','',''),
    //         array( 'ti2022','유키','to58042022','to58042022','collabo1','korea4848','신한은행','110507346898','최정훈(이지솔루션)','01056415804','','4000',''),
    //         array( 'huma77','신신신','@a2187','@a2187','abc ','soffice ','기업','539 003233 01 016','신인철','01050817891','','4000',''),
    //         array( 'kim1974','아무거나','1974','1974','mvp','shs4578 ','농협','33305051086551','김진용','01077509340','19500','',''),
    //         array( 'hyuk7755','삼봉','5258','5258','ssk','bsk','경남','698210121735','이민혁','01041531212','','1080',''),
    //         array( 'Chilbbang','칠빵','t2123@*3','Q1w2e3!!','abc','abc','카카오뱅크','3333213695232','김성학','01047948160','3500','50',''),
    //         array( 'rkdfmd135','흐름이야','ahtsksdl991','ahtsksdl991','mvp','sw5500','카카오뱅크','3333113272738','조영일','01096379101','','1088',''),
    //         array( 'yongho8857','용호','plusd7297','plusd7297','mvp ','jsh0206','카카오뱅크','3333227266350','조용호','01055258857','','2000',''),
    //         array( 'mioa12','숫커','1092zx','1092zx','mvp','kcm801586 ','새마을금고','9002177174387','김민찬','01094848550','','',''),
    //         array( 'pk0019','쇼울','3508','3508','dandy','dandy','국민','646210019678','박인철','01025067285','','2000',''),
    //         array( 'king01','신선','qwe123','qwe123','dandy','dandy','토스','100025188480','강현민','01085233872','','2000',''),
    //         array( 'kdhljm0331','지민사랑','0703','0703','mvp ','whrm78 ','카카오뱅크','3333106257236','강동휘','01053880331','9398','',''),
    //         array( 'chosen12345','토파즈','11071109','11071109','mvp ','kdhjy2','케이뱅크','100192670209','조성준','01020685978','','',''),
    //         array( 'zxz06134','밍구','alsry@1475','alsry@1475','mvp ','wook0021 ','카카오뱅크','3333167078275','송민교','01058401501','1370','2000',''),
    //         array( 'tgv456','만식이','dlrlghd1','dlrlghd1','mvp ','yy777 ','신한은행','110433009964','이기홍','01092530034','','',''),
    //         array( 'op1939','우사짝풀','wodnjs1','wodnjs1','mvp ','kkkk4230 ','카카오뱅크','3333-01-5550296','박대훈','01081211939','','2000',''),
    //         array( 'wwew13','호구형1','qwe123','qwe123','mvp','Yjyj7046 ','국민','81850204142389','박철완','01020186835','','17000',''),
    //         array( 'lionzero12','레인','sejong12','sejong12','collabo1','acadia ','카카오','3333030584548','박은수','01087068966','','',''),
    //         array( 'birdie1010','나이쓰버디','bmop@1010','bmop@1010','mvp','zxc0445','우리은행','1002835421170','이상화','01086295282','','2000',''),
    //         array( 'zxcv0202','문경자','zxcv0202!','zxcv0202!','mvp ','zxc0445','기업은행 ','18310096701010','홍석안','01056103536','','2000',''),
    //         array( 'gudgud','랭보','1357','1357','mvp','yy777','농협','3521221136143','송형태','01021137989','','',''),
    //         array( 'Jadk1020','제이디','Ssnake8912!@','Ssnake8912!@','mvp','wook0021','국민','82530204111242','김현준','01030253030','','17000',''),
    //         array( 'lyt860213','썽니','yt102310^^','yt102310^^','mvp ','p222','기업은행','27308339101013','김미자','01083114123','','2000',''),
    //         array( 'bsss1','테트리스001','ty1122','ty1122','kyu1','ssbo1','카카오뱅크','3333234107382','김태연','01029989137','','',''),
    //         array( 'kmar78','짭짤','1710','1710','total','popo6611 ','농협','3021650912 071','박광상','0102210036','','',''),
    //         array( 'Lsj7939','벤또폭탄','7248','7248','mvp','salt ','카카오뱅크','3333097777850','이승준','01038007939','','2000',''),
    //         array( 'apinkluv','탱구와제로','k1k2k3k4','k1k2k3k4','dandy','5555','카카오뱅크','3333199981494','김효식','01033439151','','2000',''),
    //         array( 'kang2844','입맛이없어','987q87','987q87','bbc','0909op','우리은행','1002464060503','강창수','01096962844','','',''),
    //         array( 'ss8440','빵빠레','zz0909','zz0909','total','nba333','농협','3521139513283','김윤하','01088106333','5517','',''),
    //         array( 'tnqls1009','누진수','ajaaja1009','ajaaja1009','kyu1','fnx999 ','카카오뱅크','3333109016424','오수빈','01020133730','','',''),
    //         array( 'lsi3639','까꿍조아','fhEh9981','fhEh9981','abc','4885','제일은행 ','72720279195','이성일','01072992884','','',''),
    //         array( 'cola84ml','까봐까봐사쿠라네','as0218','as0218','mvp','Make992 ','카카오','3333-20-0772024','정재원','01055556974','','',''),
    //         array( 'ans00323','삭잡아','dud00323','dud00323','bbc ','vip333 ','카카오뱅크','3333257729275','김인성','01071648222','','',''),
    //         array( 'rkddlscjf11','인테르','ekgml6527!','ekgml6527!','abc','4885','카카오뱅크','3333189583319','강인철','01057113442','','15000',''),
    //         array( 'Aaron95','오우쒯쒯','a741852','a741852','mvp ','soonyang ','카카오뱅크','3333166157702','이동훈','01048505740','','',''),
    //         array( '202kch','광주송정리','3567kim','3567kim','abc','4885','우리은행','1002661307417','김창희','01022361858','','',''),
    //         array( 'jhho670','홀덤맨','@gm0098021@','@gm0098021@','dandy','dandy','새마을금고','9003258583903','박진호','01046895864','','',''),
    //         array( 'koddam2','코땀이','kws159753','kws159753','top','top2','토스뱅크','100042239141','경우석','01073224034','','1335',''),
    //         array( 'zhwldpdy','수수','5753','5753','mvp ','mvp ','기업은행','23709831001014','이선재','01089698780','','1383',''),
    //         array( 'aqaq111','코스트','1234@','aqaq1234','collabo1 ','acadia','농협','3520824681243','김정선','01041141131','','',''),
    //         array( 'gkdldtka','자쓰민','tmddns99','tmddns99','dandy','dandy','토스뱅크','100023425827','장성민','01086517872','','',''),
    //         array( 'moomooya','무무','moomoo!2','moomoo!2','mvp','mvp01 ','신한은행','110371409966','이재건','01036926686','3980','',''),
    //         array( 'o0o0o','동그라미','letsgo0022','letsgo0022','mvp','sbs77','우리은행','1002300368542','경춘자','01080111822','','8555',''),
    //         array( 'bnm2811','250가자','Qw1234!@','Qw1234!@','kyu1','sun33','카카오뱅크','3333038080557','김정원','01062152394','','15000',''),
    //         array( 'lovelove0479','강릉아귀','27ek2207','27ek2207','mvp ','shs4578 ','신한','110215817805','조승희','01094250479','','3812',''),
    //         array( 'out4569','핸스','out123789!','out123789!','abc','4885','카카오뱅크','3333098288557','장민철','01096048026','','',''),
    //         array( 'kjl3348','이것도받아봐라','!kjl1816611','!kjl1816611','mvp','shs4578','농협','3521026622663','김정래','01074403348','1278','4090',''),
    //         array( 'db1878','올인가자','abc123','abc123','abc','4885','케이뱅크','100158904520','유길수','01058852081','','',''),
    //         array( 'jmc1111','오리지날','a1234','a1234','bbc','jmc3585','농협','71707551023707','김순화','01077696611','','',''),
    //         array( 'QQ2080','빨래는피존1','7288qq','7288qq','mvp','WOW114 ','케이뱅크','100156176976','정주몽','01085604442','','',''),
    //         array( '1356','아방이','rhkdwn12','1356','abc','royal','카카오뱅크','3333104445299','임채인','01048255774','','',''),
    //         array( 'yun3303','싸와디캅','3303','3303','mvp ','KWAN1129 ','광주은행','178121098095','윤상채','01041713576','','3131',''),
    //         array( 'dhtwha122','태기태래택잉','10041004','10041004','mvp','psy123','신협','132 105 518795','이성택','01083855928','','3789',''),
    //         array( 'bpkbc2','김려리','khr5063011!','khr5063011!','dandy','dandy','카카오뱅크','3333099392773','김형렬','01055503011','','',''),
    //         array( 'tkawhrdh9076','짱영호랑이','q6439077@','tkawhrdh9077','mvp ','sw5500','국민','94320200059383','장영호','01067897995','','3447',''),
    //         array( 'psy1234','태풍','psy1234','psy1234','mvp','psy123','농협','3511031425293','박상용','01024401811','177386','4469',''),
    //         array( 'dbqls980','로야리','aosqkf13@','aosqkf13@','abc ','royal ','카카오뱅크','3333044616224','오유빈','01083395652','','',''),
    //         array( 'ldc8956','동찌','4678','4678','dandy','dandy','카카오뱅크','3333079952386','이동철','01056519469','','',''),
    //         array( 'jtoh2','려월','wldnro09@','wldnro09@','dandy','dandy','카카오뱅크','3333045224385','이재혁','01086462972','','',''),
    //         array( 'gg1212','전라도주재기','zz1212','zz1212','mvp ','beastkal77','국민은행','94450200863826','고동현','01055427402','','',''),
    //         array( 'yoosoin','yoosoin','thdls@100','thdls@100','terry7520 ','Gomusin00','케이뱅크','100152307579','유소인','01087568010','','',''),
    //         array( 'Jn001','온천장','asas7788','asas7788','collabo1','collabo1','sc제일은행','50820375769','정구영','01090664318','95500','',''),
    //         array( 'qwer1','바카라','qwer1','qwer1','mvp ','mvp01','신한은행','110203167678','이한영','0101111111','','10000',''),
    //         array( 'adventurer7','위니아','sk6006','sk6006','bbc','win7','카카오뱅크','3333068639158','이형민','01048786006','','',''),
    //         array( 'chgml9391','홀덤1','aa9391@@','Aa9391@@','mvp','mvp13','신협 ','132089635399','김봉석 ','01035339390','','',''),
    //         array( 'Chupas','츄파스','t2123@*3','Bong1737','mvp','Setlife','국민은행','09470102382102','봉준구','01090773913','','',''),
    //         array( 'qkqh1000','피쉬','t2123@*3','16342512','mvp','7777','케이뱅크','1001  5027  0201','현정우','01031099001','8224','',''),
    //         array( 'qpqp1010','방수보리','t2123@*3','qpqp1010','mvp','7777','우리은행','1002  332  78  9366','장현우','01040889283','6640','3980',''),
    //         array( 'knighthyu72','대구호구','t2123@*3','moda0001!','collabo1','saka25','국민은행','63700104052752','하영욱','01095110001','','12000',''),
    //         array( 'jj0001','너트','1234aa','365pc365','mvp ','jj7922','카카오뱅크','3333-04-7431049','허세환','01012345678','50000','',''),
    //         array( 'aaa8883','대포붕어','1234','aaa0337','terry7520','kkk8883','새마을금고','9003215544572','노도철','01035192406','','2150',''),
    //         array( 'mixed6','기퍼','zxasqw1','zxasqw1','mvp','bmw01 ','케이뱅크','100105797760','김창진','01082743873','5770','',''),
    //         array( 'han100','한백','han100!!','han100!!','collabo1 ','acadia ','하나은행','50191052708307','조한백','01066362422','','2000',''),
    //         array( 'heuj','최강태풍','4447','4447','mvp ','kdhjy2','새마을금고','9003258374359','허중','01062866281','','2000',''),
    //         array( 'tlvktms','묵주','tltltldls1','tltltldls1','mvp','shs4578','토스뱅크','100061529307','김범수','01051448616','','2000',''),
    //         array( 'zzz1','세이노','baru22','baru22','mvp','km8000','케이뱅크','100107450283','정원영','01056489201','','2000',''),
    //         array( 'newwork26','사랑한당','rudtlr12','rudtlr12','abc','abc','카카오뱅크','3333095835942','황경식','01067967185','1138','20000',''),
    //         array( 'jjtjddls','초코라이','0075','0075','abc ','4885','카카오뱅크','3333091410522','조성인','01093264696','','',''),
    //         array( 'kyn6453','멍구구','zhtkr1532!','zhtkr1532!','abc ','4885','국민은행','64930204016202','경규명','01040216453','','',''),
    //         array( 'Wanda2023','완다','@wanda@','@wanda@','collabo1','kj01 ','케이뱅크','100134420288','김욱휘','01040232709','7500','',''),
    //         array( 'dkfk18tp','아풀하우스','asdf2023!','asdf2023!','mvp','soonyang','카카오뱅크','3333116842226','권원아','01075113511','','1000',''),
    //         array( 'anyang1017','용가리1','ssk1017','ssk1017','mvp','bmw01','카카오뱅크','3333040637044','김용균','01032831523','','',''),
    //         array( 'yt7911','사마','as1234','as1234','soso113 ','max1000','농협','3568962226123','임용택','01035468498','7500','',''),
    //         array( 'ghgh7896','돼지아빠','ghgh4433!','ghgh4433!','dandy ','dandy ','국민은행','23140204165969','정광희','01094164796','','',''),
    //         array( 'qjagkr123','범학입니다','qjagkr123','qjagkr123','nomaluser2','nomaluser2','농협','3021393571591','박근수','01064778045','','',''),
    //         array( 'pood365','제리아빠','rudwls12!!','rudwls12!!','kyu1 ','kyu1 ','국민은행','21070204409217','조경진','01048924255','2296','',''),
    //         array( 'shalstjd98','갈갈이','minsung4113','minsung4113','abc','4885','신한은행','110432189477','노민성','01075214113','','',''),
    //         array( 'pq1018','차무식이다','13184851','13184851','abc ','4885','카카오뱅크','3333119374736 ','이민완','01092826969','','',''),
    //         array( 'dooas2','클라우드','dooas2','dooas2','mvp','shs4578','신한은행','110323285130','오혜민','01077959964','8880','842',''),
    //         array( 'kaka8308','카카','147545','147545','mvp','mbc77','카카오뱅크','3333048711211','고재표','01057468308','','',''),
    //         array( 'as1212','흑마','as1212@','as1212@','abc','4885','카카오','3333059351768','이용빈','01099413734','','',''),
    //         array( 'boss2323','속초얼짱','boss3232','boss3232','mvp ','AK88 ','카카오','3333071950205','김남일01077942376','','','4521',''),
    //         array( '0519','리기','dlrl0113','1234','dandy ','dandy ','토스뱅크','100003620968','이기준','01053190519','','1760',''),
    //         array( 'gummookwan','마카오권','6194oioi!!','6194oioi!!','mvp','sw5500 ','카카오뱅크','3333138863411','권대겸','01033558593','','8713',''),
    //         array( 'edp96317','라우렌시오','3651','3651','ssk ','jw8188','새마을금고','9002174527309','박광수','01036752604','','',''),
    //         array( 'ab1234','엑시언트','a7894','a7894','mvp','as9724','농협','010 5477 9737 09','염윤교','01054779737','','6000',''),
    //         array( 'hyobin7360','효비니야','020328gg','020328gg','ssk','jw8188','카카오뱅크','3333162215320','김효빈','01062489288','','',''),
    //         array( 'hzoom1','아카올인','qwas1743','qwas1743','bbc','gold ','카카오뱅크','3333107343607 ','이주헌','01052379227','','',''),
    //         array( 'qkek018','블루베리','wnsgml336','wnsgml336','dandy','dandy','카카오뱅크','3333112449217','박준희','01041581197','','3000',''),
    //         array( 'jihae0038','궁금해서해본다','hjh0038@','hjh0038@','bbc ','abc1','카카오뱅크','3.33304E+12','황지혜','01075913545','','',''),
    //         array( 'kdh2082','274848','qwqw1212!!','121212','bbc','abc1 ','카카오뱅크','3333258072088 ','김동혁','01020845412','','','')
    //         );
            
    //         writeLog("==============================================");


    //         $error = '';
    //         foreach($infos as $info){

    //             $result = $this->modelMember->registerInfo($info, $error);
    //             if($result == 1){
    //                 writeLog($info[0]."-success");
    //             } else {
    //                 writeLog($info[0]."-fail");
    //                 var_dump($error);
    //                 break;
    //             }

    //             usleep(100000);
    //             // break;
    //         }

    //         echo "End";
    // }


}
