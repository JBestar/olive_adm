<?php
namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class Charge_Model extends Model 
{
    protected $table = 'member_charge';
    protected $allowedFields = [
        'charge_emp_fid', 
        'charge_mb_uid', 
        'charge_mb_realname', 
        'charge_mb_phone', 
        'charge_money', 
        'charge_time_require', 
        'charge_action_state', 
        'charge_action_uid',
        'charge_time_process', 
        'charge_money_bonnus', 
        'charge_money_before', 
        'charge_money_after', 
        'charge_state_delete', 
        'charge_client_delete', 
        'charge_alarm_state',
    ];
    protected $primaryKey = 'charge_fid';
    private $mMemberTable = 'member';

    function gets(){
    	$strSql = "SELECT ".$this->table.".*, member.mb_nickname, member.mb_money FROM ".$this->table;
    	$strSql .= " JOIN member ON ".$this->table.".charge_mb_uid = member.mb_uid ";
    	$strSql .= " WHERE charge_state_delete = '0' ";
    	$strSql .= " ORDER BY charge_fid DESC ";
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result;  
    }

    function get($strChargeFid){
        return $this->asObject()->where('charge_fid', $strChargeFid)->first();
    }

	function deleteState($strChargeFid, $bDelete){

		$this->builder()->set('charge_state_delete', $bDelete?1:0);
		$this->builder()->where('charge_fid', $strChargeFid);
    	return $this->builder()->update();
    }

    function permit($objCharge){

		$this->builder()->set('charge_action_state', $objCharge->charge_action_state);
		$this->builder()->set('charge_action_uid', $objCharge->charge_action_uid);
		$this->builder()->set('charge_time_process', 'NOW()', false);
		$this->builder()->set('charge_money_after', $objCharge->charge_money_after);

		$this->builder()->where('charge_fid', $objCharge->charge_fid);
    	return $this->builder()->update();
    }

     function getWaitCnt(){
        $strSql = "SELECT COUNT(*)  AS charge_wait_cnt FROM ".$this->table;
        $strSql .= " JOIN member ON ".$this->table.".charge_mb_uid = member.mb_uid ";
        $strSql .= " WHERE charge_action_state = '1'  AND charge_state_delete = '0' ";
        
        $objResult = $this-> db -> query($strSql)->getRow();
        if(is_null($objResult->charge_wait_cnt)) return 0;
        else return  $objResult->charge_wait_cnt;
    }

    //일충전금액
    function calcAdminCharge($arrReqData){
        $strSQL = "SELECT SUM(charge_money) AS charge_sum FROM ".$this->table;
        $strSQL.=" WHERE charge_action_state = '2' ";
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $strSQL.=" AND charge_time_require >= '".$arrReqData['start']." 0:0:0' AND charge_time_require <= '".$arrReqData['end']." 23:59:59'" ; 
        }
        
        $objResult = $this -> db -> query($strSQL)->getRow();
        if(is_null($objResult->charge_sum)) return 0;
        else return  $objResult->charge_sum;        
    }  

    //충전금액 (하부포함)
    function calcChargeMoney($objEmp, $arrReqData){

        $strTbColum = " mb_fid, mb_uid, mb_emp_fid ";
        $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_emp_fid ";

        $strSQL = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
        $strSQL .= " ( SELECT ".$strTbColum." FROM ".$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
        $strSQL .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mMemberTable." r ";
        $strSQL .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";

        $strSQL .= " SELECT SUM(charge_money) AS charge_money";
        $strSQL .="  FROM (SELECT  * FROM tbmember UNION SELECT ".$strTbColum." FROM ".$this->mMemberTable." where mb_fid='".$objEmp->mb_fid."'";        
        $strSQL .=" ) AS mb_table ";
        
        $strSQL .= " JOIN (SELECT SUM(charge_money) AS charge_money, charge_mb_uid FROM ".$this->table;
        $strSQL.=" WHERE charge_action_state = '2' ";
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 )
            $strSQL.=" AND charge_time_require >= '".$arrReqData['start']." 0:0:0' AND charge_time_require <= '".$arrReqData['end']." 23:59:59' " ;
        $strSQL .= " GROUP BY charge_mb_uid";
        $strSQL .= " )AS charge_table ON charge_table.charge_mb_uid = mb_table.mb_uid ";      

         $objResult = $this -> db -> query($strSQL)->getRow();

         $nTotalCharge = 0;
         if(!is_null($objResult->charge_money)) $nTotalCharge += $objResult->charge_money;

         return $nTotalCharge;
         /*
         $objResult = null;
         //Own Charge
         $strSQL = "SELECT SUM(charge_money) AS charge_money FROM member_charge WHERE charge_mb_uid = '".$objEmp->mb_uid."'";
         if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 )
            $strSQL.=" AND charge_time_require >= '".$arrReqData['start']." 0:0:0' AND charge_time_require <= '".$arrReqData['end']." 23:59:59' " ;
        
         $objResult = $this -> db -> query($strSql)->getRow();
         if(!is_null($objResult->charge_money)) $nTotalCharge += $objResult->charge_money;
        */
         
    }


    function search($arrReqData)
    {
        $strSql = "SELECT ".$this->table.".*, member.mb_nickname, member.mb_money FROM ".$this->table;
        $strSql .= " JOIN member ON ".$this->table.".charge_mb_uid = member.mb_uid ";
        $strSql .= " WHERE charge_state_delete = '0' ";
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $strSql.=" AND charge_time_require >= '".$arrReqData['start']." 0:0:0' AND charge_time_require <= '".$arrReqData['end']." 23:59:59'" ; 
        }
        if(strlen($arrReqData['mb_uid']) > 0){
            $strSql.=" AND charge_mb_uid = '".$arrReqData['mb_uid']."' ";
        }
        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;

        $strSql.=" ORDER BY charge_fid DESC LIMIT ".$nStartRow.", ".$arrReqData['count'];

        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result; 

    }


    function searchCount($arrReqData)
    {
        $strSql = "SELECT count(*) as count FROM ".$this->table;
        $strSql .= " WHERE charge_state_delete = '0' ";
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $strSql.=" AND charge_time_require >= '".$arrReqData['start']." 0:0:0' AND charge_time_require <= '".$arrReqData['end']." 23:59:59'" ; 
        }
        if(strlen($arrReqData['mb_uid']) > 0){
            $strSql.=" AND charge_mb_uid = '".$arrReqData['mb_uid']."' ";
        }
        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 

    }


}