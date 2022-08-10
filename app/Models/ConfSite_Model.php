<?php 
namespace App\Models;

use CodeIgniter\Model;

class ConfSite_Model extends Model 
{
    protected $table = 'conf_site';
    protected $allowedFields = ['conf_memo', 'conf_content', 'conf_active', 'conf_update', 'conf_idx'];
    protected $primaryKey = 'conf_id';
    protected $returnType = 'object'; 

    public function getConf($conf_id)
    {
        return $this->find($conf_id);
    }

    public function getSiteName()
    {

        $objConf = $this->getConf(CONF_SITENAME);
        $strSiteName = "";
        if(!is_null($objConf)){
            $strSiteName = $objConf->conf_content;
        }
        return $strSiteName;
    }

    public function getMaintainConfig(){
        //점검관련 정보
        return $this->getConf(CONF_MAINTAIN);
    }

    
    public function getBetSite($nLevel){

        $nConfigId = CONF_BETSITE;
        $arrSiteInfo = ["", "", "", 0, 0 ];

        $objConfig = $this->where(array('conf_id'=>$nConfigId))->first();
        if(!is_null($objConfig) && $nLevel >= LEVEL_ADMIN){
            $strSite = $objConfig->conf_content;
            $arrSiteInfo = explode('#', $strSite);
            if(count($arrSiteInfo) >= 3){
                $arrSiteInfo[3] = $objConfig->conf_active;
                $arrSiteInfo[4] = intval($objConfig->conf_idx);
                return $arrSiteInfo;
            }
        }
        return $arrSiteInfo;
    }


    public function setBetSite($nAdminLev, $arrReqData){
        if($nAdminLev < LEVEL_ADMIN)
            return false;

        $strContent = "";
        if(strlen($arrReqData['site'])<1) 
            $arrReqData['site']=" ";
        $strContent .= $arrReqData['site']."#";
        
        if(strlen($arrReqData['userid'])<1) 
            $arrReqData['userid']=" ";    
        $strContent .= $arrReqData['userid']."#";
        
        if(strlen($arrReqData['userpwd'])<1) 
            $arrReqData['userpwd']=" "; 
        $strContent .= $arrReqData['userpwd'];
        
        $this->builder()->set('conf_content', $strContent);
        $this->builder()->set('conf_active', $arrReqData['active']);
        $this->builder()->set('conf_idx', $arrReqData['type']);
        $this->builder()->where('conf_id', CONF_BETSITE);
        
        return $this->builder()->update();
    }

    public function saveData($arrData){

        if($arrData == null) return false;
        if (!array_key_exists("sitename", $arrData)) return false;
        if (!array_key_exists("domainname", $arrData)) return false;
        if (!array_key_exists("homepage", $arrData)) return false;
        if (!array_key_exists("adminpage", $arrData)) return false;

        $arrBatch = array();
        $updateData = array();
        $updateData['conf_id'] = CONF_SITENAME;
        $updateData['conf_content'] = $arrData['sitename'];
        $arrBatch[] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = CONF_DOMAIN;
        $updateData['conf_content'] = $arrData['domainname'];
        $arrBatch[] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = CONF_USERPAGE;
        $updateData['conf_content'] = $arrData['homepage'];
        $arrBatch[] = $updateData;
        
        $updateData = array();
        $updateData['conf_id'] = CONF_ADMINPAGE;
        $updateData['conf_content'] = $arrData['adminpage'];
        $arrBatch[] = $updateData;
        
        $updateData = array();
        $updateData['conf_id'] = CONF_NOTICE_MAIN;
        $updateData['conf_content'] = $arrData['mainnotice'];
        $updateData['conf_active'] = $arrData['mainnotice_ok'];
        $arrBatch[] = $updateData;
        
    
        $updateData = array();
        $updateData['conf_id'] = CONF_CHARGEINFO;
        $updateData['conf_content'] = $arrData['bank'];
        $arrBatch[] = $updateData;
        
        if(array_key_exists('depositenotice', $arrData)){
            $updateData = array();
            $updateData['conf_id'] = CONF_NOTICE_BANK;
            $updateData['conf_content'] = $arrData['depositenotice'];
            $updateData['conf_active'] = $arrData['depositenotice_ok'];
            $arrBatch[] = $updateData;

            $updateData = array();
            $updateData['conf_id'] = CONF_NOTICE_URGENT;
            $updateData['conf_content'] = $arrData['urgentnotice'];
            $updateData['conf_active'] = $arrData['urgentnotice_ok'];
            $arrBatch[] = $updateData;
    
            $updateData = array();
            $updateData['conf_id'] = CONF_CHARGE_MANUAL;
            $updateData['conf_content'] = $arrData['chargemanual'];
            $arrBatch[] = $updateData;
    
            $updateData = array();
            $updateData['conf_id'] = CONF_DISCHA_MANUAL;
            $updateData['conf_content'] = $arrData['discharmanual'];
            $arrBatch[] = $updateData;
    
        }
        
        $updateData = array();
        $updateData['conf_id'] = CONF_CHARGEMACRO;
        $updateData['conf_content'] = $arrData['bankmacro'];
        $arrBatch[] = $updateData;

        
        $updateData = array();
        $updateData['conf_id'] = CONF_MULTI_LOGIN;
        $updateData['conf_active'] = $arrData['multilog_ok'];
        $arrBatch[] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = CONF_TRANS_DENY;
        $updateData['conf_active'] = $arrData['trans_deny'];
        $arrBatch[] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = CONF_RETURN_DENY;
        $updateData['conf_active'] = $arrData['return_deny'];
        $arrBatch[] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = CONF_TRANS_LV1;
        $updateData['conf_active'] = $arrData['trans_lv1'];
        $arrBatch[] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = CONF_RETURN_LV1;
        $updateData['conf_active'] = $arrData['return_lv1'];
        $arrBatch[] = $updateData;

        return  $this->builder()->updateBatch($arrBatch, 'conf_id');

    }


    public function saveMaintainConfig($arrData){

        if($arrData == null) return false;
        if (!array_key_exists("maintain", $arrData)) return false;
        if (!array_key_exists("content", $arrData)) return false;
        
        $arrBatch = array();
        
        $updateData['conf_id'] = CONF_MAINTAIN;
        $updateData['conf_content'] = $arrData['content'];
        $updateData['conf_active'] = $arrData['maintain'];
        $arrBatch[0] = $updateData;
        return $this->builder()->updateBatch($arrBatch, 'conf_id');
    }

    public function getSoundConf(){

        
        $arrSoundData = array();

        for($i=0 ; $i<4; $i++){

            $arrSound = ["", 0];

            $nConfigId = CONF_SOUND_1 + $i;    
            $objConfig = $this->asObject()->where(array('conf_id'=>$nConfigId))->first();
            if(!is_null($objConfig)){
                $arrSound[0] = $objConfig->conf_content;
                $arrSound[1] = $objConfig->conf_active;
            } 

            array_push($arrSoundData, $arrSound);
        }
       
        return $arrSoundData;
    }

    public function saveSoundConf($arrSoundData){
        if(count($arrSoundData) != 4)
            return false;
        
        $arrBatch = array();

        for($i=0 ; $i<4; $i++){

            $arrUpdateData = array();   
            
            $arrUpdateData['conf_id'] = CONF_SOUND_1+$i;
            $arrUpdateData['conf_content'] = $arrSoundData[$i][0];
            $arrUpdateData['conf_active'] = $arrSoundData[$i][1];
            array_push($arrBatch, $arrUpdateData);
            
        }
       
        return  $this->builder()->updateBatch($arrBatch, 'conf_id');
            
    }

    public function getSiteConf(){
        $confIds = [CONF_SITENAME, CONF_GAMEPER_FULL, CONF_NPG_DENY, CONF_BPG_DENY,  CONF_CAS_DENY, 
           CONF_SLOT_DENY, CONF_KGON_ENABLE, CONF_EOS5_ENABLE, CONF_EOS3_ENABLE, CONF_COIN5_ENABLE, CONF_COIN3_ENABLE];  
        return $this->find($confIds);
    }

    
    public function setConfActive($confId, $balance){
        
        $this->builder()->set('conf_active', $balance);
        $this->builder()->where('conf_id', $confId);
        
        return $this->builder()->update();
    }

    
    public function IsMultiLogin(){

        $objConf = $this->find(CONF_MULTI_LOGIN);
        
        if(!is_null($objConf) && $objConf->conf_active == STATE_ACTIVE) {
            return true;
        }
        return false;
    }

    
    public function IsMaintain(){

        $objConf = $this->find(CONF_MAINTAIN);
        
        if(!is_null($objConf) && $objConf->conf_active == STATE_ACTIVE) {
            return true;
        }
        return false;
    }

    
    public function readBetConf(){
        $confIds = [CONF_BET_NL_DENY, CONF_BET_NP_DENY, CONF_BET_N2P_DENY, CONF_BET_PN_DENY, CONF_BET_BLANK_EN];  
        $arrConf = $this->find($confIds);

        foreach($arrConf as $objConf){
			switch($objConf->conf_id){
				case CONF_BET_NL_DENY:	$_ENV['bet.nl_deny'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
				case CONF_BET_NP_DENY:	$_ENV['bet.np_deny'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
				case CONF_BET_N2P_DENY:	$_ENV['bet.n2p_deny'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
				case CONF_BET_PN_DENY: $_ENV['bet.pn_deny'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
                case CONF_BET_BLANK_EN: $_ENV['bet.blank_en'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
				default:break;
			}
		}
    }
    
    public function readMemConf(){
        $confIds = [CONF_TRANS_DENY, CONF_RETURN_DENY, CONF_TRANS_LV1, CONF_RETURN_LV1, 
            CONF_TRANS_LVS, CONF_DEPOSIT_PLAY, CONF_WITHDRAW_PLAY, CONF_DELAY_PLAY];  
        $arrConf = $this->find($confIds);
        $_ENV['mem.trans_deny'] = false;
        $_ENV['mem.return_deny'] = false;
        $_ENV['mem.trans_lv1'] = false;
        $_ENV['mem.return_lv1'] = false;
        $_ENV['mem.depodeny_play'] = false;
        $_ENV['mem.withdeny_play'] = false;
        $_ENV['mem.trans_lvs'] = [];
        $_ENV['mem.delay_play'] = DELAY_PLAYING;

        foreach($arrConf as $objConf){
			switch($objConf->conf_id){
				case CONF_TRANS_DENY:	$_ENV['mem.trans_deny'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
                case CONF_RETURN_DENY:	$_ENV['mem.return_deny'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
                case CONF_TRANS_LV1:	$_ENV['mem.trans_lv1'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
                case CONF_RETURN_LV1:	$_ENV['mem.return_lv1'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
                case CONF_DEPOSIT_PLAY:	$_ENV['mem.depodeny_play'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
                case CONF_WITHDRAW_PLAY:	$_ENV['mem.withdeny_play'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
                case CONF_TRANS_LVS:	
					$lvs = explode(',', $objConf->conf_content);
                    foreach($lvs as $lv){
                        $lv = trim($lv);
                        if(strlen($lv) > 0 && !in_array($lv, $_ENV['mem.trans_lvs']))
                            array_push($_ENV['mem.trans_lvs'], intval($lv));
                    }
                    break;
                case CONF_DELAY_PLAY:	$_ENV['mem.delay_play'] = intval($objConf->conf_active);
					break;
				default:break;
			}
		}

    }
}