<?php
namespace App\Models;
use CodeIgniter\Model;

class MoneyHistory_Model extends Model 
{
    protected $table = 'money_history';
    protected $returnType = 'object'; 
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
        $this->builder()->set('money_before', allMoney($objUser));
        $this->builder()->set('money_after', allMoney($objUser)+$nChargeMoney);
        $this->builder()->set('money_change_type', MONEYCHANGE_CHARGE);     //충전일때
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
        $this->builder()->set('money_before', allMoney($objUser));
        $this->builder()->set('money_after', allMoney($objUser)-$nChargeMoney);
        $this->builder()->set('money_change_type', MONEYCANCEL_CHARGE);     //충전취소일때
        $this->builder()->set('money_update_time', 'NOW()', false);
        
        return $this->builder()->insert();
    }
    
    function registerExchange($objUser, $objExchange)
    {
        if(is_null($objUser)) return false;    
        if(is_null($objExchange)) return false;

        $this->builder()->set('money_mb_fid', $objUser->mb_fid);
        $this->builder()->set('money_mb_uid', $objUser->mb_uid);
        $this->builder()->set('money_mb_emp_fid', $objUser->mb_emp_fid);        
        $this->builder()->set('money_amount', (-1) * $objExchange->exchange_money);
        $this->builder()->set('money_before', $objExchange->exchange_money_before);
        $this->builder()->set('money_after', $objExchange->exchange_money_after);
        $this->builder()->set('money_change_type', MONEYCHANGE_EXCHANGE);     //환전일때
        // $this->builder()->set('money_update_time', 'NOW()', false);
        $this->builder()->set('money_update_time', $objExchange->exchange_time_require);
        
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
        $this->builder()->set('money_before', allMoney($objUser));
        $this->builder()->set('money_after', allMoney($objUser)+$nExchangeMoney);
        $this->builder()->set('money_change_type', MONEYCANCEL_EXCHANGE);     //환전취소일때
        $this->builder()->set('money_update_time', 'NOW()', false);
        
        return $this->builder()->insert();
    }

    function registerTransfer($objUser, $userId, $nAmount, $type)
    {
        if(is_null($objUser)) return false;

        $this->builder()->set('money_mb_fid', $objUser->mb_fid);
        $this->builder()->set('money_mb_uid', $objUser->mb_uid);
        $this->builder()->set('money_mb_emp_fid', $objUser->mb_emp_fid);        
        $this->builder()->set('money_amount', $nAmount);
        $this->builder()->set('money_before', allMoney($objUser));
        $this->builder()->set('money_after', allMoney($objUser)+$nAmount);
        $this->builder()->set('money_change_type', $type);   
        $this->builder()->set('money_bet_target', $userId);     
        $this->builder()->set('money_update_time', 'NOW()', false);
        
        return $this->builder()->insert();
    }

    function registerWithdraw($objUser, $userId, $nAmount, $type)
    {
        if(is_null($objUser)) return false;

        $this->builder()->set('money_mb_fid', $objUser->mb_fid);
        $this->builder()->set('money_mb_uid', $objUser->mb_uid);
        $this->builder()->set('money_mb_emp_fid', $objUser->mb_emp_fid);        
        $this->builder()->set('money_amount', $nAmount);
        if($type == MONEYCHANGE_WITHDRAW)
        {
            $this->builder()->set('money_before', allMoney($objUser));
            $this->builder()->set('money_after', allMoney($objUser)+$nAmount);
        } else if($type == POINTHANGE_WITHDRAW)
        {
            $this->builder()->set('money_before', $objUser->mb_point);
            $this->builder()->set('money_after', $objUser->mb_point+$nAmount);
        } else return false;
        
        $this->builder()->set('money_change_type', $type);   
        $this->builder()->set('money_bet_target', $userId);     
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
        $this->builder()->set('money_before', allMoney($objUser));
        $this->builder()->set('money_after', allMoney($objUser)+$nMoney);
        $this->builder()->set('money_change_type', $iType);     //정산 6,9,12
        $this->builder()->set('money_bet_round', $objBetInfo->bet_round_no);
        $this->builder()->set('money_bet_mode', $objBetInfo->bet_mode);
        $this->builder()->set('money_bet_target', $objBetInfo->bet_target);        
        $this->builder()->set('money_update_time', 'NOW()', false);
        
        return $this->builder()->insert();        
    }
    
    function registerAccountCsBet($objUser, $objBetInfo, $nMoney, $iType)
    {
        
        if(is_null($objUser)) return false;    
        
        $this->builder()->set('money_mb_fid', $objUser->mb_fid);
        $this->builder()->set('money_mb_uid', $objUser->mb_uid);
        $this->builder()->set('money_mb_emp_fid', $objUser->mb_emp_fid);        
        $this->builder()->set('money_amount', $nMoney);
        $this->builder()->set('money_before', allMoney($objUser));
        $this->builder()->set('money_after', allMoney($objUser)+$nMoney);
        $this->builder()->set('money_change_type', $iType);     
        $this->builder()->set('money_bet_round', $objBetInfo->bet_round_no);
        $this->builder()->set('money_bet_target', $objBetInfo->bet_table_code);        
        $this->builder()->set('money_update_time', 'NOW()', false);
        
        return $this->builder()->insert();        
    }

    public function getRangeId($arrReqData){
        $range = [-1, -1];
        
        $strCond = ""; 
        $strCond .=" WHERE money_update_time >= '".$arrReqData['start']." 00:00:00' AND money_update_time <= '".$arrReqData['end']." 23:59:59'" ;
        
        $strSQL = " SELECT MIN(money_fid) AS min_fid, MAX(money_fid) AS max_fid FROM ".$this->table;
        $strSQL.= $strCond; 

        // writeLog($strSQL);
        $objResult = $this->db->query($strSQL)->getRow();
        // writeLog("getRangeId END");

        if (!is_null($objResult->min_fid) && !is_null($objResult->max_fid)) {
            $range[0] = $objResult->min_fid;
            $range[1] = $objResult->max_fid;
         }
         return $range;
     }

    function search($objEmp, $arrReqData)
    {
        $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid, mb_nickname, mb_money, mb_live_money, mb_slot_money, mb_fslot_money, mb_kgon_money, mb_gslot_money ";
        $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid , r.mb_nickname, r.mb_money, r.mb_live_money, r.mb_slot_money, r.mb_fslot_money, r.mb_kgon_money, r.mb_gslot_money ";

        $strSql = "";
        if($objEmp->mb_level < LEVEL_ADMIN){
            $strSql = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSql .= " ( SELECT ".$strTbColum." FROM ".$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
            $strSql .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mMemberTable." r ";
            $strSql .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";

            $strSql .= "SELECT * FROM ".$this->table;

            // $strSql .= "SELECT ".$this->table.".*, mb_table.mb_nickname, mb_table.mb_money, mb_table.mb_live_money, mb_table.mb_slot_money, mb_table.mb_fslot_money, mb_table.mb_kgon_money, mb_table.mb_gslot_money FROM ".$this->table;
            // $strSql .="  JOIN (SELECT  * FROM tbmember UNION SELECT ".$strTbColum." FROM ".$this->mMemberTable." where mb_fid='".$objEmp->mb_fid."'";           
            // $strSql .=" ) AS mb_table ";
            // $strSql .=" ON ".$this->table.".money_mb_fid = mb_table.mb_fid ";
        } else {
            $strSql .= "SELECT * FROM ".$this->table;
            // $strSql .= "SELECT ".$this->table.".*, member.mb_nickname, member.mb_money, member.mb_live_money, member.mb_slot_money, member.mb_fslot_money, member.mb_kgon_money, member.mb_gslot_money FROM ".$this->table;
            // $strSql .="  LEFT JOIN member ";
            // $strSql .=" ON ".$this->table.".money_mb_fid = member.mb_fid ";
        }

        $rangeIds = $this->getRangeId($arrReqData);

        // if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            // $strSql.=" WHERE money_update_time >= '".$arrReqData['start']." 0:0:0' AND money_update_time <= '".$arrReqData['end']." 23:59:59'" ;
            $strSql.=" WHERE money_fid >= ".$rangeIds[0]." AND money_fid <= ".$rangeIds[1]." " ;
        // }
        if(strlen($arrReqData['user']) > 0){
            $strSql.=" AND money_mb_fid = '".$arrReqData['user']."' ";
        }
        if(intval($arrReqData['mode']) > 0){
            $strSql.=" AND money_change_type = '".$arrReqData['mode']."' ";
        }
        if($objEmp->mb_level < LEVEL_ADMIN){
            // $strSql.=" AND money_change_type <= '".MONEYCHANGE_WIN_CO3."' ";
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
        $strSql .= "SELECT count(money_fid) as count  FROM ".$this->table;
        if($objEmp->mb_level < LEVEL_ADMIN){
            $strSql .="  JOIN (SELECT  * FROM tbmember UNION SELECT ".$strTbColum." FROM ".$this->mMemberTable." where mb_fid='".$objEmp->mb_fid."'";           
            $strSql .=" ) AS mb_table ";
            $strSql .=" ON ".$this->table.".money_mb_fid = mb_table.mb_fid ";
        }
        $rangeIds = $this->getRangeId($arrReqData);

        // if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            // $strSql.=" WHERE money_update_time >= '".$arrReqData['start']." 0:0:0' AND money_update_time <= '".$arrReqData['end']." 23:59:59'" ;
        // }
        $strSql.=" WHERE money_fid >= ".$rangeIds[0]." AND money_fid <= ".$rangeIds[1] ;
        if(strlen($arrReqData['user']) > 0){
            $strSql.=" AND money_mb_fid = '".$arrReqData['user']."' ";
        }
        if((int)$arrReqData['mode'] > 0){
            $strSql.=" AND money_change_type = '".$arrReqData['mode']."' ";
        }
        if($objEmp->mb_level < LEVEL_ADMIN){
            // $strSql.=" AND money_change_type <= '".MONEYCHANGE_WIN_CO3."' ";
        }
        
        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();

        return $result; 

    }



}