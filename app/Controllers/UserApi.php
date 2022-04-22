<?php

namespace App\Controllers;

use App\Models\BbBet_Model;
use App\Models\BsBet_model;
use App\Models\Charge_Model;
use App\Models\ConfGame_model;
use App\Models\ConfSite_Model;
use App\Models\Exchange_Model;
use App\Models\Member_Model;
use App\Models\Notice_Model;
use App\Models\PbBet_model;
use App\Models\PsBet_model;
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
            $this->response->redirect(base_url());
        } else {
            $this->response->redirect(base_url().'pages/login');
        }
    }

    // 사용자 정보 변경
    public function addmember()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);
        if (is_login()) {
            $bPermit = false;
            $memberModel = new Member_Model();
            $strUid = $this->session->user_id;
            $objUser = $memberModel->getInfo($strUid);
            $arrData['mb_level'] = LEVEL_COMPANY;
            $arrData['mb_emp_fid'] = 0;
            if (strlen($arrData['mb_emp_uid']) > 0){
                $objEmp = $memberModel->getInfo($arrData['mb_emp_uid']);
                if ($objEmp == null || $objEmp->mb_level <= LEVEL_MIN){
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

                $iResult = $memberModel->register($arrData, $strError);
                if (1 == $iResult) {
                    $arrResult['status'] = 'success';
                } elseif (4 == $iResult) {
                    $arrResult['status'] = 'pb_ratio_error';
                    $arrResult['error'] = $strError;
                } elseif (5 == $iResult) {
                    $arrResult['status'] = 'ps_ratio_error';
                    $arrResult['error'] = $strError;
                } elseif (6 == $iResult) {
                    $arrResult['status'] = 'ks_ratio_error';
                    $arrResult['error'] = $strError;
                } elseif (7 == $iResult) {
                    $arrResult['status'] = 'ev_ratio_error';
                    $arrResult['error'] = $strError;
                } elseif (8 == $iResult) {
                    $arrResult['status'] = 'sl_ratio_error';
                    $arrResult['error'] = $strError;
                } elseif (9 == $iResult) {
                    $arrResult['status'] = 'bb_ratio_error';
                    $arrResult['error'] = $strError;
                } elseif (10 == $iResult) {
                    $arrResult['status'] = 'bs_ratio_error';
                    $arrResult['error'] = $strError;
                } elseif (-1 == $iResult) {
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
            $memberModel = new Member_Model();
            $strUid = $this->session->user_id;
            $objAdmin = $memberModel->getInfo($strUid);
            $objReqUser = $memberModel->getInfoByFid($arrData['mb_fid']);
            $arrData['mb_emp_fid'] = 0;
            if ($objAdmin->mb_level == LEVEL_ADMIN && strlen($arrData['mb_emp_uid']) > 0){
                $objEmp = $memberModel->getInfo($arrData['mb_emp_uid']);
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
                $iResult = 0;
                if ($objAdmin->mb_level == LEVEL_ADMIN) {
                    $iResult = $memberModel->modifyMember($arrData, $strError);
                } else {
                    $iResult = $memberModel->modifyMemberRatio($arrData, $strError);
                }

                if (1 == $iResult) {
                    $arrResult['status'] = 'success';
                } elseif (4 == $iResult) {
                    $arrResult['status'] = 'pb_ratio_error';
                    $arrResult['error'] = $strError;
                } elseif (5 == $iResult) {
                    $arrResult['status'] = 'ps_ratio_error';
                    $arrResult['error'] = $strError;
                } elseif (6 == $iResult) {
                    $arrResult['status'] = 'ks_ratio_error';
                    $arrResult['error'] = $strError;
                } elseif (7 == $iResult) {
                    $arrResult['status'] = 'ev_ratio_error';
                    $arrResult['error'] = $strError;
                } elseif (8 == $iResult) {
                    $arrResult['status'] = 'sl_ratio_error';
                    $arrResult['error'] = $strError;
                } elseif (9 == $iResult) {
                    $arrResult['status'] = 'bb_ratio_error';
                    $arrResult['error'] = $strError;
                } elseif (10 == $iResult) {
                    $arrResult['status'] = 'bs_ratio_error';
                    $arrResult['error'] = $strError;
                } elseif (-1 == $iResult) {
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
            $memberModel = new Member_Model();
            $strUid = $this->session->user_id;
            $objUser = $memberModel->getInfo($strUid);
            $objReqUser = $memberModel->getInfoByFid($arrData['mb_fid']);

            // 현재 가입한 유저가 요청한 유저보다 레벨이 높은 경우에 변경이 가능하다.
            if (!is_null($objUser) && !is_null($objReqUser)) {
                if ($objUser->mb_level > $objReqUser->mb_level) {
                    $bPermit = true;
                }
            }
            if ($bPermit) {
                $bResult = $memberModel->updateMemberByFid($arrData);

                if ($bResult) {
                    /*
                    $arrUserData = $memberModel->find($arrData['mb_fid']);
                    $arrUserData['mb_empname'] = '';
                    if ($arrUserData['mb_emp_fid'] != 0){
                        $arrEmpInfo = $memberModel->find($arrUserData['mb_emp_fid']);
                        if ($arrEmpInfo != null)
                        {
                            $arrUserData['mb_empname'] = $arrEmpInfo['mb_uid'];
                        }
                    }
                    */
                    $arrResult['status'] = 'success';
                    // $arrResult['level'] = $objUser->mb_level;
                    // $arrResult['data'] = $arrUserData;                    
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
            $memberModel = new Member_Model();
            $strUid = $this->session->user_id;
            $objUser = $memberModel->getInfo($strUid);
            $objReqUser = $memberModel->getInfoByFid($arrData['mb_fid']);

            // 현재 가입한 유저가 요청한 유저보다 레벨이 높은 경우에 삭제가 가능하다.
            if (!is_null($objUser) && !is_null($objReqUser)) {
                if ($objUser->mb_level >= LEVEL_ADMIN) {
                    $bPermit = true;
                }
            }
            if ($bPermit) {
                $bResult = $memberModel->deleteMemberByFid($arrData);

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
            $memberModel = new Member_Model();

            $strUid = $this->session->user_id;
            $objUser = $memberModel->getInfo($strUid);
            $objReqUser = $memberModel->getMemberByFid($arrData['mb_fid']);

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
                $bResult = $memberModel->updateMemberByFid($arrData);

                if ($bResult) {
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
            $memberModel = new Member_Model();

            $objUser = $memberModel->getInfo($strUid);

            $objResult = new \stdClass();
            if (null != $objUser) {
                $objResult->data = $objUser;
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

    // 가입한 직원의 정보: 머니, 배당율
    public function empinfo()
    {
        if (is_login()) {
            $strUid = $this->session->user_id;
            // model
            $memberModel = new Member_Model();
            $chargeModel = new Charge_Model();
            $exchangeModel = new Exchange_Model();
            $noticeModel = new Notice_Model();
            $confsiteModel = new ConfSite_Model();

            $objUser = $memberModel->getInfo($strUid);

            $objResult = new \stdClass();
            if ($objUser->mb_level >= LEVEL_ADMIN) {
                $arrEmpInfo = $memberModel->getEmpUserCnt($objUser);
                $arrEmpInfo['waitcharge'] = $chargeModel->getWaitCnt();
                $arrEmpInfo['waitexchange'] = $exchangeModel->getWaitCnt();
                $arrEmpInfo['newmessage'] = $noticeModel->getNewMessageCnt();
                $objAdminMoney = $memberModel->calcAdminMoney();
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
            $memberModel = new Member_Model();
            $confgameModel = new ConfGame_model();
            $pbbetModel = new PbBet_model();
            $psbetModel = new PsBet_model();
            $bbbetModel = new BbBet_Model();
            $bsbetModel = new BsBet_model();

            $objUser = $memberModel->getInfo($strUid);

            $objResult = new \stdClass();
            if ($objUser->mb_level >= LEVEL_ADMIN) {
                $strDate = date('Y-m-d');
                $arrReqData['start'] = $strDate.' 00:00:00';
                $arrReqData['end'] = $strDate.' 23:59:59';

                $objConfPb = $confgameModel->getByIndex(GAME_POWER_BALL);
                $arrSumData = $pbbetModel->getBetSumByDay($arrReqData, $objConfPb);

                $objConfPs = $confgameModel->getByIndex(GAME_POWER_LADDER);
                $arrSumData[2] = $psbetModel->getBetSumByDay($arrReqData, $objConfPs);

                $objConfBb = $confgameModel->getByIndex(GAME_BOGLE_BALL);
                $arrSum = $bbbetModel->getBetSumByDay($arrReqData, $objConfBb);
                $arrSumData[3] = $arrSum[0];
                $arrSumData[4] = $arrSum[1];

                $objConfBs = $confgameModel->getByIndex(GAME_BOGLE_LADDER);
                $arrSumData[5] = $bsbetModel->getBetSumByDay($arrReqData, $objConfBs);

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

    public function getmembers()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            // model
            $memberModel = new Member_Model();
			$objResult = new \stdClass();
            $strUid = $this->session->user_id;
            $objUser = $memberModel->getInfo($strUid);
			$empFid = 0;
			if ($objUser->mb_level >= LEVEL_ADMIN){
				if (strlen($arrData['mb_emp_uid']) > 0){
					$objEmp = $memberModel->getInfo($arrData['mb_emp_uid']);
					if ($objEmp == null){
						$objResult->status = 'fail';
						echo json_encode($objResult);
						return;
					}
					$empFid = $objEmp->mb_fid;
				}
			}
			else {
				$empFid = $objUser->mb_fid;
			}
            
            $arrMember = $memberModel->searchMemberByEmpFid($empFid, $objUser->mb_level, $arrData);
            if (is_null($arrMember)) {
                $arrMember = [];
            }
            foreach ($arrMember as $objMember) {
                $arrEmpInfo = $memberModel->find($objMember->mb_emp_fid);
                if ($arrEmpInfo != null){
                    $objMember->mb_empname = $arrEmpInfo['mb_uid'];
                }
                else {
                    $objMember->mb_empname = '';
                }
                
            }

            $objResult->status = 'success';
            $objResult->level = $objUser->mb_level;
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
            $memberModel = new Member_Model();
			$objResult = new \stdClass();
            $strUid = $this->session->user_id;
            $objUser = $memberModel->getInfo($strUid);
			$empFid = 0;
			if ($objUser->mb_level == LEVEL_ADMIN){
				if (strlen($arrData['mb_emp_uid']) > 0){
					$objEmp = $memberModel->getInfo($arrData['mb_emp_uid']);
					if ($objEmp == null){
						$objResult->status = 'fail';
						echo json_encode($objResult);
						return;
					}
					$empFid = $objEmp->mb_fid;
				}
			}
			else {
				$empFid = $objUser->mb_fid;
			}
			$objCount = $memberModel->searchCountByEmpFid($empFid, $objUser->mb_level, $arrData);
			$objResult->status = 'success';
			$objResult->data = $objCount;

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
            $memberModel = new Member_Model();

            $strUid = $this->session->user_id;
            $objUser = $memberModel->getInfo($strUid);

            $objResult = new \stdClass();

            if ($objUser->mb_level >= LEVEL_ADMIN) {
                $arrMember = $memberModel->getMemberByLevel(LEVEL_ADMIN, true);

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
            $memberModel = new Member_Model();

            $strUid = $this->session->user_id;
            $objUser = $memberModel->getInfo($strUid);

            if($objUser->mb_level  >= LEVEL_ADMIN) {
                $modelSesslog = new SessLog_Model();

                $arrData = $modelSesslog->search($arrReqData);
                    
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
            $memberModel = new Member_Model();
         
            $strUid = $this->session->user_id;
            $objUser = $memberModel->getInfo($strUid);

            if($objUser->mb_level  >= LEVEL_ADMIN) {
                $modelSesslog = new SessLog_Model();
                $objCount = $modelSesslog->searchCount($arrReqData);

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
            $memberModel = new Member_Model();
            
            $strUid = $this->session->user_id;
            $objUser = $memberModel->getInfo($strUid);

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
            $memberModel = new Member_Model();

            $strUid = $this->session->user_id;
            $objUser = $memberModel->getInfo($strUid);

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
            $memberModel = new Member_Model();
            $strUid = $this->session->user_id;
            $objUser = $memberModel->getInfo($strUid);

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
            $memberModel = new Member_Model();
            $strUid = $this->session->user_id;
            $objUser = $memberModel->getInfo($strUid);

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
            $memberModel = new Member_Model();
            $strUid = $this->session->user_id;
            $objUser = $memberModel->getInfo($strUid);

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
            $memberModel = new Member_Model();
            $moneyhistoryModel = new MoneyHistory_Model();

            $strUid = $this->session->user_id;
            $objEmp = $memberModel->getInfo($strUid);
            $objMember = $memberModel->getInfoByFid($arrData['mb_fid']);

            $objResult = new \stdClass();

            if(is_null($objMember)){
                $objResult->status = 'fail';
            } else if($objMember->mb_emp_fid !== $objEmp->mb_fid){
                $objResult->status = 'fail';
            } else if($arrData['amount'] > $objEmp->mb_money){
                $objResult->status = 'fail';
                $objResult->msg = '이송금액이 보유금액을 초화하셧습니다.';
            }
            else if($memberModel->trasferMoney($objEmp, $objMember, $arrData['amount'])){

                $moneyhistoryModel->registerTransfer($objEmp, $objMember->mb_uid, 0-$arrData['amount'], MONEYCHANGE_TRANS_S);
                $moneyhistoryModel->registerTransfer($objMember, $objEmp->mb_uid, $arrData['amount'], MONEYCHANGE_TRANS_R);
                $objResult->status = 'success';
            } else{
                $objResult->status = 'fail';

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
            $memberModel = new Member_Model();
            $strUid = $this->session->user_id;
            $objEmp = $memberModel->getInfo($strUid);

            if(is_null($objEmp) || $objEmp->mb_level < LEVEL_ADMIN){
                $arrResult['status'] = 'fail';
            } else{
                $arrInfo = [
                    'ip_addr'  => $objEmp->mb_ip_join,
                    'ip_check' => $objEmp->mb_state_view > 0 ? 1:0
                ];
                $arrResult['data'] = $arrInfo;
                $arrResult['status'] = 'success';
            }

        } else {
            $arrResult['status'] = 'logout';
            
        }
        echo json_encode($arrResult);
    }

    public function egg_ev(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

        $result = new \StdClass;
        
        if(!is_login())
		{
			$result->msg = "세션이 만료되었습니다. 다시 로그인하세요.";
            $result->status = STATUS_LOGOUT;		

        } else {
            $libApicas = new ApiCas_Lib();
			$modelMember  = new Member_Model();

			$objMember = $modelMember->getInfoByFid($arrReqData['mb_fid']);

            $iCreated = 0;
            if(is_null($objMember))
				$iCreated = 0;
			else if($objMember->mb_live_id == 0) {
                $iCreated = 2;								//회원창조실패
            } else {
                $iCreated = 1;
            }
			if($iCreated == 0){
                $result->msg = '준비중입니다.';
                $result->status = "fail";
            } else if($iCreated == 2){
				$result->msg = '계정이 존재하지 않습니다.';
                $result->status = "fail";
			} else if($iCreated == 5){
				$result->msg = '중복된 사용자입니다. 관리자에게 문의해주세요.';
                $result->status = "fail";
			} else if($iCreated == 1){
				$arrResult = $libApicas->getUserInfo($objMember->mb_live_uid);
				writeLog("<Casino>".$objMember->mb_uid."-UserInfo Status=".$arrResult['status']);
                if($arrResult['status'] == 1)
                {
                    writeLog("<Casino>".$objMember->mb_uid."-UserInfo Balance=".$arrResult['balance']);

                    $objMember->mb_live_money = $arrResult['balance'];
                    $modelMember->updateLiveMoney($objMember);   
                    
                    $result->live_money = $objMember->mb_live_money;
                    $result->money = $objMember->mb_money;
                    $result->point = $objMember->mb_point;

                    $result->status = "success";
                }else {
                    if(array_key_exists('error', $arrResult) && $arrResult['error'] == INVALID_USER){
                        $result->msg = '존재하지 않는 사용자입니다. 관리자에게 문의해주세요.';
                        $result->status = "fail";
                    }
                    else {
                        $result->msg = '오류가 발생하였습니다. 관리자에게 문의해주세요.';
                        $result->status = "fail";
                    } 
                }
			}

        }
        echo json_encode($result);
    }
	
    public function egg_sl(){
        $jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

        $result = new \StdClass;
        
        if(!is_login())
		{
			$result->msg = "세션이 만료되었습니다. 다시 로그인하세요.";
            $result->status = STATUS_LOGOUT;		

        } else {
            $libApislot = new ApiSlot_Lib();
            $modelMember  = new Member_Model();

			$objMember = $modelMember->getInfoByFid($arrReqData['mb_fid']);

            $iCreated = 0;
			if(is_null($objMember))
				$iCreated = 0;
			else if($objMember->mb_slot_uid == ""){
                //플레이어 창조
                $iCreated = 2;								//회원창조실패
            } else {
                $iCreated = 1;
            }

			if($iCreated == 0){
				$result->msg = '준비중입니다.';
                $result->status = "fail";
			} else if($iCreated == 2){
				$result->msg = '계정이 존재하지 않습니다.';
                $result->status = "fail";
			} else if($iCreated == 5){
				$result->msg = '중복된 사용자입니다. 관리자에게 문의해주세요.';
                $result->status = "fail";
			} else if($iCreated == 1){
				$arrResult =  $libApislot->getUserInfo($objMember->mb_slot_uid);
				writeLog("<XSLOT>".$objMember->mb_uid."-UserInfo resultCode=".$arrResult['resultCode']);
                if($arrResult['status'] == 1)
                {
                    writeLog("<XSLOT>".$objMember->mb_uid."-UserInfo Balance=".$arrResult['balance']);

                    $objMember->mb_slot_money = $arrResult['balance'];
                    $modelMember->updateSlotMoney($objMember);   
                    
                    $result->slot_money = $objMember->mb_slot_money;
                    $result->fslot_money = $objMember->mb_fslot_money;
                    $result->status = "success";
                }else {
                    if(array_key_exists('resultCode', $arrResult) ){
                        $result->msg = '요청실패. 코드='.$arrResult['resultCode'];
                        $result->status = "fail";
                    }
                    else {
                        $result->msg = '오류가 발생하였습니다. 관리자에게 문의해주세요.';
                        $result->status = "fail";
                    } 
                }
			}

        }
        echo json_encode($result);
    }

    public function egg_fsl(){
        $jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

        $result = new \StdClass;
        
        if(!is_login())
		{
			$result->msg = "세션이 만료되었습니다. 다시 로그인하세요.";
            $result->status = STATUS_LOGOUT;		

        } else {
			$libApifslot = new ApiFslot_Lib();
            $modelMember  = new Member_Model();

            $objMember = $modelMember->getInfoByFid($arrReqData['mb_fid']);

            $iCreated = 0;
			if(is_null($objMember))
				$iCreated = 0;
			else if($objMember->mb_fslot_id == 0){
                $iCreated = 2;								//회원창조실패
            } else {
                $iCreated = 1;
            }

			if($iCreated == 0){
				$result->msg = '준비중입니다.';
                $result->status = "fail";
			} else if($iCreated == 0 || $iCreated == 2){
				$result->msg = '계정이 존재하지 않습니다.';
                $result->status = "fail";
			} else if($iCreated == 5){
				$result->msg = '중복된 사용자입니다. 관리자에게 문의해주세요.';
                $result->status = "fail";
			} else if($iCreated == 1){
				$arrResult = $libApifslot->getUserInfo($objMember->mb_fslot_uid);
				writeLog("<FSlot>".$objMember->mb_uid."-UserInfo Status=".$arrResult['status']);
                if($arrResult['status'] == 1)
                {
                    writeLog("<FSlot>".$objMember->mb_uid."-UserInfo Balance=".$arrResult['balance']);

                    $objMember->mb_fslot_money = $arrResult['balance'];
                    $modelMember->updateFslotMoney($objMember);   
                    
					$result->slot_money = $objMember->mb_slot_money;
					$result->fslot_money = $objMember->mb_fslot_money;
                    $result->status = "success";
                } else {
                    if(array_key_exists('error', $arrResult) && $arrResult['error'] == INVALID_USER){
                        $result->msg = '존재하지 않는 사용자입니다. 관리자에게 문의해주세요.';
                        $result->status = "fail";
                    }
                    else {
                        $result->msg = '오류가 발생하였습니다. 관리자에게 문의해주세요.';
                        $result->status = "fail";
                    } 
                }
			}

        }
        echo json_encode($result);
    }

}
