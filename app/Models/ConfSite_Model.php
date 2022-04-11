<?php 
namespace App\Models;

use CodeIgniter\Model;

class ConfSite_Model extends Model 
{
    protected $table = 'conf_site';
    protected $allowedFields = ['conf_memo', 'conf_content', 'conf_active', 'conf_update', 'conf_idx'];
    protected $primaryKey = 'conf_id';

    public function getConf($conf_id)
    {
        try { 
            $data = $this->find($conf_id);
            return $data;       
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }

    public function updateLast($objConf){
        
    }

    public function getSiteName():string
    {

        $objConf = $this->getConf(CONF_SITENAME);
        $strSiteName = "";
        if(!is_null($objConf)){
            $strSiteName = $objConf['conf_content'];
        }
        return $strSiteName;
    }

    public function getMaintainConfig(){
        //점검관련 정보
        return $this->getConf(CONF_MAINTAIN);
    }

    
    public function getBetSite($nLevel){

        $nConfigId = CONF_BETSITE;
        $arrSiteInfo = ["", "", "", 0 ];

        $objConfig = $this->asObject()->where(array('conf_id'=>$nConfigId))->first();
        if(!is_null($objConfig) && $nLevel >= LEVEL_ADMIN){
            $strSite = $objConfig->conf_content;
            $arrSiteInfo = explode('#', $strSite);
            if(count($arrSiteInfo) >= 3){
                $arrSiteInfo[3] = $objConfig->conf_active;
                return $arrSiteInfo;
            }
        }
        return $arrSiteInfo;
    }


    public function setBetSite($nAdminLev, $arrReqData){
        if($nAdminLev < LEVEL_ADMIN)
            return false;

        $strContent = "";
        if(strlen($arrReqData['site'])<1) return false;
        $strContent .= $arrReqData['site']."#";
        
        if(strlen($arrReqData['userid'])<1) return false;
        $strContent .= $arrReqData['userid']."#";
        
        if(strlen($arrReqData['userpwd'])<1) return false;
        $strContent .= $arrReqData['userpwd'];
        
        // if(strlen($arrReqData['pball'])<1) return false;
        // $strContent .= $arrReqData['pball']."#";
        
        // if(strlen($arrReqData['pladder'])<1) return false;
        // $strContent .= $arrReqData['pladder']."#";
        
        // if(strlen($arrReqData['bball'])<1) return false;
        // $strContent .= $arrReqData['bball']."#";

        // if(strlen($arrReqData['bladder'])<1) return false;
        // $strContent .= $arrReqData['bladder'];

        $this->builder()->set('conf_content', $strContent);
        $this->builder()->set('conf_active', $arrReqData['active']);
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
        $arrBatch[0] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = CONF_DOMAIN;
        $updateData['conf_content'] = $arrData['domainname'];
        $arrBatch[1] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = CONF_USERPAGE;
        $updateData['conf_content'] = $arrData['homepage'];
        $arrBatch[2] = $updateData;
        
        $updateData = array();
        $updateData['conf_id'] = CONF_ADMINPAGE;
        $updateData['conf_content'] = $arrData['adminpage'];
        $arrBatch[3] = $updateData;
        
        $updateData = array();
        $updateData['conf_id'] = CONF_NOTICE_MAIN;
        $updateData['conf_content'] = $arrData['mainnotice'];
        $updateData['conf_active'] = $arrData['mainnotice_ok'];
        $arrBatch[4] = $updateData;
        

        $updateData = array();
        $updateData['conf_id'] = CONF_NOTICE_BANK;
        $updateData['conf_content'] = $arrData['depositenotice'];
        $updateData['conf_active'] = $arrData['depositenotice_ok'];
        $arrBatch[5] = $updateData;

        // $updateData = array();
        // $updateData['conf_id'] = 7;
        // $updateData['conf_content'] = $arrData['withdrawnotice'];
        // $arrBatch[6] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = CONF_CHARGEINFO;
        $updateData['conf_content'] = $arrData['bank'];
        $arrBatch[7] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = CONF_CHARGEMACRO;
        $updateData['conf_content'] = $arrData['bankmacro'];
        $arrBatch[8] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = CONF_NOTICE_URGENT;
        $updateData['conf_content'] = $arrData['urgentnotice'];
        $updateData['conf_active'] = $arrData['urgentnotice_ok'];
        $arrBatch[9] = $updateData;


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
}