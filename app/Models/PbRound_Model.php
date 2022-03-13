<?php 
namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class PbRound_Model extends Model {

    private $mDb;
    private $mBuilder;
    private $mTbName = "round_powerball";
    private $mTbColumn;

    function __construct()
    {
      
        $this->mDb      = Database::connect();
        $this->mBuilder = $this->mDb->table($this->mTbName);
    }

    public function gets($count){
        
        try {
            $this->mBuilder->orderBy('round_fid', 'DESC')
                           ->limit($count) 
                           ->getCompiledSelect(false);
            $query = $this->mBuilder->get();
            return $query->getResult();            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }

    public function getByFid($round_fid){
        
        try { 
            
            $this->mBuilder->where(['round_fid'=>$round_fid])
                           ->getCompiledSelect(false);
            $query = $this->mBuilder->get();
            return $query->getRow();            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }


}