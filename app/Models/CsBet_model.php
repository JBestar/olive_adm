<?php
namespace App\Models;
use CodeIgniter\Model;

class CsBet_Model extends Model 
{
    protected $table = 'bet_casino';
    protected $returnType = 'object'; 
    protected $allowedFields = [
        'bet_idx', 
        'bet_emp_fid', 
        'bet_mb_uid', 
        'bet_round_no', 
        'bet_time', 
        'bet_money', 
        'bet_win_money',
        'bet_agent_id', 
        'bet_player_id', 
        'bet_game_id' ,
        'bet_game_type', 
        'bet_table_code', 
        'bet_choice', 
        'point_amount', 
        'employee_amount', 
        'agency_amount', 
        'company_amount', 
    ];
    protected $primaryKey = 'bet_fid';
    private $mMemberTable = 'member';
    private $mGameTable = 'casino_game';
    private $mPrdTable = 'casino_prd';
    private $mRewardTable = 'bet_reward';

    function getBetAccount($arrReqData){

        
        $strCondition = " WHERE bet_money != bet_win_money ";
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $strCondition.=" AND ".getBetTimeRange($arrReqData);
        }
        if(strlen($arrReqData['user']) > 0){
            $strCondition.=" AND bet_mb_uid = '".$arrReqData['user']."' ";            
        }
        if(intval($arrReqData['mode']) >= 0){
            $strCondition.=" AND bet_game_id = '".$arrReqData['mode']."' ";
        }

        //총배팅금, 적중금
        $arrSum = array();
        $strSql = " SELECT SUM(bet_money) AS bet_money_sum, SUM(bet_win_money) AS win_money_sum, ";
        $strSql .= " SUM(CASE WHEN bet_win_money= 0 THEN bet_money ELSE 0 END) AS loss_money_sum, ";
        $strSql .= " SUM(CASE WHEN bet_win_money > 0 THEN bet_win_money-bet_money ELSE 0 END) AS benefit_money_sum ";
        $strSql .= " FROM ".$this->table;
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
        $nSum = 0;
        if(!is_null($objResult->loss_money_sum)) {
            $nSum = $objResult->loss_money_sum;
        }
        $arrSum[2] = $nSum;
        //총당첨금
        $nSum = 0;
        if(!is_null($objResult->benefit_money_sum)) {
            $nSum = $objResult->benefit_money_sum;
        }
        $arrSum[3] = $nSum;
        
        return $arrSum;
    }


    function search($objEmp, $arrReqData)
    {
        $gameId = GAME_CASINO_EVOL;

        $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid, mb_nickname, mb_live_id ";
        $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid, r.mb_nickname, r.mb_live_id ";

        $bWhere = false;
        $strWhere="";
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $strWhere.=" WHERE ".getBetTimeRange($arrReqData);
            $bWhere = true;            
        }
        if(strlen($arrReqData['user']) > 0){
            if($bWhere) $strWhere.= " AND ";
            else $strWhere.= " WHERE ";
            $strWhere.=" bet_mb_uid = '".$arrReqData['user']."' ";
            $bWhere = true;
        }
        if(intval($arrReqData['mode']) >= 0){
            if($bWhere) $strWhere.= " AND ";
            else $strWhere.= " WHERE ";
            $strWhere.=" bet_game_id = '".$arrReqData['mode']."' ";
        }
        if($objEmp->mb_level < LEVEL_ADMIN){
            $strWhere.=" AND bet_mb_uid in ( SELECT mb_uid FROM  tbmember UNION ALL SELECT '".$objEmp->mb_uid."' AS mb_uid ) ";
        }
        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
        $strWhere.=" ORDER BY bet_time DESC LIMIT ".$nStartRow.", ".$arrReqData['count'];
        
        $strSql = "";
        $strSql .= "SELECT bet_fid, bet_idx, bet_mb_uid, bet_round_no, bet_time, bet_money, bet_win_money, bet_player_id, bet_game_id, bet_game_type, bet_table_code, ";
        $strSql .= " bet_choice, ".$this->mGameTable.".name as game_name, rw_mb_uid, rw_point, ".$this->mPrdTable.".name as prd_name";
        $strSql .= " FROM ( ";

        $tbBetSearch = "bet_search";

        if($objEmp->mb_level < LEVEL_ADMIN){

            $strSql .= " WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSql .= " ( SELECT ".$strTbColum." FROM ".$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
            $strSql .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mMemberTable." r ";
            $strSql .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";

            $strSql .= " SELECT * FROM ".$this->table;  
            // $strSql .="  JOIN (SELECT  * FROM tbmember UNION SELECT ".$strTbColum." FROM ".$this->mMemberTable." where mb_fid='".$objEmp->mb_fid."'";           
            // $strSql .=" ) AS mb_table ";
            // $strSql .=" ON ".$this->table.".bet_mb_uid = mb_table.mb_uid ";
            $strSql .=$strWhere.") ".$tbBetSearch;

            //Join bet_reward
            $strSql .= '  LEFT JOIN '.$this->mRewardTable.' ON '.$this->mRewardTable.".rw_game = '".$gameId."' ";
                $strSql .= ' AND '.$tbBetSearch.'.bet_fid = '.$this->mRewardTable.'.rw_bet_id ';
                $strSql .= ' AND '.$this->mRewardTable.".rw_mb_uid = '".$objEmp->mb_uid."' ";
            
        } else{
            
            $strSql .= " SELECT * FROM ".$this->table;  
        	// $strSql .= " JOIN ".$this->mMemberTable." ON ".$this->table.".bet_mb_uid = ".$this->mMemberTable.".mb_uid ";
            $strSql .=$strWhere.") ".$tbBetSearch;

            //Join bet_reward
            $strSql .= '  LEFT JOIN '.$this->mRewardTable.' ON '.$this->mRewardTable.".rw_game = '".$gameId."' ";
                $strSql .= ' AND '.$tbBetSearch.'.bet_fid = '.$this->mRewardTable.'.rw_bet_id ';
                $strSql .= ' AND '.$this->mRewardTable.".rw_mb_uid = ".$tbBetSearch.".bet_mb_uid ";
            
        }
        $strSql .= " LEFT JOIN ".$this->mPrdTable." ON ".$tbBetSearch.".bet_game_id = ".$this->mPrdTable.".vendor_id ";

        $strSql .= " LEFT JOIN ".$this->mGameTable." ON ".$tbBetSearch.".bet_table_code = ".$this->mGameTable.".tid ";
        $strSql .= " ORDER BY bet_time DESC";

        // writeLog($strSql);
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        // writeLog("searchCount End");
        
        return $result; 

    }


    function searchCount($objEmp, $arrReqData)
    {
        $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid, mb_live_id ";
        $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid, r.mb_live_id ";

         $strSql = "";
        if($objEmp->mb_level < LEVEL_ADMIN){


            $strSql = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSql .= " ( SELECT ".$strTbColum." FROM ".$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
            $strSql .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mMemberTable." r ";
            $strSql .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";

            $strSql .= "SELECT count(bet_fid) as count  FROM ".$this->table;

            // $strSql .="  JOIN (SELECT  * FROM tbmember UNION SELECT ".$strTbColum." FROM ".$this->mMemberTable." where mb_fid='".$objEmp->mb_fid."'";         
            // $strSql .=" ) AS mb_table ";
            // $strSql .=" ON ".$this->table.".bet_mb_uid = mb_table.mb_uid ";
        } else {
            $strSql .= "SELECT count(bet_fid) as count  FROM ".$this->table;
            // $strSql .= " JOIN member ON ".$this->table.".bet_mb_uid = ".$this->mMemberTable.".mb_uid ";
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
        if(intval($arrReqData['mode']) >= 0){
            if($bWhere) $strSql.= " AND ";
            else $strSql.= " WHERE ";    
            $strSql.=" bet_game_id = '".$arrReqData['mode']."' ";
            $bWhere = true;
        }
        if($objEmp->mb_level < LEVEL_ADMIN){
            if($bWhere) $strSql.= " AND ";
            else $strSql.= " WHERE ";    
            $strSql.=" bet_mb_uid in ( SELECT mb_uid FROM  tbmember UNION ALL SELECT '".$objEmp->mb_uid."' AS mb_uid ) ";
        }

        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 

    }

    function getBetSumByDay($arrReqInfo, $objConf){

        $arrSum = array();
        $strSql = " SELECT SUM(bet_money) AS bet_money_sum, SUM(bet_win_money) AS win_money_sum  FROM ".$this->table;
        $strSql .= " WHERE bet_time >= '".$arrReqInfo['start']."' AND bet_time <= '".$arrReqInfo['end']."' ";
        $strSql .= " AND bet_money != bet_win_money ";
        if($objConf->game_index == GAME_CASINO_EVOL)
            $strSql .= " AND bet_game_id = 0 ";
        else 
            $strSql .= " AND bet_game_id != 0 ";

        $strSql .= " AND bet_mb_uid NOT IN (SELECT mb_uid FROM ".$this->mMemberTable." WHERE mb_level >= ".LEVEL_ADMIN.") ";
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
           
        return $arrSum;
    }


}
