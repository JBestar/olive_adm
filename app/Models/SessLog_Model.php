<?php 
namespace App\Models;

use CodeIgniter\Model;

class SessLog_Model extends Model {

    protected $table      = 'sess_log';
    protected $primaryKey = 'log_fid';

    protected $returnType = 'object'; 

    protected $allowedFields = ['log_mb_uid', 'log_ip', 'log_time']; 

    public function add($userData){
        
        $data = [
            'log_mb_uid' => $userData['mb_uid'],
            'log_ip' => $userData['mb_ip_last'],
            'log_time' => date("Y-m-d H:i:s"),
        ];
        
        return $this->insert($data);
    }



    function search($arrReqData)
    {
        $tbBlock = "block_list";

        $strSql = "SELECT ".$this->table.".*, member.mb_nickname, block_ip, block_state FROM ".$this->table;
        $strSql .= " JOIN member ON ".$this->table.".log_mb_uid = member.mb_uid ";
        $strSql .= ' LEFT JOIN '.$tbBlock.' ON '.$this->table.'.log_ip = '.$tbBlock.'.block_ip ';
        $strSql .= " WHERE log_delete = '0' ";
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $strSql.=" AND log_time >= '".$arrReqData['start']." 0:0:0' AND log_time <= '".$arrReqData['end']." 23:59:59'" ; 
        }
        if(strlen($arrReqData['mb_uid']) > 0){
            $strSql.=" AND log_mb_uid = '".$arrReqData['mb_uid']."' ";
        }
        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;

        $strSql.=" ORDER BY log_fid DESC LIMIT ".$nStartRow.", ".$arrReqData['count'];

        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result; 

    }


    function searchCount($arrReqData)
    {
        $strSql = "SELECT count(*) as count FROM ".$this->table;
        $strSql .= " JOIN member ON ".$this->table.".log_mb_uid = member.mb_uid ";
        $strSql .= " WHERE log_delete = '0' ";
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $strSql.=" AND log_time >= '".$arrReqData['start']." 0:0:0' AND log_time <= '".$arrReqData['end']." 23:59:59'" ; 
        }
        if(strlen($arrReqData['mb_uid']) > 0){
            $strSql.=" AND log_mb_uid = '".$arrReqData['mb_uid']."' ";
        }
        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 

    }


}