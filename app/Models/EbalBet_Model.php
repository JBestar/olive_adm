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
        
    ];
    protected $primaryKey = 'bet_id';

    public function searchCount($reqData){

        $bWhere = false;
        $where = "";
        if(strlen($reqData['start']) > 0 && strlen($reqData['end']) > 0 ){
            $where.=" WHERE ".getTimeRange('bet_tm_req', $reqData);
            $bWhere = true;
        }
        if(strlen($reqData['user']) > 0){
            if($bWhere) $where.= " AND ";
            else $where.= " WHERE ";    
            $where.=" bet_site_uid = '".$reqData['user']."' ";
            $bWhere = true;
        }

        $strSql = "SELECT count('bet_id') as count FROM ".$this->table;
        $strSql .= $where;

        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 
    }

    public function searchList($reqData){
        
        $bWhere = false;
        $where = "";
        if(strlen($reqData['start']) > 0 && strlen($reqData['end']) > 0 ){
            $where.=" WHERE ".getTimeRange('bet_tm_req', $reqData);
            $bWhere = true;
        }
        if(strlen($reqData['user']) > 0){
            if($bWhere) $where.= " AND ";
            else $where.= " WHERE ";    
            $where.=" bet_site_uid = '".$reqData['user']."' ";
            $bWhere = true;
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
        writeLog($strSql);
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        return $result;
    }
}