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

    
    public function getBetSite(){

        $data = ["", "", "", 0, 0];

        $objConfig = $this->where('conf_id', CONF_BETSITE)->first();
        if(!is_null($objConfig)){
            $info = explode('#', $objConfig->conf_content);
            if(count($info) >= 3){
                $data[0] = $info[0];   
                $data[1] = $info[1];   
                $data[2] = $info[2];   
                $data[3] = $objConfig->conf_active;
                $data[4] = intval($objConfig->conf_idx);
            }
        }

        return $data;
    }


    public function setBetSite($data){
        $arrBatch = array();

        $strContent = "";
        if(strlen($data['site'])<1) 
            $data['site']=" ";
        $strContent .= $data['site']."#";
        
        if(strlen($data['userid'])<1) 
            $data['userid']=" ";    
        $strContent .= $data['userid']."#";
        
        if(strlen($data['userpwd'])<1) 
            $data['userpwd']=" "; 
        $strContent .= $data['userpwd'];
        
        $arrBatch = array();
        $updateData = array();
        $updateData['conf_id'] = CONF_BETSITE;
        $updateData['conf_content'] = $strContent;
        $updateData['conf_active'] = $data['active'];
        $updateData['conf_idx'] = $data['type'];
        $arrBatch[] = $updateData;

        return  $this->builder()->updateBatch($arrBatch, 'conf_id');
    }

    public function getEvolSite(){

        $arrBatch = array();
        
        $confIds = [CONF_EVOLSITE_1, CONF_EVOLRUN_1, CONF_EVOLSITE_2, CONF_EVOLRUN_2, CONF_EVOLSITE_3, CONF_EVOLRUN_3];  
        $arrConf = $this->find($confIds);
        
        $data[] = ["", "", "", 0, 0, 0, 0, 0, 0, 0, "", 20, 50, 0, 0 ];
        $data[] = ["", "", "", 0, 0, 0, 0, 0, 0, 0, "", 0, 0, 0, 0 ];
        $data[] = ["", "", "", 0, 0, 0, 0, 0, 0, 0, "", 0, 0, 0, 0 ];

        $idx = 0;
        foreach($arrConf as $objConf){

			switch($objConf->conf_id){
				case CONF_EVOLSITE_1:	
				case CONF_EVOLSITE_2:	
                case CONF_EVOLSITE_3:	
                    if($objConf->conf_id == CONF_EVOLSITE_1)
                        $idx = 0;
                    else if($objConf->conf_id == CONF_EVOLSITE_2)
                        $idx = 1;
                    else 
                        $idx = 2;
                        
                    $info = explode(';', $objConf->conf_content);
                    if(count($info) >= 3){
                        $data[$idx][0] = $info[0];   
                        $data[$idx][1] = $info[1];   
                        $data[$idx][2] = $info[2];   
                        $objConf->conf_active = intval($objConf->conf_active); 
                        
                        $data[$idx][7] = -3; 
                        if(diffDt(date('Y-m-d H:i:s'), $objConf->conf_update) < 20){
                            $data[$idx][7] = $objConf->conf_active;
                        }
                        
                        $info = explode('#', $objConf->conf_idx);
                        if(count($info) >= 3){
                            $data[$idx][4] = intval($info[0]);
                            $data[$idx][5] = intval($info[1]);
                            $data[$idx][6] = intval($info[2]);
                            $data[$idx][6] = intval($info[2]);
                            if(count($info) >= 5 && $idx == 0){
                                $data[$idx][8] = intval($info[3]);
                                $data[$idx][9] = intval($info[4]);
                                $data[$idx][11] = intval($info[5]);
                                $data[$idx][12] = intval($info[6]);
                            } 
                            if(count($info) >= 8)
                                $data[$idx][13] = intval($info[7]);

                        }
                    }
					break;
				case CONF_EVOLRUN_1:	
				case CONF_EVOLRUN_2:	
                case CONF_EVOLRUN_3:
                    if($objConf->conf_id == CONF_EVOLRUN_1)
                        $idx = 0;
                    else if($objConf->conf_id == CONF_EVOLRUN_2)
                        $idx = 1;
                    else 
                        $idx = 2;	
                    $data[$idx][3] = $objConf->conf_active;
                    $data[$idx][10] = $objConf->conf_idx;
					break;

                default:break;
			}
		}
        

        return $data;
    }

    public function setEvolSite($data){
        $arrBatch = array();

        if(array_key_exists('site_ev', $data)){
            $strContent = "";
            if(strlen($data['site_ev'])<1) 
                $data['site_ev']=" ";
            $strContent .= $data['site_ev'].";";
            
            if(strlen($data['userid_ev'])<1) 
                $data['userid_ev']=" ";    
            $strContent .= $data['userid_ev'].";";
            
            if(strlen($data['userpwd_ev'])<1) 
                $data['userpwd_ev']=" "; 
            $strContent .= $data['userpwd_ev'];
    
            $updateData = array();
            $updateData['conf_id'] = CONF_EVOLSITE_1;
            $updateData['conf_content'] = $strContent;
            $strContent = $data['type_ev']."#".$data['bet_ev']."#".$data['con_ev']."#".$data['bet_min']."#".$data['bet_max']."#".$data['con_min']."#".$data['user_max']."#".$data['is_signal'];
            $updateData['conf_idx'] = $strContent;
            $arrBatch[] = $updateData;
        }

        $updateData = array();
        $updateData['conf_id'] = CONF_EVOLRUN_1;
        $updateData['conf_active'] = $data['active_ev'];
        $arrBatch[] = $updateData;


        if(array_key_exists('site_ev2', $data)){
            $strContent = "";
            if(strlen($data['site_ev2'])<1) 
                $data['site_ev2']=" ";
            $strContent .= $data['site_ev2'].";";
            
            if(strlen($data['userid_ev2'])<1) 
                $data['userid_ev2']=" ";    
            $strContent .= $data['userid_ev2'].";";
            
            if(strlen($data['userpwd_ev2'])<1) 
                $data['userpwd_ev2']=" "; 
            $strContent .= $data['userpwd_ev2'];
    
            $updateData = array();
            $updateData['conf_id'] = CONF_EVOLSITE_2;
            $updateData['conf_content'] = $strContent;
            $strContent = $data['type_ev2']."#".$data['betmode_ev']."#".$data['con_ev2']."#0#0#0#0#".$data['is_signal2'];
            $updateData['conf_idx'] = $strContent;
            $arrBatch[] = $updateData;

            $updateData = array();
            $updateData['conf_id'] = CONF_EVOLRUN_2;
            $updateData['conf_active'] = $data['active_ev2'];
            $arrBatch[] = $updateData;

            //site 3
            $strContent = "";
            if(strlen($data['site_ev3'])<1) 
                $data['site_ev3']=" ";
            $strContent .= $data['site_ev3'].";";
            
            if(strlen($data['userid_ev3'])<1) 
                $data['userid_ev3']=" ";    
            $strContent .= $data['userid_ev3'].";";
            
            if(strlen($data['userpwd_ev3'])<1) 
                $data['userpwd_ev3']=" "; 
            $strContent .= $data['userpwd_ev3'];
    
            $updateData = array();
            $updateData['conf_id'] = CONF_EVOLSITE_3;
            $updateData['conf_content'] = $strContent;
            $strContent = $data['type_ev3']."#".$data['betmode_ev']."#".$data['con_ev3']."#0#0#0#0#".$data['is_signal3'];
            $updateData['conf_idx'] = $strContent;
            $arrBatch[] = $updateData;

            $updateData = array();
            $updateData['conf_id'] = CONF_EVOLRUN_3;
            $updateData['conf_active'] = $data['active_ev3'];
            $arrBatch[] = $updateData;
        }


        return  $this->builder()->updateBatch($arrBatch, 'conf_id');
    }
    
    public function saveData($arrData){

        if($arrData == null) return false;
        if (!array_key_exists("sitename", $arrData)) return false;

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
        $updateData['conf_id'] = CONF_NOTICE_MAIN;
        $updateData['conf_content'] = $arrData['mainnotice'];
        $updateData['conf_active'] = $arrData['mainnotice_ok'];
        if(array_key_exists('mainnotice_cn', $arrData)){
            $updateData['conf_content_cn'] = $arrData['mainnotice_cn'];
        }
        $arrBatch[] = $updateData;
        
    
        if(array_key_exists('bank', $arrData)){
            $updateData = array();
            $updateData['conf_id'] = CONF_CHARGEINFO;
            $updateData['conf_content'] = $arrData['bank'];
            $arrBatch[] = $updateData;
        } else if(array_key_exists('bankapi', $arrData)){
            $updateData = array();
            $updateData['conf_id'] = CONF_API_VACC;
            $updateData['conf_content'] = $arrData['bankapi'];
            $arrBatch[] = $updateData;
        }

        $updateData = array();
        $updateData['conf_id'] = CONF_NOTICE_BANK;
        $updateData['conf_content'] = $arrData['depositenotice'];
        $updateData['conf_active'] = $arrData['depositenotice_ok'];
        $updateData['conf_idx'] = $arrData['depositenotice_color'];
        if(array_key_exists('depositenotice_cn', $arrData)){
            $updateData['conf_content_cn'] = $arrData['depositenotice_cn'];
        }
        $arrBatch[] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = CONF_NOTICE_URGENT;
        $updateData['conf_content'] = $arrData['urgentnotice'];
        $updateData['conf_active'] = $arrData['urgentnotice_ok'];
        $updateData['conf_idx'] = $arrData['urgentnotice_color'];
        if(array_key_exists('urgentnotice_cn', $arrData)){
            $updateData['conf_content_cn'] = $arrData['urgentnotice_cn'];
        }
        $arrBatch[] = $updateData;
    
        if(array_key_exists('chargemanual', $arrData)){
            $updateData = array();
            $updateData['conf_id'] = CONF_CHARGE_MANUAL;
            $updateData['conf_content'] = $arrData['chargemanual'];
            $arrBatch[] = $updateData;
        } else if(array_key_exists('exchange_delay', $arrData)){
            $updateData = array();
            $updateData['conf_id'] = CONF_CHARGE_MANUAL;
            $updateData['conf_idx'] = $arrData['exchange_delay'];
            $arrBatch[] = $updateData;
        }

        if(array_key_exists('discharmanual', $arrData)){
            $updateData = array();
            $updateData['conf_id'] = CONF_DISCHA_MANUAL;
            $updateData['conf_content'] = $arrData['discharmanual'];
            $arrBatch[] = $updateData;
        } else if(array_key_exists('bank_rest', $arrData)){
            $updateData = array();
            $updateData['conf_id'] = CONF_DISCHA_MANUAL;
            $updateData['conf_idx'] = $arrData['bank_rest'];
            $arrBatch[] = $updateData;
        }
        
        $updateData = array();
        $updateData['conf_id'] = CONF_CHARGEMACRO;
        $updateData['conf_content'] = $arrData['bankmacro'];
        if(array_key_exists('bankmacro_cn', $arrData)){
            $updateData['conf_content_cn'] = $arrData['bankmacro_cn'];
        }
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

        if(array_key_exists('chargeurl', $arrData)){
            $updateData = array();
            $updateData['conf_id'] = CONF_CHARGE_URL;
            $updateData['conf_content'] = $arrData['chargeurl'];
            $arrBatch[] = $updateData;
        }

        if(array_key_exists('teleid', $arrData)){
            $updateData = array();
            $updateData['conf_id'] = CONF_TELE_ID;
            $updateData['conf_content'] = $arrData['teleid'];
            $arrBatch[] = $updateData;
        }

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

    
    public function getEvpressState(){

        $objConfig = $this->where('conf_id', CONF_EVOLPRESS)->first();
        if(!is_null($objConfig) && intval($objConfig->conf_content) == STATE_ACTIVE){
            return true;
        }

        return false;
    }

    public function getEvpressConfig(){

        $data = ["", "", 0];

        $objConfig = $this->where('conf_id', CONF_EVOLPRESS)->first();
        if(!is_null($objConfig)){
            $data[2] = intval($objConfig->conf_active);
            $info = explode('#', $objConfig->conf_idx);
            if(count($info) >= 2){
                $data[0] = $info[0];   
                $data[1] = $info[1];   
            }
        }

        return $data;
    }

    public function saveEvpressConfig($arrData){

        if($arrData == null) return false;
        if (!array_key_exists("enable", $arrData)) return false;
        // if (!array_key_exists("time", $arrData)) return false;
        if (!array_key_exists("money", $arrData)) return false;
        
        $arrBatch = array();
        $arrData['time'] = 0;
        $updateData['conf_id'] = CONF_EVOLPRESS;
        $updateData['conf_active'] = $arrData['enable'];
        $updateData['conf_idx'] = $arrData['time']."#".$arrData['money'];
        $arrBatch[0] = $updateData;
        return $this->builder()->updateBatch($arrBatch, 'conf_id');
    }

    public function getSoundConf(){

        $confIds = [CONF_SOUND_1, CONF_SOUND_2, CONF_SOUND_3, CONF_SOUND_4];  
        $arrConf = $this->find($confIds);

        $arrSoundData = array();
        foreach($arrConf as $objConf){
            $arrSound = ["", 0];
            $arrSound[0] = $objConf->conf_content;
            $arrSound[1] = $objConf->conf_active;
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
        $confIds = [CONF_SITENAME, CONF_GAMEPER_FULL, CONF_BPG_DENY,  CONF_EVOL_DENY, CONF_SLOT_DENY, 
            CONF_CAS_DENY, CONF_EOS5_DENY, CONF_EOS3_DENY, CONF_COIN5_DENY, CONF_COIN3_DENY, 
            CONF_HPG_DENY, CONF_HOLD_DENY, CONF_EVOLFOLLOW];  
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
            CONF_TRANS_LVS, CONF_DEPOSIT_PLAY, CONF_WITHDRAW_PLAY, CONF_DELAY_PLAY, CONF_AUTO_PERMIT];  
        $arrConf = $this->find($confIds);
        $_ENV['mem.trans_deny'] = false;
        $_ENV['mem.return_deny'] = false;
        $_ENV['mem.trans_lv1'] = false;
        $_ENV['mem.return_lv1'] = false;
        $_ENV['mem.depodeny_play'] = false;
        $_ENV['mem.withdeny_play'] = false;
        $_ENV['mem.trans_lvs'] = [];
        $_ENV['mem.delay_play'] = DELAY_PLAYING;
        $_ENV['mem.auto_permit'] = false;

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
                case CONF_AUTO_PERMIT:	$_ENV['mem.auto_permit'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
				default:break;
			}
		}

    }
}