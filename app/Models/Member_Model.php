<?php 
namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class Member_Model extends Model {

    
    private $mDb;
    private $mBuilder;
    private $mTbName = "member";
    private $mTbColumn;
    

    function __construct()
    {
        //parent::__construct();        
        $this->mDb      = Database::connect();
        $this->mBuilder = $this->mDb->table($this->mTbName);
        $this->mTbColumn = ['mb_fid', 'mb_uid', 'mb_level', 'mb_emp_fid', 'mb_nickname', 'mb_email', 'mb_phone',
            'mb_time_join', 'mb_time_last', 'mb_ip_last', 'mb_color', 'mb_state_active', 'mb_state_alarm'];
        
    }

    public function getByUid($uid){
        
        try {             
            $this->mBuilder ->select($this->mTbColumn)
                            ->where('mb_uid', $uid)
                            ->getCompiledSelect(false);
            $query = $this->mBuilder->get();
            return $query->getRow();
            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }

    public function getByFid($fid){
        
        try {
            if(!in_array('mb_pwd', $this->mTbColumn))
                array_push($this->mTbColumn, 'mb_pwd');     

            $this->mBuilder ->select($this->mTbColumn)
                            ->where('mb_fid', $fid)
                            ->getCompiledSelect(false);
            $query = $this->mBuilder->get();
            return $query->getRow();
            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }

    
    public function getByName($mb_name, $mb_fid = 0){
        
        try { 
            $where = "mb_nickname = '".$mb_name."' ";
            if($mb_fid > 0)
                $where.= "AND mb_fid != '".$mb_fid."' ";

            $this->mBuilder ->select($this->mTbColumn)
                            ->where($where)
                            ->getCompiledSelect(false);
            $query = $this->mBuilder->get();
            return $query->getRow();
            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }

    
    public function getAllMember($level, $search="", $bLow=false, $page=0, $cntPer=20){
        
        try { 

            $where = 'mb_level' ;
            $where .= $bLow ? '<' : '=' ;
            $where .= "'".$level."' ";
            if(strlen($search) > 0){
                $where .= " AND (mb_uid LIKE '%".$search."%' OR mb_nickname LIKE '%".$search."%')";
            }

            $this->mBuilder ->select($this->mTbColumn)
                            ->orderBy('mb_uid', 'ASC')
                            ->where($where)
                            ->getCompiledSelect(false);

            if($page > 0){
                $query = $this->mBuilder->get($cntPer, $cntPer*($page-1));
            } else $query = $this->mBuilder->get();
            
            return $query->getResult();
            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }

    public function getMemberByEmp($emp_fid, $mb_level, $emp_level, $search="", $bLow=false, $page=0, $cntPer=20){

        if($emp_level > LEVEL_COMPANY)
        {
            return $this->getAllMember($mb_level, $search, $bLow, $page, $cntPer);
        } else {
            
            $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid, mb_nickname, mb_time_join, mb_time_last, 
                        mb_color, mb_state_active";


            $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid, r.mb_nickname, r.mb_time_join, r.mb_time_last,  
                        r.mb_color, r.mb_state_active";


            $strSQL = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSQL .= " ( SELECT ".$strTbColum." FROM ".$this->mTbName." WHERE mb_emp_fid = '".$emp_fid."'";
            $strSQL .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mTbName." r ";
            $strSQL .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";
            $strSQL .= " SELECT * FROM tbmember WHERE ";
            
            $strSQL .= 'mb_level' ;
            $strSQL .= $bLow ? '<' : '=' ;
            $strSQL .= "'".$mb_level."' ";

            if(strlen($search) > 0){
                $strSQL .= "AND (mb_uid LIKE '%".$search."%' OR mb_nickname LIKE '%".$search."%')";
            }
            
            $strSQL .= " ORDER BY mb_uid  ASC ";
            if($page > 0){
                $nStart = ($page-1) * $cntPer ;
                $strSQL .= " LIMIT ".$nStart.", ".$cntPer;

            }

            return $this->mDb->query($strSQL)->getResult();
        }

    }

    function register($arrData){
        
        if(!array_key_exists('mb_uid', $arrData))
            return RESULT_ERROR;
        $objMember = $this->getByUid($arrData['mb_uid']);

        if(!is_null($objMember))
            return RESULT_EXIST_ID;
        
        if(!array_key_exists('mb_name', $arrData))
            return RESULT_ERROR;
        $objMember = $this->getByName($arrData['mb_name']);

        if(!is_null($objMember))
            return RESULT_EXIST_NAME;

        if(!array_key_exists('mb_emp_uid', $arrData))
            return RESULT_ERROR;
        else if(strlen($arrData['mb_emp_uid']) > 0){
            $objAdmin = $this->getByUid($arrData['mb_emp_uid']);
            
            if(is_null($objAdmin))
                return RESULT_EMP_ERROR;
            else if($objAdmin->mb_level == LEVEL_EMPLOYEE){
                $arrData['mb_level'] = LEVEL_USER ; 
                $arrData['mb_emp_fid'] = $objAdmin->mb_fid;
            }    
            else if($objAdmin->mb_level == LEVEL_AGENCY || $objAdmin->mb_level == LEVEL_COMPANY){
                $arrData['mb_level'] = $objAdmin->mb_level - 1;
                $arrData['mb_emp_fid'] = $objAdmin->mb_fid;
            }
            else return RESULT_EMP_ERROR;

        } else return RESULT_ERROR;        

        if(strlen($arrData['mb_pwd']) == 0)
            return RESULT_ERROR;

        $this->mBuilder->set('mb_uid', $arrData['mb_uid']);
        $this->mBuilder->set('mb_pwd', $arrData['mb_pwd']);
        $this->mBuilder->set('mb_level', $arrData['mb_level']);
        $this->mBuilder->set('mb_emp_fid', $arrData['mb_emp_fid']);
        $this->mBuilder->set('mb_nickname', $arrData['mb_name']);
        $this->mBuilder->set('mb_time_join', 'NOW()', false);
        if(array_key_exists('mb_phone', $arrData))
            $this->mBuilder->set('mb_phone', $arrData['mb_phone']);
        if(array_key_exists('mb_color', $arrData))        
            $this->mBuilder->set('mb_color', $arrData['mb_color']);        
        $this->mBuilder->set('mb_state_active', PERMIT_WAIT);
        
        if($this->mBuilder->insert())   //if success, return true
            return RESULT_OK;
        return RESULT_FAIL;
    }

    function modifyByFid($mb_fid, $arrData){
        
        
        $objMember = $this->getByName($arrData['mb_name'], $mb_fid);

        if(is_null($objMember))
            $this->mBuilder->set('mb_nickname', $arrData['mb_name']);
        else return RESULT_EXIST_NAME;

        if(!array_key_exists('mb_emp_fid', $arrData))
            return RESULT_ERROR;
        else if(strlen($arrData['mb_emp_fid']) > 0)
            $this->mBuilder->set('mb_emp_fid', $arrData['mb_emp_fid']);
        else return RESULT_ERROR;

        if(strlen($arrData['mb_pwd']) > 0)
            $this->mBuilder->set('mb_pwd', $arrData['mb_pwd']);
        else return RESULT_ERROR;

        $this->mBuilder->set('mb_color', $arrData['mb_color']);
        
        $this->mBuilder->where('mb_fid', $mb_fid);
        if($this->mBuilder->update())   //if success, return true
            return RESULT_OK;
        return RESULT_FAIL;
    }

    function updateByFid($mb_fid, $arrData){
        
        if(array_key_exists("mb_state_active", $arrData))
            $this->mBuilder->set('mb_state_active', $arrData['mb_state_active']);       
        if(array_key_exists("mb_state_alarm", $arrData))
            $this->mBuilder->set('mb_state_alarm', $arrData['mb_state_alarm']);    
        else return false;

        $this->mBuilder->where('mb_fid', $mb_fid);
        return $this->mBuilder->update();   //if success, return true
    }

    public function updateLastTime($uid){

        $this->mBuilder->set('mb_time_last', 'NOW()', false);
        $this->mBuilder->where('mb_uid', $uid);

        return $this->mBuilder->update();   //if success, return true
    }

    function updatePwd($mb_fid, $pwd_new){
        
        $this->mBuilder->set('mb_pwd', $pwd_new);
        
        $this->mBuilder->where('mb_fid', $mb_fid);
        return $this->mBuilder->update();   //if success, return true
    }

    function deleteByFid($mb_fid){
        
        $this->mBuilder->where('mb_fid', $mb_fid);
        return $this->mBuilder->delete();   //if success, return true
    }

    
    function deleteAllByEmp($objMember){
        if(is_null($objMember))
            return false;
        $arrMember = $this->getMemberByEmp($objMember->mb_fid, $objMember->mb_level, $objMember->mb_level, "", true);
        if(!is_null($arrMember)){
            foreach($arrMember as $objChild){
                $this->deleteByFid($objChild->mb_fid);					
            }
        } 
        return $this->deleteByFid($objMember->mb_fid);
    }
    
    public function login($uid, $pwd){
        
        try { 
            $where = "mb_uid = '".$uid."' AND mb_pwd = '".$pwd."' ";

            $this->mBuilder ->select($this->mTbColumn)
                            ->where($where)
                            ->getCompiledSelect(false);
            
            $query = $this->mBuilder->get();
            
            return $query->getRow();
            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }

    function getEmpName($objMember){
        if(is_null($objMember)) return "";

        //9레벨일때
        if($objMember->mb_level >= LEVEL_COMPANY) return $objMember->mb_nickname;

        $strBuf = "";        
        //8레벨일때 
        $objEmp = $this->getByFid($objMember->mb_emp_fid);
        if(is_null($objEmp)) return "";
        $strBuf = $objEmp->mb_nickname; 
        if($objMember->mb_level == LEVEL_AGENCY) return $strBuf."::".$objMember->mb_nickname;

        //7레벨일때 
        $objEmp = $this->getByFid($objEmp->mb_emp_fid);
        if(is_null($objEmp)) return "";
        $strBuf = $objEmp->mb_nickname."::".$strBuf;            
        if($objMember->mb_level == LEVEL_EMPLOYEE) return $strBuf."::".$objMember->mb_nickname;

        //그이하일때 
        $objEmp = $this->getByFid($objEmp->mb_emp_fid);
        if(is_null($objEmp)) return "";
        $strBuf = $objEmp->mb_nickname."::".$strBuf;            
        return $strBuf;

    }

    function getEmpIds($objMember)
    {
        $arrEmpId = [];
        if($objMember->mb_level > LEVEL_EMPLOYEE){
            $arrMember = $this->getMemberByEmp($objMember->mb_fid, LEVEL_EMPLOYEE, $objMember->mb_level);
            if(!is_null($arrMember)){
                foreach($arrMember as $objChild){
                    if(!in_array($objChild->mb_fid, $arrEmpId)){
                        array_push($arrEmpId, $objChild->mb_fid);
                    }
                }
            } 
        }
        else if($objMember->mb_level == LEVEL_EMPLOYEE){
            array_push($arrEmpId, $objMember->mb_fid);
        }
        return $arrEmpId;
    }
    

    function getNamedMember($objMember, $level, $search="", $bLow=false, $page=0, $cntPer=20){

		$arrEmp = [];
		
        if($objMember->mb_level > $level) {
            $arrMember = $this->getMemberByEmp($objMember->mb_fid, $level, $objMember->mb_level, $search, $bLow);
            foreach($arrMember as $objChild){
                
                $objChild->mb_name = $this->getEmpName($objChild);
                array_push($arrEmp, $objChild); 								
            }
        } else if(!$bLow && $objMember->mb_level == $level){      
            $objMember->mb_name = $this->getEmpName($objMember);
            array_push($arrEmp, $objMember);
        }
        return  $arrEmp;
    }

    
    function getCategoryMember($objMember, $level, $search="", $bLow=false){

		$arrEmp = [];
		
        if($objMember->mb_level > $level) {
            $arrMember = $this->getMemberByEmp($objMember->mb_fid, $level, $objMember->mb_level, $search, $bLow);
            foreach($arrMember as $objChild){                
                array_push($arrEmp, $objChild); 								
            }
        } else if(!$bLow && $objMember->mb_level == $level){      
            array_push($arrEmp, $objMember);
        }
        return  $arrEmp;
    }
    
    function getSortMemberNames($objMember, $level){

        
		$arrEmp = [];
		$arrName = [];
        $arrMember = [];

        if($objMember->mb_level > $level) {
            $arrMember = $this->getMemberByEmp($objMember->mb_fid, $level, $objMember->mb_level);
            
            foreach($arrMember as $objChild){

                $objChild->mb_name = $this->getEmpName($objChild);

                if(!in_array($objChild->mb_name, $arrName))
                    array_push($arrName, $objChild->mb_name); 								
            }
        } else if($objMember->mb_level == $level){
            $objMember->mb_name = $this->getEmpName($objMember);
            array_push($arrName, $objMember->mb_name); 	
            array_push($arrMember, $objMember); 								
        }

		sort($arrName);

		foreach($arrName as $name){
			foreach($arrMember as $objChild){
				if($name == $objChild->mb_name){
					$objEmp = new \StdClass;
					$objEmp->mb_fid = $objChild->mb_fid;
					$objEmp->mb_name = $objChild->mb_name;
					if(!in_array($objEmp, $arrEmp))
						array_push($arrEmp, $objEmp);
					break;
				}
			}							
		}

		return $arrEmp;
	}


	function getSortEmpNames($objMember, $bLow=false){

		$arrEmp = [];
		
                  
        if($bLow && $objMember->mb_level > LEVEL_COMPANY){
            $objMember->mb_level = LEVEL_ADMIN;
            $objEmp = new \StdClass;
            $objEmp->mb_fid = $objMember->mb_fid;
            $objEmp->mb_name = "전체";
            array_push($arrEmp, $objEmp);
        } 
    
		
        $arrName = [];
        $arrMember = [];
        if($objMember->mb_level > LEVEL_EMPLOYEE) {
            $arrMember = $this->getMemberByEmp($objMember->mb_fid, $bLow?$objMember->mb_level:LEVEL_EMPLOYEE, $objMember->mb_level, "", $bLow);
            
            foreach($arrMember as $objChild){

                $objChild->mb_name = $this->getEmpName($objChild);

                if(!in_array($objChild->mb_name, $arrName))
                    array_push($arrName, $objChild->mb_name); 								
            }
        } else {
            $objEmp = new \StdClass;
            $objEmp->mb_fid = $objMember->mb_fid;
            $objEmp->mb_name = $this->getEmpName($objMember);
            array_push($arrEmp, $objEmp);            
        }

		sort($arrName);

		foreach($arrName as $name){
			foreach($arrMember as $objChild){
				if($name == $objChild->mb_name){
					$objEmp = new \StdClass;
					$objEmp->mb_fid = $objChild->mb_fid;
					$objEmp->mb_name = $objChild->mb_name;
					if(!in_array($objEmp, $arrEmp))
						array_push($arrEmp, $objEmp);
					break;
				}
			}							
		}

		return $arrEmp;
	}

    
    function isEnableMember($objAdmin, $objMember, $bLarge=false){
        $bPermit = false;
        if($objAdmin->mb_level < $objMember->mb_level)
            $bPermit = false;
        else if($objAdmin->mb_level == $objMember->mb_level){
            if($bLarge)
                $bPermit = false;    
            else if($objMember->mb_fid == $objAdmin->mb_fid)
                $bPermit = true;
            else $bPermit = false;
        } else {
            $arrMember = $this->getMemberByEmp($objAdmin->mb_fid, $objMember->mb_level, $objAdmin->mb_level);
            foreach($arrMember as $objChild){
                if($objChild->mb_fid == $objMember->mb_fid){
                    $bPermit = true;
                    break;
                }
            }
        }			
        return $bPermit;
    }


    function isPermitMember($objMember){
        if(is_null($objMember))
            return false;
        if($objMember->mb_level >= LEVEL_COMPANY && $objMember->mb_state_active == PERMIT_OK)
            return true;
    
        //매장
        $objEmpl = $this->getByFid($objMember->mb_emp_fid);        
        if(is_null($objEmpl))
            return false;
        if($objEmpl->mb_level == LEVEL_EMPLOYEE && $objEmpl->mb_state_active != PERMIT_OK)
            return false;
    
        //총판
        $objAgen = $this->getByFid($objEmpl->mb_emp_fid);        
        if(is_null($objAgen))
            return false;
        if($objAgen->mb_level == LEVEL_AGENCY && $objAgen->mb_state_active != PERMIT_OK)
            return false;

        //부본사
        $objComp = $this->getByFid($objAgen->mb_emp_fid);        
        if(is_null($objComp))
            return false;
        if($objComp->mb_level == LEVEL_COMPANY && $objComp->mb_state_active != PERMIT_OK)
            return false;

        return true;
    }
    



}