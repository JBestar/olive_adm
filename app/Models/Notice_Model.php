<?php
namespace App\Models;
use CodeIgniter\Model;

class Notice_Model extends Model {
	
	protected $table ='board_notice';
    protected $allowedFields = [
        'notice_type', 
        'notice_title', 
        'notice_content', 
        'notice_answer', 
        'notice_mb_uid', 
        'notice_emp_fid', 
        'notice_read_count', 
        'notice_time_create', 
        'notice_state_active', 
        'notice_state_delete', 
        'notice_client_delete',
    ];
    protected $primaryKey = 'notice_fid';

    //notice_type 0:ÂÊÁö 1: °øÁö»çÇ×, 2:ÀÌº¥Æ® 3:¹®ÀÇ 4:ÀüÃ¼¹ß¼Û
    public function getNotices(){
        return $this->asObject()->where([
            'notice_type' => 1,
            'notice_state_delete' => 0,
        ])->findAll();
    }

	 public function getEvents(){
         return $this->asObject()->where([
            'notice_type '=>2, 
            'notice_state_delete'=>0
         ])->first();
    }

    public function getNoticeByFid($strNoticeFid){
        return $this->asObject()->where([
            'notice_type '=>1, 
            'notice_state_delete'=>0, 
            'notice_fid'=>$strNoticeFid
        ])->first();
    }

    public function getEventByFid($strNoticeFid){
        return $this->asObject()->where([
            'notice_type '=>2,
            'notice_state_delete'=>0, 
            'notice_fid'=>$strNoticeFid
        ])->first();
    }

    public function updateNoticeByFid($arrUpdateData){
        return $this->builder()->updateBatch($arrUpdateData, 'notice_fid');
    }

    
    public function updateNoticeByEmpFid($arrData){

        $objMessage = $this->getMessageByFid($arrData['notice_fid']);

        if(is_null($objMessage)) return false;

        if($objMessage->notice_mb_uid != '*' || $objMessage->notice_type != 0)
          return false;

        if (array_key_exists("notice_title", $arrData))
          $this->builder()->set('notice_title', $arrData['notice_title']);

        if (array_key_exists("notice_content", $arrData))
          $this->builder()->set('notice_content', $arrData['notice_content']);

        if (array_key_exists("notice_state_delete", $arrData))
          $this->builder()->set('notice_state_delete', $arrData['notice_state_delete']);

        if (array_key_exists("notice_state_active", $arrData))
          $this->builder()->set('notice_state_active', $arrData['notice_state_active']);


        $this->builder()->where('notice_emp_fid', $objMessage->notice_fid);
        $this->builder()->where('notice_type', 4);
        
        return $this->builder()->update();

    
    }   

    function addNoticeBatch($arrBatchData){
        if(count($arrBatchData) < 1) 
            return false;
        return $this->builder()->insertBatch($arrBatchData);    
    } 


    function addNotice($arrData){
       
       $this->builder()->set('notice_type', $arrData['notice_type']);
       $this->builder()->set('notice_title', $arrData['notice_title']);
       $this->builder()->set('notice_content', $arrData['notice_content']);
       $this->builder()->set('notice_state_active', $arrData['notice_state_active']);
       $this->builder()->set('notice_mb_uid', $arrData['notice_mb_uid']);
       $this->builder()->set('notice_time_create', 'NOW()', false);
       if (array_key_exists("notice_emp_fid", $arrData))
          $this->builder()->set('notice_emp_fid', $arrData['notice_emp_fid']);
       if (array_key_exists("notice_read_count", $arrData))
          $this->builder()->set('notice_read_count', $arrData['notice_read_count']);


       $this->builder()->insert();
       return $this->db->insertID();
    }


    public function getMessageByFid($strNoticeFid){
        return $this->asObject()->where([
            'notice_state_delete'=>0, 
            'notice_fid'=>$strNoticeFid,
        ])->first();
    }


    public function setNoticeRead($objNotice){
        $this->builder()->set('notice_read_count', 1);

        $this->builder()->where('notice_fid', $objNotice->notice_fid);
        $this->builder()->where('notice_type', 3);
        
        return $this->builder()->update();
    }


    function searchMessage($arrReqData)
    {
        $strSql = "SELECT *  FROM ".$this->table;
        

        if($arrReqData['notice_type'] == 1 ){                           //수신쪽지

          $strSql.=" WHERE notice_state_delete = '0' AND notice_type = '3'  ";

          if(strlen($arrReqData['notice_mb_uid']) > 0 )
              $strSql.=" AND notice_mb_uid = '".$arrReqData['notice_mb_uid']."' ";            

        } else if($arrReqData['notice_type'] == 2 ){                    //발송쪽지
          $strSql.=" WHERE notice_state_delete = '0' AND notice_type = '0'  ";

          if(strlen($arrReqData['notice_mb_uid']) > 0 )
              $strSql.=" AND notice_mb_uid = '".$arrReqData['notice_mb_uid']."' ";

        } else {                                                        //전체
          $strSql.=" WHERE (notice_state_delete = '0' AND notice_type = '3'  ";

          if(strlen($arrReqData['notice_mb_uid']) > 0 )
              $strSql.=" AND notice_mb_uid = '".$arrReqData['notice_mb_uid']."' ";

          $strSql.=") OR (notice_state_delete = '0' AND notice_type = '0' ";            
          if(strlen($arrReqData['notice_mb_uid']) > 0 )
              $strSql.=" AND notice_mb_uid = '".$arrReqData['notice_mb_uid']."' ";
          $strSql.=") ";
        }


        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
        $strSql.=" ORDER BY notice_time_create DESC LIMIT ".$nStartRow.", ".$arrReqData['count'];
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result; 

    }


    function searchMessageCnt($arrReqData)
    {
        $strSql = "SELECT count(*) as count  FROM ".$this->table;
        

        if($arrReqData['notice_type'] == 1 ){

          $strSql.=" WHERE notice_state_delete = '0' AND notice_type = '3'  ";

          if(strlen($arrReqData['notice_mb_uid']) > 0 )
              $strSql.=" AND notice_mb_uid = '".$arrReqData['notice_mb_uid']."' ";            

        } else if($arrReqData['notice_type'] == 2 ){
          $strSql.=" WHERE notice_state_delete = '0' AND notice_type = '0'  ";

          if(strlen($arrReqData['notice_mb_uid']) > 0 )
              $strSql.=" AND notice_mb_uid = '".$arrReqData['notice_mb_uid']."' ";

        } else {
          $strSql.=" WHERE (notice_state_delete = '0' AND notice_type = '3'  ";

          if(strlen($arrReqData['notice_mb_uid']) > 0 )
              $strSql.=" AND notice_mb_uid = '".$arrReqData['notice_mb_uid']."' ";

          $strSql.=") OR (notice_state_delete = '0' AND notice_type = '0' ";            
          if(strlen($arrReqData['notice_mb_uid']) > 0 )
              $strSql.=" AND notice_mb_uid = '".$arrReqData['notice_mb_uid']."' ";
          $strSql.=") ";
        }

        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 
    }

    function getNewMessageCnt(){
        $strSql = "SELECT count(*) as count  FROM ".$this->table;
        $strSql.=" WHERE notice_state_delete = '0' AND notice_type = '3'  ";
        $strSql.=" AND notice_read_count = '0' ";  
     
        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result->count;  
    }

}
