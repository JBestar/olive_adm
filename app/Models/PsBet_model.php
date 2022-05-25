<?php
namespace App\Models;
use CodeIgniter\Model;

class PsBet_Model extends Model {
	

    protected $table = 'bet_powerladder';
    protected $returnType = 'object'; 
    protected $allowedFields = [
        'bet_state', 
        'bet_emp_fid', 
        'bet_mb_uid', 
        'bet_round_fid', 
        'bet_round_no', 
        'bet_time', 
        'bet_mode', 
        'bet_target', 
        'bet_ratio', 
        'bet_money', 
        'bet_bonus', 
        'bet_result', 
        'bet_win_money', 
        'user_before_money', 
        'user_after_money', 
        'user_view_state', 
        'auto_config_id', 
        'auto_config_step', 
        'cumulate_amount', 
        'account_time', 
        'point_amount', 
        'employee_amount', 
        'agency_amount', 
        'company_amount'
    ];
    protected $primaryKey = 'bet_fid';
    private $mMemberTable = 'member';
    private $mRewardTable = 'bet_reward';

    function gets($nCount)
    {
        
        $strSql = "SELECT bet_fid, bet_state, bet_mb_uid, bet_round_fid, bet_round_no, bet_time, bet_mode, bet_target, bet_ratio, bet_money, bet_result, bet_win_money, user_before_money, user_after_money FROM ".$this->table." ORDER BY bet_time DESC LIMIT 0, ".$nCount;
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result;  
    }

    function get($nRoundFid){        
        return $this->where(array('bet_round_fid'=>$nRoundFid))->findAll();
    }


    function getByFid($nBetFid){        
        return $this->where(array('bet_fid'=>$nBetFid))->first();
    }

    function getByUserId($strUserId, $nCount)
    {
        
        $strSql = "SELECT bet_state, bet_round_fid, bet_round_no, bet_time, bet_mode, bet_target, bet_money, bet_win_money  FROM ".$this->table;
        $strSql .=" WHERE bet_mb_uid='".$strUserId."' ORDER BY bet_time DESC LIMIT 0, ".$nCount;
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result;  
    }

    function getBetSumByMode($arrRoundInfo, $objConfPs){

        $arrSumData = array();

        for($i = 0; $i <3; $i ++){

            $iMode = $i+1;
            
            // $strSql = " SELECT SUM(bet_money_sum * mb_game_ps_percent DIV 100) AS bet_money_allsum FROM ( ";
            $strSql = " SELECT SUM(bet_money_sum) AS bet_money_allsum FROM ( ";
            $strSql .= " SELECT bet_mb_uid, bet_mode, bet_target, bet_ratio, SUM(bet_money) AS bet_money_sum, mb_game_ps_percent FROM ".$this->table;
            $strSql .= " JOIN ".$this->mMemberTable." ON ".$this->mMemberTable.".mb_uid = ".$this->table.".bet_mb_uid ";
            $strSql .= " WHERE bet_round_no='".$arrRoundInfo['round_no']."' AND bet_state = '1' ";
            $strSql .= " AND bet_time >= '".$arrRoundInfo['round_start']."' AND bet_time <= '".$arrRoundInfo['round_end']."' ";
            $strSql .= " AND bet_mode='".$iMode."' AND bet_target='P' GROUP BY bet_mb_uid ";
            $strSql .= " ) tb_sum ";
            $objResult = $this -> db -> query($strSql)->getRow();
            
            //유저별 배팅결과 합
            $nSum = 0;
            if(!is_null($objResult->bet_money_allsum)) {
                $nSum = $objResult->bet_money_allsum;
            }
            // $nSum = $nSum * $objConfPs->game_percent_1 / 100;
            $arrSum[0] = (int)$nSum;

            
            // $strSql = " SELECT SUM(bet_money_sum * mb_game_ps_percent DIV 100) AS bet_money_allsum FROM ( ";
            $strSql = " SELECT SUM(bet_money_sum) AS bet_money_allsum FROM ( ";
            $strSql .= " SELECT bet_mb_uid, bet_mode, bet_target, bet_ratio, SUM(bet_money) AS bet_money_sum, mb_game_ps_percent FROM ".$this->table;
            $strSql .= " JOIN ".$this->mMemberTable." ON ".$this->mMemberTable.".mb_uid = ".$this->table.".bet_mb_uid ";
            $strSql .= " WHERE bet_round_no='".$arrRoundInfo['round_no']."' AND bet_state = '1' ";
            $strSql .= " AND bet_time >= '".$arrRoundInfo['round_start']."' AND bet_time <= '".$arrRoundInfo['round_end']."' ";
            $strSql .= " AND bet_mode='".$iMode."' AND bet_target='B' GROUP BY bet_mb_uid ";
            $strSql .= " ) tb_sum ";
            $objResult = $this -> db -> query($strSql)->getRow();
        
            //유저별 배팅결과 합
            $nSum = 0;
            if(!is_null($objResult->bet_money_allsum)) {
                $nSum = $objResult->bet_money_allsum;
            }
            //게임별 누르기율 계산
            // $nSum = $nSum * $objConfPs->game_percent_1 / 100;
            $arrSum[1] = (int)$nSum;
        
            $arrSumData[$i] = $arrSum;
        }  
        $arrSum = array();
        for($i = 3; $i <7; $i ++){
            $iMode = $i+1;
            // $strSql = " SELECT SUM(bet_money_sum * mb_game_ps_percent DIV 100) AS bet_money_allsum FROM ( ";
            $strSql = " SELECT SUM(bet_money_sum) AS bet_money_allsum FROM ( ";
            $strSql .= " SELECT bet_mb_uid, bet_mode, bet_target, bet_ratio, SUM(bet_money) AS bet_money_sum, mb_game_ps_percent FROM ".$this->table;
            $strSql .= " JOIN ".$this->mMemberTable." ON ".$this->mMemberTable.".mb_uid = ".$this->table.".bet_mb_uid ";
            $strSql .= " WHERE bet_round_no='".$arrRoundInfo['round_no']."' AND bet_state = '1' ";
            $strSql .= " AND bet_time > '".$arrRoundInfo['round_start']."' AND bet_time < '".$arrRoundInfo['round_end']."' ";
            $strSql .= " AND bet_mode='".$iMode."' GROUP BY bet_mb_uid ";
            $strSql .= " ) tb_sum ";
            $objResult = $this -> db -> query($strSql)->getRow();

            //유저별 배팅결과 합
            $nSum = 0;
             $nSum = 0;
            if(!is_null($objResult->bet_money_allsum)) {
                $nSum = $objResult->bet_money_allsum;
            }
            //게임별 누르기율 계산
            // $nSum = $nSum * $objConfPs->game_percent_2 / 100;
            $arrSum[0] = (int)$nSum;
    
            $arrSumData[$i] = $arrSum;
        }


        return $arrSumData;  
    }

     function getBetSumByDay($arrReqInfo, $objConfPs){

        $arrSum = array();
        $strSql = " SELECT SUM(bet_money) AS bet_money_sum, SUM(bet_win_money) AS win_money_sum  FROM ".$this->table;
        $strSql .= " WHERE bet_time >= '".$arrReqInfo['start']."' AND bet_time <= '".$arrReqInfo['end']."' ";
        $strSql .= " AND bet_mode>='1' AND bet_mode<='7'  AND bet_state != 4 ";
        $objResult = $this -> db -> query($strSql)->getRow();

        $nSum = 0;
        if(!is_null($objResult->bet_money_sum)) {
            $nSum = $objResult->bet_money_sum;
        }
        $arrSum[0] = $nSum;
        $nSum = 0;
        if(!is_null($objResult->win_money_sum)) {
            $nSum = $objResult->win_money_sum;
        }
        $arrSum[1] = $nSum;
           
        //단폴 누르기율
        // $strSql = " SELECT SUM(bet_money_sum * mb_game_ps_percent DIV 100) AS bet_money_allsum FROM ( ";
        // $strSql .= " SELECT bet_mb_uid, bet_mode, bet_target, bet_ratio, SUM(bet_money) AS bet_money_sum, mb_game_ps_percent FROM ".$this->table;
        // $strSql .= " JOIN ".$this->mMemberTable." ON ".$this->mMemberTable.".mb_uid = ".$this->table.".bet_mb_uid ";
        // $strSql .= " WHERE bet_time >= '".$arrReqInfo['start']."' AND bet_time <= '".$arrReqInfo['end']."' ";
        // $strSql .= " AND bet_mode>='1' AND bet_mode<='7' AND bet_state != 4 GROUP BY bet_mb_uid ";
        // $strSql .= " ) tb_sum ";            
        // $objResult = $this -> db -> query($strSql)->getRow();
        
        // //유저별 배팅결과 합
        // $nSum = 0;
        // if(!is_null($objResult->bet_money_allsum)) {
        //     $nSum = $objResult->bet_money_allsum;
        // }
        // //게임별 누르기율 계산
        // $nSum = $nSum * $objConfPs->game_percent_1 / 100;
        // $arrSum[2] = $nSum;

        return $arrSum;
    }
    
    function getBetAccount($arrReqData){

        
        $strCondition = " WHERE bet_state != 4 ";
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $strCondition.=" AND ".getBetTimeRange($arrReqData);
                        
        }
        if(strlen($arrReqData['user']) > 0){
            $strCondition.=" AND bet_mb_uid = '".$arrReqData['user']."' ";            
        }
        if(strlen($arrReqData['round']) > 0){
            $strCondition.=" AND bet_round_no = '".$arrReqData['round']."' ";            
        }
        if((int)$arrReqData['mode'] > 0){
            $strCondition.=" AND bet_mode = '".$arrReqData['mode']."' ";

        }
        //총배팅금, 적중금
        $arrSum = array();
        $strSql = " SELECT SUM(bet_money) AS bet_money_sum, SUM(bet_win_money) AS win_money_sum  FROM ".$this->table;
        $strSql .= $strCondition;
        $objResult = $this -> db -> query($strSql)->getRow();

        $nSum = 0;
        if(!is_null($objResult->bet_money_sum)) {
            $nSum = $objResult->bet_money_sum;
        }
        $arrSum[0] = $nSum;
        $nSum = 0;
        if(!is_null($objResult->win_money_sum)) {
            $nSum = $objResult->win_money_sum;
        }
        $arrSum[1] = $nSum;
        //총미적중금
        $strSql = " SELECT SUM(bet_money) AS loss_money_sum  FROM ".$this->table;
        $strSql .= $strCondition;
        $strSql .= " AND bet_win_money = 0 ";
        
        $objResult = $this -> db -> query($strSql)->getRow();

        $nSum = 0;
        if(!is_null($objResult->loss_money_sum)) {
            $nSum = $objResult->loss_money_sum;
        }
        $arrSum[2] = $nSum;
        //총당첨금
        $strSql = " SELECT SUM(bet_win_money-bet_money) AS benefit_money_sum  FROM ".$this->table;
        $strSql .= $strCondition;
        $strSql .= " AND bet_win_money > 0 ";
        
        $objResult = $this -> db -> query($strSql)->getRow();

        $nSum = 0;
        if(!is_null($objResult->benefit_money_sum)) {
            $nSum = $objResult->benefit_money_sum;
        }
        $arrSum[3] = $nSum;
        
        return $arrSum;
    }

    function search($objEmp, $arrReqData)
    {
        
        if(is_null($objEmp))
            return [];
        $gameId = GAME_POWER_LADDER;

        $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid ";
        $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid ";

        $bWhere = false;
        $strWhere="";
        if (strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0) {
            $strWhere .= " WHERE ".getBetTimeRange($arrReqData);
            $bWhere = true;
        }
        if (strlen($arrReqData['user']) > 0) {
            if ($bWhere) {
                $strWhere .= ' AND ';
            } else {
                $strWhere .= ' WHERE ';
            }
            $strWhere .= " bet_mb_uid = '".$arrReqData['user']."' ";
            $bWhere = true;
        }
        if (strlen($arrReqData['round']) > 0) {
            if ($bWhere) {
                $strWhere .= ' AND ';
            } else {
                $strWhere .= ' WHERE ';
            }
            $strWhere .= " bet_round_no = '".$arrReqData['round']."' ";
            $bWhere = true;
        }
        if ((int) $arrReqData['mode'] > 0) {
            if ($bWhere) {
                $strWhere .= ' AND ';
            } else {
                $strWhere .= ' WHERE ';
            }

            if($arrReqData['mode'] <= 3)
                $strWhere.=" bet_mode = '".$arrReqData['mode']."' ";
            else 
                $strWhere.=" bet_mode >= 4 AND bet_mode <= 7 ";
        }
        $nStartRow = ($arrReqData['page'] - 1) * $arrReqData['count'];
        $strWhere .= ' ORDER BY bet_fid DESC LIMIT '.$nStartRow.', '.$arrReqData['count'];

        $strSql = "";
        $strSql .= ' SELECT bet_fid, bet_state, bet_emp_fid, bet_mb_uid, bet_round_fid, bet_round_no, bet_time, ';
        $strSql .= ' bet_mode, bet_target, bet_ratio, bet_money, bet_result, bet_win_money, rw_mb_uid, rw_point  ';
        $strSql .= " FROM ( ";

        $tbBetSearch = "bet_search";

        if($objEmp->mb_level < LEVEL_ADMIN){
            $strSql .= "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSql .= " ( SELECT ".$strTbColum." FROM ".$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
            $strSql .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mMemberTable." r ";
            $strSql .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";

            $strSql .= " SELECT * FROM ".$this->table;  
            $strSql .= '  JOIN (SELECT  * FROM tbmember UNION SELECT '.$strTbColum.' FROM '.$this->mMemberTable." where mb_fid='".$objEmp->mb_fid."'";
            $strSql .= ' ) AS mb_table ';
            $strSql .= ' ON '.$this->table.'.bet_mb_uid = mb_table.mb_uid ';
            $strSql .=$strWhere.") ".$tbBetSearch;

            //Join bet_reward
            $strSql .= '  LEFT JOIN '.$this->mRewardTable.' ON '.$tbBetSearch.'.bet_fid = '.$this->mRewardTable.'.rw_bet_id ';
                $strSql .= ' AND '.$this->mRewardTable.".rw_game = '".$gameId."' ";
                $strSql .= ' AND '.$this->mRewardTable.".rw_mb_uid = '".$objEmp->mb_uid."' ";
            
        } else {
            $strSql .= " SELECT * FROM ".$this->table;  
            $strSql .=$strWhere.") ".$tbBetSearch;

            //Join bet_reward
            $strSql .= '  LEFT JOIN '.$this->mRewardTable.' ON '.$tbBetSearch.'.bet_fid = '.$this->mRewardTable.'.rw_bet_id ';
                $strSql .= ' AND '.$this->mRewardTable.".rw_game = '".$gameId."' ";
                $strSql .= ' AND '.$this->mRewardTable.".rw_mb_uid = ".$tbBetSearch.".bet_mb_uid ";
            
        }
        $strSql .= " ORDER BY bet_fid  DESC";

        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result; 

    }


    function searchCount($objEmp, $arrReqData)
    {
        $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid ";
        $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid ";

         $strSql = "";
        if($objEmp->mb_level < LEVEL_ADMIN){

            $strSql = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSql .= " ( SELECT ".$strTbColum." FROM ".$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
            $strSql .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mMemberTable." r ";
            $strSql .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";

            $strSql .= "SELECT count(*) as count  FROM ".$this->table;
            
            $strSql .="  JOIN (SELECT  * FROM tbmember UNION SELECT ".$strTbColum." FROM ".$this->mMemberTable." where mb_fid='".$objEmp->mb_fid."'";      ;       
            $strSql .=" ) AS mb_table ";
            $strSql .=" ON ".$this->table.".bet_mb_uid = mb_table.mb_uid ";
        } else {
            $strSql .= "SELECT count(*) as count  FROM ".$this->table;
        }

        $bWhere = false;
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $strSql.=" WHERE ".getBetTimeRange($arrReqData);
            $bWhere = true;
        }
        if(strlen($arrReqData['user']) > 0){
            if($bWhere) $strSql.= " AND ";
            else $strSql.= " WHERE ";    
            $strSql.=" bet_mb_uid = '".$arrReqData['user']."' ";
            $bWhere = true;
        }
        if(strlen($arrReqData['round']) > 0){
            if($bWhere) $strSql.= " AND ";
            else $strSql.= " WHERE ";    
            $strSql.=" bet_round_fid = '".$arrReqData['round']."' ";
            $bWhere = true;
        }
        if((int)$arrReqData['mode'] > 0){
            if($bWhere) $strSql.= " AND ";
            else $strSql.= " WHERE ";    
            if($arrReqData['mode'] <= 3)
                $strSql.=" bet_mode = '".$arrReqData['mode']."' ";
            else 
                $strSql.=" bet_mode >= 4 AND bet_mode <= 7 ";
        }

        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 

    }

    //배팅 무효처리
    function ignore($objBet)
    {
        $this->builder()->set('bet_state', 4);
        $this->builder()->set('bet_win_money', 0);
        $this->builder()->set('bet_result', "");
        $this->builder()->set('user_after_money', 0);

        $this->builder()->where('bet_fid', $objBet->bet_fid);
        
        return $this->builder()->update();
    }



    function updateBetRound($objRoundInfo, $objBetInfo)
    {
        if(is_null($objRoundInfo)) return false;
        if(is_null($objBetInfo)) return false;
        if($objRoundInfo->round_state == 0) return false;

        $nWinMoney = 0;
        $isWin = false;
        //bet_state=2:Betting-loss 3:Betting-Earn 
        switch(intval($objBetInfo->bet_mode)){
            case 1:
                $objBetInfo->bet_result = $objRoundInfo->round_result_1;
                if($objBetInfo->bet_target == $objRoundInfo->round_result_1){
                    $isWin = true;                            
                }
                break;
            case 2:
                $objBetInfo->bet_result = $objRoundInfo->round_result_2;
                if($objBetInfo->bet_target == $objRoundInfo->round_result_2){
                    $isWin = true;
                }
                break;
            case 3:
                $objBetInfo->bet_result = $objRoundInfo->round_result_3;
                if($objBetInfo->bet_target == $objRoundInfo->round_result_3){
                    $isWin = true;
                }
                break;
            case 4:
                $objBetInfo->bet_result = $objRoundInfo->round_result_1.$objRoundInfo->round_result_2;
                if($objRoundInfo->round_result_1 == "P" && $objRoundInfo->round_result_2 == "P"){
                    $isWin = true;
                }
                break;
            case 5:
                $objBetInfo->bet_result = $objRoundInfo->round_result_1.$objRoundInfo->round_result_2;
                if($objRoundInfo->round_result_1 == "P" && $objRoundInfo->round_result_2 == "B"){
                    $isWin = true;
                }
                break;
            case 6:
                $objBetInfo->bet_result = $objRoundInfo->round_result_1.$objRoundInfo->round_result_2;
                if($objRoundInfo->round_result_1 == "B" && $objRoundInfo->round_result_2 == "P"){
                    $isWin = true;
                }
                break;
            case 7:
                $objBetInfo->bet_result = $objRoundInfo->round_result_1.$objRoundInfo->round_result_2;
                if($objRoundInfo->round_result_1 == "B" && $objRoundInfo->round_result_2 == "B"){
                    $isWin = true;
                }
                break;
            default:return false;
        } 

        if($isWin){                     //적중
            $objBetInfo->bet_state = 3;
            //bet_win_money
            $nWinMoney = $objBetInfo->bet_money * $objBetInfo->bet_ratio;
            $objBetInfo->bet_win_money = (int)$nWinMoney;
            //user_after_money
            $objBetInfo->user_after_money = ($objBetInfo->user_before_money + $objBetInfo->bet_win_money - $objBetInfo->bet_money);
        } else {                        //미적중
            $objBetInfo->bet_state = 2;
            $objBetInfo->bet_win_money = 0;
            //user_after_money
            $objBetInfo->user_after_money = ($objBetInfo->user_before_money - $objBetInfo->bet_money);
        }


        $this->builder()->set('bet_result', $objBetInfo->bet_result);
        $this->builder()->set('bet_state', $objBetInfo->bet_state); 
        $this->builder()->set('bet_win_money', $objBetInfo->bet_win_money);
        $this->builder()->set('user_after_money', $objBetInfo->user_after_money);

        $this->builder()->set('account_time', 'NOW()', false);
        
        $this->builder()->where('bet_fid', $objBetInfo->bet_fid);
        return $this->builder()->update();
        
    }

}