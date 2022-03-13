<?php 
namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class ConfGame_Model extends Model {

    private $mDb;
    private $mBuilder;
    private $mTbName = "conf_game";
    private $mTbColumn;

    function __construct()
    {
      
        $this->mDb      = Database::connect();
        $this->mBuilder = $this->mDb->table($this->mTbName);
    }

    public function getById($game_id){
        
        try { 
            
            $this->mBuilder->where(['game_index'=>$game_id])
                           ->getCompiledSelect(false);
            $query = $this->mBuilder->get();
            return $query->getRow();            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }


}