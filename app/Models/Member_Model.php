<?php

namespace App\Models;

use CodeIgniter\Model;

class Member_Model extends Model
{
    protected $table = 'member';
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
        'mb_game_pb_ratio',
        'mb_game_pb2_ratio',
        'mb_game_ps_ratio',
        'mb_game_bb_ratio',
        'mb_game_bb2_ratio',
        'mb_game_bs_ratio',
        'mb_game_cs_ratio',
        'mb_game_sl_ratio',
        'mb_game_pb_percent',
        'mb_game_pb2_percent',
        'mb_game_ps_percent',
        'mb_game_bb_percent',
        'mb_game_bb2_percent',
        'mb_game_bs_percent',
        'mb_live_id',
        'mb_live_uid',
        'mb_live_money',
        'mb_slot_uid',
        'mb_slot_money',
        'mb_fslot_id',
        'mb_fslot_uid',
        'mb_fslot_money',
    ];

    protected $primaryKey = 'mb_fid';
    
    private $getFields = ['mb_fid', 'mb_uid', 'mb_level','mb_emp_fid', 'mb_emp_permit', 'mb_nickname', 
        'mb_email', 'mb_phone', 'mb_bank_name', 'mb_bank_own', 'mb_bank_num', 'mb_bank_pwd',
        'mb_ip_join', 'mb_ip_last',
        'mb_money', 'mb_point', 'mb_money_charge', 'mb_money_exchange', 'mb_grade', 'mb_color',
        'mb_state_active', 'mb_state_delete', 'mb_state_alarm', 'mb_state_view',
        'mb_game_pb', 'mb_game_ps', 'mb_game_bb', 'mb_game_bs', 'mb_game_cs', 'mb_game_sl', 
        'mb_game_pb_ratio', 'mb_game_pb2_ratio','mb_game_ps_ratio', 'mb_game_bb_ratio', 'mb_game_bb2_ratio', 
        'mb_game_bs_ratio', 'mb_game_cs_ratio', 'mb_game_sl_ratio', 
        'mb_game_pb_percent', 'mb_game_pb2_percent', 'mb_game_ps_percent', 'mb_game_bb_percent',
        'mb_game_bb2_percent', 'mb_game_bs_percent',
        'mb_live_id', 'mb_live_uid', 'mb_live_money', 
        'mb_slot_uid', 'mb_slot_money', 
        'mb_fslot_id', 'mb_fslot_uid', 'mb_fslot_money' ];


    private $fields = ['mb_fid', 'mb_uid', 'mb_level','mb_emp_fid', 'mb_emp_permit', 'mb_nickname', 
            'mb_ip_join', 'mb_ip_last',
            'mb_money', 'mb_point', 'mb_money_charge', 'mb_money_exchange', 'mb_grade', 
            'mb_state_active', 'mb_state_delete', 'mb_state_alarm', 'mb_state_view',
            'mb_game_pb', 'mb_game_ps', 'mb_game_bb', 'mb_game_bs', 'mb_game_cs', 'mb_game_sl', 
            'mb_game_pb_ratio', 'mb_game_pb2_ratio','mb_game_ps_ratio', 'mb_game_bb_ratio', 'mb_game_bb2_ratio', 
            'mb_game_bs_ratio', 'mb_game_cs_ratio', 'mb_game_sl_ratio', 
            'mb_game_pb_percent', 'mb_game_pb2_percent', 'mb_game_ps_percent', 'mb_game_bb_percent',
            'mb_game_bb2_percent', 'mb_game_bs_percent',
            'mb_live_id', 'mb_live_uid', 'mb_live_money', 
            'mb_slot_uid', 'mb_slot_money', 
            'mb_fslot_id', 'mb_fslot_uid', 'mb_fslot_money' ];
        
    
    protected $validationRules = [
        'mb_uid' => 'required|alpha_numeric|is_unique[member.mb_uid, mb_fid, {mb_fid}]',
        'mb_nickname' => 'required|min_length[3]|max_length[20]|is_unique[member.mb_nickname, mb_fid, {mb_fid}]',
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
            'min_length' => '닉네임은 최소 3글자 이상입니다.',
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
        return $this->select($this->fields)->where('mb_uid', $strId)->first();
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

    // public function deleteMemberByFid($arrDeleteData)
    // {
    //     return $this->delete($arrDeleteData['mb_fid']);
    // }

    public function changePassword($strUserId, $arrPwd, &$query)
    {
        if (null == $arrPwd) {
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
        if (null == $objUser) {
            return 2;
        }

        // 이전 패스워드가 같은가 검사
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
        if (null == $arrReqData) {
            return 0;
        }
        if (!array_key_exists('mb_state_alarm', $arrReqData)) {
            return false;
        }

        return $this->builder()->set('mb_state_alarm', $arrReqData['mb_state_alarm'])
        ->where('mb_uid', $strUserId)
        ->update();
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

    public function calcMemberMoney($strMemFid, $upLevel)
    {
        $arrTotalMoney = [0, 0, 0, 0];

        $strTbColum = ' mb_fid, mb_uid, mb_level, mb_emp_fid, mb_state_active, mb_money, mb_live_money, mb_slot_money, mb_fslot_money ';
        $strTbRColum = ' r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid , r.mb_state_active, r.mb_money, r.mb_live_money, r.mb_slot_money, r.mb_fslot_money ';

        $strSQL = 'WITH RECURSIVE tbmember ('.$strTbColum.') AS';
        $strSQL .= ' ( SELECT '.$strTbColum.' FROM '.$this->table." WHERE "; 
        if ($upLevel) {
            $strSQL .= " mb_emp_fid = '".$strMemFid."'";
        } else {
            $strSQL .= " mb_fid = '".$strMemFid."'";
        }
        $strSQL .= ' UNION ALL SELECT '.$strTbRColum.' FROM '.$this->table.' r ';
        $strSQL .= ' INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )';

        $strSQL .= ' SELECT SUM(mb_money) AS mb_money, SUM(mb_live_money) AS mb_live_money, SUM(mb_slot_money) AS mb_slot_money, SUM(mb_fslot_money) AS mb_fslot_money FROM tbmember ';
        $strSQL .=" WHERE mb_state_active != '".PERMIT_DELETE."' ";
        if ($upLevel) {
            $strSQL .= " AND mb_level >= '".LEVEL_EMPLOYEE."' ";
        } else {
            $strSQL .= " AND mb_level < '".LEVEL_EMPLOYEE."' ";
        }

        $objResult = $this->db->query($strSQL)->getRow();

        if (!is_null($objResult->mb_money)) {
            $arrTotalMoney[0] = $objResult->mb_money;
        }
        if (!is_null($objResult->mb_live_money)) {
            $arrTotalMoney[1] = $objResult->mb_live_money;
        }
        if (!is_null($objResult->mb_slot_money)) {
            $arrTotalMoney[2] = $objResult->mb_slot_money;
        }
        if (!is_null($objResult->mb_fslot_money)) {
            $arrTotalMoney[3] = $objResult->mb_fslot_money;
        }
        
        return $arrTotalMoney;
    }

    // 유저 보유금
    public function calcUserMoney($strEmpFid)
    {
        return $this->calcMemberMoney($strEmpFid, false);
    }

    // 직원별 보유금
    public function calcEmpMoney($objEmp)
    {
        $arrTotalMoney = [0, 0, 0, 0];
        if ($objEmp->mb_level >= LEVEL_EMPLOYEE) {
            $arrResult = $this->calcMemberMoney($objEmp->mb_fid, true);
            $arrTotalMoney[0] = $arrResult[0] + $objEmp->mb_money;
            $arrTotalMoney[1] = $arrResult[1] + $objEmp->mb_live_money;
            $arrTotalMoney[2] = $arrResult[2] + $objEmp->mb_slot_money;
            $arrTotalMoney[3] = $arrResult[3] + $objEmp->mb_fslot_money;
        }

        return $arrTotalMoney;
    }

    // 배팅금액 (하부포함)
    public function calcBetMoneys($objEmp, $arrReqData, $confs)
    {
        $strCond = "";
        if (strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0) {
            $strCond = " WHERE ".getBetTimeRange($arrReqData);
        }

        $strTbColum = ' mb_fid, mb_uid, mb_level, mb_emp_fid ';
        $strTbRColum = ' r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid ';

        $strSQL = 'WITH RECURSIVE tbmember ('.$strTbColum.') AS';
        $strSQL .= ' ( SELECT '.$strTbColum.' FROM '.$this->table." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
        $strSQL .= ' UNION ALL SELECT '.$strTbRColum.' FROM '.$this->table.' r ';
        $strSQL .= ' INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )';

        $strSQL .= ' SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money ';
        $strSQL .= '  FROM (SELECT  * FROM tbmember UNION SELECT '.$strTbColum.' FROM '.$this->table." where mb_fid='".$objEmp->mb_fid."'";
        $strSQL .= ' ) AS mb_table ';
        
        $strSQL .= ' JOIN ( (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, bet_emp_fid, bet_mb_uid FROM bet_slot';
        $strSQL .= $strCond;
        $strSQL .= ' GROUP BY bet_mb_uid) ';

        if(!$confs['npg_deny']){
            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, bet_emp_fid, bet_mb_uid FROM bet_powerball ';
            $strSQL .= $strCond;
            $strSQL .= ' GROUP BY bet_mb_uid) ';

            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, bet_emp_fid, bet_mb_uid FROM bet_powerladder ';
            $strSQL .= $strCond;
            $strSQL .= ' GROUP BY bet_mb_uid) ';
        }

        if(!$confs['bpg_deny']){
            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, bet_emp_fid, bet_mb_uid FROM bet_bogleball ';
            $strSQL .= $strCond;
            $strSQL .= ' GROUP BY bet_mb_uid) ';

            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, bet_emp_fid, bet_mb_uid FROM bet_bogleladder ';
            $strSQL .= $strCond;
            $strSQL .= ' GROUP BY bet_mb_uid) ';
        }

        if(!$confs['cas_deny']){
            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, bet_emp_fid, bet_mb_uid FROM bet_casino ';
            $strSQL .= $strCond;
            $strSQL .= ' GROUP BY bet_mb_uid) ';
        }

        $strSQL .= ' )AS bet_table ON bet_table.bet_mb_uid = mb_table.mb_uid ';

        $objResult = $this->db->query($strSQL)->getRow();

        $arrBetData['bet_money'] = 0;          // 베팅머니
        $arrBetData['bet_win_money'] = 0;      // 적중머니
        $arrBetData['bet_benefit_money'] = 0;  // 베팅손익
        // $arrBetData['rate_money'] = 0;         // 수수료
        // $arrBetData['last_money'] = 0;         // 최종손익

         if (!is_null($objResult->bet_money)) {
             $arrBetData['bet_money'] += $objResult->bet_money;
         }
        if (!is_null($objResult->bet_win_money)) {
            $arrBetData['bet_win_money'] += $objResult->bet_win_money;
        }

        // $arrBetData['bet_money'] = 0;          //베팅머니
        // $arrBetData['bet_win_money'] = $arrBetData['bet_win_money']-$arrBetData['bet_money'];      //적중머니
        $arrBetData['bet_benefit_money'] = $arrBetData['bet_money'] - $arrBetData['bet_win_money'];  // 베팅손익
        // $arrBetData['rate_money'] = 0;          //수수료
        // $arrBetData['last_money'] = $arrBetData['bet_benefit_money'] - $arrBetData['rate_money'];

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

        $strSQL .= ' SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money ';
        $strSQL .= '  FROM (SELECT  * FROM tbmember UNION SELECT '.$strTbColum.' FROM '.$this->table." where mb_fid='".$objEmp->mb_fid."'";
        $strSQL .= ' ) AS mb_table ';

        $strSQL .= ' JOIN ( SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, bet_emp_fid, bet_mb_uid FROM ';
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
        } elseif ($arrReqData['type'] == GAME_SLOT_1 || $arrReqData['type'] == GAME_SLOT_2 || $arrReqData['type'] == GAME_SLOT_12 ) {
            $strSQL .= ' bet_slot ';
        } else {
            return null;
        }
        $strSQL .= " WHERE ".getBetTimeRange($arrReqData);
        if ($arrReqData['type'] == GAME_SLOT_1 || $arrReqData['type'] == GAME_SLOT_2){
            $strSQL .= " AND bet_game_id = '".$arrReqData['type']."' ";
        }
        $strSQL .= ' GROUP BY bet_mb_uid ';
        $strSQL .= ' )AS bet_table ON bet_table.bet_mb_uid = mb_table.mb_uid ';

        $objResult = $this->db->query($strSQL)->getRow();

        $arrBetData['bet_money'] = 0;          // 베팅머니
        $arrBetData['bet_win_money'] = 0;      // 적중머니
        $arrBetData['bet_benefit_money'] = 0;  // 베팅손익
        // $arrBetData['rate_money'] = 0;         // 수수료
        // $arrBetData['last_money'] = 0;         // 최종손익

         if (!is_null($objResult->bet_money)) {
             $arrBetData['bet_money'] += $objResult->bet_money;
         }
        if (!is_null($objResult->bet_win_money)) {
            $arrBetData['bet_win_money'] += $objResult->bet_win_money;
        }

        // $arrBetData['bet_money'] = 0;          //베팅머니
        // $arrBetData['bet_win_money'] = $arrBetData['bet_win_money']-$arrBetData['bet_money'];      //적중머니
        $arrBetData['bet_benefit_money'] = $arrBetData['bet_money'] - $arrBetData['bet_win_money'];  // 베팅손익
        // $arrBetData['rate_money'] = 0;          //수수료
        // $arrBetData['last_money'] = $arrBetData['bet_benefit_money'] - $arrBetData['rate_money'];

        return $arrBetData;
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

    public function getMemberByLevel($strLevel, $bLowLev = false, $mbFid = 0)
    {
        $query = null;

        $where =" mb_state_active != '".PERMIT_DELETE."' ";

        if ($bLowLev) {
            $where .= 'AND mb_level <= '.$strLevel;
        } else {
            $where .= 'AND mb_level = '.$strLevel;
        }
        if($mbFid > 0)
            $where .= " AND mb_fid = '".$mbFid."' ";
        
        $query = $this->where($where);
        if (null == $query) {
            return [];
        }

        return $query->findAll();
    }


    public function getMemberByEmpFid($nEmpFid, $nReqLevel, $nEmpLev = LEVEL_AGENCY, $bLowLev = false, $mbFid=0)
    {
        if ($nEmpLev > LEVEL_COMPANY) {
            return $this->getMemberByLevel($nReqLevel, $bLowLev, $mbFid);
        } else {
            $strTbColum = ' mb_fid, mb_uid, mb_level, mb_emp_fid, mb_emp_permit, mb_nickname, mb_phone, mb_money, mb_point, ';
            $strTbColum .= ' mb_grade, mb_color, mb_state_active, mb_state_delete, ';
            $strTbColum .= ' mb_game_pb_ratio, mb_game_pb2_ratio, mb_game_ps_ratio, mb_game_bb_ratio, mb_game_bb2_ratio, ';
            $strTbColum .= ' mb_game_bs_ratio, mb_game_cs_ratio, mb_game_sl_ratio, ';
            $strTbColum .= ' mb_game_pb_percent, mb_game_pb2_percent, mb_game_ps_percent, mb_game_bb_percent, mb_game_bb2_percent, mb_game_bs_percent, ';
            $strTbColum .= ' mb_live_money, mb_slot_money, mb_fslot_money';

            $strTbRColum = ' r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid, r.mb_emp_permit, r.mb_nickname, r.mb_phone, r.mb_money, r.mb_point, ';
            $strTbRColum .= ' r.mb_grade, r.mb_color, r.mb_state_active, r.mb_state_delete, ';
            $strTbRColum .= ' r.mb_game_pb_ratio, r.mb_game_pb2_ratio, r.mb_game_ps_ratio, r.mb_game_bb_ratio, r.mb_game_bb2_ratio,';
            $strTbRColum .= ' r.mb_game_bs_ratio, r.mb_game_cs_ratio, r.mb_game_sl_ratio, ';
            $strTbRColum .= ' r.mb_game_pb_percent, r.mb_game_pb2_percent, r.mb_game_ps_percent, r.mb_game_bb_percent, r.mb_game_bb2_percent, r.mb_game_bs_percent, ';
            $strTbRColum .= ' r.mb_live_money, r.mb_slot_money, r.mb_fslot_money ';

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


    private function checkGameRatio($objEmployee, $arrRegData, &$strError)
    {
        // 배당율 검사
        if ($objEmployee->mb_game_pb_ratio < $arrRegData['mb_game_pb_ratio']) {
            $strError = $objEmployee->mb_game_pb_ratio;

            return 4;
        } elseif ($objEmployee->mb_game_pb2_ratio < $arrRegData['mb_game_pb2_ratio']) {
            $strError = $objEmployee->mb_game_pb2_ratio;

            return 4;
        } elseif ($objEmployee->mb_game_ps_ratio < $arrRegData['mb_game_ps_ratio']) {
            $strError = $objEmployee->mb_game_ps_ratio;

            return 5;
        } elseif ($objEmployee->mb_game_cs_ratio < $arrRegData['mb_game_cs_ratio']) {
            $strError = $objEmployee->mb_game_cs_ratio;

            return 7;
        } elseif ($objEmployee->mb_game_sl_ratio < $arrRegData['mb_game_sl_ratio']) {
            $strError = $objEmployee->mb_game_sl_ratio;

            return 8;
        } elseif ($objEmployee->mb_game_bb_ratio < $arrRegData['mb_game_bb_ratio']) {
            $strError = $objEmployee->mb_game_bb_ratio;

            return 9;
        } elseif ($objEmployee->mb_game_bb2_ratio < $arrRegData['mb_game_bb2_ratio']) {
            $strError = $objEmployee->mb_game_bb2_ratio;

            return 9;
        } elseif ($objEmployee->mb_game_bs_ratio < $arrRegData['mb_game_bs_ratio']) {
            $strError = $objEmployee->mb_game_bs_ratio;

            return 10;
        }

        return 1;
    }

    private function setZeroGameRatio(&$arrRegData)
    {
        if (strlen($arrRegData['mb_game_pb_ratio']) < 1) {
            $arrRegData['mb_game_pb_ratio'] = 0;
        }
        if (strlen($arrRegData['mb_game_pb2_ratio']) < 1) {
            $arrRegData['mb_game_pb2_ratio'] = 0;
        }
        if (strlen($arrRegData['mb_game_ps_ratio']) < 1) {
            $arrRegData['mb_game_ps_ratio'] = 0;
        }
        if (strlen($arrRegData['mb_game_cs_ratio']) < 1) {
            $arrRegData['mb_game_cs_ratio'] = 0;
        }
        if (strlen($arrRegData['mb_game_sl_ratio']) < 1) {
            $arrRegData['mb_game_sl_ratio'] = 0;
        }
        if (strlen($arrRegData['mb_game_bb_ratio']) < 1) {
            $arrRegData['mb_game_bb_ratio'] = 0;
        }
        if (strlen($arrRegData['mb_game_bb2_ratio']) < 1) {
            $arrRegData['mb_game_bb2_ratio'] = 0;
        }
        if (strlen($arrRegData['mb_game_bs_ratio']) < 1) {
            $arrRegData['mb_game_bs_ratio'] = 0;
        }
        

        if (floatval($arrRegData['mb_game_pb_ratio']) < 0) {
            $arrRegData['mb_game_pb_ratio'] = 0;
        }
        if (floatval($arrRegData['mb_game_pb2_ratio']) < 0) {
            $arrRegData['mb_game_pb2_ratio'] = 0;
        }
        if (floatval($arrRegData['mb_game_ps_ratio']) < 0) {
            $arrRegData['mb_game_ps_ratio'] = 0;
        }
        if (floatval($arrRegData['mb_game_cs_ratio']) < 0) {
            $arrRegData['mb_game_cs_ratio'] = 0;
        }
        if (floatval($arrRegData['mb_game_sl_ratio']) < 0) {
            $arrRegData['mb_game_sl_ratio'] = 0;
        }
        if (floatval($arrRegData['mb_game_bb_ratio']) < 0) {
            $arrRegData['mb_game_bb_ratio'] = 0;
        }
        if (floatval($arrRegData['mb_game_bb2_ratio']) < 0) {
            $arrRegData['mb_game_bb2_ratio'] = 0;
        }
        if (floatval($arrRegData['mb_game_bs_ratio']) < 0) {
            $arrRegData['mb_game_bs_ratio'] = 0;
        }
    }

    private function BuilderSetGameRatioAndPercent($arrRegData)
    {
        $this->builder()->set('mb_game_pb_ratio', $arrRegData['mb_game_pb_ratio']);
        $this->builder()->set('mb_game_pb2_ratio', $arrRegData['mb_game_pb2_ratio']);
        $this->builder()->set('mb_game_ps_ratio', $arrRegData['mb_game_ps_ratio']);
        $this->builder()->set('mb_game_cs_ratio', $arrRegData['mb_game_cs_ratio']);
        $this->builder()->set('mb_game_sl_ratio', $arrRegData['mb_game_sl_ratio']);
        $this->builder()->set('mb_game_pb_percent', $arrRegData['mb_game_pb_percent']);
        $this->builder()->set('mb_game_pb2_percent', $arrRegData['mb_game_pb2_percent']);
        $this->builder()->set('mb_game_ps_percent', $arrRegData['mb_game_ps_percent']);
        $this->builder()->set('mb_game_bb_percent', $arrRegData['mb_game_bb_percent']);
        $this->builder()->set('mb_game_bb2_percent', $arrRegData['mb_game_bb2_percent']);
        $this->builder()->set('mb_game_bs_percent', $arrRegData['mb_game_bs_percent']);
    }

    public function register($arrRegData, &$strError)
    {
        // 결과 -1: query error 0:오유 1:성공 3:추천인 오유 4:파워볼 배당율오유 5:파워사다리 배당율오유 6:키노사다리 배당율 오유

        if (LEVEL_COMPANY == $arrRegData['mb_level']) {
            $arrRegData['mb_emp_fid'] = 0;
        } elseif ($arrRegData['mb_emp_fid'] > 0) {
            // 추천인 검사
            $objEmployee = $this->getInfoByFid($arrRegData['mb_emp_fid']);
            if (is_null($objEmployee)) {
                return 3;
            }

            if ($arrRegData['mb_level'] < LEVEL_MIN){
                return 3;
            }
            
            // 배당율 검사
            $ratioResult = $this->checkGameRatio($objEmployee, $arrRegData, $strError);
            if (1 != $ratioResult) {
                return $ratioResult;
            }
            
        } else {
            return 0;
        }
        $this->setZeroGameRatio($arrRegData);

        // 자료기지 등록
        $arrRegData['mb_uid'] = trim($arrRegData['mb_uid']);
        $arrRegData['mb_nickname'] = trim($arrRegData['mb_nickname']);
        $arrRegData['mb_bank_name'] = trim($arrRegData['mb_bank_name']);
        $arrRegData['mb_bank_own'] = trim($arrRegData['mb_bank_own']);
        $arrRegData['mb_bank_num'] = trim($arrRegData['mb_bank_num']);
        $arrRegData['mb_time_join'] = date('Y-m-d H:i:s');
        if ($arrRegData['mb_money'] < 0) {
            $arrRegData['mb_money'] = 0;
        }

        if ($arrRegData['mb_point'] < 0) {
            $arrRegData['mb_point'] = 0;
        }
        $arrRegData['mb_state_active'] = 2;
        $arrRegData['mb_game_pb'] = 1;
        $arrRegData['mb_game_ps'] = 1;
        $arrRegData['mb_game_bb'] = 1;
        $arrRegData['mb_game_bs'] = 1;
        $arrRegData['mb_game_cs'] = 1;
        $arrRegData['mb_game_sl'] = 1;
        $result = $this->insert($arrRegData);
        if (!$result) {
            $strError = $this->errors();

            return -1;
        }

        return 1;
    }

    public function modifyMember($arrData, &$strError, &$query)
    {
        // 결과 0:오유 1:성공 2:아이디중복 3:추천인 오유 4:파워볼 배당율오유 5:파워사다리 배당율오유 6:키노사다리 배당율 오유, 11 중복닉네임

        // 아이디검사
        $objMember = $this->getInfoByFid($arrData['mb_fid']);
        if (is_null($objMember)) {
            return 0;
        }

        if (LEVEL_COMPANY == $objMember->mb_level) {
            $arrData['mb_emp_fid'] = 0;

            $objUser = $this->getByNickname($arrData['mb_nickname']);
            if (!is_null($objUser) && $objUser->mb_fid != $arrData['mb_fid']) {
                return 11;
            }
        } elseif ($arrData['mb_emp_fid'] > 0) {
            // 추천인 검사
            $objEmployee = $this->getInfoByFid($arrData['mb_emp_fid']);
            if (is_null($objEmployee) || $objEmployee->mb_level < LEVEL_MIN) {
                return 3;
            }

            // 닉네임 검사
            if ($objMember->mb_level >= LEVEL_EMPLOYEE) {
                $objUser = $this->getByNickname($arrData['mb_nickname']);
                if (!is_null($objUser) && $objUser->mb_fid != $arrData['mb_fid']) {
                    return 11;
                }
            }
            $resultRatio = $this->checkGameRatio($objEmployee, $arrData, $strError);
            if (1 != $resultRatio) {
                return $resultRatio;
            }
        } else {
            return 0;
        }

        $this->setZeroGameRatio($arrData);

        if (strlen($arrData['mb_game_pb_percent']) < 1) {
            $arrData['mb_game_pb_percent'] = 0;
        }
        if (strlen($arrData['mb_game_pb2_percent']) < 1) {
            $arrData['mb_game_pb2_percent'] = 0;
        }
        if (strlen($arrData['mb_game_ps_percent']) < 1) {
            $arrData['mb_game_ps_percent'] = 0;
        }
        if (strlen($arrData['mb_game_bb_percent']) < 1) {
            $arrData['mb_game_bb_percent'] = 0;
        }
        if (strlen($arrData['mb_game_bb2_percent']) < 1) {
            $arrData['mb_game_bb2_percent'] = 0;
        }
        if (strlen($arrData['mb_game_bs_percent']) < 1) {
            $arrData['mb_game_bs_percent'] = 0;
        }

        $arrData['mb_bank_name'] = trim($arrData['mb_bank_name']);
        $arrData['mb_bank_own'] = trim($arrData['mb_bank_own']);
        $arrData['mb_bank_num'] = trim($arrData['mb_bank_num']);

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

        return -1;
    }

    public function modifyMemberRatio($arrData, &$strError, &$query)
    {
        // 결과 0:오유 1:성공 2:아이디중복 3:추천인 오유 4:파워볼 배당율오유 5:파워사다리 배당율오유 6:키노사다리 배당율 오유

        // 아이디검사
        $objMember = $this->getInfoByFid($arrData['mb_fid']);
        if (is_null($objMember)) {
            return 0;
        }

        if ($objMember->mb_level < LEVEL_ADMIN) {
            // 추천인 검사
            $objEmployee = $this->getInfoByFid($objMember->mb_emp_fid);
            if (null == $objEmployee) {
                return 0;
            }

            $resultRatio = $this->checkGameRatio($objEmployee, $arrData, $strError);
            if (1 != $resultRatio) {
                return $resultRatio;
            }
        } else {
            return 0;
        }

        
        $this->setZeroGameRatio($arrData);

        if (strlen($arrData['mb_game_pb_percent']) < 1) {
            $arrData['mb_game_pb_percent'] = 0;
        }
        if (strlen($arrData['mb_game_pb2_percent']) < 1) {
            $arrData['mb_game_pb2_percent'] = 0;
        }
        if (strlen($arrData['mb_game_ps_percent']) < 1) {
            $arrData['mb_game_ps_percent'] = 0;
        }
        if (strlen($arrData['mb_game_bb_percent']) < 1) {
            $arrData['mb_game_bb_percent'] = 0;
        }
        if (strlen($arrData['mb_game_bb2_percent']) < 1) {
            $arrData['mb_game_bb2_percent'] = 0;
        }
        if (strlen($arrData['mb_game_bs_percent']) < 1) {
            $arrData['mb_game_bs_percent'] = 0;
        }

        $this->builder()->set('mb_color', $arrData['mb_color']);
        if(array_key_exists('mb_state_delete', $arrData)){
            $this->builder()->set('mb_state_delete', $arrData['mb_state_delete']);
        }
        $this->builderSetGameRatioAndPercent($arrData);

        $this->builder()->where('mb_fid', $arrData['mb_fid']);
        $bResult = $this->builder()->update();
        $query = $this->db->getLastQuery();

        return 1;
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
        } else {
            return false;
        }

        $this->builder()->where('mb_fid', $arrData['mb_fid']);
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
        $strSQL = 'SELECT SUM(mb_money+mb_live_money+mb_slot_money+mb_fslot_money) AS emp_money, SUM(mb_point) AS emp_point FROM '.$this->table;
        $strSQL .= ' WHERE mb_level < '.LEVEL_ADMIN;
        $strSQL .=" AND mb_state_active != '".PERMIT_DELETE."' ";

        $objResult = $this->db->query($strSQL)->getRow();

        return $objResult;
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
            $sqlBuilder->like('mb_uid', $arrReqData['mb_uid']);
        }
        return $sqlBuilder->get()->getRow();
    }

    public function searchCountByEmpFid($objUser, $arrReqData, $iEmpFid)
    {
        if($objUser->mb_level > LEVEL_COMPANY)
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
            'mb_money', 'mb_point', 'mb_grade', 'mb_color', 'mb_state_active', 
            'mb_game_pb', 'mb_game_ps', 'mb_game_bb', 'mb_game_bs', 'mb_game_cs', 'mb_game_sl', 
            'mb_live_money', 'mb_slot_money', 'mb_fslot_money' ];

        $strTbColum = " ".implode(", ", $fields);
        $strTbColum.= ", block_ip, block_state ";

        $tbBlock = "block_list";

        $where = "";
        if ($iEmpFid != 0){
            $where .= " AND mb_emp_fid = '".$iEmpFid."'";
        }
        if (strlen($arrReqData['mb_uid']) > 0) {
            $where .= " AND mb_uid LIKE '%".$arrReqData['mb_uid']."%'";
        }
        if ($arrReqData['mb_grade'] != 0){
            $where .= " AND mb_grade = '".$arrReqData['mb_grade']."'";
        }
        if ($arrReqData['mb_state'] >= 0){
            $where .= " AND mb_state_active = '".$arrReqData['mb_state']."' ";
        }


        $strQuery = "SELECT ".$strTbColum." FROM ".$this->table;
        $strQuery.= ' LEFT JOIN '.$tbBlock.' ON '.$this->table.'.mb_ip_last = '.$tbBlock.'.block_ip ';
        $strQuery.= " WHERE mb_level < '".LEVEL_ADMIN."' ";
        $strQuery .=" AND mb_state_active != '".PERMIT_DELETE."' ";
        $strQuery.= $where;

        $strQuery .= " ORDER BY (CASE WHEN mb_state_active = 2 THEN 0 ELSE 1 END) ";
        $strQuery .= " , mb_uid ASC ";
        
        $nStartRow = ($arrReqData['page'] - 1) * $arrReqData['count'];
        $strQuery .= ' LIMIT '.$nStartRow.', '.$arrReqData['count'];
        return $this->db->query($strQuery)->getResult();
    }

    public function searchMemberByEmpFid($objUser, $arrReqData, $iEmpFid)
    {
        if($objUser->mb_level > LEVEL_COMPANY)
        {
            return $this->searchMemberByLevel($arrReqData, $iEmpFid);
        } else {
            $fields = ['mb_fid', 'mb_uid', 'mb_level','mb_emp_fid', 'mb_nickname', 
            'mb_money', 'mb_point', 'mb_grade', 'mb_color', 'mb_state_active',
            'mb_live_money', 'mb_slot_money', 'mb_fslot_money' ];

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

        if($objMember->mb_level > LEVEL_COMPANY)
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

}
