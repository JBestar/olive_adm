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
                if($objConfig->conf_idx == 1){
                    $arrSiteInfo[4] = 1;
                } else 
                    $arrSiteInfo[4] = 0;
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
        $updateData['conf_id'] = CONF_NOTICE_BANK;
        $updateData['conf_content'] = $arrData['depositenotice'];
        $updateData['conf_active'] = $arrData['depositenotice_ok'];
        $arrBatch[] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = CONF_CHARGEINFO;
        $updateData['conf_content'] = $arrData['bank'];
        $arrBatch[] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = CONF_CHARGEMACRO;
        $updateData['conf_content'] = $arrData['bankmacro'];
        $arrBatch[] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = CONF_NOTICE_URGENT;
        $updateData['conf_content'] = $arrData['urgentnotice'];
        $updateData['conf_active'] = $arrData['urgentnotice_ok'];
        $arrBatch[] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = CONF_MULTI_LOGIN;
        $updateData['conf_active'] = $arrData['multilog_ok'];
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
        $confIds = [CONF_SITENAME, CONF_GAMEPER_FULL, CONF_NPG_DENY, CONF_BPG_DENY, 
            CONF_CAS_DENY, CONF_SLOT_DENY];  
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
}