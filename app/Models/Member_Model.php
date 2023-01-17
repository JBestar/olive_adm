<?php

namespace App\Models;

use CodeIgniter\Model;

class Member_Model extends Model
{
    protected $table = 'member';
    private $chargeTb = 'member_charge';
    protected $exchangeTb = 'member_exchange';
    protected $rewardTb = 'bet_reward';
    protected $returnType = 'object'; 

    protected $allowedFields = [
        'mb_uid',
        'mb_pwd',
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
        'mb_time_bet', 
        'mb_time_call', 
        'mb_ip_join',
        'mb_ip_last',
        'mb_money',
        'mb_point',
        'mb_money_charge',
        'mb_money_exchange',
        'mb_grade',
        'mb_color',
        'mb_state_active',
        'mb_state_bet',
        'mb_state_delete',
        'mb_state_alarm',
        'mb_state_view',
        'mb_game_pb',
        'mb_game_ps',
        'mb_game_bb',
        'mb_game_bs',
        'mb_game_cs',
        'mb_game_sl',
        'mb_game_eo',
        'mb_game_co',
        'mb_game_pb_ratio',
        'mb_game_pb2_ratio',
        'mb_game_ps_ratio',
        'mb_game_bb_ratio',
        'mb_game_bb2_ratio',
        'mb_game_bs_ratio',
        'mb_game_cs_ratio',
        'mb_game_sl_ratio',
        'mb_game_eo_ratio',
        'mb_game_eo2_ratio',
        'mb_game_co_ratio',
        'mb_game_co2_ratio',
        'mb_game_pb_percent',
        'mb_game_pb2_percent',
        'mb_game_ps_percent',
        'mb_game_bb_percent',
        'mb_game_bb2_percent',
        'mb_game_bs_percent',
        'mb_game_eo_percent',
        'mb_game_eo2_percent',
        'mb_game_co_percent',
        'mb_game_co2_percent',
        'mb_blank_count',
        'mb_range_ev', 
        'mb_live_id',
        'mb_live_uid',
        'mb_live_money',
        'mb_slot_uid',
        'mb_slot_money',
        'mb_fslot_id',
        'mb_fslot_uid',
        'mb_fslot_money',
        'mb_kgon_id',
        'mb_kgon_uid',
        'mb_kgon_money',
        'mb_gslot_uid',
        'mb_gslot_money',
    ];

    protected $primaryKey = 'mb_fid';
    
    private $getFields = ['mb_fid', 'mb_uid', 'mb_level','mb_emp_fid', 'mb_emp_permit', 'mb_nickname', 
        'mb_email', 'mb_phone', 'mb_bank_name', 'mb_bank_own', 'mb_bank_num', 'mb_bank_pwd',
        'mb_time_bet', 'mb_ip_join', 'mb_ip_last',
        'mb_money', 'mb_point', 'mb_money_charge', 'mb_money_exchange', 'mb_grade', 'mb_color',
        'mb_state_active', 'mb_state_delete', 'mb_state_alarm', 'mb_state_view',
        'mb_game_pb', 'mb_game_ps', 'mb_game_bb', 'mb_game_bs', 'mb_game_cs', 'mb_game_sl', 'mb_game_eo', 'mb_game_co', 
        'mb_game_pb_ratio', 'mb_game_pb2_ratio','mb_game_ps_ratio', 'mb_game_bb_ratio', 'mb_game_bb2_ratio', 
        'mb_game_bs_ratio', 'mb_game_cs_ratio', 'mb_game_sl_ratio', 'mb_game_eo_ratio', 'mb_game_eo2_ratio', 'mb_game_co_ratio', 'mb_game_co2_ratio', 
        'mb_game_pb_percent', 'mb_game_pb2_percent', 'mb_game_ps_percent', 'mb_game_bb_percent',
        'mb_game_bb2_percent', 'mb_game_bs_percent', 'mb_game_eo_percent', 'mb_game_eo2_percent', 'mb_game_co_percent', 'mb_game_co2_percent', 'mb_blank_count',
        'mb_live_id', 'mb_live_uid', 'mb_live_money', 
        'mb_slot_uid', 'mb_slot_money', 
        'mb_fslot_id', 'mb_fslot_uid', 'mb_fslot_money',
        'mb_kgon_id', 'mb_kgon_uid', 'mb_kgon_money',
        'mb_gslot_uid', 'mb_gslot_money', 
    ];


    private $fields = ['mb_fid', 'mb_uid', 'mb_level','mb_emp_fid', 'mb_emp_permit', 'mb_nickname', 
            'mb_ip_join', 'mb_ip_last',
            'mb_money', 'mb_point', 'mb_money_charge', 'mb_money_exchange', 'mb_grade', 
            'mb_state_active', 'mb_state_delete', 'mb_state_alarm', 'mb_state_view',
            'mb_game_pb', 'mb_game_ps', 'mb_game_bb', 'mb_game_bs', 'mb_game_cs', 'mb_game_sl', 'mb_game_eo', 'mb_game_co', 
            'mb_game_pb_ratio', 'mb_game_pb2_ratio','mb_game_ps_ratio', 'mb_game_bb_ratio', 'mb_game_bb2_ratio', 
            'mb_game_bs_ratio', 'mb_game_cs_ratio', 'mb_game_sl_ratio', 'mb_game_eo_ratio', 'mb_game_eo2_ratio', 'mb_game_co_ratio', 'mb_game_co2_ratio', 
            'mb_game_pb_percent', 'mb_game_pb2_percent', 'mb_game_ps_percent', 'mb_game_bb_percent',
            'mb_game_bb2_percent', 'mb_game_bs_percent', 'mb_game_eo_percent', 'mb_game_eo2_percent', 'mb_game_co_percent', 'mb_game_co2_percent', 
            'mb_live_id', 'mb_live_uid', 'mb_live_money', 
            'mb_slot_uid', 'mb_slot_money', 
            'mb_fslot_id', 'mb_fslot_uid', 'mb_fslot_money' ,
            'mb_kgon_id', 'mb_kgon_uid', 'mb_kgon_money',
            'mb_gslot_uid', 'mb_gslot_money', 
        ];
        
    
    protected $validationRules = [
        'mb_uid' => 'required|alpha_numeric|is_unique[member.mb_uid, mb_fid, {mb_fid}]',
        'mb_nickname' => 'required|min_length[2]|max_length[20]|is_unique[member.mb_nickname, mb_fid, {mb_fid}]',
        'mb_pwd' => 'required',
        'mb_level' => 'required',
        'mb_phone' => 'required|numeric',
        'mb_bank_pwd' => 'required',
        'mb_bank_name' => 'required',
    ];
    protected $validationMessages = [
        'mb_uid' => [
            'required' => '아이디는 필수입력사항입니다.',
            'alpha_numeric' => '아이디는 영문,숫자만 가능합니다.',
            'is_unique' => '아이디가 이미 존재합니다.',
        ],
        'mb_nickname' => [
            'required' => '닉네임은 필수입력사항입니다.',
            'min_length' => '닉네임은 최소 2글자 이상입니다.',
            'max_length' => '닉네임은 최대 20글자 이하입니다.',
            'is_unique' => '닉네임이 이미 존재합니다.',
        ],
        'mb_pwd' => [
            'required' => '비밀번호는 필수입력사항입니다.',
        ],
        'mb_level' => [
            'required' => '레벨은 필수입력사항입니다.',
        ],
        'mb_phone' => [
            'required' => '핸드폰번호는 필수입력사항입니다.',
            'numeric' => '핸드폰번호는 숫자만 가능합니다.',
        ],
        'mb_bank_pwd' => [
            'required' => '출금비번은 필수입력사항입니다.',
        ],
        'mb_bank_name' => [
            'required' => '은행번호는 필수입력사항입니다.',
        ],
    ];

    
    public function getInfoByFid($strFid, $bAll = false)
    {
        if($bAll)
            return $this->find($strFid);
        else return $this->select($this->getFields)->find($strFid);
    }
    
    public function getInfo($strId)
    {
        return $this->select($this->getFields)->where('mb_uid', $strId)->first();
    }

    public function getInfoByUid($strId)
    {
        $fields = ['mb_fid', 'mb_uid', 'mb_level','mb_nickname', 
            'mb_money', 'mb_point', 'mb_money_charge', 'mb_money_exchange', 'mb_grade', 
            'mb_state_active', 'mb_state_delete', 'mb_state_alarm', 'mb_state_view',
            'mb_game_pb_ratio', 'mb_game_pb2_ratio','mb_game_ps_ratio', 'mb_game_bb_ratio', 'mb_game_bb2_ratio', 
            'mb_game_bs_ratio', 'mb_game_cs_ratio', 'mb_game_sl_ratio', 'mb_game_eo_ratio', 'mb_game_eo2_ratio', 'mb_game_co_ratio', 'mb_game_co2_ratio', 
            'mb_live_money', 'mb_slot_money', 'mb_fslot_money', 'mb_kgon_money', 'mb_gslot_money' ];

        return $this->select($fields)->where('mb_uid', $strId)->first();
    }

    public function getByNickname($strName)
    {
        return $this->select($this->getFields)->where('mb_nickname', $strName)->first();
    }

    public function login($strUserId, $strPwd)
    {
        return $this->where([
            'mb_uid' => $strUserId,
            'mb_pwd' => $strPwd ])
            ->first();
    }

    public function changePassword($strUserId, $arrPwd, &$query)
    {
        if (is_null($arrPwd)) {
            return 0;
        }
        if (!array_key_exists('password', $arrPwd)) {
            return 0;
        }
        if (!array_key_exists('password_new', $arrPwd)) {
            return 0;
        }
        if (!array_key_exists('password_newok', $arrPwd)) {
            return 0;
        }

        
        $objUser = $this->login($strUserId, $arrPwd['password']);
        if (is_null($objUser) ) {
            return 2;
        }

        // 이전 패스워드가 같은가 체크
        if (strlen($arrPwd['password_new']) > 0 && 0 != strcmp($arrPwd['password_new'], $arrPwd['password_newok'])) {
            return 0;
        }

        if(strlen($arrPwd['password_new']) < 1 && !array_key_exists('ip_addr', $arrPwd) )
            return 0; 

        if(strlen($arrPwd['password_new']) > 0)
            $data['mb_pwd'] =  $arrPwd['password_new'];

        if(array_key_exists('ip_addr', $arrPwd) ){
            $data['mb_ip_join'] = $arrPwd['ip_addr'];
            $data['mb_state_view'] = $arrPwd['ip_check'];

        }

        $this->builder()->set($data)
        ->where('mb_fid', $objUser->mb_fid)
        ->update();

        $query = $this->db->getLastQuery();
        return 1;
    }

    public function changeAlarmState($strUserId, $arrReqData)
    {
        if (is_null($arrReqData)) {
            return 0;
        }
        if (!array_key_exists('mb_state_alarm', $arrReqData)) {
            return false;
        }

        return $this->builder()->set('mb_state_alarm', $arrReqData['mb_state_alarm'])
        ->where('mb_uid', $strUserId)
        ->update();
    }

    public function updateCallTm($member){
        $data = [
            'mb_time_call' => date("Y-m-d H:i:s"),
        ];
        return $this->update($member->mb_fid, $data);
    }

    public function moneyProc(&$objUser, $dtMoney, $dtPoint=0, $nCharge=0, $nExchange=0)
    {
        $strSql1 = 'UPDATE '.$this->table.' SET ';
        if ($dtMoney >= 0) {
            $strSql1 .= 'mb_money = mb_money+'.$dtMoney;
        } else {
            $dtMoney = abs($dtMoney);
            $strSql1 .= 'mb_money = mb_money-'.$dtMoney;
        }
        if ($dtPoint > 0) {
            $strSql1 .= ', mb_point = mb_point+'.$dtPoint;
        } elseif ($dtPoint < 0) {
            $dtPoint = abs($dtPoint);
            $strSql1 .= ', mb_point = mb_point-'.$dtPoint;
        }
        if ($nCharge > 0) {
            $strSql1 .= ', mb_money_charge = mb_money_charge+'.$nCharge;
        } elseif ($nCharge < 0) {
            $nCharge = abs($nCharge);
            $strSql1 .= ', mb_money_charge = mb_money_charge-'.$nCharge;
        }
        if ($nExchange > 0) {
            $strSql1 .= ', mb_money_exchange = mb_money_exchange+'.$nExchange;
        } elseif ($nExchange < 0) {
            $nExchange = abs($nExchange);
            $strSql1 .= ', mb_money_exchange = mb_money_exchange-'.$nExchange;
        }

        $this->db->transBegin();
        $strSql2 = 'SELECT mb_money FROM '.$this->table;
        $strSql2 .= ' WHERE mb_fid='.$objUser->mb_fid;
        $objResult = $this->db->query($strSql2)->getRow();

        $strSql1 .= ' WHERE mb_fid='.$objUser->mb_fid;
        $this->db->query($strSql1);

        $bResult = false;

        if (false === $this->db->transStatus()) {
            $this->db->transRollback();
            $bResult = false;
        } else {
            $this->db->transCommit();
            $objUser->mb_money = $objResult->mb_money;
            $bResult = true;
        }

        return $bResult;
    }

    public function trasferMoney($objSender, $objReceiver, $amount){

        if($amount <= 0)
            return false;
        
        $this->db->transBegin();

        $strSql1 = 'UPDATE '.$this->table.' SET ';
        $strSql1 .= 'mb_money = mb_money-'.$amount;
        $strSql1 .= ' WHERE mb_fid='.$objSender->mb_fid;
        $this->db->query($strSql1);

        $strSql2 = 'UPDATE '.$this->table.' SET ';
        $strSql2 .= 'mb_money = mb_money+'.$amount;
        $strSql2 .= ' WHERE mb_fid='.$objReceiver->mb_fid;
        $this->db->query($strSql2);
        
        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            $bResult = false;
        } else {
            $this->db->transCommit();
            $bResult = true;
        }

        return $bResult;
    }

    public function updateMoney($objMember)
    {
        $this->builder()->set('mb_money', $objMember->mb_money);
        $this->builder()->set('mb_money_charge', $objMember->mb_money_charge);
        $this->builder()->set('mb_money_exchange', $objMember->mb_money_exchange);
        $this->builder()->set('mb_point', $objMember->mb_point);

        $this->builder()->where('mb_fid', $objMember->mb_fid);

        return $this->builder()->update();
    }

     // 배팅금액 (하부포함)
     public function allGameRange(&$arrReqData, $confs)
     {
        //  writeLog("allGameRange");
        // if(!$confs['npg_deny']){
        //     $arrReqData['npb_range'] = $this->getBetRangeId($arrReqData, "bet_powerball");
        //     $arrReqData['nps_range'] = $this->getBetRangeId($arrReqData, "bet_powerladder");
        // }
        if($confs['hpg_enable']){
            $arrReqData['hpb_range'] = $this->getBetRangeId($arrReqData, "bet_happyball");
        }
        if(!$confs['bpg_deny']){
            $arrReqData['bpb_range'] = $this->getBetRangeId($arrReqData, "bet_bogleball");
            $arrReqData['bps_range'] = $this->getBetRangeId($arrReqData, "bet_bogleladder");
        }
        if($confs['eos5_enable']){
            $arrReqData['eos5_range'] = $this->getBetRangeId($arrReqData, "bet_eos5ball");
        }
        if($confs['eos3_enable']){
            $arrReqData['eos3_range'] = $this->getBetRangeId($arrReqData, "bet_eos3ball");
        }
        if($confs['coin5_enable']){
            $arrReqData['coin5_range'] = $this->getBetRangeId($arrReqData, "bet_coin5ball");
        }
        if($confs['coin3_enable']){
            $arrReqData['coin3_range'] = $this->getBetRangeId($arrReqData, "bet_coin3ball");
        }
        if(!$confs['cas_deny'] || $confs['kgon_enable']){
            if(isEBalMode()){
                $tbName = "bet_ebal";
            } else 
                $tbName = "bet_casino";
            $arrReqData['cas_range'] = $this->getBetRangeId($arrReqData, $tbName);
        }
        if(!$confs['slot_deny']){
            $arrReqData['slot_range'] = $this->getBetRangeId($arrReqData, "bet_slot");
        }

        $arrReqData['rw_range'] = $this->getRwRangeId($arrReqData, "bet_reward");
        // writeLog("allGameRange END");
        
     }

     public function gameRange(&$arrReqData, $bRw = true)
     {
        if ($arrReqData['type'] == GAME_POWER_BALL ) {
            $arrReqData['gm_range'] = $this->getBetRangeId($arrReqData, "bet_powerball");
        } elseif ($arrReqData['type'] == GAME_POWER_LADDER ) {
            $arrReqData['gm_range'] = $this->getBetRangeId($arrReqData, "bet_powerladder");
        } elseif ($arrReqData['type'] == GAME_CASINO_EVOL ) {
            if(isEBalMode()){
                $tbName = "bet_ebal";
            } else 
                $tbName = "bet_casino";
            $arrReqData['gm_range'] = $this->getBetRangeId($arrReqData, $tbName);
        } elseif ($arrReqData['type'] == GAME_BOGLE_BALL ) {
            $arrReqData['gm_range'] = $this->getBetRangeId($arrReqData, "bet_bogleball");
        } elseif ($arrReqData['type'] == GAME_BOGLE_LADDER ) {
            $arrReqData['gm_range'] = $this->getBetRangeId($arrReqData, "bet_bogleladder");
        } elseif ($arrReqData['type'] == GAME_SLOT_1 || $arrReqData['type'] == GAME_SLOT_2 || $arrReqData['type'] == GAME_SLOT_3 || $arrReqData['type'] == GAME_SLOT_12 ) {
            $arrReqData['gm_range'] = $this->getBetRangeId($arrReqData, "bet_slot");
        } elseif ($arrReqData['type'] == GAME_EOS5_BALL ) {
            $arrReqData['gm_range'] = $this->getBetRangeId($arrReqData, "bet_eos5ball");
        } elseif ($arrReqData['type'] == GAME_EOS3_BALL ) {
            $arrReqData['gm_range'] = $this->getBetRangeId($arrReqData, "bet_eos3ball");
        } elseif ($arrReqData['type'] == GAME_COIN5_BALL ) {
            $arrReqData['gm_range'] = $this->getBetRangeId($arrReqData, "bet_coin5ball");
        } elseif ($arrReqData['type'] == GAME_COIN3_BALL ) {
            $arrReqData['gm_range'] = $this->getBetRangeId($arrReqData, "bet_coin3ball");
        } elseif ($arrReqData['type'] == GAME_HAPPY_BALL ) {
            $arrReqData['gm_range'] = $this->getBetRangeId($arrReqData, "bet_happyball");
        }
        if($bRw)
            $arrReqData['rw_range'] = $this->getRwRangeId($arrReqData, "bet_reward");
        
     }

     public function getBetRangeId($arrReqData, $tbName){
        $range = [-1, -1];
        
        $strCond = ""; 
        if (strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0) {
            $strCond = " WHERE ".getBetTimeRange($arrReqData);
        }
        $strSQL = " SELECT MIN(bet_fid) AS min_fid, MAX(bet_fid) AS max_fid FROM ".$tbName;
        $strSQL.= $strCond; 

        // writeLog($strSQL);
        $objResult = $this->db->query($strSQL)->getRow();
        // writeLog("getBetRangeId END");

        if (!is_null($objResult->min_fid) && !is_null($objResult->max_fid)) {
            $range[0] = $objResult->min_fid;
            $range[1] = $objResult->max_fid;
         }
         return $range;
     }

     public function getBetMinId($arrReqData, $tbName){
        $range = [-1, -1];
        
        $strCond = ""; 
        if (strlen($arrReqData['start']) > 0) {
            $strCond = " WHERE bet_time >= '".$arrReqData['start']."' " ;
        }
        $strSQL = " SELECT MIN(bet_fid) AS min_fid FROM ".$tbName;
        $strSQL.= $strCond; 

        // writeLog($strSQL);
        $objResult = $this->db->query($strSQL)->getRow();
        // writeLog("getBetRangeId END");

        if (!is_null($objResult->min_fid)) {
            $range[0] = $objResult->min_fid;
         }
         
         return $range;
     }

     public function getRwRangeId($arrReqData, $tbName){
        $range = [-1, -1];
        
        $strCond = ""; 
        if (strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0) {
            $strCond = " WHERE ".getTimeRange("rw_time", $arrReqData);
        }
        $strSQL = " SELECT MIN(rw_fid) AS min_fid, MAX(rw_fid) AS max_fid FROM ".$tbName;
        $strSQL.= $strCond; 

        // writeLog($strSQL);
        $objResult = $this->db->query($strSQL)->getRow();
        // writeLog("getRwRangeId END");

        if (!is_null($objResult->min_fid) && !is_null($objResult->max_fid)) {
            $range[0] = $objResult->min_fid;
            $range[1] = $objResult->max_fid;
         }
         return $range;
     }
     /*
    // 배팅금액 (하부포함)
    public function calcBetMoneys($objEmp, $arrReqData, $confs)
    {
        // $strCond = "";
        // if (strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0) {
        //     $strCond = " WHERE ".getBetTimeRange($arrReqData);
        // }

        $strTbColum = ' mb_fid, mb_uid, mb_level, mb_emp_fid ';
        $strTbRColum = ' r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid ';

        $strSQL = 'WITH RECURSIVE tbmember ('.$strTbColum.') AS';
        $strSQL .= ' ( SELECT '.$strTbColum.' FROM '.$this->table." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
        $strSQL .= ' UNION ALL SELECT '.$strTbRColum.' FROM '.$this->table.' r ';
        $strSQL .= ' INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )';

        $strSQL .= ' SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money ';
        $strSQL .= '  FROM ( SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_slot';
        $strSQL .= " WHERE bet_fid >= ".$arrReqData['slot_range'][0]." AND bet_fid <= ".$arrReqData['slot_range'][1];
        $strSQL .= " AND bet_mb_uid IN (SELECT mb_uid from tbmember UNION ALL SELECT '".$objEmp->mb_uid."' as mb_uid) ";

        // if(!$confs['npg_deny']){
        //     $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_powerball ';
        //     $strSQL .= " WHERE bet_fid >= ".$arrReqData['npb_range'][0]." AND bet_fid <= ".$arrReqData['npb_range'][1];
        //     $strSQL .= " AND bet_mb_uid IN (SELECT mb_uid from tbmember UNION ALL SELECT '".$objEmp->mb_uid."' as mb_uid) )";

        //     $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_powerladder ';
        //     $strSQL .= " WHERE bet_fid >= ".$arrReqData['nps_range'][0]." AND bet_fid <= ".$arrReqData['nps_range'][1];
        //     $strSQL .= " AND bet_mb_uid IN (SELECT mb_uid from tbmember UNION ALL SELECT '".$objEmp->mb_uid."' as mb_uid) )";
        // }
        if($confs['hpg_enable']){
            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_happyball ';
            $strSQL .= " WHERE bet_fid >= ".$arrReqData['hpb_range'][0]." AND bet_fid <= ".$arrReqData['hpb_range'][1];
            $strSQL .= " AND bet_mb_uid IN (SELECT mb_uid from tbmember UNION ALL SELECT '".$objEmp->mb_uid."' as mb_uid) )";
        }

        if(!$confs['bpg_deny']){
            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_bogleball ';
            $strSQL .= " WHERE bet_fid >= ".$arrReqData['bpb_range'][0]." AND bet_fid <= ".$arrReqData['bpb_range'][1];
            $strSQL .= " AND bet_mb_uid IN (SELECT mb_uid from tbmember UNION ALL SELECT '".$objEmp->mb_uid."' as mb_uid) )";

            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_bogleladder ';
            $strSQL .= " WHERE bet_fid >= ".$arrReqData['bps_range'][0]." AND bet_fid <= ".$arrReqData['bps_range'][1];
            $strSQL .= " AND bet_mb_uid IN (SELECT mb_uid from tbmember UNION ALL SELECT '".$objEmp->mb_uid."' as mb_uid) )";
        }

        if($confs['eos5_enable']){
            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_eos5ball ';
            $strSQL .= " WHERE bet_fid >= ".$arrReqData['eos5_range'][0]." AND bet_fid <= ".$arrReqData['eos5_range'][1];
            $strSQL .= " AND bet_mb_uid IN (SELECT mb_uid from tbmember UNION ALL SELECT '".$objEmp->mb_uid."' as mb_uid) )";
        }

        if($confs['eos3_enable']){
            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_eos3ball ';
            $strSQL .= " WHERE bet_fid >= ".$arrReqData['eos3_range'][0]." AND bet_fid <= ".$arrReqData['eos3_range'][1];
            $strSQL .= " AND bet_mb_uid IN (SELECT mb_uid from tbmember UNION ALL SELECT '".$objEmp->mb_uid."' as mb_uid) )";
        }

        if($confs['coin5_enable']){
            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_coin5ball ';
            $strSQL .= " WHERE bet_fid >= ".$arrReqData['coin5_range'][0]." AND bet_fid <= ".$arrReqData['coin5_range'][1];
            $strSQL .= " AND bet_mb_uid IN (SELECT mb_uid from tbmember UNION ALL SELECT '".$objEmp->mb_uid."' as mb_uid) )";
        }

        if($confs['coin3_enable']){
            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_coin3ball ';
            $strSQL .= " WHERE bet_fid >= ".$arrReqData['coin3_range'][0]." AND bet_fid <= ".$arrReqData['coin3_range'][1];
            $strSQL .= " AND bet_mb_uid IN (SELECT mb_uid from tbmember UNION ALL SELECT '".$objEmp->mb_uid."' as mb_uid) )";
        }

        if(!$confs['cas_deny'] || $confs['kgon_enable']){
            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_casino ';
            $strSQL .= " WHERE bet_fid >= ".$arrReqData['cas_range'][0]." AND bet_fid <= ".$arrReqData['cas_range'][1];
            $strSQL .= " AND company_amount = 0 AND bet_mb_uid IN (SELECT mb_uid from tbmember UNION ALL SELECT '".$objEmp->mb_uid."' as mb_uid) )";
        }
        $strSQL .= " ) AS bet_table ";

        // writeLog($strSQL);

        $objResult = $this->db->query($strSQL)->getRow();
        // writeLog("calcBetMoneys END");

        $arrBetData['bet_money'] = 0;          // 배팅머니
        $arrBetData['bet_win_money'] = 0;      // 적중머니
        $arrBetData['bet_benefit_money'] = 0;  // 배팅손익

         if (!is_null($objResult->bet_money)) {
             $arrBetData['bet_money'] += $objResult->bet_money;
         }
        if (!is_null($objResult->bet_win_money)) {
            $arrBetData['bet_win_money'] += $objResult->bet_win_money;
        }

        $arrBetData['bet_benefit_money'] = $arrBetData['bet_money'] - $arrBetData['bet_win_money'];  // 배팅손익

        return $arrBetData;
    }

    // 배팅금액 (하부포함)
    public function calcBetMoneysByGame($objEmp, $arrReqData)
    {
        $strTbColum = ' mb_fid, mb_uid, mb_level, mb_emp_fid ';
        $strTbRColum = ' r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid ';

        $strSQL = 'WITH RECURSIVE tbmember ('.$strTbColum.') AS';
        $strSQL .= ' ( SELECT '.$strTbColum.' FROM '.$this->table." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
        $strSQL .= ' UNION ALL SELECT '.$strTbRColum.' FROM '.$this->table.' r ';
        $strSQL .= ' INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )';

        $strSQL .= ' SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM ';
        if ($arrReqData['type'] == GAME_POWER_BALL ) {
            $strSQL .= ' bet_powerball ';
        } elseif ($arrReqData['type'] == GAME_POWER_LADDER ) {
            $strSQL .= ' bet_powerladder ';
        } elseif ($arrReqData['type'] == GAME_CASINO_EVOL ) {
            $strSQL .= ' bet_casino ';
        } elseif ($arrReqData['type'] == GAME_BOGLE_BALL ) {
            $strSQL .= ' bet_bogleball ';
        } elseif ($arrReqData['type'] == GAME_BOGLE_LADDER ) {
            $strSQL .= ' bet_bogleladder ';
        } elseif ($arrReqData['type'] == GAME_SLOT_1 || $arrReqData['type'] == GAME_SLOT_2 || $arrReqData['type'] == GAME_SLOT_3 || $arrReqData['type'] == GAME_SLOT_12 ) {
            $strSQL .= ' bet_slot ';
        } elseif ($arrReqData['type'] == GAME_EOS5_BALL ) {
            $strSQL .= ' bet_eos5ball ';
        } elseif ($arrReqData['type'] == GAME_EOS3_BALL ) {
            $strSQL .= ' bet_eos3ball ';
        } elseif ($arrReqData['type'] == GAME_COIN5_BALL ) {
            $strSQL .= ' bet_coin5ball ';
        } elseif ($arrReqData['type'] == GAME_COIN3_BALL ) {
            $strSQL .= ' bet_coin3ball ';
        } elseif ($arrReqData['type'] == GAME_HAPPY_BALL ) {
            $strSQL .= ' bet_happyball ';
        } else {
            return null;
        }
        $strSQL .= " WHERE bet_fid >= ".$arrReqData['gm_range'][0]." AND bet_fid <= ".$arrReqData['gm_range'][1];
        if ($arrReqData['type'] == GAME_SLOT_1 || $arrReqData['type'] == GAME_SLOT_2 || $arrReqData['type'] == GAME_SLOT_3){
            $strSQL .= " AND bet_game_id = '".$arrReqData['type']."' ";
        }
        $strSQL .= " AND bet_mb_uid IN (SELECT mb_uid from tbmember UNION ALL SELECT '".$objEmp->mb_uid."' as mb_uid) ";
        // writeLog($strSQL);
        $objResult = $this->db->query($strSQL)->getRow();
        // writeLog("calcBetMoneysByGame END");

        $arrBetData['bet_money'] = 0;          // 배팅머니
        $arrBetData['bet_win_money'] = 0;      // 적중머니
        $arrBetData['bet_benefit_money'] = 0;  // 배팅손익

         if (!is_null($objResult->bet_money)) {
             $arrBetData['bet_money'] += $objResult->bet_money;
         }
        if (!is_null($objResult->bet_win_money)) {
            $arrBetData['bet_win_money'] += $objResult->bet_win_money;
        }

        $arrBetData['bet_benefit_money'] = $arrBetData['bet_money'] - $arrBetData['bet_win_money'];  // 배팅손익

        return $arrBetData;
    }
    */
    //유저별 충환전 총액
    public function calcTransfer(&$objUser){
        $strSQL = " SELECT SUM(charge_money) AS result_1 FROM ".$this->chargeTb;
        $strSQL.=" WHERE (charge_action_state = '".STATE_VERIFY."' OR charge_action_state = '".STATE_HOT."') "; 
        $strSQL.=" AND charge_mb_uid = '".$objUser->mb_uid."' ";
        $strSQL .= " UNION ALL (SELECT SUM(exchange_money) AS result_1 FROM ".$this->exchangeTb;
        $strSQL.=" WHERE (exchange_action_state = '".STATE_VERIFY."' OR exchange_action_state = '".STATE_HOT."') "; 
        $strSQL.=" AND exchange_mb_uid = '".$objUser->mb_uid."') ";
        $arrResult = $this->db->query($strSQL)->getResult();

        if(is_null($arrResult) || count($arrResult) != 2){
            $objUser->mb_money_charge = 0;
            $objUser->mb_money_exchange = 0;
        } else {
            $objUser->mb_money_charge = $arrResult[0]->result_1 != null ? $arrResult[0]->result_1 : 0 ;
            $objUser->mb_money_exchange = $arrResult[1]->result_1 != null ? $arrResult[1]->result_1 : 0 ;
        }
    }

    private function calcCommonSql($objEmp, $arrReqData){
        $strTbColum = ' mb_fid, mb_uid, mb_level, mb_emp_fid, mb_state_active, mb_money, mb_point, mb_live_money, mb_slot_money, mb_fslot_money, mb_kgon_money, mb_gslot_money ';
        $strTbRColum = ' r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid, r.mb_state_active, r.mb_money, r.mb_point, r.mb_live_money, r.mb_slot_money, r.mb_fslot_money, r.mb_kgon_money, r.mb_gslot_money ';

        $strSQL = 'WITH RECURSIVE tbmember ('.$strTbColum.') AS';
        $strSQL .= ' ( SELECT '.$strTbColum.' FROM '.$this->table." WHERE "; 
        $strSQL .= " mb_fid = '".$objEmp->mb_fid."'";
        $strSQL .= ' UNION ALL SELECT '.$strTbRColum.' FROM '.$this->table.' r ';
        $strSQL .= ' INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )';
        //보유금액
        $strSQL .= " SELECT SUM(mb_money+mb_live_money+mb_slot_money+mb_fslot_money+mb_kgon_money+mb_gslot_money) AS result_1, ";
        $strSQL .= " SUM(CASE WHEN mb_fid = ".$objEmp->mb_fid." THEN mb_money+mb_live_money+mb_slot_money+mb_fslot_money+mb_kgon_money+mb_gslot_money ELSE 0 END) AS result_2 "; 
        $strSQL .= " FROM tbmember  WHERE mb_state_active != '".PERMIT_DELETE."' ";
        //포인트
        $strSQL .= " UNION ALL ( SELECT SUM(mb_point) AS result_1, ";
        $strSQL .= " SUM(CASE WHEN mb_fid = ".$objEmp->mb_fid." THEN mb_point ELSE 0 END) AS result_2 ";
        $strSQL .= " FROM tbmember  WHERE mb_state_active != '".PERMIT_DELETE."' )";
        //회원수
        $strSQL .= " UNION ALL ( SELECT COUNT(mb_fid) AS result_1, 0 AS result_2 ";
        $strSQL .= " FROM tbmember  WHERE mb_state_active != '".PERMIT_DELETE."' )";
        //충전금액
        $strSQL .= " UNION ALL ( SELECT SUM(charge_money) AS result_1, '0' AS result_2 FROM ".$this->chargeTb;
        $strSQL.=" WHERE (charge_action_state = '2'  OR charge_action_state = '5') ";
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 )
            $strSQL.=" AND ".getTimeRange("charge_time_require", $arrReqData);
        $strSQL .= " AND charge_mb_uid IN (SELECT mb_uid from tbmember ) )";
        //환전금액
        $strSQL .= " UNION ALL ( SELECT SUM(exchange_money) AS result_1, '0' AS result_2 FROM ".$this->exchangeTb;
        $strSQL.=" WHERE (exchange_action_state = '2'  OR exchange_action_state = '5') ";
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 )
            $strSQL.=" AND ".getTimeRange("exchange_time_require", $arrReqData);
        $strSQL .= " AND exchange_mb_uid IN (SELECT mb_uid from tbmember ) )";

        return $strSQL;
    }

    public function calculate($objEmp, $arrReqData, $confs){

        $strSQL = $this->calcCommonSql($objEmp, $arrReqData);
        // $strWhereMem = " AND (bet_emp_fid IN (SELECT mb_emp_fid FROM tbmember WHERE mb_fid != ".$objEmp->mb_fid." GROUP BY mb_emp_fid ) OR bet_mb_uid = '".$objEmp->mb_uid."')";
        $strWhereMem = " AND bet_mb_uid IN (SELECT mb_uid from tbmember) ";
        //배팅금액
        $strSQL .= ' UNION ALL ( SELECT SUM(bet_money) AS result_1, SUM(bet_win_money) AS result_2 ';
        $strSQL .= '  FROM ( SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_slot';
        $strSQL .= " WHERE bet_fid >= ".$arrReqData['slot_range'][0]." AND bet_fid <= ".$arrReqData['slot_range'][1];
        $strSQL .= $strWhereMem;
        // $strSQL .= " AND bet_mb_fid IN (SELECT mb_fid from tbmember) ";

        // if(!$confs['npg_deny']){
        //     $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_powerball ';
        //     $strSQL .= " WHERE bet_fid >= ".$arrReqData['npb_range'][0]." AND bet_fid <= ".$arrReqData['npb_range'][1];
        //     $strSQL .= $strWhereMem." )";

        //     $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_powerladder ';
        //     $strSQL .= " WHERE bet_fid >= ".$arrReqData['nps_range'][0]." AND bet_fid <= ".$arrReqData['nps_range'][1];
        //     $strSQL .= $strWhereMem." )";
        // }
        if($confs['hpg_enable']){
            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_happyball ';
            $strSQL .= " WHERE bet_fid >= ".$arrReqData['hpb_range'][0]." AND bet_fid <= ".$arrReqData['hpb_range'][1];
            $strSQL .= $strWhereMem." )";
        }
        if(!$confs['bpg_deny']){
            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_bogleball ';
            $strSQL .= " WHERE bet_fid >= ".$arrReqData['bpb_range'][0]." AND bet_fid <= ".$arrReqData['bpb_range'][1];
            $strSQL .= $strWhereMem." )";

            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_bogleladder ';
            $strSQL .= " WHERE bet_fid >= ".$arrReqData['bps_range'][0]." AND bet_fid <= ".$arrReqData['bps_range'][1];
            $strSQL .= $strWhereMem." )";
        }

        if($confs['eos5_enable']){
            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_eos5ball ';
            $strSQL .= " WHERE bet_fid >= ".$arrReqData['eos5_range'][0]." AND bet_fid <= ".$arrReqData['eos5_range'][1];
            $strSQL .= $strWhereMem." )";
        }

        if($confs['eos3_enable']){
            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_eos3ball ';
            $strSQL .= " WHERE bet_fid >= ".$arrReqData['eos3_range'][0]." AND bet_fid <= ".$arrReqData['eos3_range'][1];
            $strSQL .= $strWhereMem." )";
        }

        if($confs['coin5_enable']){
            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_coin5ball ';
            $strSQL .= " WHERE bet_fid >= ".$arrReqData['coin5_range'][0]." AND bet_fid <= ".$arrReqData['coin5_range'][1];
            $strSQL .= $strWhereMem." )";
        }

        if($confs['coin3_enable']){
            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_coin3ball ';
            $strSQL .= " WHERE bet_fid >= ".$arrReqData['coin3_range'][0]." AND bet_fid <= ".$arrReqData['coin3_range'][1];
            $strSQL .= $strWhereMem." )";
        }

        if(!$confs['cas_deny'] || $confs['kgon_enable']){
            if(isEBalMode()){
                $tbName = "bet_ebal";
                $strWhereMem2= " AND bet_mb_fid IN (SELECT mb_fid from tbmember) ";
                $strWhere2 = " point_amount <> ".BET_STATE_TIE;
            } else {
                $tbName = "bet_casino";
                $strWhereMem2= $strWhereMem;
                $strWhere2 = " bet_money <> bet_win_money ";
            }
            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM '.$tbName;
            $strSQL .= " WHERE bet_fid >= ".$arrReqData['cas_range'][0]." AND bet_fid <= ".$arrReqData['cas_range'][1];
            $strSQL .= " AND ".$strWhere2." AND company_amount = 0 ";  //sum without Tie
            $strSQL .= $strWhereMem2." )";
        }
        $strSQL .= " ) AS bet_table ) ";
        //적립포인트
        $strSQL .= " UNION ALL ( SELECT SUM(rw_point) AS result_1, ";
        $strSQL .= " SUM(CASE WHEN rw_mb_fid = ".$objEmp->mb_fid." THEN rw_point ELSE 0 END) AS result_2 FROM ".$this->rewardTb;
        $strSQL .= " WHERE rw_fid >= ".$arrReqData['rw_range'][0]." AND rw_fid <= ".$arrReqData['rw_range'][1];
        if($arrReqData['rw_blank']){
            $strSQL.=" AND rw_state = '0' ";
        }
        $strSQL .= " AND rw_mb_fid IN (SELECT mb_fid from tbmember) )";
        
        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog($strSQL);
        $arrResult = $this->db->query($strSQL)->getResult();
        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog("calculate END");

        return $arrResult;

    }

    public function calculateByGame($objEmp, $arrReqData){

        $strSQL = $this->calcCommonSql($objEmp, $arrReqData);
        //배팅금액
        $strSQL .= ' UNION ALL ( SELECT SUM(bet_money) AS result_1, SUM(bet_win_money) AS result_2 FROM ';
        if ($arrReqData['type'] == GAME_POWER_BALL ) {
            $strSQL .= ' bet_powerball ';
        } elseif ($arrReqData['type'] == GAME_POWER_LADDER ) {
            $strSQL .= ' bet_powerladder ';
        } elseif ($arrReqData['type'] == GAME_CASINO_EVOL ) {
            if(isEBalMode()){
                $tbName = "bet_ebal";
            } else 
                $tbName = "bet_casino";
            $strSQL .= $tbName;
        } elseif ($arrReqData['type'] == GAME_BOGLE_BALL ) {
            $strSQL .= ' bet_bogleball ';
        } elseif ($arrReqData['type'] == GAME_BOGLE_LADDER ) {
            $strSQL .= ' bet_bogleladder ';
        } elseif ($arrReqData['type'] == GAME_SLOT_1 || $arrReqData['type'] == GAME_SLOT_2 || $arrReqData['type'] == GAME_SLOT_3 || $arrReqData['type'] == GAME_SLOT_12 ) {
            $strSQL .= ' bet_slot ';
        } elseif ($arrReqData['type'] == GAME_EOS5_BALL ) {
            $strSQL .= ' bet_eos5ball ';
        } elseif ($arrReqData['type'] == GAME_EOS3_BALL ) {
            $strSQL .= ' bet_eos3ball ';
        } elseif ($arrReqData['type'] == GAME_COIN5_BALL ) {
            $strSQL .= ' bet_coin5ball ';
        } elseif ($arrReqData['type'] == GAME_COIN3_BALL ) {
            $strSQL .= ' bet_coin3ball ';
        } else if ($arrReqData['type'] == GAME_HAPPY_BALL ) {
            $strSQL .= ' bet_happyball ';
        } else {
            return null;
        }
        $strSQL .= " WHERE bet_fid >= ".$arrReqData['gm_range'][0]." AND bet_fid <= ".$arrReqData['gm_range'][1];
        if ($arrReqData['type'] == GAME_SLOT_1 || $arrReqData['type'] == GAME_SLOT_2  || $arrReqData['type'] == GAME_SLOT_3){
            $strSQL .= " AND bet_game_id = '".$arrReqData['type']."' ";
        } else if ($arrReqData['type'] == GAME_CASINO_EVOL ) {
            if(isEBalMode()){
                $strSQL .= " AND point_amount <> ".BET_STATE_TIE;  //sum without Tie
            } else 
                $strSQL .= " AND bet_money <> bet_win_money ";  //sum without Tie
        }
        if(isEBalMode() && $arrReqData['type'] == GAME_CASINO_EVOL){
            $strSQL .= " AND bet_mb_fid IN (SELECT mb_fid from tbmember) ) ";
        } else
            $strSQL .= " AND bet_mb_uid IN (SELECT mb_uid from tbmember) ) ";
        //포인트
        $strSQL .= " UNION ALL ( SELECT SUM(rw_point) AS result_1, ";
        $strSQL .= " SUM(CASE WHEN rw_mb_fid = ".$objEmp->mb_fid." THEN rw_point ELSE 0 END) AS result_2 FROM ".$this->rewardTb;
        $strSQL .= " WHERE rw_fid >= ".$arrReqData['rw_range'][0]." AND rw_fid <= ".$arrReqData['rw_range'][1];
        $strSQL .= " AND rw_mb_fid IN (SELECT mb_fid from tbmember) ";
        if($arrReqData['type'] == GAME_SLOT_12){
            if($_ENV['app.type'] == APPTYPE_4)
                $strSQL.=" AND rw_game IN ( '".GAME_SLOT_1."', '".GAME_SLOT_3."') ";
            else
                $strSQL.=" AND rw_game IN ( '".GAME_SLOT_1."', '".GAME_SLOT_2."') ";
        }
        else if($arrReqData['type'] > 0)
            $strSQL.=" AND rw_game = '".$arrReqData['type']."' ";
        if($arrReqData['rw_blank']){
            $strSQL.=" AND rw_state = '0' ";
        }
        $strSQL.= " ) ";

        // writeLog($strSQL);
        $arrResult = $this->db->query($strSQL)->getResult();
        // writeLog("calcPoint END");

        return $arrResult;
    }
    
    // 배팅금액 (하부포함)
    public function calcUserBet($arrReqData, $confs)
    {
        $strCond = " WHERE bet_mb_uid = '".$arrReqData['mb_uid']."' ";
        if (array_key_exists('start', $arrReqData) && strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0) {
            $strCond .= " AND ".getBetTimeRange($arrReqData);
        }

        $strSQL = ' SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money ';
        $strSQL .= '  FROM  (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_slot';
        $strSQL .= $strCond;

        // if(!$confs['npg_deny']){
        //     $strSQL .= 'UNION ALL SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_powerball ';
        //     $strSQL .= $strCond;

        //     $strSQL .= 'UNION ALL SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_powerladder ';
        //     $strSQL .= $strCond;
        // }
        if($confs['hpg_enable']){
            $strSQL .= 'UNION ALL SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_happyball ';
            $strSQL .= $strCond;
        }
        if(!$confs['bpg_deny']){
            $strSQL .= 'UNION ALL SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_bogleball ';
            $strSQL .= $strCond;

            $strSQL .= 'UNION ALL SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_bogleladder ';
            $strSQL .= $strCond;
        }

        if($confs['eos5_enable']){
            $strSQL .= 'UNION ALL SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_eos5ball ';
            $strSQL .= $strCond;
        }

        if($confs['eos3_enable']){
            $strSQL .= 'UNION ALL SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_eos3ball ';
            $strSQL .= $strCond;
        }

        if($confs['coin5_enable']){
            $strSQL .= 'UNION ALL SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_coin5ball ';
            $strSQL .= $strCond;
        }

        if($confs['coin3_enable']){
            $strSQL .= 'UNION ALL SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_coin3ball ';
            $strSQL .= $strCond;
        }

        if(!$confs['cas_deny'] || $confs['kgon_enable']){
            if(isEBalMode()){
                $tbName = "bet_ebal";
            } else 
                $tbName = "bet_casino";
            $strCond .= " company_amount = 0 ";
            $strSQL .= 'UNION ALL SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM '.$tbName;
            $strSQL .= $strCond;
        }

        $strSQL .= ' ) bet_all';

        $objResult = $this->db->query($strSQL)->getRow();

        $arrBetData['bet_money'] = 0;          // 배팅머니
        $arrBetData['bet_win_money'] = 0;      // 적중머니

         if (!is_null($objResult->bet_money)) {
             $arrBetData['bet_money'] += $objResult->bet_money;
         }
        if (!is_null($objResult->bet_win_money)) {
            $arrBetData['bet_win_money'] += $objResult->bet_win_money;
        }

        return $arrBetData;
    }

    public function statistUserBet($arrReqData, $confs){
        $strCond = " WHERE bet_mb_uid = '".$arrReqData['mb_uid']."' ";
        if (array_key_exists('start', $arrReqData) && strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0) {
            $strCond .= " AND ".getBetTimeRange($arrReqData);
        }

         $strSQL = " SELECT bet_money, bet_win_money, bet_count, name_kr AS bet_name, '".GAME_SLOT_1."' As bet_kind From ";
         $strSQL.= " (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, COUNT(*) AS bet_count, bet_game_type  FROM bet_slot ";
		 $strSQL.= $strCond." group by bet_game_type) AS bet_slot_g JOIN slot_prd on slot_prd.code = bet_slot_g.bet_game_type ";
         
        //  if(!$confs['npg_deny']){
        //     $strSQL.= " UNION ALL SELECT bet_money, bet_win_money, bet_count, '파워볼' AS bet_name, '".GAME_POWER_BALL."' As bet_kind  From ";
        //     $strSQL.= " (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, COUNT(*) AS bet_count FROM bet_powerball  ";
        //     $strSQL.= $strCond." ) AS bet_pb_g ";
	
        //     $strSQL.= " UNION ALL SELECT bet_money, bet_win_money, bet_count, '파워사다리' AS bet_name, '".GAME_POWER_LADDER."' As bet_kind  From ";
        //     $strSQL.= " (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, COUNT(*) AS bet_count FROM bet_powerladder  ";
        //     $strSQL.= $strCond." ) AS bet_ps_g ";
        //  }
        if($confs['hpg_enable']){
            $strSQL.= " UNION ALL SELECT bet_money, bet_win_money, bet_count, '해피볼' AS bet_name, '".GAME_HAPPY_BALL."' As bet_kind  From ";
            $strSQL.= " (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, COUNT(*) AS bet_count FROM bet_happyball  ";
            $strSQL.= $strCond." ) AS bet_pb_g ";
        }
            
         if(!$confs['bpg_deny']){
            $strSQL.= " UNION ALL SELECT bet_money, bet_win_money, bet_count, '보글파워볼' AS bet_name, '".GAME_BOGLE_BALL."' As bet_kind  From ";
            $strSQL.= " (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, COUNT(*) AS bet_count FROM bet_bogleball  ";
            $strSQL.= $strCond." ) AS bet_bb_g ";
	
            $strSQL.= " UNION ALL SELECT bet_money, bet_win_money, bet_count, '보글사다리' AS bet_name, '".GAME_BOGLE_LADDER."' As bet_kind  From ";
            $strSQL.= " (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, COUNT(*) AS bet_count FROM bet_bogleladder  ";
            $strSQL.= $strCond." ) AS bet_bs_g ";
        }

        if($confs['eos5_enable']){
            $strSQL.= " UNION ALL SELECT bet_money, bet_win_money, bet_count, 'EOS5분 파워볼' AS bet_name, '".GAME_EOS5_BALL."' As bet_kind  From ";
            $strSQL.= " (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, COUNT(*) AS bet_count FROM bet_eos5ball  ";
            $strSQL.= $strCond." ) AS bet_e5_g ";
        }

        if($confs['eos3_enable']){
            $strSQL.= " UNION ALL SELECT bet_money, bet_win_money, bet_count, 'EOS3분 파워볼' AS bet_name, '".GAME_EOS3_BALL."' As bet_kind  From ";
            $strSQL.= " (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, COUNT(*) AS bet_count FROM bet_eos3ball  ";
            $strSQL.= $strCond." ) AS bet_e3_g ";
        }

        if($confs['coin5_enable']){
            $strSQL.= " UNION ALL SELECT bet_money, bet_win_money, bet_count, '코인5분 파워볼' AS bet_name, '".GAME_COIN5_BALL."' As bet_kind  From ";
            $strSQL.= " (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, COUNT(*) AS bet_count FROM bet_coin5ball  ";
            $strSQL.= $strCond." ) AS bet_c5_g ";
        }

        if($confs['coin3_enable']){
            $strSQL.= " UNION ALL SELECT bet_money, bet_win_money, bet_count, '코인3분 파워볼' AS bet_name, '".GAME_COIN3_BALL."' As bet_kind  From ";
            $strSQL.= " (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, COUNT(*) AS bet_count FROM bet_coin3ball  ";
            $strSQL.= $strCond." ) AS bet_c3_g ";
        }

        if(!$confs['cas_deny'] || $confs['kgon_enable']){
            if(isEBalMode()){
                $tbName = "bet_ebal";
            } else 
                $tbName = "bet_casino";

            $strCond.= " AND company_amount = 0 ";
            $strSQL.= " UNION All SELECT bet_money, bet_win_money, bet_count, bet_name, '".GAME_CASINO_EVOL."' As bet_kind From ";
            $strSQL.= " (SELECT bet_casino_g.*, name_ko AS bet_name from (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, COUNT(*) AS bet_count, bet_game_id FROM ".$tbName;
            $strSQL.=  $strCond." group by bet_game_id) AS bet_casino_g ";
            $strSQL.= " JOIN casino_prd on casino_prd.vendor_id = bet_casino_g.bet_game_id ) AS bet_casino_gj ";
        }

        return $this->db->query($strSQL)->getResult();
    }

    public function updateLogin($member)
    {
        $this->builder()->set('mb_time_last', 'NOW()', false);
        $this->builder()->set('mb_ip_last', $member->mb_ip_last);
        $this->builder()->where('mb_fid', $member->mb_fid);

        return $this->builder()->update();
    }

    
    public function updateLiveMoney($member){
        $data = [
            'mb_live_money' => $member->mb_live_money,
        ];
        return $this->update($member->mb_fid, $data);

    }
    
    public function updateKgonMoney($member){
        $data = [
            'mb_kgon_money' => $member->mb_kgon_money,
        ];
        return $this->update($member->mb_fid, $data);

    }
    public function updateSlotMoney($member){
        $data = [
            'mb_slot_money' => $member->mb_slot_money,
        ];
        return $this->update($member->mb_fid, $data);
    }
    
    public function updateFslotMoney($member){
        $data = [
            'mb_fslot_money' => $member->mb_fslot_money,
        ];
        return $this->update($member->mb_fid, $data);

    }

    public function updateGslotMoney($member){
        $data = [
            'mb_gslot_money' => $member->mb_gslot_money,
        ];
        return $this->update($member->mb_fid, $data);
    }

    public function getMemberByLevel($strLevel, $bLowLev = false, $mbFid = 0)
    {

        $where =" mb_state_active != '".PERMIT_DELETE."' ";

        if ($bLowLev) {
            $where .= 'AND mb_level <= '.$strLevel;
        } else {
            $where .= 'AND mb_level = '.$strLevel;
        }
        if($mbFid > 0)
            $where .= " AND mb_fid = '".$mbFid."' ";
        
        return $this->where($where)->findAll();
        
    }


    public function getMemberByEmpFid($nEmpFid, $nReqLevel, $nEmpLev = LEVEL_MIN, $bLowLev = false, $mbFid=0)
    {
        if ($nEmpLev >= LEVEL_ADMIN) {
            return $this->getMemberByLevel($nReqLevel, $bLowLev, $mbFid);
        } else {
            $strTbColum = ' mb_fid, mb_uid, mb_level, mb_emp_fid, mb_emp_permit, mb_nickname, mb_phone, mb_money, mb_point, ';
            $strTbColum .= ' mb_grade, mb_color, mb_state_active, mb_state_delete, ';
            $strTbColum .= ' mb_game_pb_ratio, mb_game_pb2_ratio, mb_game_ps_ratio, mb_game_bb_ratio, mb_game_bb2_ratio, ';
            $strTbColum .= ' mb_game_bs_ratio, mb_game_cs_ratio, mb_game_sl_ratio, mb_game_eo_ratio, mb_game_eo2_ratio, mb_game_co_ratio, mb_game_co2_ratio, ';
            $strTbColum .= ' mb_game_pb_percent, mb_game_pb2_percent, mb_game_ps_percent, mb_game_bb_percent, mb_game_bb2_percent, mb_game_bs_percent, ';
            $strTbColum .= ' mb_game_eo_percent, mb_game_eo2_percent, mb_game_co_percent, mb_game_co2_percent, mb_range_ev, ';
            $strTbColum .= ' mb_live_money, mb_slot_money, mb_fslot_money, mb_kgon_money, mb_gslot_money ';

            $strTbRColum = ' r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid, r.mb_emp_permit, r.mb_nickname, r.mb_phone, r.mb_money, r.mb_point, ';
            $strTbRColum .= ' r.mb_grade, r.mb_color, r.mb_state_active, r.mb_state_delete, ';
            $strTbRColum .= ' r.mb_game_pb_ratio, r.mb_game_pb2_ratio, r.mb_game_ps_ratio, r.mb_game_bb_ratio, r.mb_game_bb2_ratio, ';
            $strTbRColum .= ' r.mb_game_bs_ratio, r.mb_game_cs_ratio, r.mb_game_sl_ratio, r.mb_game_eo_ratio, r.mb_game_eo2_ratio, r.mb_game_co_ratio, r.mb_game_co2_ratio,';
            $strTbRColum .= ' r.mb_game_pb_percent, r.mb_game_pb2_percent, r.mb_game_ps_percent, r.mb_game_bb_percent, r.mb_game_bb2_percent, r.mb_game_bs_percent, ';
            $strTbRColum .= ' r.mb_game_eo_percent, r.mb_game_eo2_percent, r.mb_game_co_percent, r.mb_game_co2_percent, r.mb_range_ev, ';
            $strTbRColum .= ' r.mb_live_money, r.mb_slot_money, r.mb_fslot_money, r.mb_kgon_money, r.mb_gslot_money ';

            $strSQL = 'WITH RECURSIVE tbmember ('.$strTbColum.') AS';
            $strSQL .= ' ( SELECT '.$strTbColum.' FROM '.$this->table." WHERE mb_emp_fid = '".$nEmpFid."'";
            $strSQL .= ' UNION ALL SELECT '.$strTbRColum.' FROM '.$this->table.' r ';
            $strSQL .= ' INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )';
            $strSQL .= ' SELECT * FROM tbmember ';
            $strSQL .=" WHERE mb_state_active != '".PERMIT_DELETE."' ";
            if ($bLowLev) {
                $strSQL .= " AND mb_level < '".$nReqLevel."' ";
            } else {
                $strSQL .= " AND mb_level = '".$nReqLevel."' ";
            }
            if($mbFid > 0)
                $strSQL .= " AND mb_fid = '".$mbFid."' ";


            return $this->db->query($strSQL)->getResult();
        }
    }

    private function getChildsRatio($mbFid){
        $strSQL = "SELECT MAX(mb_game_pb_ratio) AS mb_game_pb_ratio, MAX(mb_game_pb2_ratio) AS mb_game_pb2_ratio,  MAX(mb_game_ps_ratio) AS mb_game_ps_ratio, ";
        $strSQL.= "  MAX(mb_game_bb_ratio) AS mb_game_bb_ratio, MAX(mb_game_bb2_ratio) AS mb_game_bb2_ratio, MAX(mb_game_bs_ratio) AS mb_game_bs_ratio, ";
        $strSQL.= "  MAX(mb_game_cs_ratio) AS mb_game_cs_ratio, MAX(mb_game_sl_ratio) AS mb_game_sl_ratio, ";
        $strSQL.= "  MAX(mb_game_eo_ratio) AS mb_game_eo_ratio, MAX(mb_game_eo2_ratio) AS mb_game_eo2_ratio, ";
        $strSQL.= "  MAX(mb_game_co_ratio) AS mb_game_co_ratio, MAX(mb_game_co2_ratio) AS mb_game_co2_ratio ";
        $strSQL.= " FROM ".$this->table." WHERE mb_emp_fid = '".$mbFid."' AND mb_state_active != '".PERMIT_DELETE."' ";
        
        // writeLog($strSQL);
        $objResult = $this->db->query($strSQL)->getRow();
        return $objResult;
    }

    private function checkGameRatio($objEmployee, $arrRegData, &$strError)
    {
        // 배당율 체크
        if(!is_null($objEmployee)){
            if (array_key_exists('mb_game_pb_ratio', $arrRegData) && $objEmployee->mb_game_pb_ratio < $arrRegData['mb_game_pb_ratio']) {
                $strError = "해피볼 단폴 배당율이 추천인설정값 ".$objEmployee->mb_game_pb_ratio."보다 클수 없습니다.";
                return 4;
            } elseif (array_key_exists('mb_game_pb2_ratio', $arrRegData) && $objEmployee->mb_game_pb2_ratio < $arrRegData['mb_game_pb2_ratio']) {
                $strError = "해피볼 조합 배당율이 추천인설정값 ".$objEmployee->mb_game_pb2_ratio."보다 클수 없습니다.";
                return 4;
            } elseif (array_key_exists('mb_game_ps_ratio', $arrRegData) && $objEmployee->mb_game_ps_ratio < $arrRegData['mb_game_ps_ratio']) {
                $strError = "파워사다리 배당율이 추천인설정값 ".$objEmployee->mb_game_ps_ratio."보다 클수 없습니다.";
                return 4;
            } elseif (array_key_exists('mb_game_cs_ratio', $arrRegData) && $objEmployee->mb_game_cs_ratio < $arrRegData['mb_game_cs_ratio']) {
                $strError = "카지노 배당율이 추천인설정값 ".$objEmployee->mb_game_cs_ratio."보다 클수 없습니다.";
                return 4;
            } elseif (array_key_exists('mb_game_sl_ratio', $arrRegData) && $objEmployee->mb_game_sl_ratio < $arrRegData['mb_game_sl_ratio']) {
                $strError = "슬롯 배당율이 추천인설정값 ".$objEmployee->mb_game_sl_ratio."보다 클수 없습니다.";
                return 4;
            } elseif (array_key_exists('mb_game_bb_ratio', $arrRegData) && $objEmployee->mb_game_bb_ratio < $arrRegData['mb_game_bb_ratio']) {
                $strError = "보글볼 단폴 배당율이 추천인설정값 ".$objEmployee->mb_game_bb_ratio."보다 클수 없습니다.";
                return 4;
            } elseif (array_key_exists('mb_game_bb2_ratio', $arrRegData) && $objEmployee->mb_game_bb2_ratio < $arrRegData['mb_game_bb2_ratio']) {
                $strError = "보글볼 조합 배당율이 추천인설정값 ".$objEmployee->mb_game_bb2_ratio."보다 클수 없습니다.";
                return 4;
            } elseif (array_key_exists('mb_game_bs_ratio', $arrRegData) && $objEmployee->mb_game_bs_ratio < $arrRegData['mb_game_bs_ratio']) {
                $strError = "보글사다리 배당율이 추천인설정값 ".$objEmployee->mb_game_bs_ratio."보다 클수 없습니다.";
                return 4;
            } elseif (array_key_exists('mb_game_eo_ratio', $arrRegData) && $objEmployee->mb_game_eo_ratio < $arrRegData['mb_game_eo_ratio']) {
                $strError = "EOS파워볼 단폴 배당율이 추천인설정값 ".$objEmployee->mb_game_eo_ratio."보다 클수 없습니다.";
                return 4;
            } elseif (array_key_exists('mb_game_eo2_ratio', $arrRegData) && $objEmployee->mb_game_eo2_ratio < $arrRegData['mb_game_eo2_ratio']) {
                $strError = "EOS파워볼 조합 배당율이 추천인설정값 ".$objEmployee->mb_game_eo2_ratio."보다 클수 없습니다.";
                return 4;
            } elseif (array_key_exists('mb_game_co_ratio', $arrRegData) && $objEmployee->mb_game_co_ratio < $arrRegData['mb_game_co_ratio']) {
                $strError = "코인파워볼 단폴 배당율이 추천인설정값 ".$objEmployee->mb_game_co_ratio."보다 클수 없습니다.";
                return 4;
            } elseif (array_key_exists('mb_game_co2_ratio', $arrRegData) && $objEmployee->mb_game_co2_ratio < $arrRegData['mb_game_co2_ratio']) {
                $strError = "코인파워볼 조합 배당율이 추천인설정값 ".$objEmployee->mb_game_co2_ratio."보다 클수 없습니다.";
                return 4;
            }
        }
        if(array_key_exists('mb_fid', $arrRegData) && $arrRegData['mb_fid'] > 0 ){
            $chRatio = $this->getChildsRatio($arrRegData['mb_fid']);

            if (array_key_exists('mb_game_pb_ratio', $arrRegData) && $chRatio->mb_game_pb_ratio != null && $chRatio->mb_game_pb_ratio > $arrRegData['mb_game_pb_ratio']) {
                $strError = "해피볼 단폴 배당율이 하위설정값 ".$chRatio->mb_game_pb_ratio."보다 작을수 없습니다.";
                return 5;
            } elseif (array_key_exists('mb_game_pb2_ratio', $arrRegData) && $chRatio->mb_game_pb2_ratio != null && $chRatio->mb_game_pb2_ratio > $arrRegData['mb_game_pb2_ratio']) {
                $strError = "해피볼 조합 배당율이 하위설정값 ".$chRatio->mb_game_pb2_ratio."보다 작을수 없습니다.";
                return 5;
            } elseif (array_key_exists('mb_game_ps_ratio', $arrRegData) && $chRatio->mb_game_ps_ratio != null && $chRatio->mb_game_ps_ratio > $arrRegData['mb_game_ps_ratio']) {
                $strError = "파워사다리 배당율이 하위설정값 ".$chRatio->mb_game_ps_ratio."보다 작을수 없습니다.";
                return 5;
            } elseif (array_key_exists('mb_game_cs_ratio', $arrRegData) && $chRatio->mb_game_cs_ratio != null && $chRatio->mb_game_cs_ratio > $arrRegData['mb_game_cs_ratio']) {
                $strError = "카지노 배당율이 하위설정값 ".$chRatio->mb_game_cs_ratio."보다 작을수 없습니다.";
                return 5;
            } elseif (array_key_exists('mb_game_sl_ratio', $arrRegData) && $chRatio->mb_game_sl_ratio != null && $chRatio->mb_game_sl_ratio > $arrRegData['mb_game_sl_ratio']) {
                $strError = "슬롯 배당율이 하위설정값 ".$chRatio->mb_game_sl_ratio."보다 작을수 없습니다.";
                return 5;
            } elseif (array_key_exists('mb_game_bb_ratio', $arrRegData) && $chRatio->mb_game_bb_ratio != null && $chRatio->mb_game_bb_ratio > $arrRegData['mb_game_bb_ratio']) {
                $strError = "보글볼 단폴 배당율이 하위설정값 ".$chRatio->mb_game_bb_ratio."보다 작을수 없습니다.";
                return 5;
            } elseif (array_key_exists('mb_game_bb2_ratio', $arrRegData) && $chRatio->mb_game_bb2_ratio != null && $chRatio->mb_game_bb2_ratio > $arrRegData['mb_game_bb2_ratio']) {
                $strError = "보글볼 조합 배당율이 하위설정값 ".$chRatio->mb_game_bb2_ratio."보다 작을수 없습니다.";
                return 5;
            } elseif (array_key_exists('mb_game_bs_ratio', $arrRegData) && $chRatio->mb_game_bs_ratio != null && $chRatio->mb_game_bs_ratio > $arrRegData['mb_game_bs_ratio']) {
                $strError = "보글사다리 배당율이 하위설정값 ".$chRatio->mb_game_bs_ratio."보다 작을수 없습니다.";
                return 5;
            } elseif (array_key_exists('mb_game_eo_ratio', $arrRegData) && $chRatio->mb_game_eo_ratio != null && $chRatio->mb_game_eo_ratio > $arrRegData['mb_game_eo_ratio']) {
                $strError = "EOS파워볼 단폴 배당율이 하위설정값 ".$chRatio->mb_game_eo_ratio."보다 작을수 없습니다.";
                return 5;
            } elseif (array_key_exists('mb_game_eo2_ratio', $arrRegData) && $chRatio->mb_game_eo2_ratio != null && $chRatio->mb_game_eo2_ratio > $arrRegData['mb_game_eo2_ratio']) {
                $strError = "EOS파워볼 배당율이 하위설정값 ".$chRatio->mb_game_eo2_ratio."보다 작을수 없습니다.";
                return 5;
            } elseif (array_key_exists('mb_game_co_ratio', $arrRegData) && $chRatio->mb_game_co_ratio != null && $chRatio->mb_game_co_ratio > $arrRegData['mb_game_co_ratio']) {
                $strError = "코인파워볼 단폴 배당율이 하위설정값 ".$chRatio->mb_game_co_ratio."보다 작을수 없습니다.";
                return 5;
            } elseif (array_key_exists('mb_game_co2_ratio', $arrRegData) && $chRatio->mb_game_co2_ratio != null && $chRatio->mb_game_co2_ratio > $arrRegData['mb_game_co2_ratio']) {
                $strError = "코인파워볼 배당율이 하위설정값 ".$chRatio->mb_game_co2_ratio."보다 작을수 없습니다.";
                return 5;
            }
    
        }

        return 1;
    }

    private function setZeroGameRatio(&$arrRegData)
    {
        if(array_key_exists('mb_game_pb_ratio', $arrRegData)){
            if (strlen($arrRegData['mb_game_pb_ratio']) < 1) {
                $arrRegData['mb_game_pb_ratio'] = 0;
            }
            if (floatval($arrRegData['mb_game_pb_ratio']) < 0) {
                $arrRegData['mb_game_pb_ratio'] = 0;
            }
        }
        
        if(array_key_exists('mb_game_pb2_ratio', $arrRegData)){
            if (strlen($arrRegData['mb_game_pb2_ratio']) < 1) {
                $arrRegData['mb_game_pb2_ratio'] = 0;
            }
            if (floatval($arrRegData['mb_game_pb2_ratio']) < 0) {
                $arrRegData['mb_game_pb2_ratio'] = 0;
            }
        }

        if(array_key_exists('mb_game_ps_ratio', $arrRegData)){
            if (strlen($arrRegData['mb_game_ps_ratio']) < 1) {
                $arrRegData['mb_game_ps_ratio'] = 0;
            }
            if (floatval($arrRegData['mb_game_ps_ratio']) < 0) {
                $arrRegData['mb_game_ps_ratio'] = 0;
            }
        }

        if(array_key_exists('mb_game_cs_ratio', $arrRegData)){
            if (strlen($arrRegData['mb_game_cs_ratio']) < 1) {
                $arrRegData['mb_game_cs_ratio'] = 0;
            }
            if (floatval($arrRegData['mb_game_cs_ratio']) < 0) {
                $arrRegData['mb_game_cs_ratio'] = 0;
            }
        }

        if(array_key_exists('mb_game_sl_ratio', $arrRegData)){
            if (strlen($arrRegData['mb_game_sl_ratio']) < 1) {
                $arrRegData['mb_game_sl_ratio'] = 0;
            }
            if (floatval($arrRegData['mb_game_sl_ratio']) < 0) {
                $arrRegData['mb_game_sl_ratio'] = 0;
            }
        }

        if(array_key_exists('mb_game_bb_ratio', $arrRegData)){
            if (strlen($arrRegData['mb_game_bb_ratio']) < 1) {
                $arrRegData['mb_game_bb_ratio'] = 0;
            }
            if (floatval($arrRegData['mb_game_bb_ratio']) < 0) {
                $arrRegData['mb_game_bb_ratio'] = 0;
            }
        }

        if(array_key_exists('mb_game_bb2_ratio', $arrRegData)){
            if (strlen($arrRegData['mb_game_bb2_ratio']) < 1) {
                $arrRegData['mb_game_bb2_ratio'] = 0;
            }
            if (floatval($arrRegData['mb_game_bb2_ratio']) < 0) {
                $arrRegData['mb_game_bb2_ratio'] = 0;
            }
        }

        if(array_key_exists('mb_game_bs_ratio', $arrRegData)){
            if (strlen($arrRegData['mb_game_bs_ratio']) < 1) {
                $arrRegData['mb_game_bs_ratio'] = 0;
            }
            if (floatval($arrRegData['mb_game_bs_ratio']) < 0) {
                $arrRegData['mb_game_bs_ratio'] = 0;
            }
        }

        if(array_key_exists('mb_game_eo_ratio', $arrRegData)){
            if (strlen($arrRegData['mb_game_eo_ratio']) < 1) {
                $arrRegData['mb_game_eo_ratio'] = 0;
            }
            if (floatval($arrRegData['mb_game_eo_ratio']) < 0) {
                $arrRegData['mb_game_eo_ratio'] = 0;
            }
        }

        if(array_key_exists('mb_game_eo2_ratio', $arrRegData)){
            if (strlen($arrRegData['mb_game_eo2_ratio']) < 1) {
                $arrRegData['mb_game_eo2_ratio'] = 0;
            }
            if (floatval($arrRegData['mb_game_eo2_ratio']) < 0) {
                $arrRegData['mb_game_eo2_ratio'] = 0;
            }
        }

        if(array_key_exists('mb_game_co_ratio', $arrRegData)){
            if (strlen($arrRegData['mb_game_co_ratio']) < 1) {
                $arrRegData['mb_game_co_ratio'] = 0;
            }
            if (floatval($arrRegData['mb_game_co_ratio']) < 0) {
                $arrRegData['mb_game_co_ratio'] = 0;
            }
        }

        if(array_key_exists('mb_game_co2_ratio', $arrRegData)){
            if (strlen($arrRegData['mb_game_co2_ratio']) < 1) {
                $arrRegData['mb_game_co2_ratio'] = 0;
            }
            if (floatval($arrRegData['mb_game_co2_ratio']) < 0) {
                $arrRegData['mb_game_co2_ratio'] = 0;
            }
        }
    }

    private function setZeroGamePercent(&$arrData){

        if (array_key_exists('mb_game_pb_percent', $arrData) && strlen($arrData['mb_game_pb_percent']) < 1) {
            $arrData['mb_game_pb_percent'] = 0;
        }
        if (array_key_exists('mb_game_pb2_percent', $arrData) && strlen($arrData['mb_game_pb2_percent']) < 1) {
            $arrData['mb_game_pb2_percent'] = 0;
        }
        if (array_key_exists('mb_game_ps_percent', $arrData) && strlen($arrData['mb_game_ps_percent']) < 1) {
            $arrData['mb_game_ps_percent'] = 0;
        }
        if (array_key_exists('mb_game_bb_percent', $arrData) && strlen($arrData['mb_game_bb_percent']) < 1) {
            $arrData['mb_game_bb_percent'] = 0;
        }
        if (array_key_exists('mb_game_bb2_percent', $arrData) && strlen($arrData['mb_game_bb2_percent']) < 1) {
            $arrData['mb_game_bb2_percent'] = 0;
        }
        if (array_key_exists('mb_game_bs_percent', $arrData) && strlen($arrData['mb_game_bs_percent']) < 1) {
            $arrData['mb_game_bs_percent'] = 0;
        }
        if (array_key_exists('mb_game_eo_percent', $arrData) && strlen($arrData['mb_game_eo_percent']) < 1) {
            $arrData['mb_game_eo_percent'] = 0;
        }
        if (array_key_exists('mb_game_eo2_percent', $arrData) && strlen($arrData['mb_game_eo2_percent']) < 1) {
            $arrData['mb_game_eo2_percent'] = 0;
        }
        if (array_key_exists('mb_game_co_percent', $arrData) && strlen($arrData['mb_game_co_percent']) < 1) {
            $arrData['mb_game_co_percent'] = 0;
        }
        if (array_key_exists('mb_game_co2_percent', $arrData) && strlen($arrData['mb_game_co2_percent']) < 1) {
            $arrData['mb_game_co2_percent'] = 0;
        }
    }

    public function register($arrRegData, &$strError)
    {
        // 결과 -1: query error 0:오류 1:성공 3:추천인 오류 4:파워볼 배당율오류 5:파워사다리 배당율오류 6:키노사다리 배당율 오류
        $objEmployee = null;
        if (LEVEL_COMPANY == $arrRegData['mb_level']) {
            $arrRegData['mb_emp_fid'] = 0;
            // $objUser = $this->getByNickname(trim($arrData['mb_nickname']));
            // if (!is_null($objUser)) {
            //     return 12;
            // }
        } elseif ($arrRegData['mb_emp_fid'] > 0) {
            // 추천인 체크
            $objEmployee = $this->getInfoByFid($arrRegData['mb_emp_fid']);
            if (is_null($objEmployee)) {
                return 3;
            }

            if ($arrRegData['mb_level'] < LEVEL_MIN){
                return 3;
            }
            
            
            
        } else {
            return 0;
        }
        $this->setZeroGameRatio($arrRegData);
        // 배당율 체크
        $ratioResult = $this->checkGameRatio($objEmployee, $arrRegData, $strError);
        if ($ratioResult != 1) {
            return $ratioResult;
        }

        $objUser = $this->getInfo(trim($arrRegData['mb_uid']));
        if (!is_null($objUser) && $objUser->mb_state_active == PERMIT_DELETE) {
            if(array_key_exists('mb_state_alarm', $arrRegData) && $arrRegData['mb_state_alarm'] == 1) {
                $this->delete($objUser->mb_fid);
            }   
            else return 2;
        }

        // 자료기지 등록
        $arrRegData['mb_uid'] = trim($arrRegData['mb_uid']);
        $arrRegData['mb_nickname'] = trim($arrRegData['mb_nickname']);
        $arrRegData['mb_bank_name'] = trim($arrRegData['mb_bank_name']);
        $arrRegData['mb_bank_own'] = trim($arrRegData['mb_bank_own']);
        $arrRegData['mb_bank_num'] = trim($arrRegData['mb_bank_num']);
        $arrRegData['mb_bank_pwd'] = trim($arrRegData['mb_bank_pwd']);
        $arrRegData['mb_time_join'] = date('Y-m-d H:i:s');
        
        if(!array_key_exists('mb_state_active', $arrRegData)){
            $arrRegData['mb_state_active'] = PERMIT_WAIT;
        }
        if($_ENV['mem.auto_permit']){
            $arrRegData['mb_state_active'] = PERMIT_OK;
        }

        $arrRegData['mb_game_pb'] = 1;
        $arrRegData['mb_game_ps'] = 1;
        $arrRegData['mb_game_bb'] = 1;
        $arrRegData['mb_game_bs'] = 1;
        $arrRegData['mb_game_cs'] = 1;
        $arrRegData['mb_game_sl'] = 1;
        $arrRegData['mb_game_eo'] = 1;
        $arrRegData['mb_game_co'] = 1;
        $result = $this->insert($arrRegData);
        if (!$result) {
            $strError = $this->errors();

            return -1;
        }

        return 1;
    }

    public function modifyMember($arrData, &$strError, &$query)
    {
        // 결과 0:오류 1:성공 2:아이디중복 3:추천인 오류 4:파워볼 배당율오류 5:파워사다리 배당율오류 6:키노사다리 배당율 오류, 12 중복닉네임

        // 아이디체크
        $objMember = $this->getInfoByFid($arrData['mb_fid']);
        if (is_null($objMember)) {
            return 0;
        }

        $objEmployee = null;
        if ($objMember->mb_level == LEVEL_COMPANY) {
            $arrData['mb_emp_fid'] = 0;

            // $objUser = $this->getByNickname($arrData['mb_nickname']);
            // if (!is_null($objUser) && $objUser->mb_fid != $arrData['mb_fid']) {
            //     return 12;
            // }
        } elseif ($arrData['mb_emp_fid'] > 0) {
            // 추천인 체크
            $objEmployee = $this->getInfoByFid($arrData['mb_emp_fid']);
            if (is_null($objEmployee) || $objEmployee->mb_level < LEVEL_MIN) {
                return 3;
            }

            // 닉네임 체크
            // $objUser = $this->getByNickname($arrData['mb_nickname']);
            // if (!is_null($objUser) && $objUser->mb_fid != $arrData['mb_fid']) {
            //     return 12;
            // }
           
        } else {
            return 0;
        }

        $this->setZeroGameRatio($arrData);
        $this->setZeroGamePercent($arrData);
        $resultRatio = $this->checkGameRatio($objEmployee, $arrData, $strError);
        if ($resultRatio != 1) {
            return $resultRatio;
        }

        $arrData['mb_bank_name'] = trim($arrData['mb_bank_name']);
        $arrData['mb_bank_own'] = trim($arrData['mb_bank_own']);
        $arrData['mb_bank_num'] = trim($arrData['mb_bank_num']);
        $arrData['mb_bank_pwd'] = trim($arrData['mb_bank_pwd']);

        if(array_key_exists('mb_money', $arrData))
            unset($arrData['mb_money']);

        if(array_key_exists('mb_point', $arrData))
            unset($arrData['mb_point']);

        $bResult = $this->update($arrData['mb_fid'], $arrData);
        
        if ($bResult) {
            $query = $this->db->getLastQuery();
            return 1;
        }
        $strError = $this->errors();
        // writeLog($strError);
        return -1;
    }

    public function modifyMemberRatio($arrData, &$strError, &$query)
    {
        // 결과 0:오류 1:성공 2:아이디중복 3:추천인 오류 4:파워볼 배당율오류 5:파워사다리 배당율오류 6:키노사다리 배당율 오류

        // 아이디체크
        $objMember = $this->getInfoByFid($arrData['mb_fid']);
        if (is_null($objMember)) {
            return 0;
        }

        if ($objMember->mb_level < LEVEL_ADMIN) {
            // 추천인 체크
            $objEmployee = $this->getInfoByFid($objMember->mb_emp_fid);
            if (is_null($objEmployee)) {
                return 0;
            }
            
            $this->setZeroGameRatio($arrData);
            $this->setZeroGamePercent($arrData);

            $resultRatio = $this->checkGameRatio($objEmployee, $arrData, $strError);
            if ($resultRatio != 1) {
                return $resultRatio;
            }
        } else {
            return 0;
        }

        $this->builder()->set('mb_color', $arrData['mb_color']);
        if(array_key_exists('mb_state_delete', $arrData)){
            $this->builder()->set('mb_state_delete', $arrData['mb_state_delete']);
        }
        // if(array_key_exists('mb_state_view', $arrData)){
        //     $this->builder()->set('mb_state_view', $arrData['mb_state_view']);
        // }
        $this->builderSetGameRatioAndPercent($arrData);

        $this->builder()->where('mb_fid', $arrData['mb_fid']);
        $bResult = $this->builder()->update();
        $query = $this->db->getLastQuery();

        return 1;
    }

    private function builderSetGameRatioAndPercent($arrRegData)
    {
        if(array_key_exists('mb_game_pb_ratio', $arrRegData))
            $this->builder()->set('mb_game_pb_ratio', $arrRegData['mb_game_pb_ratio']);
        if(array_key_exists('mb_game_pb2_ratio', $arrRegData))
            $this->builder()->set('mb_game_pb2_ratio', $arrRegData['mb_game_pb2_ratio']);
        if(array_key_exists('mb_game_ps_ratio', $arrRegData))
            $this->builder()->set('mb_game_ps_ratio', $arrRegData['mb_game_ps_ratio']);
        
        if(array_key_exists('mb_game_bb_ratio', $arrRegData))
            $this->builder()->set('mb_game_bb_ratio', $arrRegData['mb_game_bb_ratio']);
        if(array_key_exists('mb_game_bb2_ratio', $arrRegData))
            $this->builder()->set('mb_game_bb2_ratio', $arrRegData['mb_game_bb2_ratio']);
        if(array_key_exists('mb_game_bs_ratio', $arrRegData))
            $this->builder()->set('mb_game_bs_ratio', $arrRegData['mb_game_bs_ratio']);
        
        if(array_key_exists('mb_game_cs_ratio', $arrRegData))
            $this->builder()->set('mb_game_cs_ratio', $arrRegData['mb_game_cs_ratio']);
        if(array_key_exists('mb_game_sl_ratio', $arrRegData))
            $this->builder()->set('mb_game_sl_ratio', $arrRegData['mb_game_sl_ratio']);
        if(array_key_exists('mb_game_eo_ratio', $arrRegData))
            $this->builder()->set('mb_game_eo_ratio', $arrRegData['mb_game_eo_ratio']);
        if(array_key_exists('mb_game_eo2_ratio', $arrRegData))
            $this->builder()->set('mb_game_eo2_ratio', $arrRegData['mb_game_eo2_ratio']);
        if(array_key_exists('mb_game_co_ratio', $arrRegData))
            $this->builder()->set('mb_game_co_ratio', $arrRegData['mb_game_co_ratio']);
        if(array_key_exists('mb_game_co2_ratio', $arrRegData))
            $this->builder()->set('mb_game_co2_ratio', $arrRegData['mb_game_co2_ratio']);
            
        if(array_key_exists('mb_game_pb_percent', $arrRegData))
            $this->builder()->set('mb_game_pb_percent', $arrRegData['mb_game_pb_percent']);
        if(array_key_exists('mb_game_pb2_percent', $arrRegData))
            $this->builder()->set('mb_game_pb2_percent', $arrRegData['mb_game_pb2_percent']);
        if(array_key_exists('mb_game_ps_percent', $arrRegData))
            $this->builder()->set('mb_game_ps_percent', $arrRegData['mb_game_ps_percent']);
        if(array_key_exists('mb_game_bb_percent', $arrRegData))
            $this->builder()->set('mb_game_bb_percent', $arrRegData['mb_game_bb_percent']);
        if(array_key_exists('mb_game_bb2_percent', $arrRegData))
            $this->builder()->set('mb_game_bb2_percent', $arrRegData['mb_game_bb2_percent']);
        if(array_key_exists('mb_game_bs_percent', $arrRegData))
            $this->builder()->set('mb_game_bs_percent', $arrRegData['mb_game_bs_percent']);
        if(array_key_exists('mb_game_eo_percent', $arrRegData))
            $this->builder()->set('mb_game_eo_percent', $arrRegData['mb_game_eo_percent']);
        if(array_key_exists('mb_game_eo2_percent', $arrRegData))
            $this->builder()->set('mb_game_eo2_percent', $arrRegData['mb_game_eo2_percent']);
        if(array_key_exists('mb_game_co_percent', $arrRegData))
            $this->builder()->set('mb_game_co_percent', $arrRegData['mb_game_co_percent']);
        if(array_key_exists('mb_game_co2_percent', $arrRegData))
            $this->builder()->set('mb_game_co2_percent', $arrRegData['mb_game_co2_percent']);
    }

    public function updateMemberByFid($arrData, &$query)
    {
        if (array_key_exists('mb_state_active', $arrData)) {
            $this->builder()->set('mb_state_active', $arrData['mb_state_active']);
        } elseif (array_key_exists('mb_game_pb', $arrData)) {
            $this->builder()->set('mb_game_pb', $arrData['mb_game_pb']);
        } elseif (array_key_exists('mb_game_ps', $arrData)) {
            $this->builder()->set('mb_game_ps', $arrData['mb_game_ps']);
        } elseif (array_key_exists('mb_game_cs', $arrData)) {
            $this->builder()->set('mb_game_cs', $arrData['mb_game_cs']);
        } elseif (array_key_exists('mb_game_sl', $arrData)) {
            $this->builder()->set('mb_game_sl', $arrData['mb_game_sl']);
        } elseif (array_key_exists('mb_game_bb', $arrData)) {
            $this->builder()->set('mb_game_bb', $arrData['mb_game_bb']);
        } elseif (array_key_exists('mb_game_bs', $arrData)) {
            $this->builder()->set('mb_game_bs', $arrData['mb_game_bs']);
        } elseif (array_key_exists('mb_game_sl', $arrData)) {
            $this->builder()->set('mb_game_sl', $arrData['mb_game_sl']);
        } elseif (array_key_exists('mb_game_eo', $arrData)) {
            $this->builder()->set('mb_game_eo', $arrData['mb_game_eo']);
        } elseif (array_key_exists('mb_game_co', $arrData)) {
            $this->builder()->set('mb_game_co', $arrData['mb_game_co']);
        } elseif (array_key_exists('mb_blank_count', $arrData)) {
            $this->builder()->set('mb_blank_count', $arrData['mb_blank_count']);
        } else {
            return false;
        }

        $this->builder()->where('mb_fid', $arrData['mb_fid']);
        $bResult = $this->builder()->update();
        $query = $this->db->getLastQuery();
        
        return $bResult;
    }

    public function updateMemberByFids($arrData, &$query)
    {
        if (array_key_exists('mb_state_active', $arrData)) {
            $this->builder()->set('mb_state_active', $arrData['mb_state_active']);
        }else return false;

        $this->builder()->whereIn('mb_fid', $arrData['mb_fids']);
        $bResult = $this->builder()->update();
        $query = $this->db->getLastQuery();
        
        return $bResult;
    }

    public function getEmpUserCnt($objMember)
    {
        $arrEmpUserInfo['alluser'] = 0;
        $arrEmpUserInfo['waituser'] = 0;
        $arrEmpUserInfo['waitemployee'] = 0;
        $arrEmpUserInfo['waitagency'] = 0;
        $arrEmpUserInfo['waitcompany'] = 0;

        if ($objMember->mb_level >= LEVEL_ADMIN) {
            
            // 대기중인 회원수
            $strSQL = ' SELECT  COUNT(*) AS mb_count FROM '.$this->table." WHERE mb_level < '".LEVEL_ADMIN."'";
            $strSQL .= " AND mb_state_active = '".PERMIT_WAIT."'";
            $objResult = $this->db->query($strSQL)->getRow();
            if (!is_null($objResult->mb_count)) {
                $arrEmpUserInfo['waituser'] = $objResult->mb_count;
            }
            
        }

        return $arrEmpUserInfo;
    }

    // 관리자 보유금
    public function calcAdminMoney()
    {
        $strSQL = 'SELECT SUM(mb_money+mb_live_money+mb_slot_money+mb_fslot_money+mb_kgon_money+mb_gslot_money) AS emp_money, SUM(mb_point) AS emp_point FROM '.$this->table;
        $strSQL .= ' WHERE mb_level < '.LEVEL_ADMIN;
        $strSQL .=" AND mb_state_active != '".PERMIT_DELETE."' ";

        $objResult = $this->db->query($strSQL)->getRow();

        return $objResult;
    }

    // 게임별 머니
    public function calcGameEgg($iGame)
    {
        if($iGame == GAME_CASINO_EVOL){
            $strSQL = 'SELECT SUM(mb_live_money) AS mb_game_money FROM '.$this->table;
            $strSQL .= ' WHERE mb_live_id > 0 ';
        }
        else if($iGame == GAME_CASINO_KGON){
            $strSQL = 'SELECT SUM(mb_kgon_money) AS mb_game_money FROM '.$this->table;
            $strSQL .= ' WHERE mb_kgon_id > 0 ';
        }
        else if($iGame == GAME_SLOT_1){
            $strSQL = 'SELECT SUM(mb_slot_money) AS mb_game_money FROM '.$this->table;
            $strSQL .= " WHERE LENGTH(mb_slot_uid) > 0 ";
        } else if($iGame == GAME_SLOT_2){
            $strSQL = 'SELECT SUM(mb_fslot_money) AS mb_game_money FROM '.$this->table;
            $strSQL .= ' WHERE mb_fslot_id > 0';
        } else if($iGame == GAME_SLOT_3){
            $strSQL = 'SELECT SUM(mb_gslot_money) AS mb_game_money FROM '.$this->table;
            $strSQL .= ' WHERE LENGTH(mb_gslot_uid) > 0';
        } else null;

        $objResult = $this->db->query($strSQL)->getRow();

        return $objResult->mb_game_money;
    }
    
    public function searchCountByLevel($arrReqData, $iEmpFid)
    {
        $sqlBuilder = $this->builder()->selectCount('*', 'count');
        $sqlBuilder = $sqlBuilder->where('mb_level <', LEVEL_ADMIN);
        $sqlBuilder = $sqlBuilder->where('mb_state_active !=', PERMIT_DELETE);
        if ($iEmpFid != 0)
            $sqlBuilder->where('mb_emp_fid', $iEmpFid);

        if ($arrReqData['mb_grade'] != 0){
            $sqlBuilder->where('mb_grade', $arrReqData['mb_grade']);
        }
        if ($arrReqData['mb_state'] >= 0){
            $sqlBuilder->where('mb_state_active', $arrReqData['mb_state']);
        }
        if (strlen($arrReqData['mb_uid']) > 0){
            $where = "  mb_uid LIKE '%".$arrReqData['mb_uid']."%' OR mb_fid = '".$arrReqData['mb_uid']."' ";

            $sqlBuilder->where($where);
        }
        return $sqlBuilder->get()->getRow();
    }

    public function searchCountByEmpFid($objUser, $arrReqData, $iEmpFid)
    {
        if($objUser->mb_level >= LEVEL_ADMIN)
        {
            return $this->searchCountByLevel($arrReqData, $iEmpFid);
        } else {
            $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid, mb_grade, mb_state_active ";
            $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid, r.mb_grade, r.mb_state_active ";

            $strSQL = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSQL .= " ( SELECT ".$strTbColum." FROM ".$this->table." WHERE mb_emp_fid = '".$objUser->mb_fid."'";
            $strSQL .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->table." r ";
            $strSQL .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";
            $strSQL .= " SELECT COUNT(*) as count FROM tbmember WHERE ";
            $strSQL .= " mb_level < '".$objUser->mb_level."' ";
            $strSQL .=" AND mb_state_active != '".PERMIT_DELETE."' ";

            if ($iEmpFid != 0)
                $strSQL.=" AND mb_emp_fid = '".$iEmpFid."' ";

            if($arrReqData['mb_grade'] != 0){            
                $strSQL.=" AND mb_grade = '".$arrReqData['mb_grade']."' ";
            }
            if ($arrReqData['mb_state'] >= 0){
                $strSQL.=" AND mb_state_active = '".$arrReqData['mb_state']."' ";
            }
            if(strlen($arrReqData['mb_uid']) > 0){            
                $strSQL.=" AND mb_uid LIKE '".$arrReqData['mb_uid']."%' ";
            }
            return $this -> db -> query($strSQL)->getRow();

        }
    }

    public function searchMemberByLevel($arrReqData, $iEmpFid)
    {
        $fields = ['mb_fid', 'mb_uid', 'mb_level','mb_emp_fid','mb_nickname', 'mb_ip_last',
            'mb_money', 'mb_point', 'mb_grade', 'mb_color', 'mb_state_active', 'mb_state_delete', 
            'mb_game_pb', 'mb_game_ps', 'mb_game_bb', 'mb_game_bs', 'mb_game_cs', 'mb_game_sl', 'mb_game_eo', 'mb_game_co', 
            'mb_blank_count', 'mb_live_money', 'mb_slot_money', 'mb_fslot_money', 'mb_kgon_money', 'mb_gslot_money' ];

        $strTbColum = " ".implode(", ", $fields);
        $strTbColum.= ", block_ip, block_state ";

        $tbBlock = "block_list";

        $where = "";
        if ($iEmpFid != 0){
            $where .= " AND mb_emp_fid = '".$iEmpFid."'";
        }
        if (strlen($arrReqData['mb_uid']) > 0) {
            $where .= " AND ( mb_uid LIKE '%".$arrReqData['mb_uid']."%' OR mb_fid = '".$arrReqData['mb_uid']."') ";
        }
        if ($arrReqData['mb_grade'] != 0){
            $where .= " AND mb_grade = '".$arrReqData['mb_grade']."'";
        }
        if ($arrReqData['mb_state'] >= 0){
            $where .= " AND mb_state_active = '".$arrReqData['mb_state']."' ";
        }


        $strSQL = "SELECT ".$strTbColum." FROM ".$this->table;
        $strSQL.= ' LEFT JOIN '.$tbBlock.' ON '.$this->table.'.mb_ip_last = '.$tbBlock.'.block_ip ';
        $strSQL.= " WHERE mb_level < '".LEVEL_ADMIN."' ";
        $strSQL .=" AND mb_state_active != '".PERMIT_DELETE."' ";
        $strSQL.= $where;

        $strSQL .= " ORDER BY (CASE WHEN mb_state_active = 2 THEN 0 ELSE 1 END) ";
        $strSQL .= " , mb_uid ASC ";
        
        $nStartRow = ($arrReqData['page'] - 1) * $arrReqData['count'];
        $strSQL .= ' LIMIT '.$nStartRow.', '.$arrReqData['count'];
        return $this->db->query($strSQL)->getResult();
    }

    public function searchMemberByEmpFid($objUser, $arrReqData, $iEmpFid)
    {
        if($objUser->mb_level >= LEVEL_ADMIN)
        {
            return $this->searchMemberByLevel($arrReqData, $iEmpFid);
        } else {
            $fields = ['mb_fid', 'mb_uid', 'mb_level','mb_emp_fid', 'mb_nickname', 
            'mb_money', 'mb_point', 'mb_grade', 'mb_color', 'mb_state_active', 'mb_state_delete', 'mb_blank_count',
            'mb_live_money', 'mb_slot_money', 'mb_fslot_money', 'mb_kgon_money', 'mb_gslot_money' ];

            $strTbColum = " ".implode(", ", $fields);
            $strTbRColum = " r.".implode(", r.", $fields);

            $strSQL = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSQL .= " ( SELECT ".$strTbColum." FROM ".$this->table." WHERE mb_emp_fid = '".$objUser->mb_fid."'";
            $strSQL .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->table." r ";
            $strSQL .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";
            $strSQL .= " SELECT * FROM tbmember ";
            $strSQL .= " WHERE mb_level < '".$objUser->mb_level."' ";
            $strSQL .=" AND mb_state_active != '".PERMIT_DELETE."' ";

            if ($iEmpFid != 0){
                $strSQL .= " AND mb_emp_fid = '".$iEmpFid."'";
            }
            if (strlen($arrReqData['mb_uid']) > 0) {
                $strSQL .= " AND mb_uid LIKE '%".$arrReqData['mb_uid']."%'";
            }
            if ($arrReqData['mb_grade'] != 0){
                $strSQL .= " AND mb_grade = '".$arrReqData['mb_grade']."'";
            }
            if ($arrReqData['mb_state'] >= 0){
                $strSQL .= " AND mb_state_active = '".$arrReqData['mb_state']."' ";
            }

            $strSQL .= " ORDER BY (CASE WHEN mb_state_active = 2 THEN 0 ELSE 1 END) ";
            $strSQL .= " , mb_uid ASC ";

            $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
            $strSQL .= ' LIMIT '.$nStartRow.', '.$arrReqData['count'];
            
            return $this -> db -> query($strSQL)->getResult();
          
        }
    }

    
    
    public function getEmpMemberByFid($fid)
    {
        $strTbColum = " ".implode(", ", $this->getFields);
        $strTbRColum = " r.".implode(", r.", $this->getFields);

        $strSQL = 'WITH RECURSIVE tbmember ('.$strTbColum.') AS';
        $strSQL .= ' ( SELECT '.$strTbColum.' FROM '.$this->table." WHERE mb_fid = '".$fid."'";
        $strSQL .= ' UNION ALL SELECT '.$strTbRColum.' FROM '.$this->table.' r ';
        $strSQL .= ' INNER JOIN tbmember ON r.mb_fid = tbmember.mb_emp_fid )';
        $strSQL .= ' SELECT * FROM tbmember ';
        
        $strSQL .=  " ORDER BY mb_level DESC ";
        return $this->db->query($strSQL)->getResult();
    }

    public function isPermitMember($objMember, $iGame = 0){

        if(is_null($objMember))
            return false;

        if($objMember->mb_level >= LEVEL_ADMIN)
            return true;

        $arrMember = $this->getEmpMemberByFid($objMember->mb_fid);
        if(count($arrMember) < 1)
            return false;

        if($arrMember[0]->mb_level != LEVEL_COMPANY)
            return false;

        foreach($arrMember as $member){
            if(getMemberState($member, $iGame) === false)
                return false;
        }
        
        return true;
    }


    public function searchUserByLevel($arrReqData, $iEmpFid, $confs)
    {
        $strTbColum = ' mb_fid, mb_uid, mb_pwd, mb_level, mb_emp_fid, mb_nickname, mb_phone, ';
        $strTbColum.= ' mb_bank_name, mb_bank_own, mb_bank_num, mb_bank_pwd, mb_time_join, ';
        $strTbColum.= ' (mb_money + mb_live_money + mb_slot_money + mb_fslot_money + mb_kgon_money + mb_gslot_money) as mb_money, ';
        $strTbColum.= ' (mb_live_money + mb_slot_money + mb_fslot_money + mb_kgon_money + mb_gslot_money) as mb_egg, ';
        $strTbColum.= ' mb_point, mb_grade, mb_color, mb_state_active, mb_state_delete, ' ;
        $strTbColum .= ' mb_game_pb, mb_game_ps, mb_game_bb, mb_game_bs, mb_game_cs, mb_game_sl, mb_game_eo, mb_game_co, mb_game_pb_ratio, mb_game_pb2_ratio, mb_game_ps_ratio, ';
        $strTbColum .= ' mb_game_bb_ratio, mb_game_bb2_ratio, mb_game_bs_ratio, mb_game_cs_ratio, mb_game_sl_ratio, mb_game_eo_ratio, mb_game_eo2_ratio, mb_game_co_ratio, mb_game_co2_ratio, ';
        $strTbColum .= ' mb_game_pb_percent, mb_game_pb2_percent, mb_game_ps_percent, mb_game_bb_percent, mb_game_bb2_percent, mb_game_bs_percent, mb_game_eo_percent, mb_game_eo2_percent, ';
        $strTbColum .= ' mb_game_co_percent, mb_game_co2_percent, mb_blank_count, ';
        $strTbColum .= " bet_sl.bet_sl_m, bet_sl.bet_sl_w, ";
        $strTbColum .= "  ";
        // if(!$confs['npg_deny']){
        //     $strBetM.= " + bet_pb.bet_m + bet_pl.bet_m "; 
        //     $strBetW.= " + bet_pb.bet_w + bet_pl.bet_w "; 
        // }
        if($confs['hpg_enable']){
            $strTbColum.= " bet_pb.bet_pb_m, bet_pb.bet_pb_w, ";
            $strTbColum.= "  "; 
        }
        if(!$confs['bpg_deny']){
            $strTbColum.= " bet_bb.bet_bb_m, bet_bb.bet_bb_w, "; 
            $strTbColum.= " bet_bl.bet_bl_m, bet_bl.bet_bl_w, "; 
        }
        if($confs['eos5_enable']){
            $strTbColum.= " bet_e5.bet_e5_m, bet_e5.bet_e5_w, "; 
        }
        if($confs['eos3_enable']){
            $strTbColum.= " bet_e3.bet_e3_m, bet_e3.bet_e3_w, "; 
        }
        if($confs['coin5_enable']){
            $strTbColum.= " bet_c5.bet_c5_m, bet_c5.bet_c5_w, "; 
        }
        if($confs['coin3_enable']){
            $strTbColum.= " bet_c3.bet_c3_m, bet_c3.bet_c3_w, "; 
        }
        if(!$confs['cas_deny'] || $confs['kgon_enable']){
            $strTbColum.= " bet_cs.bet_cs_m, bet_cs.bet_cs_w, "; 
        }
        $strTbColum.= " rw_point, chg_point ";

        $where = "";
        if ($iEmpFid != 0){
            $where .= " AND mb_emp_fid = '".$iEmpFid."'";
        }
        if (strlen($arrReqData['mb_uid']) > 0) {
            $where .= " AND ( mb_uid LIKE '%".$arrReqData['mb_uid']."%' OR mb_fid = '".$arrReqData['mb_uid']."') ";
        }
        if ($arrReqData['mb_grade'] != 0){
            $where .= " AND mb_grade = '".$arrReqData['mb_grade']."'";
        }
        if ($arrReqData['mb_state'] >= 0){
            $where .= " AND mb_state_active = '".$arrReqData['mb_state']."' ";
        }


        $strSQL = "SELECT ".$strTbColum." FROM ".$this->table;
        $strSQL.= " LEFT JOIN ( select bet_mb_uid, sum(bet_money) AS bet_sl_m, sum(bet_win_money) AS bet_sl_w from bet_slot group by bet_mb_uid ) bet_sl ON bet_sl.bet_mb_uid = member.mb_uid";

        // if(!$confs['npg_deny']){
        //     $strSQL.= " LEFT JOIN ( select bet_mb_uid, sum(bet_money) AS bet_m, sum(bet_win_money) AS bet_w from bet_powerball group by bet_mb_uid ) bet_pb ON bet_pb.bet_mb_uid = member.mb_uid";
        //     $strSQL.= " LEFT JOIN ( select bet_mb_uid, sum(bet_money) AS bet_m, sum(bet_win_money) AS bet_w from bet_powerladder group by bet_mb_uid ) bet_pl ON bet_pl.bet_mb_uid = member.mb_uid";
        // }
        if($confs['hpg_enable']){
            $strSQL.= " LEFT JOIN ( select bet_mb_uid, sum(bet_money) AS bet_pb_m, sum(bet_win_money) AS bet_pb_w from bet_happyball group by bet_mb_uid ) bet_pb ON bet_pb.bet_mb_uid = member.mb_uid";
        }
        if(!$confs['bpg_deny']){
            $strSQL.= " LEFT JOIN ( select bet_mb_uid, sum(bet_money) AS bet_bb_m, sum(bet_win_money) AS bet_bb_w from bet_bogleball group by bet_mb_uid ) bet_bb ON bet_bb.bet_mb_uid = member.mb_uid";
            $strSQL.= " LEFT JOIN ( select bet_mb_uid, sum(bet_money) AS bet_bl_m, sum(bet_win_money) AS bet_bl_w from bet_bogleladder group by bet_mb_uid ) bet_bl ON bet_bl.bet_mb_uid = member.mb_uid";
        }
        if($confs['eos5_enable']){
            $strSQL.= " LEFT JOIN ( select bet_mb_uid, sum(bet_money) AS bet_e5_m, sum(bet_win_money) AS bet_e5_w from bet_eos5ball group by bet_mb_uid ) bet_e5 ON bet_e5.bet_mb_uid = member.mb_uid";
        }
        if($confs['eos3_enable']){
            $strSQL.= " LEFT JOIN ( select bet_mb_uid, sum(bet_money) AS bet_e3_m, sum(bet_win_money) AS bet_e3_w from bet_eos3ball group by bet_mb_uid ) bet_e3 ON bet_e3.bet_mb_uid = member.mb_uid";
        }
        if($confs['coin5_enable']){
            $strSQL.= " LEFT JOIN ( select bet_mb_uid, sum(bet_money) AS bet_c5_m, sum(bet_win_money) AS bet_c5_w from bet_coin5ball group by bet_mb_uid ) bet_c5 ON bet_c5.bet_mb_uid = member.mb_uid";
        }
        if($confs['coin3_enable']){
            $strSQL.= " LEFT JOIN ( select bet_mb_uid, sum(bet_money) AS bet_c3_m, sum(bet_win_money) AS bet_c3_w from bet_coin3ball group by bet_mb_uid ) bet_c3 ON bet_c3.bet_mb_uid = member.mb_uid";
        }
        if(!$confs['cas_deny'] || $confs['kgon_enable']){
            if(isEBalMode()){
                $tbName = "bet_ebal";
            } else 
                $tbName = "bet_casino";

            $strSQL.= " LEFT JOIN ( select bet_mb_uid, sum(bet_money) AS bet_cs_m, sum(bet_win_money) AS bet_cs_w from ".$tbName." group by bet_mb_uid ) bet_cs ON bet_cs.bet_mb_uid = member.mb_uid";
        }
	    $strSQL.= " LEFT JOIN ( select rw_mb_fid, sum(rw_point) AS rw_point from bet_reward group by rw_mb_fid ) sum_reward ON sum_reward.rw_mb_fid = member.mb_fid";
	    $strSQL.= " LEFT JOIN ( select money_mb_fid, sum(money_amount) AS chg_point from money_history where money_change_type = ".POINTCHANGE_EXCHANGE." group by money_mb_fid ) chg_point ON chg_point.money_mb_fid = member.mb_fid";

        $strSQL.= " WHERE mb_level < '".LEVEL_ADMIN."' ";
        $strSQL .=" AND mb_state_active != '".PERMIT_DELETE."' ";
        $strSQL.= $where;

        $strSQL .= " ORDER BY (CASE WHEN mb_state_active = 2 THEN 0 ELSE 1 END) ";
        if (strlen($arrReqData['order']) > 0 && strlen($arrReqData['dir']) > 0) {
            $strSQL .= " , ".$arrReqData['order']." ".$arrReqData['dir']." ";
        }
        else $strSQL .= " , mb_uid ASC ";
        
        $nStartRow = ($arrReqData['page'] - 1) * $arrReqData['count'];
        $strSQL .= ' LIMIT '.$nStartRow.', '.$arrReqData['count'];
        // writeLog($strSQL);

        return $this->db->query($strSQL)->getResult();
    }



}
