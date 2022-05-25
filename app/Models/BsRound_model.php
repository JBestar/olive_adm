<?php
namespace App\Models;
use CodeIgniter\Model;

class BsRound_Model extends Model {
    
    private $mStrPowballRoundURL ;

    protected $table ='round_bogleladder';
    protected $returnType = 'object'; 
    protected $allowedFields = [
        'round_date', 
        'round_num', 
        'round_time', 
        'round_state',
        'round_result_1', 
        'round_result_2', 
        'round_result_3', 
    ];
    protected $primaryKey = 'round_fid';

    function gets($nCount)
    {
        $strSql = "SELECT round_fid, round_date, round_num, round_time, round_state, round_result_1, round_result_2, round_result_3  FROM ".$this->table." ORDER BY round_fid DESC LIMIT 0, ".$nCount;
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result;  

    }

    public function get($nRoundFid){
        
        return $this->where(array('round_fid'=>$nRoundFid))->first();
    }

    public function getByDate($strDate, $nRoundNo){
        if(strlen($strDate) < 1 || $nRoundNo < 1)
            return null;
        
        return $this->where(array('round_num'=>$nRoundNo, 'round_date'=>$strDate))->first();
    }

    public function register($arrReqData){
        //2:이미 등록된 회차 4:회차번호, 일회차오유
        
        $objRound = $this->getByDate($arrReqData['round_date'], $arrReqData['round_num']);
        if(!is_null($objRound))
            return 2;
        
        
        $this->builder()->set('round_date', $arrReqData['round_date']);
        $this->builder()->set('round_num', $arrReqData['round_num']);
        $this->builder()->set('round_time', $arrReqData['round_time']);
        $this->builder()->set('round_state', 1);
        $this->builder()->set('round_result_1', $arrReqData['round_result_1']);
        $this->builder()->set('round_result_2', $arrReqData['round_result_2']);
        $this->builder()->set('round_result_3', $arrReqData['round_result_3']);
        
        $bResult = $this->builder()->insert();
        
        return $bResult?1:0;
    }

    public function modify($arrReqData){
        $this->builder()->set('round_state', 1);
        $this->builder()->set('round_result_1', $arrReqData['round_result_1']);
        $this->builder()->set('round_result_2', $arrReqData['round_result_2']);
        $this->builder()->set('round_result_3', $arrReqData['round_result_3']);
    
        $this->builder()->where('round_fid', $arrReqData['round_fid']);
        return $this->builder()->update();
        
    }


    function search($arrReqData)
    {
        $strSql = "SELECT round_fid, round_date, round_num, round_time, round_state, round_result_1, round_result_2, round_result_3  FROM ".$this->table;
        $bWhere = false;
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $bWhere = true;
            $strSql.=" WHERE round_date >= '".$arrReqData['start']."' AND round_date <= '".$arrReqData['end']."'" ; 
        } 
        if(strlen($arrReqData['round']) > 0){
          if($bWhere) $strSql.=" AND round_num = '".$arrReqData['round']."' ";
          else $strSql.=" WHERE round_num = '".$arrReqData['round']."' ";
        }
        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
        $strSql.=" ORDER BY round_time DESC LIMIT ".$nStartRow.", ".$arrReqData['count'];
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result; 

    }


    function searchCount($arrReqData)
    {
        $strSql = "SELECT count(*) as count  FROM ".$this->table;
        $bWhere = false;
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $bWhere = true;
            $strSql.=" WHERE round_date >= '".$arrReqData['start']."' AND round_date <= '".$arrReqData['end']."'" ; 
        } 
        if(strlen($arrReqData['round']) > 0){
          if($bWhere) $strSql.=" AND round_num = '".$arrReqData['round']."' ";
          else $strSql.=" WHERE round_num = '".$arrReqData['round']."' ";
        }
        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 

    }
}