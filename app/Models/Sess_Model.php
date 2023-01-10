<?php 
namespace App\Models;

use CodeIgniter\Model;

class Sess_Model extends Model {

    protected $table      = 'sess';
    protected $primaryKey = 'sess_fid';
    protected $returnType = 'object'; 

    protected $allowedFields = ['sess_id', 'sess_mb_fid', 'sess_mb_uid', 'sess_ip', 'sess_join', 'sess_update']; 

    public function add($member, $sessId){
        $this->deleteBySess($sessId);
        
        $data = [
            'sess_id' => $sessId,
            'sess_mb_fid' => $member->mb_fid,
            'sess_mb_uid' => $member->mb_uid,
            'sess_ip' => $member->mb_ip_last,
            'sess_join' => date("Y-m-d H:i:s"),
            'sess_update' => date("Y-m-d H:i:s"),
        ];
        
        return $this->insert($data);
    }
    
    public function getBySess($sessId){
        
        return $this->where('sess_id', $sessId)
                    ->first();
    }

    
    public function getByUid($uid){
        
        return $this->where('sess_mb_uid', $uid)
                    ->first();

    }

    public function updateLast($sessId){
        
        $data = [
            'sess_update' => date("Y-m-d H:i:s"),
        ];
        
        return $this->set($data)
                    ->where('sess_id', $sessId)
                    ->update();
    }

    public function deleteBySess($sess){
        
        $data = [
            'sess_id' => $sess,
        ];
        
        return $this->where($data)
                    ->delete();

    }

    public function deleteByMember($mb_fid){
        
        $data = [
            'sess_mb_fid' => $mb_fid,
        ];
        
        return $this->where($data)
                    ->delete();

    }

    public function deleteLast(){
        $tmLast = strtotime("-5 minutes", time());

        return $this->where('sess_update <', date("Y-m-d H:i:s", $tmLast))
                    ->delete();
    }

    
    function search($arrReqData, $mbLv=LEVEL_ADMIN)
    {
        $strSql = "SELECT ".$this->table.".*, member.mb_nickname, member.mb_level, ";
        $strSql .= " (member.mb_money+member.mb_live_money+member.mb_slot_money+member.mb_fslot_money+member.mb_kgon_money+member.mb_gslot_money ) AS mb_money, member.mb_point FROM ".$this->table;
        $strSql .= " JOIN member ON ".$this->table.".sess_mb_fid = member.mb_fid ";
        $strSql .= " WHERE mb_level < '".$mbLv."' ";
        
        if(strlen($arrReqData['mb_uid']) > 0){
            $strSql.=" AND sess_mb_uid = '".$arrReqData['mb_uid']."' ";
        }
        if(intval($arrReqData['type']) >= 0){
            $strSql.=" AND sess_type = '".$arrReqData['type']."' ";
        }
        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;

        $strSql.=" ORDER BY sess_fid ASC LIMIT ".$nStartRow.", ".$arrReqData['count'];

        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result; 

    }


    function searchCount($arrReqData, $mbLv=LEVEL_ADMIN)
    {
        $strSql = "SELECT count(*) as count FROM ".$this->table;
        $strSql .= " JOIN member ON ".$this->table.".sess_mb_fid = member.mb_fid ";
        $strSql .= " WHERE mb_level < '".$mbLv."' ";
        if(strlen($arrReqData['mb_uid']) > 0){
            $strSql.=" AND sess_mb_uid = '".$arrReqData['mb_uid']."' ";
        }
        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 

    }

}