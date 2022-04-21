<?php 
namespace App\Models;

use CodeIgniter\Model;

class SlotGame_Model extends Model {

    protected $table      = 'slot_game';
    protected $primaryKey = 'fid';

    protected $returnType = 'object'; 
    protected $allowedFields = [
        'act'
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
        
        if (!array_key_exists('act', $arrReqData) || !array_key_exists('fid', $arrReqData)) {
            return false;
        }

        return $this->builder()->set('act', $arrReqData['act'])
            ->where('fid', $arrReqData['fid'])
            ->update();
    }

    public function search($arrReqData){
        $where = ""; 
        if(strlen($arrReqData['name']) > 0){
            $where = " AND ( name LIKE '%".$arrReqData['name']."%' OR name_ko LIKE '%".$arrReqData['name']."%' )";
        }

        // $strSql = " SELECT fslot_game.*, ".$this->prdTable.".name as prd_name FROM ";
        $strSql = " SELECT fslot_game.* FROM ";
            $strSql.= "( SELECT * FROM ".$this->table;
            $strSql.= " WHERE cat = '".GAME_SLOT_2."' AND open = '1' ".$where;
            $strSql.= " ) AS fslot_game";
        $strSql.= " JOIN (SELECT * FROM ".$this->table;
            $strSql.= " WHERE cat = '".GAME_SLOT_1."' AND open = '1' ) AS rslot_game ";
            $strSql.= " ON fslot_game.name = rslot_game.name ";
        // $strSql.= " LEFT JOIN ".$this->prdTable." ON ".$this->prdTable.".code = fslot_game.prd_code ";  
        
        $strSql.= " group by fslot_game.fid ";
        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
        $strSql.=" ORDER BY fid ASC LIMIT ".$nStartRow.", ".$arrReqData['count'];
        
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