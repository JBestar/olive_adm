<?php
namespace App\Models;
use CodeIgniter\Model;

class TransferHistory_model extends Model {
	
	protected $table = 'transfer_history';
    protected $allowedFields = [
        'money_mb_fid', 
        'money_mb_uid', 
        'money_mb_emp_fid', 
        'money_amount', 
        'money_site_before', 
        'money_site_after', 
        'money_live_before', 
        'money_live_after', 
        'money_change_type', 
        'money_update_time',
    ];
    protected $primaryKey = 'money_fid';
    private $mMemberTable  = 'member';


    public function getByUserFid($strUserFId){
        $this->builder()->where('money_mb_fid', $strUserFId);
        
        $this->builder()->limit(20);
        $this->builder()->orderBy('money_update_time', 'DESC');
        return $this->builder()->get()->getResult();
    }



    function search($objEmp, $arrReqData)
    {
        $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid, mb_nickname, mb_money, mb_live_money";
        $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid , r.mb_nickname, r.mb_money, r.mb_live_money ";

        $strSql = "";
        if($objEmp->mb_level < LEVEL_ADMIN){
            $strSql = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSql .= " ( SELECT ".$strTbColum." FROM ".$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
            $strSql .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mMemberTable." r ";
            $strSql .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";

            $strSql .= "SELECT ".$this->table.".*, mb_table.mb_nickname, mb_table.mb_money, mb_table.mb_live_money FROM ".$this->table;
            $strSql .="  JOIN (SELECT  * FROM tbmember UNION SELECT ".$strTbColum." FROM ".$this->mMemberTable." where mb_fid='".$objEmp->mb_fid."'";           
            $strSql .=" ) AS mb_table ";
            $strSql .=" ON ".$this->table.".money_mb_uid = mb_table.mb_uid ";
        } else {
            $strSql .= "SELECT ".$this->table.".*, member.mb_nickname, member.mb_money, member.mb_live_money FROM ".$this->table;
            $strSql .="  JOIN member ";
            $strSql .=" ON ".$this->table.".money_mb_uid = member.mb_uid ";
        }
        $bWhere = false;

        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $strSql.=" WHERE money_update_time >= '".$arrReqData['start']." 0:0:0' AND money_update_time <= '".$arrReqData['end']." 23:59:59'" ;
            $bWhere = true;            
        }
        if(strlen($arrReqData['user']) > 0){
            
            if($bWhere) $strSql.= " AND ";
            else $strSql.= " WHERE ";
            $strSql.=" money_mb_uid = '".$arrReqData['user']."' ";
            $bWhere = true;
        }
        if((int)$arrReqData['mode'] > 0){
            if($bWhere) $strSql.= " AND ";
            else $strSql.= " WHERE ";
            $strSql.=" money_change_type = '".$arrReqData['mode']."' ";
        }

        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
        $strSql.=" ORDER BY money_fid DESC LIMIT ".$nStartRow.", ".$arrReqData['count'];
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result; 

    }


    function searchCount($objEmp, $arrReqData)
    {
        $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid";
        $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid ";
        $strSql = "";
        if($objEmp->mb_level < LEVEL_ADMIN){
            $strSql = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSql .= " ( SELECT ".$strTbColum." FROM ".$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
            $strSql .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mMemberTable." r ";
            $strSql .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";
        }
        $strSql .= "SELECT count(*) as count  FROM ".$this->table;
        if($objEmp->mb_level < LEVEL_ADMIN){
            $strSql .="  JOIN (SELECT  * FROM tbmember UNION SELECT ".$strTbColum." FROM ".$this->mMemberTable." where mb_fid='".$objEmp->mb_fid."'";           
            $strSql .=" ) AS mb_table ";
            $strSql .=" ON ".$this->table.".money_mb_uid = mb_table.mb_uid ";
        }
        $bWhere = false;

        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $strSql.=" WHERE money_update_time >= '".$arrReqData['start']." 0:0:0' AND money_update_time <= '".$arrReqData['end']." 23:59:59'" ;
            $bWhere = true;            
        }
        if(strlen($arrReqData['user']) > 0){
            
            if($bWhere) $strSql.= " AND ";
            else $strSql.= " WHERE ";
            $strSql.=" money_mb_uid = '".$arrReqData['user']."' ";
            $bWhere = true;
        }
        if((int)$arrReqData['mode'] > 0){
            if($bWhere) $strSql.= " AND ";
            else $strSql.= " WHERE ";
            $strSql.=" money_change_type = '".$arrReqData['mode']."' ";
        }

        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 

    }



}