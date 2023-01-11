<?php
namespace App\Models;
use CodeIgniter\Model;

class Clean_Model extends Model {
	
    //디비정리
    public function cleanDb(){
        $tmNow = time();
        $strDate = date('Y-m-d', strtotime("-2 months", $tmNow));
        
        $strSql = " DELETE FROM bet_casino WHERE bet_time < '".$strDate."' ";
        $this -> db -> query($strSql);
        
        $strSql = " DELETE FROM bet_slot WHERE bet_time < '".$strDate."' ";
        $this -> db -> query($strSql);
        
        $strSql = " DELETE FROM bet_reward WHERE rw_time < '".$strDate."' ";
        $this -> db -> query($strSql);
        
        $strSql = " DELETE FROM bet_powerball WHERE bet_time < '".$strDate."' ";
        $this -> db -> query($strSql);
        
        $strSql = " DELETE FROM bet_powerladder WHERE bet_time < '".$strDate."' ";
        $this -> db -> query($strSql);
        
        $strSql = " DELETE FROM bet_bogleball WHERE bet_time < '".$strDate."' ";
        $this -> db -> query($strSql);
        
        $strSql = " DELETE FROM bet_bogleladder WHERE bet_time < '".$strDate."' ";
        $this -> db -> query($strSql);
        
        $strSql = " DELETE FROM bet_eos5ball WHERE bet_time < '".$strDate."' ";
        $this -> db -> query($strSql);
        
        $strSql = " DELETE FROM bet_eos3ball WHERE bet_time < '".$strDate."' ";
        $this -> db -> query($strSql);
        
        $strSql = " DELETE FROM bet_coin5ball WHERE bet_time < '".$strDate."' ";
        $this -> db -> query($strSql);
        
        $strSql = " DELETE FROM bet_coin3ball WHERE bet_time < '".$strDate."' ";
        $this -> db -> query($strSql);
        
        // $strSql = " DELETE FROM board_notice WHERE notice_time_create < '".$strDate."'  AND notice_type != '".NOTICE_BOARD."' ";
        // $this -> db -> query($strSql);

        // $strSql = " DELETE FROM member_charge WHERE charge_time_require < '".$strDate."' ";
        // $this -> db -> query($strSql);
        
        // $strSql = " DELETE FROM member_exchange WHERE exchange_time_require < '".$strDate."' ";
        // $this -> db -> query($strSql);
        
        // $strSql = " DELETE FROM money_history WHERE money_update_time < '".$strDate."' ";
        // $this -> db -> query($strSql);
        
        // $strSql = " DELETE FROM transfer_history WHERE money_update_time < '".$strDate."' ";
        // $this -> db -> query($strSql);
                
        // $strSql = " DELETE FROM round_powerball WHERE round_date < '".$strDate."' ";
        // $this -> db -> query($strSql);

        // $strSql = " DELETE FROM round_powerladder WHERE round_date < '".$strDate."' ";
        // $this -> db -> query($strSql);

        // $strSql = " DELETE FROM round_bogleball WHERE round_date < '".$strDate."' ";
        // $this -> db -> query($strSql);

        // $strSql = " DELETE FROM round_bogleladder WHERE round_date < '".$strDate."' ";
        // $this -> db -> query($strSql);

        // $strSql = " DELETE FROM sess_try WHERE log_time < '".$strDate."' ";
        // $this -> db -> query($strSql);
        
        // $strSql = " DELETE FROM sess_log WHERE log_time < '".$strDate."' ";
        // $this -> db -> query($strSql);

        $this->db->query("TRUNCATE sessions");

        
        return 1;
    }
    //디비초기화
    public function initDb(){
        $this->db->query("TRUNCATE bet_slot");
        $this->db->query("TRUNCATE bet_casino");
        $this->db->query("TRUNCATE bet_balance");
        $this->db->query("TRUNCATE bet_powerball");
        $this->db->query("TRUNCATE bet_powerladder");
        $this->db->query("TRUNCATE bet_bogleball");
        $this->db->query("TRUNCATE bet_bogleladder");
        $this->db->query("TRUNCATE bet_eos5ball");
        $this->db->query("TRUNCATE bet_eos3ball");
        $this->db->query("TRUNCATE bet_coin5ball");
        $this->db->query("TRUNCATE bet_coin3ball");
        $this->db->query("TRUNCATE bet_reward");
        $this->db->query("TRUNCATE bet_follow");
        $this->db->query("TRUNCATE block_list");
        $this->db->query("TRUNCATE log_modify");
        $this->db->query("TRUNCATE member_charge");
        $this->db->query("TRUNCATE member_exchange");
        $this->db->query("TRUNCATE money_history");
        $this->db->query("TRUNCATE sessions");
        $this->db->query("TRUNCATE sess");
        $this->db->query("TRUNCATE sess_log");
        $this->db->query("TRUNCATE sess_try");

        $tmNow = time();
        $strDate = date('Y-m-d', strtotime("-2 months", $tmNow));

        $strSql = " DELETE FROM round_powerball WHERE round_date < '".$strDate."' ";
        $this -> db -> query($strSql);

        $strSql = " DELETE FROM round_powerladder WHERE round_date < '".$strDate."' ";
        $this -> db -> query($strSql);

        $strSql = " DELETE FROM round_bogleball WHERE round_date < '".$strDate."' ";
        $this -> db -> query($strSql);

        $strSql = " DELETE FROM round_bogleladder WHERE round_date < '".$strDate."' ";
        $this -> db -> query($strSql);

        $strSql = " DELETE FROM round_eos5ball WHERE round_date < '".$strDate."' ";
        $this -> db -> query($strSql);
        
        $strSql = " DELETE FROM round_eos3ball WHERE round_date < '".$strDate."' ";
        $this -> db -> query($strSql);

        $strSql = " DELETE FROM board_notice WHERE notice_type != '".NOTICE_BOARD."' ";
        $this -> db -> query($strSql);

        $strSql = " DELETE FROM member WHERE mb_level < '".LEVEL_ADMIN."' ";
        $this -> db -> query($strSql);

        return 1;
    }

}