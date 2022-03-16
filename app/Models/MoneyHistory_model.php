<?php
namespace App\Models;
use CodeIgniter\Model;

class MoneyHistory_model extends Model 
{
    protected $table = 'money_history';
    protected $allowedFields = [
        'money_mb_fid', 
        'money_mb_uid', 
        'money_mb_emp_fid', 
        'money_amount', 
        'money_before', 
        'money_after', 
        'money_change_type', 
        'money_bet_round', 
        'money_bet_mode', 
        'money_bet_target', 
        'money_update_time'
    ];
    protected $primaryKey = 'money_fid';
    private $mMemberTable = 'member';

    


    function registerCharge($objUser, $nChargeMoney)
    {
        if(is_null($objUser)) return false;    
        if($nChargeMoney < 1) return false;

        $this->builder()->set('money_mb_fid', $objUser->mb_fid);
        $this->builder()->set('money_mb_uid', $objUser->mb_uid);
        $this->builder()->set('money_mb_emp_fid', $objUser->mb_emp_fid);        
        $this->builder()->set('money_amount', $nChargeMoney);
        $this->builder()->set('money_before', $objUser->mb_money);
        $this->builder()->set('money_after', $objUser->mb_money+$nChargeMoney);
        $this->builder()->set('money_change_type', 1);     //충전일때
        $this->builder()->set('money_update_time', 'NOW()', false);
        
        return $this->builder()->insert();
    }

    function cancelCharge($objUser, $nChargeMoney)
    {
        if(is_null($objUser)) return false;    
        if($nChargeMoney < 1) return false;

        $this->builder()->set('money_mb_fid', $objUser->mb_fid);
        $this->builder()->set('money_mb_uid', $objUser->mb_uid);
        $this->builder()->set('money_mb_emp_fid', $objUser->mb_emp_fid);        
        $this->builder()->set('money_amount', (-1) * $nChargeMoney);
        $this->builder()->set('money_before', $objUser->mb_money);
        $this->builder()->set('money_after', $objUser->mb_money-$nChargeMoney);
        $this->builder()->set('money_change_type', 21);     //충전취소일때
        $this->builder()->set('money_update_time', 'NOW()', false);
        
        return $this->builder()->insert();
    }
    
    function registerExchange($objUser, $nExchangeMoney)
    {
        if(is_null($objUser)) return false;    
        if($nExchangeMoney < 1) return false;

        $this->builder()->set('money_mb_fid', $objUser->mb_fid);
        $this->builder()->set('money_mb_uid', $objUser->mb_uid);
        $this->builder()->set('money_mb_emp_fid', $objUser->mb_emp_fid);        
        $this->builder()->set('money_amount', (-1) * $nExchangeMoney);
        $this->builder()->set('money_before', $objUser->mb_money);
        $this->builder()->set('money_after', $objUser->mb_money-$nExchangeMoney);
        $this->builder()->set('money_change_type', 2);     //환전일때
        $this->builder()->set('money_update_time', 'NOW()', false);
        
        return $this->builder()->insert();
    }

    
    function cancelExchange($objUser, $nExchangeMoney)
    {
        if(is_null($objUser)) return false;    
        if($nExchangeMoney < 1) return false;

        $this->builder()->set('money_mb_fid', $objUser->mb_fid);
        $this->builder()->set('money_mb_uid', $objUser->mb_uid);
        $this->builder()->set('money_mb_emp_fid', $objUser->mb_emp_fid);        
        $this->builder()->set('money_amount', $nExchangeMoney);
        $this->builder()->set('money_before', $objUser->mb_money);
        $this->builder()->set('money_after', $objUser->mb_money+$nExchangeMoney);
        $this->builder()->set('money_change_type', 22);     //환전취소일때
        $this->builder()->set('money_update_time', 'NOW()', false);
        
        return $this->builder()->insert();
    }

    function registerAccountBet($objUser, $objBetInfo, $nMoney, $iType)
    {
        
        if(is_null($objUser)) return false;    
        
        $this->builder()->set('money_mb_fid', $objUser->mb_fid);
        $this->builder()->set('money_mb_uid', $objUser->mb_uid);
        $this->builder()->set('money_mb_emp_fid', $objUser->mb_emp_fid);        
        $this->builder()->set('money_amount', $nMoney);
        $this->builder()->set('money_before', $objUser->mb_money);
        $this->builder()->set('money_after', $objUser->mb_money+$nMoney);
        $this->builder()->set('money_change_type', $iType);     //정산 6,9,12
        $this->builder()->set('money_bet_round', $objBetInfo->bet_round_no);
        $this->builder()->set('money_bet_mode', $objBetInfo->bet_mode);
        $this->builder()->set('money_bet_target', $objBetInfo->bet_target);        
        $this->builder()->set('money_update_time', 'NOW()', false);
        
        return $this->builder()->insert();        
    }
    function search($objEmp, $arrReqData)
    {
        $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid, mb_nickname, mb_money";
        $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid , r.mb_nickname, r.mb_money";

        $strSql = "";
        if($objEmp->mb_level < LEVEL_ADMIN){
            $strSql = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSql .= " ( SELECT ".$strTbColum." FROM ".$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
            $strSql .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mMemberTable." r ";
            $strSql .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";

            $strSql .= "SELECT ".$this->table.".*, mb_table.mb_nickname, mb_table.mb_money FROM ".$this->table;
            $strSql .="  JOIN (SELECT  * FROM tbmember UNION SELECT ".$strTbColum." FROM ".$this->mMemberTable." where mb_fid='".$objEmp->mb_fid."'";           
            $strSql .=" ) AS mb_table ";
            $strSql .=" ON ".$this->table.".money_mb_uid = mb_table.mb_uid ";
        } else {
            $strSql .= "SELECT ".$this->table.".*, member.mb_nickname, member.mb_money FROM ".$this->table;
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