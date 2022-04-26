<?php 
namespace App\Models;

use CodeIgniter\Model;

class SlotGame_Model extends Model {

    protected $table      = 'slot_game';
    protected $primaryKey = 'fid';

    protected $returnType = 'object'; 
    protected $allowedFields = [
        'act',
        'hidden',
        'maintain',
    ];
    protected $prdTable      = 'slot_prd';

    public function getById($cat, $prd, $uuid){
        $where = [
            'cat' => $cat,
            'prd_code' => $prd,
            'uuid' => $uuid,
        ];
        return $this->where($where)->first();
    }

    public function gets($cat, $prd){
        $where = [
            'cat' => $cat,
            'prd_code' => $prd,
            'open' => PERMIT_OK
        ];
        
        return $this->where($where)
                    ->orderBy('fid', 'ASC')
                    ->findAll(); 
    }

    
    public function changeAct($arrReqData)
    {
        $data = [];

        if(array_key_exists('act', $arrReqData)){
            $data['act'] =  $arrReqData['act'];
            return $this->update($arrReqData['fid'], $data);
        } else if(array_key_exists('hidden', $arrReqData)){
            $data['hidden'] =  $arrReqData['hidden'];
            
            return $this->set($data)
             ->where('name', $arrReqData['name'])
             ->update();
        } else if(array_key_exists('maintain', $arrReqData)){
            $data['maintain'] =  $arrReqData['maintain'];
            
            return $this->set($data)
             ->where('name', $arrReqData['name'])
             ->update();
        } else return false;
    
    }

    public function search($arrReqData){
        $where = ""; 
        if(strlen($arrReqData['name']) > 0){
            $where = " AND ( name LIKE '%".$arrReqData['name']."%' OR name_ko LIKE '%".$arrReqData['name']."%' )";
        }

        // $strSql = " SELECT fslot_game.*, ".$this->prdTable.".name as prd_name FROM ";
        $strSql = " SELECT fslot_game.*, rslot_game.name as rname, rslot_game.name_ko AS rname_ko, rslot_game.fid AS rfid, ";
        $strSql.= " rslot_game.hidden AS rhidden, rslot_game.maintain AS rmaintain "; 
            $strSql.= " FROM ( SELECT * FROM ".$this->table;
            $strSql.= " WHERE cat = '".GAME_SLOT_2."' AND open = '1' ".$where;
            $strSql.= " ) AS fslot_game";
        $strSql.= " JOIN (SELECT * FROM ".$this->table;
            $strSql.= " WHERE cat = '".GAME_SLOT_1."' AND open = '1' ) AS rslot_game ";
            $strSql.= " ON fslot_game.name = rslot_game.name ";
        // $strSql.= " LEFT JOIN ".$this->prdTable." ON ".$this->prdTable.".code = fslot_game.prd_code ";  
        
        $strSql.= " group by fslot_game.fid ";
        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
        $strSql.=" ORDER BY rname ASC LIMIT ".$nStartRow.", ".$arrReqData['count'];
        
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result; 

    }

    public function searchCount($arrReqData){
        
        $where = ""; 
        if(strlen($arrReqData['name']) > 0){
            $where = " AND ( name LIKE '%".$arrReqData['name']."%' OR name_ko LIKE '%".$arrReqData['name']."%' )";
        }

        $strSql = "SELECT count(*) as count FROM ";
            $strSql.= "( SELECT fslot_game.fid  FROM ";
                $strSql.= "( SELECT * FROM ".$this->table;
                $strSql.= " WHERE cat = '".GAME_SLOT_2."' AND open = '1' ".$where;
                $strSql.= " ) AS fslot_game";
            $strSql.= " JOIN (SELECT * FROM ".$this->table;
                $strSql.= " WHERE cat = '".GAME_SLOT_1."' AND open = '1' ) AS rslot_game ";
                $strSql.= " ON fslot_game.name = rslot_game.name group by fslot_game.fid )";
        $strSql.= " AS tb_result";

        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 
    }



}