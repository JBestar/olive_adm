<?php
namespace App\Models;
use CodeIgniter\Model;

class ConfGame_model extends Model {
	
	protected $table = 'conf_game';
    protected $allowedFields = [
        'game_name', 
        'game_bet_permit', 
        'game_autobet_permit', 
        'game_time_countdown', 
        'game_time_delay', 
        'game_min_bet_money', 
        'game_max_bet_money', 
        'game_ratio', 
        'game_ratio_1', 
        'game_ratio_2', 
        'game_ratio_3',
        'game_ratio_4',
        'game_ratio_5',
        'game_ratio_6',
        'game_ratio_7',
        'game_ratio_8',
        'game_ratio_9',
        'game_ratio_10', 
        'game_ratio_11', 
        'game_ratio_12', 
        'game_ratio_13', 
        'game_ratio_14', 
        'game_ratio_15', 
        'game_ratio_16', 
        'game_ratio_17', 
        'game_ratio_18', 
        'game_ratio_19', 
        'game_ratio_20', 
        'game_ratio_21', 
        'game_ratio_22', 
        'game_ratio_23', 
        'game_ratio_24', 
        'game_ratio_25', 
        'game_ratio_26',
        'game_percent_1',
        'game_percent_2',
        'game_event_id',
        'game_multibet',
    ];
    protected $primaryKey = 'game_index';

    public function getByIndex($strIndex){
        return $this->asObject()->where(array('game_index'=>$strIndex))->first();
    }

    public function saveData($arrData){

        if($arrData == null) return false;
        if (!array_key_exists("game_index", $arrData)) return false;
        if (!array_key_exists("game_bet_permit", $arrData)) return false;
        // if (!array_key_exists("game_time_countdown", $arrData)) return false;
        // if (!array_key_exists("game_ratio_1", $arrData)) return false;
        // if (!array_key_exists("game_ratio_2", $arrData)) return false;
        // if (!array_key_exists("game_ratio_3", $arrData)) return false;
                
        $arrBatch = array();
        $arrBatch[0] = $arrData;
        return  $this->builder()->updateBatch($arrBatch, 'game_index');
    }

}