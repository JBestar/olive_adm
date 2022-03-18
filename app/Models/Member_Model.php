<?php 
namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class Member_Model extends Model {
    protected $table = 'member';
    protected $allowedFields = [
        'mb_uid',
        'mb_level',  
         'mb_emp_fid',
         'mb_emp_permit',
         'mb_nickname',
         'mb_email',
         'mb_phone',
         'mb_bank_name',
         'mb_bank_own',
         'mb_bank_num',
         'mb_bank_pwd',        
         'mb_time_join',
         'mb_time_last',
         'mb_money',
         'mb_point',
         'mb_money_charge',
         'mb_money_exchange',
         'mb_money_bet',        
         'mb_money_earn',
         'mb_color',
         'mb_state_active',
         'mb_state_bet',
         'mb_state_delete',
         'mb_state_alarm',
         'mb_game_pb',
         'mb_game_ps',
         'mb_game_ks',
         'mb_game_bb',
         'mb_game_bs',
         'mb_game_ev',
         'mb_game_pb_ratio',
         'mb_game_pb2_ratio',
         'mb_game_ps_ratio',
         'mb_game_ks_ratio',
         'mb_game_ev_ratio',
         'mb_game_bb_ratio',
         'mb_game_bb2_ratio',
         'mb_game_bs_ratio',
         'mb_game_ev_ratio',
         'mb_live_money',
    ];
    protected $primaryKey = 'mb_fid';
    public function getLiveMaxId()
    {
        $this->builder()->selectMax('mb_live_id', 'mb_live_maxid');
        return $this->builder()->get()->getRow();
    }

    function changePassword($strUserId, $arrPwd){

        if($arrPwd == null) return 0;
        if (!array_key_exists("password", $arrPwd)) return 0;
        if (!array_key_exists("password_new", $arrPwd)) return 0;
        if (!array_key_exists("password_newok", $arrPwd)) return 0;
        
        if(strlen($arrPwd['password_new']) < 1) return 0;
        //이전 패스워드가 같은가 검사 
        if(strcmp($arrPwd['password_new'], $arrPwd['password_newok']) != 0) return 0;

        $objUser = $this->login($strUserId, $arrPwd['password']);
        if($objUser == null) return 2;

        return $this->builder()->set('mb_pwd', $arrPwd['password_new'])
        ->where('mb_fid', $objUser->mb_fid)
        ->update();
    }
    function changeAlarmState($strUserId, $arrReqData){

        if($arrReqData == null) return 0;
        if (!array_key_exists("mb_state_alarm", $arrReqData)) return false;
        
        return $this->builder()->set('mb_state_alarm', $arrReqData['mb_state_alarm'])
        ->where('mb_uid', $strUserId)
        ->update();
    }
    function login($strUserId, $strPwd){
        return $this->builder()
            ->where([
            'mb_uid' => $strUserId, 
            'mb_pwd' => $strPwd])
            ->get()->getRow();
    }
    

    function deleteMemberByFid($arrDeleteData)
    {
        return $this->delete($arrDeleteData['mb_fid']);
    }

    public function moneyProc(&$objUser, $dtMoney, $dtPoint, $nCharge, $nExchange)
    {

        $strSql1 = "UPDATE ".$this->table." SET ";
        if($dtMoney >= 0)
            $strSql1 .= "mb_money = mb_money+".$dtMoney;
        else {
            $dtMoney = abs($dtMoney);
            $strSql1 .= "mb_money = mb_money-".$dtMoney;
        }
        if($dtPoint > 0){
            $strSql1.= ", mb_point = mb_point+".$dtPoint; 
        } else if($dtPoint < 0){
            $dtPoint = abs($dtPoint);
            $strSql1.= ", mb_point = mb_point-".$dtPoint; 
        }
        if($nCharge > 0){
            $strSql1.= ", mb_money_charge = mb_money_charge+".$nCharge; 
        } else if($nCharge < 0){
            $nCharge = abs($nCharge);
            $strSql1.= ", mb_money_charge = mb_money_charge-".$nCharge; 
        }
        if($nExchange > 0){
            $strSql1.= ", mb_money_exchange = mb_money_exchange+".$nExchange; 
        } else if($nExchange < 0){
            $nExchange = abs($nExchange);
            $strSql1.= ", mb_money_exchange = mb_money_exchange-".$nExchange; 
        }
        
        $this->db->transBegin();
        $strSql2 = "SELECT mb_money FROM ".$this->table;
        $strSql2.= " WHERE mb_fid=".$objUser->mb_fid;
        $objResult = $this->db->query($strSql2)->row();
        

        $strSql1.= " WHERE mb_fid=".$objUser->mb_fid;
        $this->db->query($strSql1);

        $bResult = false;
        
        if ($this->db->transStatus() === FALSE)
        {
            
            $this->db->transRollback();
            $bResult = false;

        } else {
            $this->db->transCommit();
            $objUser->mb_money = $objResult->mb_money;
            $bResult = true;
        }    

        return $bResult;
    }

    function updateMoney($objMember){

        $this->builder()->set('mb_money', $objMember->mb_money);
        $this->builder()->set('mb_money_charge', $objMember->mb_money_charge);
        $this->builder()->set('mb_money_exchange', $objMember->mb_money_exchange);
        $this->builder()->set('mb_point', $objMember->mb_point);
        
        $this->builder()->where('mb_fid', $objMember->mb_fid);
        
        return $this->builder()->update();        
    }
    function calcMemberMoney($strMemFid, $upSum){
        $arrTotalMoney = array(0, 0);

        $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid, mb_money, mb_live_money ";
        $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid , r.mb_money, r.mb_live_money ";

        $strSQL = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
        $strSQL .= " ( SELECT ".$strTbColum." FROM ".$this->table." WHERE mb_emp_fid = '".$strMemFid."'";
        $strSQL .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->table." r ";
        $strSQL .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";

        $strSQL .= " SELECT SUM(mb_money) AS mb_money, SUM(mb_live_money) AS mb_live_money FROM tbmember where ";
        if ($upSum)
        {
            $strSQL .= " mb_level >= '".LEVEL_EMPLOYEE."' ";
        }
        else {
            $strSQL .= " mb_level < '".LEVEL_EMPLOYEE."' ";
        }

        $objResult = $this -> db -> query($strSQL)->getRow();
        
        if(!is_null($objResult->mb_money)) {
            $arrTotalMoney[0] = $objResult->mb_money;                
        }
        if(!is_null($objResult->mb_money)) {
            $arrTotalMoney[1] = $objResult->mb_live_money;                
        }
        return $arrTotalMoney; 
    }
    //유저 보유금
    function calcUserMoney($strEmpFid){
        return $this->calcMemberMoney($strEmpFid, false);
    }
    //직원별 보유금
    function calcEmpMoney($objEmp){
        $arrTotalMoney = array(0, 0);
        if($objEmp->mb_level >= LEVEL_EMPLOYEE){
            
            $arrResult = $this->calcMemberMoney($objEmp->mb_fid, true);
            $arrTotalMoney[0] = $arrResult[0] + $objEmp->mb_money;
            $arrTotalMoney[1] = $arrResult[1] + $objEmp->mb_live_money;
        }
        return $arrTotalMoney;

    }
    
    //배팅금액 (하부포함)
    function calcBetMoneys($objEmp, $arrReqData){

        $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid ";
        $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid ";

        $strSQL = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
        $strSQL .= " ( SELECT ".$strTbColum." FROM ".$this->table." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
        $strSQL .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->table." r ";
        $strSQL .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";

        $strSQL .= " SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, SUM(employee_amount) AS employee_amount, SUM(agency_amount) AS agency_amount, SUM(company_amount) AS company_amount ";
        $strSQL .="  FROM (SELECT  * FROM tbmember UNION SELECT ".$strTbColum." FROM ".$this->table." where mb_fid='".$objEmp->mb_fid."'";          
        $strSQL .=" ) AS mb_table ";
        
        $strSQL .= " JOIN ( (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, SUM(employee_amount) AS employee_amount, SUM(agency_amount) AS agency_amount , SUM(company_amount) AS company_amount, bet_emp_fid, bet_mb_uid FROM bet_powerball ";
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 )
            $strSQL.=" WHERE bet_time >= '".$arrReqData['start']." 0:0:0' AND bet_time <= '".$arrReqData['end']." 23:59:59' " ;
        $strSQL .= " GROUP BY bet_mb_uid) ";

        $strSQL .= "UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, SUM(employee_amount) AS employee_amount, SUM(agency_amount) AS agency_amount , SUM(company_amount) AS company_amount, bet_emp_fid, bet_mb_uid FROM bet_powerladder ";
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 )
            $strSQL.=" WHERE bet_time >= '".$arrReqData['start']." 0:0:0' AND bet_time <= '".$arrReqData['end']." 23:59:59' " ;
        $strSQL .= " GROUP BY bet_mb_uid) ";

        $strSQL .= "UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, SUM(employee_amount) AS employee_amount, SUM(agency_amount) AS agency_amount , SUM(company_amount) AS company_amount, bet_emp_fid, bet_mb_uid FROM bet_kenoladder ";
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 )
            $strSQL.=" WHERE bet_time >= '".$arrReqData['start']." 0:0:0' AND bet_time <= '".$arrReqData['end']." 23:59:59' " ;
        $strSQL .= " GROUP BY bet_mb_uid) ";

        $strSQL .= "UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, SUM(employee_amount) AS employee_amount, SUM(agency_amount) AS agency_amount , SUM(company_amount) AS company_amount, bet_emp_fid, bet_mb_uid FROM bet_casino ";
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 )
            $strSQL.=" WHERE bet_time >= '".$arrReqData['start']." 0:0:0' AND bet_time <= '".$arrReqData['end']." 23:59:59' " ;
        $strSQL .= " GROUP BY bet_mb_uid) ";

        $strSQL .= "UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, SUM(employee_amount) AS employee_amount, SUM(agency_amount) AS agency_amount , SUM(company_amount) AS company_amount, bet_emp_fid, bet_mb_uid FROM bet_bogleball ";
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 )
            $strSQL.=" WHERE bet_time >= '".$arrReqData['start']." 0:0:0' AND bet_time <= '".$arrReqData['end']." 23:59:59' " ;
        $strSQL .= " GROUP BY bet_mb_uid) ";

        $strSQL .= "UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, SUM(employee_amount) AS employee_amount, SUM(agency_amount) AS agency_amount , SUM(company_amount) AS company_amount, bet_emp_fid, bet_mb_uid FROM bet_bogleladder ";
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 )
            $strSQL.=" WHERE bet_time >= '".$arrReqData['start']." 0:0:0' AND bet_time <= '".$arrReqData['end']." 23:59:59' " ;
        $strSQL .= " GROUP BY bet_mb_uid) ";

        $strSQL .= " )AS bet_table ON bet_table.bet_mb_uid = mb_table.mb_uid ";      

         $objResult = $this -> db -> query($strSQL)->getRow();

         $arrBetData['bet_money'] = 0;          //베팅머니
         $arrBetData['bet_win_money'] = 0;      //적중머니
         $arrBetData['bet_benefit_money'] = 0;  //베팅손익
         $arrBetData['rate_money'] = 0;          //수수료
         $arrBetData['last_money'] = 0;         //촤지종손익

         if(!is_null($objResult->bet_money)) {
            $arrBetData['bet_money'] = $arrBetData['bet_money'] + $objResult->bet_money;
         } 
         if(!is_null($objResult->bet_win_money)) {
            $arrBetData['bet_win_money'] = $arrBetData['bet_win_money'] + $objResult->bet_win_money;
         } 

         if($objEmp->mb_level == 9){
            if(!is_null($objResult->company_amount)) {
                $arrBetData['rate_money'] = $arrBetData['rate_money'] + $objResult->company_amount;
            } 
        } else if($objEmp->mb_level == 8){
            if(!is_null($objResult->agency_amount)) {
                $arrBetData['rate_money'] = $arrBetData['rate_money'] + $objResult->agency_amount;
            } 
        } else if($objEmp->mb_level == 7){
            if(!is_null($objResult->employee_amount)) {
                $arrBetData['rate_money'] = $arrBetData['rate_money'] + $objResult->employee_amount;
            } 
        }

        //$arrBetData['bet_money'] = 0;          //베팅머니
         //$arrBetData['bet_win_money'] = $arrBetData['bet_win_money']-$arrBetData['bet_money'];      //적중머니
         $arrBetData['bet_benefit_money'] = $arrBetData['bet_money']-$arrBetData['bet_win_money'];  //베팅손익
         //$arrBetData['rate_money'] = 0;          //수수료
         $arrBetData['last_money'] = $arrBetData['bet_benefit_money']-$arrBetData['rate_money'];

         return $arrBetData;
         
    }
    //배팅금액 (하부포함)
    function calcBetMoneysByGame($objEmp, $arrReqData){

        $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid ";
        $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid ";

        $strSQL = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
        $strSQL .= " ( SELECT ".$strTbColum." FROM ".$this->table." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
        $strSQL .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->table." r ";
        $strSQL .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";

        $strSQL .= " SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, SUM(employee_amount) AS employee_amount, SUM(agency_amount) AS agency_amount, SUM(company_amount) AS company_amount ";
        $strSQL .="  FROM (SELECT  * FROM tbmember UNION SELECT ".$strTbColum." FROM ".$this->table." where mb_fid='".$objEmp->mb_fid."'";      ;       
        $strSQL .=" ) AS mb_table ";

        $strSQL .= " JOIN ( SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, SUM(employee_amount) AS employee_amount, SUM(agency_amount) AS agency_amount , SUM(company_amount) AS company_amount, bet_emp_fid, bet_mb_uid FROM ";
        if($arrReqData['type'] == GAME_POWER_BALL)
            $strSQL .= " bet_powerball ";
        else if($arrReqData['type'] == GAME_POWER_LADDER)
            $strSQL .= " bet_powerladder ";
        else if($arrReqData['type'] == GAME_KENO_LADDER)
            $strSQL .= " bet_kenoladder ";
        else if($arrReqData['type'] == GAME_CASINO)
            $strSQL .= " bet_casino ";
        else if($arrReqData['type'] == GAME_BOGLE_BALL)
            $strSQL .= " bet_bogleball ";
        else if($arrReqData['type'] == GAME_BOGLE_LADDER)
            $strSQL .= " bet_bogleladder ";
        else return null;
        
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 )
            $strSQL.=" WHERE bet_time >= '".$arrReqData['start']." 0:0:0' AND bet_time <= '".$arrReqData['end']." 23:59:59' " ;
        $strSQL .= " GROUP BY bet_mb_uid ";
        $strSQL .= " )AS bet_table ON bet_table.bet_mb_uid = mb_table.mb_uid ";      

         $objResult = $this -> db -> query($strSQL)->getRow();

         $arrBetData['bet_money'] = 0;          //베팅머니
         $arrBetData['bet_win_money'] = 0;      //적중머니
         $arrBetData['bet_benefit_money'] = 0;  //베팅손익
         $arrBetData['rate_money'] = 0;         //수수료
         $arrBetData['last_money'] = 0;         //최종손익

         if(!is_null($objResult->bet_money)) {
            $arrBetData['bet_money'] = $arrBetData['bet_money'] + $objResult->bet_money;
         } 
         if(!is_null($objResult->bet_win_money)) {
            $arrBetData['bet_win_money'] = $arrBetData['bet_win_money'] + $objResult->bet_win_money;
         } 

         if($objEmp->mb_level == 9){
            if(!is_null($objResult->company_amount)) {
                $arrBetData['rate_money'] = $arrBetData['rate_money'] + $objResult->company_amount;
            } 
        } else if($objEmp->mb_level == 8){
            if(!is_null($objResult->agency_amount)) {
                $arrBetData['rate_money'] = $arrBetData['rate_money'] + $objResult->agency_amount;
            } 
        } else if($objEmp->mb_level == 7){
            if(!is_null($objResult->employee_amount)) {
                $arrBetData['rate_money'] = $arrBetData['rate_money'] + $objResult->employee_amount;
            } 
        }

        //$arrBetData['bet_money'] = 0;          //베팅머니
         //$arrBetData['bet_win_money'] = $arrBetData['bet_win_money']-$arrBetData['bet_money'];      //적중머니
         $arrBetData['bet_benefit_money'] = $arrBetData['bet_money']-$arrBetData['bet_win_money'];  //베팅손익
         //$arrBetData['rate_money'] = 0;          //수수료
         $arrBetData['last_money'] = $arrBetData['bet_benefit_money']-$arrBetData['rate_money'];

         return $arrBetData;
         
    }
    public function updateLoginTime($userFid, $ipAddress){
        
        $this->builder()->set('mb_time_last', 'NOW()', false);
        $this->builder()->set('mb_ip_last', $ipAddress);
        $this->builder()->where('mb_fid', $userFid);
        
        return $this->builder()->update();
   }
   public function getUserData($user_id){
       $userData = $this->where('mb_uid', $user_id)->first();
       return $userData;
   }

   public function getMemberByLevel($strLevel, $bLowLev=false)
   {
        $query = null;
        if($bLowLev)
            $query = $this->asObject()->where('mb_level <', $strLevel);
        else 
            $query = $this->asObject()->where('mb_level', $strLevel);
        if ($query == null)
            return array();
        return $query->findAll();
    }
    public function getMemberByFid($strFid){
        return $this->asObject()->where('mb_fid', $strFid)->first();
    }
    public function getMemberByEmpFid($nEmpFid, $nReqLevel, $nEmpLev=LEVEL_AGENCY, $bLowLev=false){

        if($nEmpLev > LEVEL_COMPANY)
        {
            return $this->getMemberByLevel($nReqLevel, $bLowLev);
        } else {
            
            $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid, mb_emp_permit, mb_nickname, mb_email, mb_phone, mb_money, mb_point, ";
            $strTbColum .= " mb_money_charge, mb_money_exchange, mb_money_bet, mb_money_earn, mb_color, mb_state_active, mb_state_bet, mb_state_alarm, ";
            $strTbColum .= " mb_game_pb, mb_game_ps, mb_game_ks, mb_game_bb, mb_game_bs, mb_game_ev, mb_game_pb_ratio, mb_game_pb2_ratio, mb_game_ps_ratio, ";
            $strTbColum .= " mb_game_ks_ratio, mb_game_bb_ratio, mb_game_bb2_ratio, mb_game_bs_ratio, mb_game_ev_ratio, mb_live_money";

            $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid, r.mb_emp_permit, r.mb_nickname, r.mb_email, r.mb_phone, r.mb_money, r.mb_point, ";
            $strTbRColum .= " r.mb_money_charge, r.mb_money_exchange, r.mb_money_bet, r.mb_money_earn, r.mb_color, r.mb_state_active, r.mb_state_bet, r.mb_state_alarm, ";
            $strTbRColum .= " r.mb_game_pb, r.mb_game_ps, r.mb_game_ks, r.mb_game_bb, r.mb_game_bs, r.mb_game_ev, r.mb_game_pb_ratio, r.mb_game_pb2_ratio, r.mb_game_ps_ratio, ";
            $strTbRColum .= " r.mb_game_ks_ratio, r.mb_game_bb_ratio, r.mb_game_bb2_ratio, r.mb_game_bs_ratio, r.mb_game_ev_ratio, r.mb_live_money ";


            $strSQL = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSQL .= " ( SELECT ".$strTbColum." FROM ".$this->table." WHERE mb_emp_fid = '".$nEmpFid."'";
            $strSQL .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->table." r ";
            $strSQL .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";
            $strSQL .= " SELECT * FROM tbmember where ";
            if($bLowLev) $strSQL .= "mb_level < '".$nReqLevel."' ";
            else $strSQL .= "mb_level = '".$nReqLevel."' ";
            
            
            return $this -> db -> query($strSQL)->getResult();
        }

    }
    function getEmployeeNames($objAdmin, $nLevel){
        $arrData = array();
        if( $nLevel > $objAdmin->mb_level){

        }
        else if($nLevel == $objAdmin->mb_level)
        {
            $arrName['mb_name'] = $objAdmin->mb_nickname;
            $arrName['mb_fid'] = $objAdmin->mb_fid;
            $arrName['mb_color'] = $objAdmin->mb_color;
            $arrData[0] = (object)$arrName;

        } else if($nLevel == LEVEL_COMPANY ){
            $arrEmployee = $this->getMemberByEmpFid($objAdmin->mb_fid, $nLevel, $objAdmin->mb_level, false);

            $i = 0;
            foreach ($arrEmployee as $objMember) {
                $arrName['mb_name'] = $objMember->mb_nickname;
                $arrName['mb_fid'] = $objMember->mb_fid;
                $arrName['mb_color'] = $objMember->mb_color;
                $arrData[$i++] = (object)$arrName;
            }
        } else if($nLevel == LEVEL_AGENCY){
            $arrEmployee = $this->getMemberByEmpFid($objAdmin->mb_fid, $nLevel, $objAdmin->mb_level, false);
            $i = 0;
            foreach ($arrEmployee as $objMember) {
                $arrName['mb_name'] = $this->getFullName($objMember);
                $arrName['mb_fid'] = $objMember->mb_fid;
                $arrName['mb_color'] = $objMember->mb_color;
                $arrData[$i++] = (object)$arrName;
            }
        } else if($nLevel == LEVEL_EMPLOYEE){
            $arrEmployee = $this->getMemberByEmpFid($objAdmin->mb_fid, $nLevel, $objAdmin->mb_level, false);
            $i = 0;
            foreach ($arrEmployee as $objMember) {
                $arrName['mb_name'] = $this->getFullName($objMember);
                $arrName['mb_fid'] = $objMember->mb_fid;
                $arrName['mb_color'] = $objMember->mb_color;
                $arrData[$i++] = (object)$arrName;
            }
        }
        return $arrData;
    }
    function getFullName($objMember){
        if(is_null($objMember)) return "";

        //9레벨일때
        if($objMember->mb_level >= 9) return $objMember->mb_nickname;

        $strBuf = "";        
        //8레벨일때 
        $objEmp = $this->getInfoByFid($objMember->mb_emp_fid);
        if(is_null($objEmp)) return "";
        $strBuf = $objEmp->mb_nickname; 
        if($objMember->mb_level == 8) return $strBuf."::".$objMember->mb_nickname;

        //7레벨일때 
        $objEmp = $this->getInfoByFid($objEmp->mb_emp_fid);
        if(is_null($objEmp)) return "";
        $strBuf = $objEmp->mb_nickname."::".$strBuf;            
        if($objMember->mb_level == 7) return $strBuf."::".$objMember->mb_nickname;

        //그이하일때 
        $objEmp = $this->getInfoByFid($objEmp->mb_emp_fid);
        if(is_null($objEmp)) return "";
        $strBuf = $objEmp->mb_nickname."::".$strBuf;            
        return $strBuf;
    }
    public function getInfoByFid($strFid){
        return $this->asObject()->where('mb_fid', $strFid)->first();
    }
    public function getInfo($strId){
        return $this->asObject()->where('mb_uid', $strId)->first();
    }
    function getByNickname($strName){
        return $this->asObject()->where('mb_nickname', $strName)->first();
    }
    function register($arrRegData, &$strError)
    {
        
        //결과 0:오유 1:성공 2:아이디중복 3:추천인 오유 4:파워볼 배당율오유 5:파워사다리 배당율오유 6:키노사다리 배당율 오유 8:닉네임중복

        //아이디검사         
        $objMember = $this->getInfo(trim($arrRegData['mb_uid']));
        if($objMember != NULL)
            return 2;
        
        //닉네임 검사        
        
        $objMember = $this->getByNickname(trim($arrRegData['mb_nickname']));
        if(!is_null($objMember))
            return 8;
    
        
        if($arrRegData['mb_level'] == 9){
            $arrRegData['mb_emp_fid'] = 0;
    
        } else if($arrRegData['mb_emp_fid'] > 0){
            //추천인 검사
            $objEmployee = $this->getMemberByFid($arrRegData['mb_emp_fid']);
            if(is_null($objEmployee))            
                return 3;
            
            if($arrRegData['mb_level'] == 8){
                if($objEmployee->mb_level != 9)
                    return 3;
            } else if($arrRegData['mb_level'] == 7){
                if($objEmployee->mb_level != 8)
                    return 3;
            } else if($arrRegData['mb_level'] < 7 ){
                if($objEmployee->mb_level != 7)
                    return 3;
            }
            //배당율 검사
            if($objEmployee->mb_game_pb_ratio < $arrRegData['mb_game_pb_ratio']){
                $strError = $objEmployee->mb_game_pb_ratio;
                return 4;
            }
            else if($objEmployee->mb_game_pb2_ratio < $arrRegData['mb_game_pb2_ratio']){
                $strError = $objEmployee->mb_game_pb2_ratio;
                return 4;
            }
            else if($objEmployee->mb_game_ps_ratio < $arrRegData['mb_game_ps_ratio']){
                $strError = $objEmployee->mb_game_ps_ratio;
                return 5;
            }
            else if($objEmployee->mb_game_ks_ratio < $arrRegData['mb_game_ks_ratio']){
                $strError = $objEmployee->mb_game_ks_ratio;
                return 6;
            }
            else if($objEmployee->mb_game_ev_ratio < $arrRegData['mb_game_ev_ratio']){
                $strError = $objEmployee->mb_game_ev_ratio;
                return 7;
            } else if($objEmployee->mb_game_bb_ratio < $arrRegData['mb_game_bb_ratio']){
                $strError = $objEmployee->mb_game_bb_ratio;
                return 9;
            } else if($objEmployee->mb_game_bb2_ratio < $arrRegData['mb_game_bb2_ratio']){
                $strError = $objEmployee->mb_game_bb2_ratio;
                return 9;
            } else if($objEmployee->mb_game_bs_ratio < $arrRegData['mb_game_bs_ratio']){
                $strError = $objEmployee->mb_game_bs_ratio;
                return 10;
            }
            if($arrRegData['mb_level'] < 7){
                if (!array_key_exists("mb_color", $arrRegData))
                    $arrRegData['mb_color'] = $objEmployee->mb_color;
                else if(strcmp($arrRegData['mb_color'],$objEmployee->mb_color) != 0)
                    $arrRegData['mb_color'] = $objEmployee->mb_color;

                if (!array_key_exists("mb_emp_permit", $arrRegData))
                    $arrRegData['mb_emp_permit'] = 0;
            }
        } else return 0;

        if(strlen($arrRegData['mb_game_pb_ratio']) < 1)
            $arrRegData['mb_game_pb_ratio'] = 0;
        if(strlen($arrRegData['mb_game_pb2_ratio']) < 1)
            $arrRegData['mb_game_pb2_ratio'] = 0;
        if(strlen($arrRegData['mb_game_ps_ratio']) < 1)
            $arrRegData['mb_game_ps_ratio'] = 0;
        if(strlen($arrRegData['mb_game_ks_ratio']) < 1)
            $arrRegData['mb_game_ks_ratio'] = 0;
        if(strlen($arrRegData['mb_game_ev_ratio']) < 1)
            $arrRegData['mb_game_ev_ratio'] = 0;
        if(strlen($arrRegData['mb_game_bb_ratio']) < 1)
            $arrRegData['mb_game_bb_ratio'] = 0;
        if(strlen($arrRegData['mb_game_bb2_ratio']) < 1)
            $arrRegData['mb_game_bb2_ratio'] = 0;
        if(strlen($arrRegData['mb_game_bs_ratio']) < 1)
            $arrRegData['mb_game_bs_ratio'] = 0;
        
        //자료기지 등록
        $this->builder()->set('mb_uid', trim($arrRegData['mb_uid']));
        $this->builder()->set('mb_pwd', $arrRegData['mb_pwd']);
        $this->builder()->set('mb_level', $arrRegData['mb_level']);
        $this->builder()->set('mb_emp_fid', $arrRegData['mb_emp_fid']);
        $this->builder()->set('mb_emp_permit', $arrRegData['mb_emp_permit']);
        $this->builder()->set('mb_nickname', trim($arrRegData['mb_nickname']));
        $this->builder()->set('mb_phone', $arrRegData['mb_phone']);
        $this->builder()->set('mb_bank_name', trim($arrRegData['mb_bank_name']));
        $this->builder()->set('mb_bank_own', trim($arrRegData['mb_bank_own']));
        $this->builder()->set('mb_bank_num', trim($arrRegData['mb_bank_num']));
        $this->builder()->set('mb_bank_pwd', $arrRegData['mb_bank_pwd']);
        $this->builder()->set('mb_time_join', 'NOW()', false);
        //$this->builder()->set('mb_time_last', 'NOW()', false);
        if($arrRegData['mb_money'] > 0)
            $this->builder()->set('mb_money', $arrRegData['mb_money']);
        if($arrRegData['mb_point'] > 0)
            $this->builder()->set('mb_point', $arrRegData['mb_point']);
        //if($arrRegData['mb_money_charge'] > 0)
        //    $this->builder()->set('mb_money_charge', $arrRegData['mb_money_charge']);
        //if($arrRegData['mb_money_exchange'] > 0)        
        //    $this->builder()->set('mb_money_exchange', $arrRegData['mb_money_exchange']);
        $this->builder()->set('mb_color', $arrRegData['mb_color']);
        $this->builder()->set('mb_state_active', 2);               //대기
        $this->builder()->set('mb_game_pb', 1);
        $this->builder()->set('mb_game_ps', 1);
        $this->builder()->set('mb_game_ks', 1);
        $this->builder()->set('mb_game_ev', 1);
        $this->builder()->set('mb_game_pb_ratio', $arrRegData['mb_game_pb_ratio']);
        $this->builder()->set('mb_game_pb2_ratio', $arrRegData['mb_game_pb2_ratio']);
        $this->builder()->set('mb_game_ps_ratio', $arrRegData['mb_game_ps_ratio']);
        $this->builder()->set('mb_game_ks_ratio', $arrRegData['mb_game_ks_ratio']);
        $this->builder()->set('mb_game_ev_ratio', $arrRegData['mb_game_ev_ratio']);
	    $this->builder()->set('mb_game_pb_percent', $arrRegData['mb_game_pb_percent']);
        $this->builder()->set('mb_game_pb2_percent', $arrRegData['mb_game_pb2_percent']);
        $this->builder()->set('mb_game_ps_percent', $arrRegData['mb_game_ps_percent']);
        $this->builder()->set('mb_game_ks_percent', $arrRegData['mb_game_ks_percent']);
        $this->builder()->set('mb_game_bb_percent', $arrRegData['mb_game_bb_percent']);
        $this->builder()->set('mb_game_bb2_percent', $arrRegData['mb_game_bb2_percent']);
        $this->builder()->set('mb_game_bs_percent', $arrRegData['mb_game_bs_percent']);
        $result = $this->builder()->insert();        
        return $result ?1:0;
    }
    function modifyMember($arrData, &$strError){

        //결과 0:오유 1:성공 2:아이디중복 3:추천인 오유 4:파워볼 배당율오유 5:파워사다리 배당율오유 6:키노사다리 배당율 오유

        //아이디검사    
        $objMember = $this->getMemberByFid($arrData['mb_fid']);
        if(is_null($objMember))
            return 0;
        
        
        if($objMember->mb_level == LEVEL_COMPANY){
            $arrData['mb_emp_fid'] = 0;

            $objUser = $this->getByNickname($arrData['mb_nickname']);
            if(!is_null($objUser) && $objUser->mb_fid != $arrData['mb_fid'])
                return 8;
        } else if($arrData['mb_emp_fid'] > 0){
            //추천인 검사
            $objEmployee = $this->getMemberByFid($arrData['mb_emp_fid']);
            if(is_null($objEmployee))            
                return 3;
            
            if($objMember->mb_level == LEVEL_AGENCY){
                if($objEmployee->mb_level != LEVEL_COMPANY)
                    return 3;
            } else if($objMember->mb_level == LEVEL_EMPLOYEE){
                if($objEmployee->mb_level != LEVEL_AGENCY)
                    return 3;
            } else if($objMember->mb_level < LEVEL_EMPLOYEE ){
                if($objEmployee->mb_level != LEVEL_EMPLOYEE)
                    return 3;

                $arrData['mb_color'] = $objEmployee->mb_color; 

            }
            
            //닉네임 검사        
            if($objMember->mb_level >= LEVEL_EMPLOYEE)
            {
                $objUser = $this->getByNickname($arrData['mb_nickname']);
                if(!is_null($objUser) && $objUser->mb_fid != $arrData['mb_fid'])
                    return 8;
            }

            //배당율 검사
            if($objEmployee->mb_game_pb_ratio < $arrData['mb_game_pb_ratio']){
                $strError = $objEmployee->mb_game_pb_ratio;
                return 4;
            } else if($objEmployee->mb_game_pb2_ratio < $arrData['mb_game_pb2_ratio']){
                $strError = $objEmployee->mb_game_pb2_ratio;
                return 4;
            } else if($objEmployee->mb_game_ps_ratio < $arrData['mb_game_ps_ratio']){
                $strError = $objEmployee->mb_game_ps_ratio;
                return 5;
            } else if($objEmployee->mb_game_ks_ratio < $arrData['mb_game_ks_ratio']){
                $strError = $objEmployee->mb_game_ks_ratio;
                return 6;
            } else if($objEmployee->mb_game_ev_ratio < $arrData['mb_game_ev_ratio']){
                $strError = $objEmployee->mb_game_ev_ratio;
                return 7;
            } else if($objEmployee->mb_game_bb_ratio < $arrData['mb_game_bb_ratio']){
                $strError = $objEmployee->mb_game_bb_ratio;
                return 9;
            } else if($objEmployee->mb_game_bb2_ratio < $arrData['mb_game_bb2_ratio']){
                $strError = $objEmployee->mb_game_bb2_ratio;
                return 9;
            } else if($objEmployee->mb_game_bs_ratio < $arrData['mb_game_bs_ratio']){
                $strError = $objEmployee->mb_game_bs_ratio;
                return 10;
            }

        } else return 0;

        if(strlen($arrData['mb_game_pb_ratio']) < 1)
            $arrData['mb_game_pb_ratio'] = 0;
        if(strlen($arrData['mb_game_pb2_ratio']) < 1)
            $arrData['mb_game_pb2_ratio'] = 0;
        if(strlen($arrData['mb_game_ps_ratio']) < 1)
            $arrData['mb_game_ps_ratio'] = 0;
        if(strlen($arrData['mb_game_ks_ratio']) < 1)
            $arrData['mb_game_ks_ratio'] = 0;
        if(strlen($arrData['mb_game_ev_ratio']) < 1)
            $arrData['mb_game_ev_ratio'] = 0;
        if(strlen($arrData['mb_game_bb_ratio']) < 1)
            $arrData['mb_game_bb_ratio'] = 0;
        if(strlen($arrData['mb_game_bb2_ratio']) < 1)
            $arrData['mb_game_bb2_ratio'] = 0;
        if(strlen($arrData['mb_game_bs_ratio']) < 1)
            $arrData['mb_game_bs_ratio'] = 0;
        

        if(strlen($arrData['mb_game_pb_percent']) < 1)
            $arrData['mb_game_pb_percent'] = 0;
        if(strlen($arrData['mb_game_pb2_percent']) < 1)
            $arrData['mb_game_pb2_percent'] = 0;
        if(strlen($arrData['mb_game_ps_percent']) < 1)
            $arrData['mb_game_ps_percent'] = 0;
        if(strlen($arrData['mb_game_ks_percent']) < 1)
            $arrData['mb_game_ks_percent'] = 0;
        if(strlen($arrData['mb_game_bb_percent']) < 1)
            $arrData['mb_game_bb_percent'] = 0;
        if(strlen($arrData['mb_game_bb2_percent']) < 1)
            $arrData['mb_game_bb2_percent'] = 0;
        if(strlen($arrData['mb_game_bs_percent']) < 1)
            $arrData['mb_game_bs_percent'] = 0;
        
        $this->builder()->set('mb_pwd', $arrData['mb_pwd']);
        $this->builder()->set('mb_level', $arrData['mb_level']);
        //$this->builder()->set('mb_level', $arrData['mb_level']);
        $this->builder()->set('mb_emp_fid', $arrData['mb_emp_fid']);
        //$this->builder()->set('mb_nickname', trim($arrData['mb_nickname']));
        $this->builder()->set('mb_phone', $arrData['mb_phone']);
        $this->builder()->set('mb_bank_name', trim($arrData['mb_bank_name']));
        $this->builder()->set('mb_bank_own', trim($arrData['mb_bank_own']));
        $this->builder()->set('mb_bank_num', trim($arrData['mb_bank_num']));
        $this->builder()->set('mb_bank_pwd', $arrData['mb_bank_pwd']);
        $this->builder()->set('mb_money', $arrData['mb_money']);
        $this->builder()->set('mb_point', $arrData['mb_point']);
        
         if(array_key_exists("mb_emp_permit", $arrData))
            $this->builder()->set('mb_emp_permit', $arrData['mb_emp_permit']);
        
        if(array_key_exists("mb_color", $arrData))
            $this->builder()->set('mb_color', $arrData['mb_color']);
        
        $this->builder()->set('mb_game_pb_ratio', $arrData['mb_game_pb_ratio']);
        $this->builder()->set('mb_game_pb2_ratio', $arrData['mb_game_pb2_ratio']);
        $this->builder()->set('mb_game_ps_ratio', $arrData['mb_game_ps_ratio']);
        $this->builder()->set('mb_game_ks_ratio', $arrData['mb_game_ks_ratio']);
        $this->builder()->set('mb_game_ev_ratio', $arrData['mb_game_ev_ratio']);
        $this->builder()->set('mb_game_bb_ratio', $arrData['mb_game_bb_ratio']);
        $this->builder()->set('mb_game_bb2_ratio', $arrData['mb_game_bb2_ratio']);
        $this->builder()->set('mb_game_bs_ratio', $arrData['mb_game_bs_ratio']);
        
        $this->builder()->set('mb_game_pb_percent', $arrData['mb_game_pb_percent']);
        $this->builder()->set('mb_game_pb2_percent', $arrData['mb_game_pb2_percent']);
        $this->builder()->set('mb_game_ps_percent', $arrData['mb_game_ps_percent']);
        $this->builder()->set('mb_game_ks_percent', $arrData['mb_game_ks_percent']);
        $this->builder()->set('mb_game_bb_percent', $arrData['mb_game_bb_percent']);
        $this->builder()->set('mb_game_bb2_percent', $arrData['mb_game_bb2_percent']);
        $this->builder()->set('mb_game_bs_percent', $arrData['mb_game_bs_percent']);
       

        $this->builder()->where('mb_fid', $arrData['mb_fid']);
        $bResult = $this->builder()->update();


        //하부 회원색 변경
        if (array_key_exists("mb_color", $arrData) && $objMember->mb_level >= LEVEL_EMPLOYEE){
            $this->builder()->set("mb_color", $arrData['mb_color']);
            $this->builder()->where("mb_emp_fid", $arrData['mb_fid']);
            $this->builder()->update();
        }

        return 1;
    }
    function modifyMemberRatio($arrData, &$strError){

        //결과 0:오유 1:성공 2:아이디중복 3:추천인 오유 4:파워볼 배당율오유 5:파워사다리 배당율오유 6:키노사다리 배당율 오유

        //아이디검사    
        $objMember = $this->getMemberByFid($arrData['mb_fid']);
        if(is_null($objMember))
            return 0;
        
        
        if( $objMember->mb_level < LEVEL_ADMIN){
            //추천인 검사
            $objEmployee = $this->getMemberByFid($objMember->mb_emp_fid);
            if($objEmployee == NULL)            
                return 0;
            
            //배당율 검사
            if($objEmployee->mb_game_pb_ratio < $arrData['mb_game_pb_ratio']){
                $strError = $objEmployee->mb_game_pb_ratio;
                return 4;
            } else if($objEmployee->mb_game_pb2_ratio < $arrData['mb_game_pb2_ratio']){
                $strError = $objEmployee->mb_game_pb2_ratio;
                return 4;
            } else if($objEmployee->mb_game_ps_ratio < $arrData['mb_game_ps_ratio']){
                $strError = $objEmployee->mb_game_ps_ratio;
                return 5;
            } else if($objEmployee->mb_game_ks_ratio < $arrData['mb_game_ks_ratio']){
                $strError = $objEmployee->mb_game_ks_ratio;
                return 6;
            } else if($objEmployee->mb_game_ev_ratio < $arrData['mb_game_ev_ratio']){
                $strError = $objEmployee->mb_game_ev_ratio;
                return 7;
            } else if($objEmployee->mb_game_bb_ratio < $arrData['mb_game_bb_ratio']){
                $strError = $objEmployee->mb_game_bb_ratio;
                return 9;
            } else if($objEmployee->mb_game_bb2_ratio < $arrData['mb_game_bb2_ratio']){
                $strError = $objEmployee->mb_game_bb2_ratio;
                return 9;
            } else if($objEmployee->mb_game_bs_ratio < $arrData['mb_game_bs_ratio']){
                $strError = $objEmployee->mb_game_bs_ratio;
                return 10;
            }

        } else return 0;

        if(strlen($arrData['mb_game_pb_ratio']) < 1)
            $arrData['mb_game_pb_ratio'] = 0;
        if(strlen($arrData['mb_game_pb2_ratio']) < 1)
            $arrData['mb_game_pb2_ratio'] = 0;
        if(strlen($arrData['mb_game_ps_ratio']) < 1)
            $arrData['mb_game_ps_ratio'] = 0;
        if(strlen($arrData['mb_game_ks_ratio']) < 1)
            $arrData['mb_game_ks_ratio'] = 0;
        if(strlen($arrData['mb_game_ev_ratio']) < 1)
            $arrData['mb_game_ev_ratio'] = 0;
        if(strlen($arrData['mb_game_bb_ratio']) < 1)
            $arrData['mb_game_bb_ratio'] = 0;
        if(strlen($arrData['mb_game_bb2_ratio']) < 1)
            $arrData['mb_game_bb2_ratio'] = 0;
        if(strlen($arrData['mb_game_bs_ratio']) < 1)
            $arrData['mb_game_bs_ratio'] = 0;
        

        if(strlen($arrData['mb_game_pb_percent']) < 1)
            $arrData['mb_game_pb_percent'] = 0;
        if(strlen($arrData['mb_game_pb2_percent']) < 1)
            $arrData['mb_game_pb2_percent'] = 0;
        if(strlen($arrData['mb_game_ps_percent']) < 1)
            $arrData['mb_game_ps_percent'] = 0;
        if(strlen($arrData['mb_game_ks_percent']) < 1)
            $arrData['mb_game_ks_percent'] = 0;
        if(strlen($arrData['mb_game_bb_percent']) < 1)
            $arrData['mb_game_bb_percent'] = 0;
        if(strlen($arrData['mb_game_bb2_percent']) < 1)
            $arrData['mb_game_bb2_percent'] = 0;
        if(strlen($arrData['mb_game_bs_percent']) < 1)
            $arrData['mb_game_bs_percent'] = 0;


        $this->builder()->set('mb_game_pb_ratio', $arrData['mb_game_pb_ratio']);
        $this->builder()->set('mb_game_pb2_ratio', $arrData['mb_game_pb2_ratio']);
        $this->builder()->set('mb_game_ps_ratio', $arrData['mb_game_ps_ratio']);
        $this->builder()->set('mb_game_ks_ratio', $arrData['mb_game_ks_ratio']);
        $this->builder()->set('mb_game_ev_ratio', $arrData['mb_game_ev_ratio']);
        $this->builder()->set('mb_game_bb_ratio', $arrData['mb_game_bb_ratio']);
        $this->builder()->set('mb_game_bb2_ratio', $arrData['mb_game_bb2_ratio']);
        $this->builder()->set('mb_game_bs_ratio', $arrData['mb_game_bs_ratio']);
        
        $this->builder()->set('mb_game_pb_percent', $arrData['mb_game_pb_percent']);
        $this->builder()->set('mb_game_pb2_percent', $arrData['mb_game_pb2_percent']);
        $this->builder()->set('mb_game_ps_percent', $arrData['mb_game_ps_percent']);
        $this->builder()->set('mb_game_ks_percent', $arrData['mb_game_ks_percent']);
        $this->builder()->set('mb_game_bb_percent', $arrData['mb_game_bb_percent']);
        $this->builder()->set('mb_game_bb2_percent', $arrData['mb_game_bb2_percent']);
        $this->builder()->set('mb_game_bs_percent', $arrData['mb_game_bs_percent']);
       

        $this->builder()->where('mb_fid', $arrData['mb_fid']);
        $bResult = $this->builder()->update();

        return 1;
    }

    function updateMemberByFid($arrData){


        if(array_key_exists("mb_state_active", $arrData))
            $this->builder()->set('mb_state_active', $arrData['mb_state_active']);
        else if(array_key_exists("mb_game_pb", $arrData))
            $this->builder()->set('mb_game_pb', $arrData['mb_game_pb']);
        else if(array_key_exists("mb_game_ps", $arrData))
            $this->builder()->set('mb_game_ps', $arrData['mb_game_ps']);
        else if(array_key_exists("mb_game_ks", $arrData))
            $this->builder()->set('mb_game_ks', $arrData['mb_game_ks']);
        else if(array_key_exists("mb_game_ev", $arrData))
            $this->builder()->set('mb_game_ev', $arrData['mb_game_ev']);
        else if(array_key_exists("mb_game_bb", $arrData))
            $this->builder()->set('mb_game_bb', $arrData['mb_game_bb']);
        else if(array_key_exists("mb_game_bs", $arrData))
            $this->builder()->set('mb_game_bs', $arrData['mb_game_bs']);
        else return false;

        //if(array_key_exists("mb_live_id", $arrData))
        //    $this->db->set('mb_live_id', $arrData['mb_live_id']);

        $this->builder()->where('mb_fid', $arrData['mb_fid']);
        $bResult = $this->builder()->update();

        return $bResult;
    }
    public function searchCountByLevel($arrReqData){

        $strSql = "SELECT count(*) as count FROM ".$this->table;
        
        if ($arrReqData['mb_level'] == 0){
            $strSql .= " WHERE mb_level < '7' ";
        }
        else {
            $strSql .= " WHERE mb_level = '".$arrReqData['mb_level']."' ";
        }
        
        if($arrReqData['mb_emp_fid'] > 0){            
            $strSql.=" AND mb_emp_fid = '".$arrReqData['mb_emp_fid']."' ";
        }
        if(strlen($arrReqData['mb_uid']) > 0){            
            $strSql.=" AND mb_uid = '".$arrReqData['mb_uid']."' ";
        }

        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result;

    }
    public function searchCountByEmpFid($nAdminFid, $nAdminLev, $arrReqData){

        if($nAdminLev > LEVEL_COMPANY)
        {
            return $this->searchCountByLevel($arrReqData);
        } else {

            $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid ";
            $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid ";

            $strSQL = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSQL .= " ( SELECT ".$strTbColum." FROM ".$this->table." WHERE mb_emp_fid = '".$nAdminFid."'";
            $strSQL .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->table." r ";
            $strSQL .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";
            $strSQL .= " SELECT COUNT(*) as count FROM tbmember where ";
            if ($arrReqData['mb_level'] == 0){
                $strSQL .= " mb_level < '7' ";
            }
            else {
                $strSQL .= " mb_level = '".$arrReqData['mb_level']."' ";
            }
            

            if($arrReqData['mb_emp_fid'] > 0){            
                $strSQL.=" AND mb_emp_fid = '".$arrReqData['mb_emp_fid']."' ";
            }
            if(strlen($arrReqData['mb_uid']) > 0){            
                $strSQL.=" AND mb_uid = '".$arrReqData['mb_uid']."' ";
            }

            return $this -> db -> query($strSQL)->getRow();
        }
    }

    function getEmpUserCnt($objMember){
        $arrEmpUserInfo['alluser'] = 0;
        $arrEmpUserInfo['waituser'] = 0;
        $arrEmpUserInfo['waitemployee'] = 0;
        $arrEmpUserInfo['waitagency'] = 0;
        $arrEmpUserInfo['waitcompany'] = 0;

        if ($objMember->mb_level >= LEVEL_ADMIN) {
            //소속 전체 유저수
            $strSQL = " SELECT  COUNT(*) AS mb_count FROM ".$this->table." WHERE mb_level < '".LEVEL_ADMIN."'";
            $objResult = $this -> db -> query($strSQL)->getRow();
            if(!is_null($objResult->mb_count)) $arrEmpUserInfo['alluser'] = $objResult->mb_count;
            
            //대기중인 회원수
            $strSQL = " SELECT  COUNT(*) AS mb_count FROM ".$this->table." WHERE mb_level < '".LEVEL_EMPLOYEE."'";
            $strSQL .= " AND mb_state_active = '2'"; 
            $objResult = $this -> db -> query($strSQL)->getRow();
            if(!is_null($objResult->mb_count)) $arrEmpUserInfo['waituser'] = $objResult->mb_count;

            //대기중인 매장수
            $strSQL = " SELECT  COUNT(*) AS mb_count FROM ".$this->table." WHERE mb_level = '".LEVEL_EMPLOYEE."'";
            $strSQL .= " AND mb_state_active = '2'"; 
            $objResult = $this -> db -> query($strSQL)->getRow();
            if(!is_null($objResult->mb_count)) $arrEmpUserInfo['waitemployee'] = $objResult->mb_count;

            //대기중인 총판수
            $strSQL = " SELECT  COUNT(*) AS mb_count FROM ".$this->table." WHERE mb_level = '".LEVEL_AGENCY."'";
            $strSQL .= " AND mb_state_active = '2'"; 
            $objResult = $this -> db -> query($strSQL)->getRow();
            if(!is_null($objResult->mb_count)) $arrEmpUserInfo['waitagency'] = $objResult->mb_count;

            //대기중인 본사수
            $strSQL = " SELECT  COUNT(*) AS mb_count FROM ".$this->table." WHERE mb_level = '".LEVEL_COMPANY."'";
            $strSQL .= " AND mb_state_active = '2'"; 
            $objResult = $this -> db -> query($strSQL)->getRow();
            if(!is_null($objResult->mb_count)) $arrEmpUserInfo['waitcompany'] = $objResult->mb_count;

        } /*else if($objMember->mb_level >= LEVEL_EMPLOYEE){ //소속 유저수
            
            $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid, mb_state_active ";
            $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid , r.mb_state_active";

            $strSQL = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSQL .= " ( SELECT ".$strTbColum." FROM ".$this->table." WHERE mb_emp_fid = '".$objMember->mb_fid."'";
            $strSQL .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->table." r ";
            $strSQL .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";

            $strSQL .= " SELECT COUNT(*) as mb_count FROM tbmember where ";
            $strSQL .= " mb_level < '".$objMember->mb_level."' ";

            $objResult = $this -> db -> query($strSQL)->getRow();
            if(!is_null($objResult->mb_count)) $arrEmpUserInfo['alluser'] = $objResult->mb_count;
            //대기중인 유저수
            
            $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid, mb_state_active ";
            $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid , r.mb_state_active";

            $strSQL = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSQL .= " ( SELECT ".$strTbColum." FROM ".$this->table." WHERE mb_emp_fid = '".$objMember->mb_fid."'";
            $strSQL .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->table." r ";
            $strSQL .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";

            $strSQL .= " SELECT COUNT(*) as mb_count FROM tbmember where ";
            $strSQL .= " mb_level < '".$objMember->mb_level."' AND mb_state_active = '2' ";

            $objResult = $this -> db -> query($strSQL)->getRow();
            if(!is_null($objResult->mb_count)) $arrEmpUserInfo['waituser'] = $objResult->mb_count;


        }*/
        return $arrEmpUserInfo;
    }
    //관리자 보유금
    function calcAdminMoney(){
        $strSQL = "SELECT SUM(mb_money) AS emp_money, SUM(mb_point) AS emp_point FROM ".$this->table;
        $strSQL .= " WHERE mb_level < ".LEVEL_ADMIN ;

        $objResult = $this -> db -> query($strSQL)->getRow();
        return $objResult;
    }
    public function searchMemberByLevel($arrReqData){

        $strSql = "SELECT mb_fid, mb_uid, mb_level, mb_emp_fid, mb_emp_permit, mb_nickname, mb_time_join, mb_time_last, mb_ip_join, mb_ip_last, mb_money, mb_point, mb_money_charge, mb_money_exchange, ";
        $strSql .= "mb_money_bet, mb_money_earn, mb_color, mb_state_active, mb_game_pb, mb_game_ps, mb_game_ks, mb_game_bb, mb_game_bs, mb_game_ev, mb_live_money FROM ".$this->table;
        if ($arrReqData['mb_level'] == 0){
            $strSql .= " WHERE mb_level < '7' ";
        }
        else{
            $strSql .= " WHERE mb_level = '".$arrReqData['mb_level']."' ";
        }        
        
        if($arrReqData['mb_emp_fid'] > 0){            
            $strSql.=" AND mb_emp_fid = '".$arrReqData['mb_emp_fid']."' ";
        }
        if(strlen($arrReqData['mb_uid']) > 0){            
            $strSql.=" AND mb_uid = '".$arrReqData['mb_uid']."' ";
        }

        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
        $strSql.=" ORDER BY  mb_time_join DESC LIMIT ".$nStartRow.", ".$arrReqData['count'];
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result;

    }

    public function searchMemberByEmpFid($nAdminFid, $nAdminLev, $arrReqData){

        if($nAdminLev > LEVEL_COMPANY)
        {
            return $this->searchMemberByLevel($arrReqData);
        } else {
            $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid, mb_emp_permit, mb_nickname, mb_email, mb_phone, mb_time_join, mb_time_last, mb_ip_join, mb_ip_last,  mb_money, mb_point, ";
            $strTbColum .= " mb_money_charge, mb_money_exchange, mb_money_bet, mb_money_earn, mb_color, mb_state_active, mb_state_bet, mb_state_alarm, ";
            $strTbColum .= " mb_game_pb, mb_game_ps, mb_game_ks, mb_game_bb, mb_game_bs, mb_game_ev, mb_game_pb_ratio, mb_game_pb2_ratio, mb_game_ps_ratio, ";
            $strTbColum .= " mb_game_ks_ratio, mb_game_bb_ratio, mb_game_bb2_ratio, mb_game_bs_ratio, mb_game_ev_ratio, mb_live_money";

            $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid, r.mb_emp_permit, r.mb_nickname, r.mb_email, r.mb_phone, r.mb_time_join, r.mb_time_last, r.mb_ip_join, r.mb_ip_last, r.mb_money, r.mb_point, ";
            $strTbRColum .= " r.mb_money_charge, r.mb_money_exchange, r.mb_money_bet, r.mb_money_earn, r.mb_color, r.mb_state_active, r.mb_state_bet, r.mb_state_alarm, ";
            $strTbRColum .= " r.mb_game_pb, r.mb_game_ps, r.mb_game_ks, r.mb_game_bb, r.mb_game_bs, r.mb_game_ev, r.mb_game_pb_ratio, r.mb_game_pb2_ratio, r.mb_game_ps_ratio, ";
            $strTbRColum .= " r.mb_game_ks_ratio, r.mb_game_bb_ratio, r.mb_game_bb2_ratio, r.mb_game_bs_ratio, r.mb_game_ev_ratio, r.mb_live_money ";


            $strSQL = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSQL .= " ( SELECT ".$strTbColum." FROM ".$this->table." WHERE mb_emp_fid = '".$nAdminFid."'";
            $strSQL .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->table." r ";
            $strSQL .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";
            $strSQL .= " SELECT * FROM tbmember where ";
            if ($arrReqData['mb_level'] == 0){
                $strSQL .= " mb_level < '7' ";
            }
            else {
                $strSQL .= " mb_level = '".$arrReqData['mb_level']."' ";
            }
            

            if($arrReqData['mb_emp_fid'] > 0){            
                $strSQL.=" AND mb_emp_fid = '".$arrReqData['mb_emp_fid']."' ";
            }
            if(strlen($arrReqData['mb_uid']) > 0){            
                $strSQL.=" AND mb_uid = '".$arrReqData['mb_uid']."' ";
            }

            $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
            $strSQL.=" ORDER BY mb_time_join DESC LIMIT ".$nStartRow.", ".$arrReqData['count'];
            

            return $this -> db -> query($strSQL)->getResult();
          
        }

    }
}