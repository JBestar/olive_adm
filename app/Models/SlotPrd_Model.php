<?php 
namespace App\Models;

use CodeIgniter\Model;

class SlotPrd_Model extends Model {

    protected $table      = 'slot_prd';
    protected $primaryKey = 'id';

    protected $returnType = 'object'; 
    protected $allowedFields = ['hidden'];

    public function gets($cat){
        return $this->where('cat', $cat)
                    ->orderBy('id', 'ASC')
                    ->findAll(); 
    }

    public function changeAct($arrReqData)
    {
        $data = [];

        if(array_key_exists('hidden', $arrReqData)){
            $data['hidden'] =  $arrReqData['hidden'];
            
            return $this->set($data)
             ->where('name', $arrReqData['name'])
             ->update();
        } else return false;
    
    }

    public function search($arrReqData){
        
        $strSql = " SELECT * FROM ".$this->table;
        $strSql.= " WHERE cat = ".GAME_SLOT_1;
        if(strlen($arrReqData['name']) > 0){
            $strSql.= " AND (name LIKE '%".$arrReqData['name']."%' OR name_kr LIKE '%".$arrReqData['name']."%' ) ";
        }

        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
        $strSql.=" ORDER BY id ASC LIMIT ".$nStartRow.", ".$arrReqData['count'];
        
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result; 

    }

    public function searchCount($arrReqData){
        
        $strSql = "SELECT count(*) as count FROM ".$this->table;
        $strSql.= " WHERE cat = ".GAME_SLOT_1;
        if(strlen($arrReqData['name']) > 0){
            $strSql.= " AND (name LIKE '%".$arrReqData['name']."%' OR name_kr LIKE '%".$arrReqData['name']."%' ) ";
        }

        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 
    }

}