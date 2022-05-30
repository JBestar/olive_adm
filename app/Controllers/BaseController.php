<?php
namespace App\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use CodeIgniter\Controller;
use App\Models\Sess_Model;
use App\Models\Member_Model;
use App\Models\ConfSite_Model;
use App\Models\Modify_Model;

use App\Libraries\ApiCas_Lib;
use App\Libraries\ApiKgon_Lib;
use App\Libraries\ApiSlot_Lib;
use App\Libraries\ApiFslot_Lib;

class BaseController extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = ['url', 'session', 'common_helper', 'curl_helper'];
	protected $session ;
	protected $modelMember;
	protected $modelModify;

	protected $libApicas;
	protected $libApikgon;
	protected $libApislot;
	protected $libApifslot;
	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		
		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		// $this->session = \Config\Services::session();
		
		$this->session = session();
		$this->modelSess = new Sess_Model();
		$this->modelMember = new Member_Model();
		$this->modelModify = new Modify_Model();

		$this->libApicas = new ApiCas_Lib();
		$this->libApikgon = new ApiKgon_Lib();
		$this->libApislot = new ApiSlot_Lib();
        $this->libApifslot = new ApiFslot_Lib();

	}

	protected function getSiteConf($confsiteModel){
		
		$confs = ['site_name'=>"", "gameper_full"=>false, "npg_deny"=>false, "bpg_deny"=>false, "cas_deny"=>false, 
			"slot_deny"=>false, "kgon_enable"=>false, "eos5_enable"=>false, "eos3_enable"=>false];
		$arrConf = $confsiteModel->getSiteConf();  
		
		foreach($arrConf as $objConf){
			switch($objConf->conf_id){
				case CONF_SITENAME:	$confs['site_name'] = $objConf->conf_content;
					break;
				case CONF_NPG_DENY:	$confs['npg_deny'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
				case CONF_BPG_DENY:	$confs['bpg_deny'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
				case CONF_CAS_DENY:	$confs['cas_deny'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
				case CONF_SLOT_DENY: $confs['slot_deny'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
				case CONF_GAMEPER_FULL:	$confs['gameper_full'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
				case CONF_KGON_ENABLE:	$confs['kgon_enable'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
				case CONF_EOS5_ENABLE:	$confs['eos5_enable'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
				case CONF_EOS3_ENABLE:	$confs['eos3_enable'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
				default:break;
			}
		}
		
		return $confs;
	}

	protected function sess_destroy(){
		$sess_id = $this->session->session_id;
		$this->modelSess->deleteBySess($sess_id);
		$this->session->destroy();
	}

	protected function allEgg(&$objMember){
		$confsiteModel = new ConfSite_Model();
		$confs = $this->getSiteConf($confsiteModel);
		if(!$confs["cas_deny"]){
			$this->evEgg($objMember);
			usleep(100000);
		}
		if($confs["kgon_enable"]){
			$this->kgonEgg($objMember);
			usleep(100000);
		}
		$this->slEgg($objMember);
		usleep(100000);
		$this->fslEgg($objMember);
	}
	
	protected function evEgg(&$objMember){
		$iResult = 0;

		$logHead = "<EvEgg>";
		//슬롯 머니조회
		if($objMember->mb_live_id > 0){
			//슬롯머니 요청
			$arrResult = $this->libApicas->getUserInfo($objMember->mb_live_uid);
			writeLog($logHead." ".$objMember->mb_uid."-UserInfo Status=".$arrResult['status']);
			if($arrResult['status'] == 1)
			{
				writeLog($logHead." ".$objMember->mb_uid."-UserInfo Balance=".$arrResult['balance']." Money=".$objMember->mb_money);
				$objMember->mb_live_money = $arrResult['balance'];
				$this->modelMember->updateLiveMoney($objMember);   
				$iResult = 1;
			}
		} else {
            $iResult = 1;
        }
		return $iResult;
	}

	protected function kgonEgg(&$objMember){
		$iResult = 0;

		$logHead = "<KgonEgg>";
		//슬롯 머니조회
		if($objMember->mb_kgon_id > 0){
			//슬롯머니 요청
			$arrResult = $this->libApikgon->getUserInfo($objMember->mb_kgon_uid);
			writeLog($logHead." ".$objMember->mb_uid."-UserInfo Status=".$arrResult['status']);
			if($arrResult['status'] == 1)
			{
				writeLog($logHead." ".$objMember->mb_uid."-UserInfo Balance=".$arrResult['balance']." Money=".$objMember->mb_money);
				$objMember->mb_kgon_money = floor($arrResult['balance']);
				$this->modelMember->updateKgonMoney($objMember);   
				$iResult = 1;
			}
		} else {
            $iResult = 1;
        }
		return $iResult;
	}

	protected function slEgg(&$objMember){
		$iResult = 0;

		$logHead = "<SlEgg> ";
		//슬롯 머니조회
		if($objMember->mb_slot_uid !== ""){
			//슬롯머니 요청
			$arrResult = $this->libApislot->getUserInfo($objMember->mb_slot_uid);
			writeLog($logHead.$objMember->mb_uid."-UserInfo resultCode=".$arrResult['resultCode']);
			if($arrResult['status'] == 1)
			{
				writeLog($logHead.$objMember->mb_uid."-UserInfo Balance=".$arrResult['balance']." Money=".$objMember->mb_money);
				$objMember->mb_slot_money = $arrResult['balance'];
				$this->modelMember->updateSlotMoney($objMember);   
				$iResult = 1;
			}
		} else {
            $iResult = 1;
        }
		return $iResult;
	}

	
	protected function fslEgg(&$objMember){
		$iResult = 0;
		$logHead = "<FslEgg> ";

		//네츄럴 => 슬롯 머니넘기기
		if($objMember->mb_fslot_id > 0){
			//네츄럴 머니 요청
			$arrResult = $this->libApifslot->getUserInfo($objMember->mb_fslot_uid);
			writeLog($logHead.$objMember->mb_uid."-UserInfo Status=".$arrResult['status']);

			if($arrResult['status'] == 1)
			{
				writeLog($logHead.$objMember->mb_uid."-UserInfo Balance=".$arrResult['balance']." Money=".$objMember->mb_money);
				$objMember->mb_fslot_money = $arrResult['balance'];

				$this->modelMember->updateFslotMoney($objMember);   
				$iResult = 1;
			
			}
		} else {
            $iResult = 1;
        }

		return $iResult;
	}

	protected function alltoGame(&$objMember, $iGame = 0){
		$iResult = 0;
		if($iGame == GAME_CASINO_EVOL){
			if($this->sltoMb($objMember) == 1 && $this->fsltoMb($objMember) == 1 &&
				$this->kgtoMb($objMember) == 1 ){
					$iResult = $this->mbtoEv($objMember);
			}
		} else if($iGame == GAME_SLOT_1){
			if($this->evtoMb($objMember) == 1 && $this->fsltoMb($objMember) == 1 &&
				$this->kgtoMb($objMember) == 1 ) {
					$iResult = $this->mbtoSl($objMember);
			}
		} else if($iGame == GAME_SLOT_2){
			if($this->evtoMb($objMember) == 1 && $this->sltoMb($objMember) == 1 &&
				$this->kgtoMb($objMember) == 1 ) {
					$iResult = $this->mbtoFsl($objMember);
			}
		}  else if($iGame == GAME_CASINO_KGON){
			if($this->evtoMb($objMember) == 1 && $this->sltoMb($objMember) == 1 &&
				$this->fsltoMb($objMember) == 1 ) {
					$iResult = $this->mbtoKg($objMember);
			}
		} else {
			if($this->evtoMb($objMember) == 1 && $this->sltoMb($objMember) == 1 &&
				$this->fsltoMb($objMember) == 1 && $this->kgtoMb($objMember) == 1) {
					$iResult = 1;
					
			}
		}
		return $iResult ;

	}
	
	protected function evtoMb(&$objMember){
		$iResult = 0;
		$logHead = "<EvtoMb> ";
		$confsiteModel = new ConfSite_Model();
		$confs = $this->getSiteConf($confsiteModel);
		if($confs["cas_deny"]){
			return 1;
		}
		//에볼 => 지갑 머니넘기기
		if($objMember->mb_live_id > 0){
			//에볼 머니 요청
			$arrResult = $this->libApicas->getUserInfo($objMember->mb_live_uid);
			writeLog($logHead.$objMember->mb_uid."-UserInfo Status=".$arrResult['status']);

			if($arrResult['status'] == 1)
			{
				writeLog($logHead.$objMember->mb_uid."-UserInfo Balance=".$arrResult['balance']." Money=".$objMember->mb_money);
				$amount = 0;
				if($arrResult['balance'] > 0){
					//에볼 머니 꺼내기
					usleep(500000);
					$amount = $arrResult['balance'];
					$arrResp = $this->libApicas->subBalance($objMember->mb_live_uid, $amount);
				} else {
					$iResult = 1;   //success
                    return $iResult;
				}
				
				if($arrResp['status'] == 1)
                {
                    writeLog($logHead.$objMember->mb_uid."-Withdraw RemainBalance=".$arrResp['balance']);
					if($this->modelMember->moneyProc($objMember, $amount)){
                        $objMember->mb_live_money = $arrResp['balance'];
                        $this->modelMember->updateLiveMoney($objMember);   
						$objMember->mb_money += $amount;   
                        $iResult = 1;
                    }
                } 
			}
		} else {
            $iResult = 1;
        }

		return $iResult;
	}

	protected function kgtoMb(&$objMember){
		$iResult = 0;
		$logHead = "<KgtoMb> ";
		$confsiteModel = new ConfSite_Model();
		$confs = $this->getSiteConf($confsiteModel);
		if(!$confs["kgon_enable"]){
			return 1;
		}
		//카지노 => 지갑 머니넘기기
		if($objMember->mb_kgon_id > 0){
			$arrResult = $this->libApikgon->getUserInfo($objMember->mb_kgon_uid);
			writeLog($logHead." ".$objMember->mb_uid."-UserInfo Status=".$arrResult['status']);
			if($arrResult['status'] == 1)
			{
				writeLog($logHead.$objMember->mb_uid."-UserInfo Balance=".$arrResult['balance']." Money=".$objMember->mb_money);
				$amount = floor($arrResult['balance']);
				if($amount > 0){
					//에볼 머니 꺼내기
					usleep(500000);
					//카지노 머니 전부 꺼내기
					$arrResp = $this->libApikgon->subBalance($objMember->mb_kgon_uid, $amount, true);
					
				} else {
					$iResult = 1;   //success
                    return $iResult;
				}
			
				if($arrResp['status'] == 1)
				{
					$amount = floor($arrResp['amount']);
					writeLog($logHead.$objMember->mb_uid."-Withdraw RemainBalance=".$arrResp['balance']);
					if( $this->modelMember->moneyProc($objMember, $amount)){
						$objMember->mb_kgon_money = $arrResp['balance'];
						$this->modelMember->updateKgonMoney($objMember);   
						$objMember->mb_money += $amount;   
						$iResult = 1;
					}
				} 
			}
		} else {
            $iResult = 1;
        }

		return $iResult;
	}

	protected function sltoMb(&$objMember){
		$iResult = 0;

		$logHead = "<SltoMb> ";
		//슬롯 => 보유머니넘기기
		if($objMember->mb_slot_uid !== ""){
			//슬롯머니 요청
			$arrResult = $this->libApislot->getUserInfo($objMember->mb_slot_uid);
			writeLog($logHead." ".$objMember->mb_uid."-UserInfo resultCode=".$arrResult['resultCode']);
			if($arrResult['status'] == 1)
			{
				writeLog($logHead." ".$objMember->mb_uid."-UserInfo Balance=".$arrResult['balance']." Money=".$objMember->mb_money);
				$amount = 0;
				if($arrResult['balance'] > 0){
					//슬롯머니 꺼내기
					usleep(500000);
					$amount = $arrResult['balance'];
					$arrResp =  $this->libApislot->subBalance($objMember->mb_slot_uid, $amount);
					writeLog($logHead." ".$objMember->mb_uid."-Withdraw resultCode=".$arrResult['resultCode']);
				} else {
                    $iResult = 1;   //success
                    return $iResult;
                }

				if($arrResp['status'] == 1)
				{
					writeLog($logHead.$objMember->mb_uid."-Withdraw ReaminBalance=".$arrResp['balance']);
                    if($this->modelMember->moneyProc($objMember, $amount)){
                        $objMember->mb_slot_money = $arrResp['balance'];
                        $this->modelMember->updateSlotMoney($objMember);
						$objMember->mb_money += $amount;   
                        $iResult = 1;
                    }
                } 
			}
		} else {
            $iResult = 1;
        }
		return $iResult;
	}

	
	protected function fsltoMb(&$objMember){
		$iResult = 0;
		$logHead = "<FsltoMb> ";

		//네츄럴 => 지갑 머니넘기기
		if($objMember->mb_fslot_id > 0){
			//네츄럴 머니 요청
			$arrResult = $this->libApifslot->getUserInfo($objMember->mb_fslot_uid);
			writeLog($logHead.$objMember->mb_uid."-UserInfo Status=".$arrResult['status']);

			if($arrResult['status'] == 1)
			{
				writeLog($logHead.$objMember->mb_uid."-UserInfo Balance=".$arrResult['balance']." Money=".$objMember->mb_money);
				$amount = 0;
				if($arrResult['balance'] > 0){
					//네츄럴 머니 꺼내기
					usleep(500000);
					$amount = $arrResult['balance'];
					$arrResp = $this->libApifslot->subBalance($objMember->mb_fslot_uid, $amount);
				} else {
					$iResult = 1;   //success
                    return $iResult;
				}
				
				if($arrResp['status'] == 1)
                {
                    writeLog($logHead.$objMember->mb_uid."-Withdraw RemainBalance=".$arrResp['balance']);
					if($this->modelMember->moneyProc($objMember, $amount)){
                        $objMember->mb_fslot_money = $arrResp['balance'];
                        $this->modelMember->updateFslotMoney($objMember);   
						$objMember->mb_money += $amount;   
                        $iResult = 1;
                    }
                } 
			}
		} else {
            $iResult = 1;
        }

		return $iResult;
	}

	protected function mbtoEv(&$objMember){
		$iResult = 0;
		$logHead = "<MbtoEv> ";

		//에볼 <= 지갑 머니넘기기
		if($objMember->mb_live_id > 0 && $objMember->mb_money > 0){
			//에볼 머니 요청
			$arrResult = $this->libApicas->addBalance($objMember->mb_live_uid, $objMember->mb_money);
			writeLog($logHead.$objMember->mb_uid."-Deposit Status=".$arrResult['status']);
				
			if($arrResult['status'] == 1)
			{
				writeLog($logHead.$objMember->mb_uid."-Deposit Balance=".$arrResult['balance']);
				if($this->modelMember->moneyProc($objMember, 0-$arrResult['amount'])){
					$objMember->mb_live_money = $arrResult['balance'];
					$this->modelMember->updateLiveMoney($objMember);   
					$objMember->mb_money -= $arrResult['amount'];   
					$iResult = 1;
				}
			} 
		} else {
            $iResult = 1;
        }

		return $iResult;
	}

	
	protected function mbtoKg(&$objMember){
		$iResult = 0;
		$logHead = "<MbtoKg> ";

		//카지노 <= 지갑 머니넘기기
		if($objMember->mb_kgon_id > 0 && $objMember->mb_money > 0){
			//에볼 머니 요청
			$arrResult = $this->libApikgon->addBalance($objMember->mb_kgon_uid, $objMember->mb_money);
			writeLog($logHead.$objMember->mb_uid."-Deposit Status=".$arrResult['status']);
				
			if($arrResult['status'] == 1)
			{
				$arrResult['amount'] = $objMember->mb_money;
				writeLog($logHead.$objMember->mb_uid."-Deposit Balance=".$arrResult['balance']);
				if($this->modelMember->moneyProc($objMember, 0-$arrResult['amount'])){
					$objMember->mb_kgon_money = $arrResult['balance'];
					$this->modelMember->updateKgonMoney($objMember);   
					$objMember->mb_money -= $arrResult['amount'];   
					$iResult = 1;
				}
			} 
		} else {
            $iResult = 1;
        }

		return $iResult;
	}

	protected function mbtoSl(&$objMember){
		$iResult = 0;

		$logHead = "<MbtoSl> ";
		//슬롯 <= 보유머니넘기기
		if($objMember->mb_slot_uid !== "" && $objMember->mb_money > 0){
			//슬롯머니 요청
			$arrResult = $this->libApislot->addBalance($objMember->mb_slot_uid, $objMember->mb_money);
			writeLog($logHead." ".$objMember->mb_uid."-Deposit resultCode=".$arrResult['resultCode']);
			
			if($arrResult['status'] == 1)
			{
				$arrResult['amount'] = $objMember->mb_money;

				writeLog($logHead.$objMember->mb_uid."-Deposit Balance=".$arrResult['balance']);
				if($this->modelMember->moneyProc($objMember, 0-$arrResult['amount'])){
					$objMember->mb_slot_money = $arrResult['balance'];
					$this->modelMember->updateSlotMoney($objMember);
					$objMember->mb_money -= $arrResult['amount'];   
					$iResult = 1;
				}
			} 
		} else {
            $iResult = 1;
        }
		return $iResult;
	}

	
	protected function mbtoFsl(&$objMember){
		$iResult = 0;
		$logHead = "<MbtoFsl> ";

		//네츄럴 => 지갑 머니넘기기
		if($objMember->mb_fslot_id > 0 && $objMember->mb_money > 0){
			//네츄럴 머니 요청
			$arrResult = $this->libApifslot->addBalance($objMember->mb_fslot_uid, $objMember->mb_money);
			writeLog($logHead.$objMember->mb_uid."-Deposit Status=".$arrResult['status']);
				
			if($arrResult['status'] == 1)
			{
				writeLog($logHead.$objMember->mb_uid."-Deposit Balance=".$arrResult['balance']);
				if($this->modelMember->moneyProc($objMember, 0-$arrResult['amount'])){
					$objMember->mb_fslot_money = $arrResult['balance'];
					$this->modelMember->updateFslotMoney($objMember);   
					$objMember->mb_money -= $arrResult['amount'];   
					$iResult = 1;
				}
			} 
		} else {
            $iResult = 1;
        }

		return $iResult;
	}


}
