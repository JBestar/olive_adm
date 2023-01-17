<?php
namespace App\Models;
use CodeIgniter\Model;

class EbalBet_Model extends Model 
{
    protected $table = 'bet_ebal';
    protected $returnType = 'object'; 
    protected $allowedFields = [
        'bet_idx', 
        'bet_emp_fid', 
        'bet_mb_fid', 
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
        'bet_table_name', 
        'bet_choice', 
        'bet_result', 
        'point_amount', 
        'employee_amount', 
        'company_amount', 
        'org_id', 
    ];
    protected $primaryKey = 'bet_fid';
    private $mMemberTable = 'member';
    private $mGameTable = 'casino_game';
    private $mPrdTable = 'casino_prd';
    private $mRewardTable = 'bet_reward';

    function getBetAccount($arrReqData){
        
        if(array_key_exists("state", $arrReqData) && $arrReqData['state'] > 0)
            return null;

        $strCondition = " WHERE ";
        // $strCondition = " WHERE bet_result != 'Tie' ";
        // if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            // $strCondition.=" AND ".getBetTimeRange($arrReqData);
        $strCondition .= " bet_fid >= ".$arrReqData['gm_range'][0]." AND bet_fid <= ".$arrReqData['gm_range'][1];
        // }
        if(strlen($arrReqData['user']) > 0){
            $strCondition.=" AND bet_mb_fid = '".$arrReqData['user']."' ";            
        }
        $strCondition .= " AND point_amount != ".BET_STATE_TIE." AND employee_amount = 0 AND company_amount = 0 ";
        
        if(array_key_exists('room', $arrReqData) && strlen($arrReqData['room']) > 0) {
            $strCondition.=" AND bet_table_name = '".$arrReqData['room']."' ";
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
        
        // writeLog($strSql);
        
        return $arrSum;
    }


    function search($objEmp, $arrReqData)
    {
        $gameId = GAME_CASINO_EVOL;

        $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid, mb_nickname, mb_live_id ";
        $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid, r.mb_nickname, r.mb_live_id ";

        $strWhere=" WHERE ";
        $strWhere .= " bet_fid >= ".$arrReqData['gm_range'][0]." AND bet_fid <= ".$arrReqData['gm_range'][1];

        if(strlen($arrReqData['user']) > 0){
            $strWhere.=" AND bet_mb_fid = '".$arrReqData['user']."' ";
        }
        $strWhere.=" AND employee_amount = 0 ";  
        if(array_key_exists("state", $arrReqData) && $arrReqData['state'] > 0){
            $strWhere.=" AND company_amount <> 0 ";
        } else {
            $strWhere.=" AND company_amount = 0 ";
        }
        if(array_key_exists('room', $arrReqData) && strlen($arrReqData['room']) > 0)
            $strWhere.=" AND bet_table_name = '".$arrReqData['room']."' ";
        

        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
        $strWhere.=" ORDER BY bet_time DESC LIMIT ".$nStartRow.", ".$arrReqData['count'];
        
        $strSql = "";
        $strSql .= "SELECT bet_fid, bet_idx, bet_mb_uid, bet_round_no, bet_time, bet_money, bet_win_money, bet_player_id, bet_game_id, bet_game_type, bet_table_code, ";
        $strSql .= " bet_choice, bet_result, point_amount, company_amount, obj_id, ".$this->mGameTable.".name as game_name, rw_mb_uid, rw_point, ".$this->mPrdTable.".name as prd_name";
        $strSql .= " FROM ( ";

        $tbBetSearch = "bet_search";

        if($objEmp->mb_level < LEVEL_ADMIN){

            $strSql .= " WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSql .= " ( SELECT ".$strTbColum." FROM ".$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
            $strSql .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mMemberTable." r ";
            $strSql .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";

            $strSql .= " SELECT * FROM ".$this->table;  
            $strSql .=$strWhere.") ".$tbBetSearch;

            //Join bet_reward
            $strSql .= '  LEFT JOIN '.$this->mRewardTable.' ON '.$this->mRewardTable.".rw_game = '".$gameId."' ";
                $strSql .= ' AND '.$tbBetSearch.'.bet_fid = '.$this->mRewardTable.'.rw_bet_id ';
                $strSql .= ' AND '.$this->mRewardTable.".rw_mb_uid = '".$objEmp->mb_uid."' ";
            
        } else{
            
            $strSql .= " SELECT * FROM ".$this->table;  
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
        // writeLog("search End");
        
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

        } else {
            $strSql .= "SELECT count(bet_fid) as count  FROM ".$this->table;
        }
        
        $bWhere = true;
        $strSql .= " WHERE ";
        $strSql .= " bet_fid >= ".$arrReqData['gm_range'][0]." AND bet_fid <= ".$arrReqData['gm_range'][1];

        if(strlen($arrReqData['user']) > 0){
            $strSql.=" AND bet_mb_fid = '".$arrReqData['user']."' ";
        }
        
        $strSql.=" AND employee_amount = 0 ";
        if(array_key_exists("state", $arrReqData) && $arrReqData['state'] > 0){
            $strSql.=" AND company_amount > 0 ";
        } else {
            $strSql.=" AND company_amount = 0 ";
        }
        
        if(array_key_exists('room', $arrReqData) && strlen($arrReqData['room']) > 0) {
            $strSql.=" AND bet_table_name = '".$arrReqData['room']."' ";
        }
        // writeLog($strSql);
        $query = $this -> db -> query($strSql);
        // writeLog("searchCount End");

        $result = $query -> getRow();
        
        return $result; 

    }

    function getBetSumByDay($arrReqInfo, $objConf=null){

        $arrSum = array();

        if($arrReqInfo['gm_range'][0] >= 0){
            $strSql = " SELECT SUM(bet_money) AS bet_money_sum, SUM(bet_win_money) AS win_money_sum  FROM ".$this->table;
            $strSql .= " WHERE bet_fid >= ".$arrReqInfo['gm_range'][0]; //." AND bet_fid <= ".$arrReqInfo['gm_range'][1]
            // $strSql .= " WHERE bet_time >= '".$arrReqInfo['start']."' AND bet_time <= '".$arrReqInfo['end']."' ";
            $strSql .= " AND point_amount <> ".BET_STATE_TIE." AND company_amount = 0 ";  //exclude tie and success bet 
            $strSql .= " AND employee_amount = 0 ";  //member level < admin level;
    
            // writeLog($strSql);
            $objResult = $this -> db -> query($strSql)->getRow();
            // writeLog("BetSumByDay End");
        } else {
            $objResult->bet_money_sum = 0;
            $objResult->win_money_sum = 0;
        }
        
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

    public function updateBet($objbet)
    {

        $this->builder()->set('bet_win_money', $objbet->bet_win_money);
        $this->builder()->set('point_amount', $objbet->point_amount);
        $this->builder()->set('acc_time', 'NOW()', false);
        
        $this->builder()->where('bet_fid', $objbet->bet_fid);
        return $this->builder()->update();        
    }

}
