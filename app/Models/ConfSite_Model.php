<?php 
namespace App\Models;

use CodeIgniter\Model;

class ConfSite_Model extends Model 
{
    protected $table = 'conf_site';
    protected $allowedFields = ['conf_memo', 'conf_content', 'conf_active'];
    protected $primaryKey = 'conf_id';

    private function getConf($conf_id)
    {
        
        try { 
            $data = $this->find($conf_id);
            return $data;       
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }
    public function getSiteName():string
    {

        $objConf = $this->getConf(1);
        $strSiteName = "";
        if(!is_null($objConf)){
            $strSiteName = $objConf['conf_content'];
        }
        return $strSiteName;
    }

    public function getMaintainConfig(){
        //점검관련 정보
        return $this->getConf(10);
    }

    public function getLiveConfig(){
        //라이브게임 에이전트아이디 정보
        $this->builder()->where('conf_id = 13');
        return $this->builder()->get()->getRow();
    }

    public function setLiveConfigId($nPlayerId){
        //라이브게임 에이전트아이디 정보
        
        $this->builder()->set('conf_active', $nPlayerId);

        $this->builder()->where('conf_id', 13);
        
        return $this->builder()->update();
    }
    public function getBetSite($nLevel){

        $nConfigId = 11;
        $arrSiteInfo = ["", "", "", "", "", "" ];

        $objConfig = $this->asObject()->where(array('conf_id'=>$nConfigId))->first();
        if(!is_null($objConfig) && $nLevel >= LEVEL_ADMIN){
            $strSite = $objConfig->conf_content;
            $arrSiteInfo = explode('/', $strSite);
            if(count($arrSiteInfo) == 6){
                if($nLevel == LEVEL_ADMIN){
                    $arrSiteInfo[0] = "";
                } 
                return $arrSiteInfo;
            }
        }
        return $arrSiteInfo;
    }


    public function setBetSite($nAdminLev, $arrReqData){
        if($nAdminLev < LEVEL_ADMIN)
            return false;

        if($nAdminLev == LEVEL_ADMIN){
            $arrSiteInfo = $this->getBetSite(LEVEL_ADMIN + 1);
            if(strlen($arrSiteInfo[0]) > 0)
                $arrReqData['site'] = $arrSiteInfo[0];
            else return false; 
        }
        $strContent = "";
        if(strlen($arrReqData['site'])<1) return false;
        $strContent .= $arrReqData['site']."/";
        
        if(strlen($arrReqData['userid'])<1) return false;
        $strContent .= $arrReqData['userid']."/";
        
        if(strlen($arrReqData['userpwd'])<1) return false;
        $strContent .= $arrReqData['userpwd']."/";
        
        if(strlen($arrReqData['pball'])<1) return false;
        $strContent .= $arrReqData['pball']."/";
        
        if(strlen($arrReqData['pladder'])<1) return false;
        $strContent .= $arrReqData['pladder']."/";

        if(strlen($arrReqData['kladder'])<1) return false;
        $strContent .= $arrReqData['kladder'];

        $this->builder()->set('conf_content', $strContent);

        $this->builder()->where('conf_id', 11);
        
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
        $updateData['conf_id'] = 1;
        $updateData['conf_content'] = $arrData['sitename'];
        $arrBatch[0] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = 2;
        $updateData['conf_content'] = $arrData['domainname'];
        $arrBatch[1] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = 3;
        $updateData['conf_content'] = $arrData['homepage'];
        $arrBatch[2] = $updateData;
        
        $updateData = array();
        $updateData['conf_id'] = 4;
        $updateData['conf_content'] = $arrData['adminpage'];
        $arrBatch[3] = $updateData;
        /*
        $updateData = array();
        $updateData['conf_id'] = 5;
        $updateData['conf_content'] = $arrData['mainnotice'];
        $updateData['conf_active'] = $arrData['mainnoticeok'];
        $arrBatch[4] = $updateData;
        */
        $updateData = array();
        $updateData['conf_id'] = 6;
        $updateData['conf_content'] = $arrData['depositenotice'];
        $arrBatch[5] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = 7;
        $updateData['conf_content'] = $arrData['withdrawnotice'];
        $arrBatch[6] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = 8;
        $updateData['conf_content'] = $arrData['bank'];
        $arrBatch[7] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = 9;
        $updateData['conf_content'] = $arrData['bankmacro'];
        $arrBatch[8] = $updateData;


        return  $this->builder()->updateBatch($arrBatch, 'conf_id');

    }


    public function saveMaintainConfig($arrData){

        if($arrData == null) return false;
        if (!array_key_exists("maintain", $arrData)) return false;
        if (!array_key_exists("content", $arrData)) return false;
        
        $arrBatch = array();
        
        $updateData['conf_id'] = 10;
        $updateData['conf_content'] = $arrData['content'];
        $updateData['conf_active'] = $arrData['maintain'];
        $arrBatch[0] = $updateData;
        return $this->builder()->updateBatch($arrBatch, 'conf_id');
    }

    public function getSoundConf(){

        
        $arrSoundData = array();

        for($i=0 ; $i<4; $i++){

            $arrSound = ["", 0];

            $nConfigId = 15 + $i;    
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
            
            $arrUpdateData['conf_id'] = 15+$i;
            $arrUpdateData['conf_content'] = $arrSoundData[$i][0];
            $arrUpdateData['conf_active'] = $arrSoundData[$i][1];
            array_push($arrBatch, $arrUpdateData);

            
        }
       
        return  $this->builder()->updateBatch($arrBatch, 'conf_id');
            
    }
}