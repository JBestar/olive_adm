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
    protected $prdTable = 'slot_prd';

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
            return $this->update($arrReqData['fid'], $data);
            // return $this->set($data)
            //  ->where('name', $arrReqData['name'])
            //  ->update();
        } else if(array_key_exists('maintain', $arrReqData)){
            $data['maintain'] =  $arrReqData['maintain'];
            return $this->update($arrReqData['fid'], $data);
        } else return false;
    
    }

    public function search($arrReqData){
        $where = ""; 
        if(strlen($arrReqData['name']) > 0){
            $where = " AND ( name LIKE '%".$arrReqData['name']."%' OR name_ko LIKE '%".$arrReqData['name']."%' )";
        }

        // $strSql = " SELECT fslot_game.*, rslot_game.name as rname, rslot_game.name_ko AS rname_ko, rslot_game.fid AS rfid, ";
        // $strSql.= " rslot_game.hidden AS rhidden, rslot_game.maintain AS rmaintain "; 
        //     $strSql.= " FROM ( SELECT * FROM ".$this->table;
        //     $strSql.= " WHERE cat = '".GAME_SLOT_2."' AND open = '1' ";
        //     $strSql.= " ) AS fslot_game";
        // $strSql.= " JOIN (SELECT * FROM ".$this->table;
        //     $strSql.= " WHERE cat = '".GAME_SLOT_1."' AND open = '1' ".$where.") AS rslot_game ";
        //     $strSql.= " ON fslot_game.name = rslot_game.name ";
        // $strSql.= " group by fslot_game.fid ";
        if($_ENV['app.type'] == APPTYPE_2 ){
            if($arrReqData['prd'] == 200)
                $strSql = " SELECT slot_game.*, COUNT(game_code) AS fslot_cnt FROM slot_game WHERE prd_code IN (200 ,215) AND OPEN = 1  GROUP BY game_code ";
            else 
                $strSql = " SELECT slot_game.*, 1 AS fslot_cnt FROM slot_game WHERE prd_code = ".$arrReqData['prd']." AND OPEN = 1  ";
        } else {
            $strSql = " SELECT xslot_game.*, fslot_game.fid AS fslot_fid, fslot_game.prd_code AS fslot_prd , fslot_game.cnt AS fslot_cnt, fslot_game.act AS fslot_act FROM ";
            $strSql.= " ( SELECT * FROM slot_game WHERE prd_code = ".$arrReqData['prd']." AND OPEN = 1 AND ref_prd = 0 ".$where.") AS xslot_game ";
            $strSql.= " JOIN (SELECT *, COUNT(NAME) AS cnt FROM slot_game WHERE prd_code IN ( SELECT code FROM slot_prd WHERE ref_code = ".$arrReqData['prd']." ) AND OPEN = 1  GROUP BY NAME) AS fslot_game ";
            $strSql.= " ON xslot_game.name = fslot_game.name ";
            
            $strSql.= " UNION ALL SELECT xslot_game.*, fslot_game.fid AS fslot_fid, fslot_game.prd_code AS fslot_prd, 1 AS fslot_cnt, fslot_game.act AS fslot_act FROM ";
            $strSql.= " ( SELECT * FROM slot_game WHERE prd_code = ".$arrReqData['prd']." AND OPEN = 1 AND ref_prd > 0 ".$where.") AS xslot_game ";
            $strSql.= " JOIN slot_game AS fslot_game ON xslot_game.ref_prd = fslot_game.prd_code AND xslot_game.ref_uuid = fslot_game.uuid ";
        }
        
        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
        $strSql.=" ORDER BY name ASC LIMIT ".$nStartRow.", ".$arrReqData['count'];
        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog($strSql);

        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result; 

    }

    public function searchCount($arrReqData){
        
        $where = ""; 
        if(strlen($arrReqData['name']) > 0){
            $where = " AND ( name LIKE '%".$arrReqData['name']."%' OR name_ko LIKE '%".$arrReqData['name']."%' )";
        }

        // $strSql = "SELECT count(*) as count FROM ";
        //     $strSql.= "( SELECT fslot_game.fid  FROM ";
        //         $strSql.= "( SELECT * FROM ".$this->table;
        //         $strSql.= " WHERE cat = '".GAME_SLOT_2."' AND open = '1' ";
        //         $strSql.= " ) AS fslot_game";
        //     $strSql.= " JOIN (SELECT * FROM ".$this->table;
        //         $strSql.= " WHERE cat = '".GAME_SLOT_1."' AND open = '1' ".$where.") AS rslot_game ";
        //         $strSql.= " ON fslot_game.name = rslot_game.name group by fslot_game.fid )";
        // $strSql.= " AS tb_result";
        if($_ENV['app.type'] == APPTYPE_2 ){
            if($arrReqData['prd'] == 200)
                $strSql = " SELECT slot_game.fid FROM slot_game WHERE prd_code IN (200 ,215) AND OPEN = 1  GROUP BY game_code ";
            else 
                $strSql = " SELECT slot_game.fid FROM slot_game WHERE prd_code = ".$arrReqData['prd']." AND OPEN = 1  ";
        } else {
            $strSql = " SELECT xslot_game.fid FROM ";
            $strSql.= " ( SELECT * FROM slot_game WHERE prd_code = ".$arrReqData['prd']." AND OPEN = 1 AND ref_prd = 0 ".$where.") AS xslot_game ";
            $strSql.= " JOIN (SELECT *, COUNT(NAME) AS cnt FROM slot_game WHERE prd_code IN ( SELECT code FROM slot_prd WHERE ref_code = ".$arrReqData['prd']." ) AND OPEN = 1  GROUP BY NAME) AS fslot_game ";
            $strSql.= " ON xslot_game.name = fslot_game.name ";
            
            $strSql.= " UNION ALL SELECT xslot_game.fid FROM ";
            $strSql.= " ( SELECT * FROM slot_game WHERE prd_code = ".$arrReqData['prd']." AND OPEN = 1 AND ref_prd > 0 ".$where.") AS xslot_game ";
            $strSql.= " JOIN slot_game AS fslot_game ON xslot_game.ref_prd = fslot_game.prd_code AND xslot_game.ref_uuid = fslot_game.uuid ";
        }

        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog($strSql);

        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        $objResult = new \StdClass;
        $objResult->count = count($result);
        return $objResult; 
    }



}