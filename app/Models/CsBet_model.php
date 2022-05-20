<?php
namespace App\Models;
use CodeIgniter\Model;

class CsBet_model extends Model 
{
    protected $table = 'bet_casino';
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

        
        $strCondition = " WHERE bet_money > 0 ";
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
        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
        $strWhere.=" ORDER BY bet_fid DESC LIMIT ".$nStartRow.", ".$arrReqData['count'];
        
        $strSql = "";
        $strSql .= "SELECT bet_fid, bet_idx, bet_mb_uid, bet_round_no, bet_time, bet_money, bet_win_money, bet_player_id, bet_game_id, bet_game_type, bet_table_code, ";
        $strSql .= " bet_choice, mb_uid, mb_nickname, ".$this->mGameTable.".name as game_name, rw_mb_uid, rw_point, ".$this->mPrdTable.".name as prd_name";
        $strSql .= " FROM ( ";

        $tbBetSearch = "bet_search";

        if($objEmp->mb_level < LEVEL_ADMIN){

            $strSql .= " WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSql .= " ( SELECT ".$strTbColum." FROM ".$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
            $strSql .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mMemberTable." r ";
            $strSql .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";

            $strSql .= " SELECT * FROM ".$this->table;  
            $strSql .="  JOIN (SELECT  * FROM tbmember UNION SELECT ".$strTbColum." FROM ".$this->mMemberTable." where mb_fid='".$objEmp->mb_fid."'";           
            $strSql .=" ) AS mb_table ";
            $strSql .=" ON ".$this->table.".bet_mb_uid = mb_table.mb_uid ";
            $strSql .=$strWhere.") ".$tbBetSearch;

            //Join bet_reward
            $strSql .= '  LEFT JOIN '.$this->mRewardTable.' ON '.$tbBetSearch.'.bet_fid = '.$this->mRewardTable.'.rw_bet_id ';
                $strSql .= ' AND '.$this->mRewardTable.".rw_game = '".$gameId."' ";
                $strSql .= ' AND '.$this->mRewardTable.".rw_mb_uid = '".$objEmp->mb_uid."' ";
            
        } else{
            
            $strSql .= " SELECT * FROM ".$this->table;  
        	$strSql .= " JOIN ".$this->mMemberTable." ON ".$this->table.".bet_mb_uid = ".$this->mMemberTable.".mb_uid ";
            $strSql .=$strWhere.") ".$tbBetSearch;

            //Join bet_reward
            $strSql .= '  LEFT JOIN '.$this->mRewardTable.' ON '.$tbBetSearch.'.bet_fid = '.$this->mRewardTable.'.rw_bet_id ';
                $strSql .= ' AND '.$this->mRewardTable.".rw_game = '".$gameId."' ";
                $strSql .= ' AND '.$this->mRewardTable.".rw_mb_uid = ".$tbBetSearch.".bet_mb_uid ";
            
        }
        $strSql .= " LEFT JOIN ".$this->mPrdTable." ON ".$tbBetSearch.".bet_game_id = ".$this->mPrdTable.".vendor_id ";

        $strSql .= " LEFT JOIN ".$this->mGameTable." ON ".$tbBetSearch.".bet_table_code = ".$this->mGameTable.".tid ";
        $strSql .= " ORDER BY bet_fid DESC";

        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
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

            $strSql .= "SELECT count(*) as count  FROM ".$this->table;

            $strSql .="  JOIN (SELECT  * FROM tbmember UNION SELECT ".$strTbColum." FROM ".$this->mMemberTable." where mb_fid='".$objEmp->mb_fid."'";         
            $strSql .=" ) AS mb_table ";
            $strSql .=" ON ".$this->table.".bet_player_id = mb_table.mb_live_id ";
        } else {
            $strSql .= "SELECT count(*) as count  FROM ".$this->table;
            $strSql .= " JOIN member ON ".$this->table.".bet_player_id = ".$this->mMemberTable.".mb_live_id ";
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
        if(intval($arrReqData['mode']) > 0){
            if($bWhere) $strSql.= " AND ";
            else $strSql.= " WHERE ";    
            $strSql.=" bet_game_type = '".$arrReqData['mode']."' ";
            $bWhere = true;
        }

        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 

    }



}
