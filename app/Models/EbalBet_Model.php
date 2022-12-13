<?php
namespace App\Models;
use CodeIgniter\Model;

class EbalBet_Model extends Model 
{
    protected $table = 'bet_balance';
    protected $returnType = 'object'; 
    protected $allowedFields = [
        'bet_id', 
        'bet_state', 
        'bet_site_name', 
        'bet_site_uid', 
        'bet_evol_uid', 
        'bet_game_type', 
        'bet_table_id', 
        'bet_table_name', 
        'bet_round_id', 
        'bet_tm_req', 
        'bet_amount', 
        'bet_win_amount',
        'bet_choice',
        'bet_result',
        'bet_player',
        'bet_banker',
        'bet_type',

    ];
    protected $primaryKey = 'bet_id';

    
    function getBetAccount($reqData){
        
        $where = " WHERE bet_state > ".BET_STATE_REQ." AND bet_state < ".BET_STATE_DENY  ;
        if(strlen($reqData['start']) > 0 && strlen($reqData['end']) > 0 ){
            $where.=" AND ".getTimeRange('bet_tm_req', $reqData);
        }
        if(strlen($reqData['user']) > 0){
            $where.=" AND bet_site_uid = '".$reqData['user']."' ";
        }
        if(strlen(trim($reqData['room'])) > 0){
            $where.=" AND (bet_table_id = '".$reqData['room']."' OR bet_table_name LIKE '%".trim($reqData['room'])."%' ) ";
        }

        //총배팅금, 적중금
        $arrSum = array();
        $strSql = " SELECT SUM(CASE WHEN bet_type=0 THEN bet_amount ELSE 0 END ) AS bet_amount_sum, ";
        $strSql .= " SUM(CASE WHEN bet_type=0 THEN bet_win_amount ELSE 0 END ) AS win_amount_sum, ";
        $strSql .= " SUM(CASE WHEN bet_type=2 THEN bet_amount ELSE 0 END ) AS bet_con_sum, ";
        $strSql .= " SUM(CASE WHEN bet_type=2 THEN bet_win_amount ELSE 0 END ) AS win_con_sum, ";
        $strSql .= " SUM(CASE WHEN bet_type=2 THEN 0 ELSE bet_player + bet_banker END ) AS bet_user_sum, ";
        $strSql .= " SUM(CASE WHEN bet_type=0 THEN ABS(bet_player - bet_banker) ELSE 0 END ) AS bet_bal_sum, ";
        $strSql .= " SUM(CASE WHEN bet_type=0 AND bet_result = 'Banker' THEN ABS(bet_player - bet_banker) ELSE 0 END ) AS bet_bal_banker, ";
        // $strSql .= " SUM(CASE WHEN bet_type=0 AND bet_choice = 'Banker' AND bet_result = 'Banker' THEN FLOOR(bet_player DIV 1000)*50 ELSE 0 END) AS profit_sum1 ";

        $strSql .= " SUM(CASE WHEN bet_type= 0 AND bet_result = 'Player' THEN bet_banker - FLOOR(bet_player DIV 1000)*1000 - bet_amount + bet_win_amount ELSE 0 END) AS profit_sum1, ";
        $strSql .= " SUM(CASE WHEN bet_type= 0 AND bet_result = 'Banker' THEN bet_player - FLOOR(bet_banker DIV 1000)*950 - bet_amount + bet_win_amount ELSE 0 END) AS profit_sum2, ";
        $strSql .= " SUM(CASE WHEN bet_type= 1 AND bet_result = 'Player' THEN bet_banker % 1000 ELSE 0 END) AS profit_sum3, ";
        $strSql .= " SUM(CASE WHEN bet_type= 1 AND bet_result = 'Banker' THEN bet_player % 1000 + FLOOR(bet_player DIV 1000)*50 ELSE 0 END) AS profit_sum4 ";
        $strSql .= " FROM ".$this->table;
        $strSql .= $where;
        $objResult = $this -> db -> query($strSql)->getRow();

        // writeLog($strSql);

        $nSum = 0;
        if(!is_null($objResult->bet_amount_sum)) {
            $nSum = $objResult->bet_amount_sum;
        }
        $arrSum[0] = $nSum;
        $nSum = 0;
        if(!is_null($objResult->win_amount_sum)) {
            $nSum = $objResult->win_amount_sum;
        }
        $arrSum[1] = $nSum;
        //totabl profit
        $nSum = 0;
        if(!is_null($objResult->profit_sum1)) {
            $nSum += intval($objResult->profit_sum1);
        }
        if(!is_null($objResult->profit_sum2)) {
            $nSum += intval($objResult->profit_sum2);
        }
        if(!is_null($objResult->profit_sum3)) {
            $nSum += intval($objResult->profit_sum3);
        }
        if(!is_null($objResult->profit_sum4)) {
            $nSum += intval($objResult->profit_sum4);
        }
        if(!is_null($objResult->bet_con_sum)) {
            $nSum -= intval($objResult->bet_con_sum);
        }
        if(!is_null($objResult->win_con_sum)) {
            $nSum += intval($objResult->win_con_sum);
        }
        $arrSum[2] = $nSum;
        
        $nSum = 0;
        if(!is_null($objResult->bet_con_sum)) {
            $nSum = intval($objResult->bet_con_sum);
        }
        $arrSum[3] = $nSum;
        //Total user's Betting money
        $nSum = 0;
        if(!is_null($objResult->bet_user_sum)) {
            $nSum = intval($objResult->bet_user_sum);
        }
        $arrSum[4] = $nSum;
        //Total user's Betting balane money
        $nSum = 0;
        if(!is_null($objResult->bet_bal_sum)) {
            $nSum = intval($objResult->bet_bal_sum);
        }
        $arrSum[5] = $nSum;
        //if result is banker, Total user's Betting balane money
        $nSum = 0;
        if(!is_null($objResult->bet_bal_banker)) {
            $nSum = intval($objResult->bet_bal_banker);
        }
        $arrSum[6] = $nSum;
        return $arrSum;
    }

    public function searchCount($reqData){

        $where = " WHERE bet_state > ".BET_STATE_REQ." AND bet_state < ".BET_STATE_DENY  ;
        if(strlen($reqData['start']) > 0 && strlen($reqData['end']) > 0 ){
            $where.=" AND ".getTimeRange('bet_tm_req', $reqData);
        }
        if(strlen($reqData['bet']) > 0){
            if(intval($reqData['bet']) == 1)
                $where.=" AND bet_type <= '".BET_TYPE_ZERO."' ";
            else if(intval($reqData['bet']) == 2)
                $where.=" AND bet_type = '".BET_TYPE_FORCE."' ";
        }
        if(strlen($reqData['user']) > 0){
            $where.=" AND bet_site_uid = '".$reqData['user']."' ";
        }
        if(strlen(trim($reqData['room'])) > 0){
            $where.=" AND (bet_table_id = '".$reqData['room']."' OR bet_table_name LIKE '%".trim($reqData['room'])."%' ) ";
        }

        $strSql = "SELECT count('bet_id') as count FROM ".$this->table;
        $strSql .= $where;

        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 
    }

    public function searchList($reqData){
        
        $where = " WHERE bet_state > ".BET_STATE_REQ." AND bet_state < ".BET_STATE_DENY  ;
        if(strlen($reqData['start']) > 0 && strlen($reqData['end']) > 0 ){
            $where.=" AND ".getTimeRange('bet_tm_req', $reqData);
        }
        if(strlen($reqData['bet']) > 0){
            if(intval($reqData['bet']) == 1)
                $where.=" AND bet_type <= '".BET_TYPE_ZERO."' ";
            else if(intval($reqData['bet']) == 2)
                $where.=" AND bet_type = '".BET_TYPE_FORCE."' ";
        }
        if(strlen($reqData['user']) > 0){
            $where.=" AND  bet_site_uid = '".$reqData['user']."' ";
        }
        if(strlen(trim($reqData['room'])) > 0){
            $where.=" AND (bet_table_id = '".$reqData['room']."' OR bet_table_name LIKE '%".trim($reqData['room'])."%' ) ";
        }

        $strTbColum = " ".implode(", ", $this->allowedFields);

        $strSql = " SELECT ".$strTbColum." FROM ".$this->table;
        $strSql .= $where;

        $page = $reqData['page'];
        $count = $reqData['count'];
        if($page < 1)
            return NULL;
        if($count < 1)
            return NULL;
        
        $nStartRow = ($page-1) * $count ;

        $strSql.=" ORDER BY bet_id DESC LIMIT ".$nStartRow.", ".$count;
        // writeLog($strSql);
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        return $result;
    }
}