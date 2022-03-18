<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\ConfSite_Model;
use App\Models\Member_Model;
use App\Models\Notice_Model;

class Board extends StdController {

	/**


	 */
	public function index()
	{
		$this->response->redirect( base_url().'board/notice', 'refresh');
		
	}

	public function notice()
	{
		$noticeModel = new Notice_Model();
		$arrNotice = $noticeModel->getNotices();
		$this->load_view_page('board/notice', 'board_notice', LEVEL_ADMIN, [
			'arrNotice' => $arrNotice
		]);		
	}

	public function notice_edit($strNoticeFid)
	{
		$objNotice = null;
		if($strNoticeFid > 0)
		{
			$noticeModel = new Notice_Model();
			$objNotice = $noticeModel->getNoticeByFid($strNoticeFid);					
		}
		$this->load_view_page('board/notice_edit', 'board_notice', LEVEL_ADMIN, [
			'objNotice' => $objNotice
		]);			
	}

	/*
	public function event()
	{		
		if(is_login())
		{			
			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['boarddropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['boarddropdown'] = "style=\"display:block\"";
			$arrSidebar['board_event'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			if($objUser->mb_level >= LEVEL_ADMIN){

				$strSiteName = $confsiteModel->getSiteName();
				$noticeModel = new Notice_Model();
				$arrNotice = $noticeModel->getEvents();

				$arrData['arrNotice'] = $arrNotice;

				echo view('header', array("site_name"=>$strSiteName));	
				echo view('include/sidebar', $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
				echo view('board/event', $arrData);
				echo view('footer');
			} else  $this->response->redirect( base_url().'pages/nopermit', 'refresh');
			
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}			
	}

	public function event_edit($strNoticeFid)
	{		
		if(is_login())
		{			
			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['boarddropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['boarddropdown'] = "style=\"display:block\"";
			$arrSidebar['board_notice'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			if($objUser->mb_level >= LEVEL_ADMIN){

				$strSiteName = $confsiteModel->getSiteName();
				$objNotice = null;
				if($strNoticeFid > 0)
				{
					$noticeModel = new Notice_Model();
					$objNotice = $noticeModel->getEventByFid($strNoticeFid);					
				}
				$arrData['objNotice'] = $objNotice;

				echo view('header', array("site_name"=>$strSiteName));	
				echo view('include/sidebar', $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
				echo view('board/event_edit', $arrData);
				echo view('footer');
			} else  $this->response->redirect( base_url().'pages/nopermit', 'refresh');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}			
	}
	*/


	public function message()
	{
		$this->load_view_page('board/message', 'board_message', LEVEL_ADMIN);		
	}

	public function message_edit($strNoticeFid, $strUserFid)
	{
		$objNotice = null;
		if($strNoticeFid > 0)
		{
			$noticeModel = new Notice_Model();
			$objNotice = $noticeModel->getMessageByFid($strNoticeFid);

			if(!is_null($objNotice) && $objNotice->notice_type == 3 && $objNotice->notice_read_count == 0)
				$noticeModel->setNoticeRead($objNotice);
		}
		$strUserId = '*';
		$memberModel  = new Member_Model();
		$objUser = null;
		if($strUserFid > 0)
			$objUser = $memberModel->getInfoByFid($strUserFid);
		if(!is_null($objUser)) $strUserId = $objUser->mb_uid;
		$this->load_view_page('board/message_edit', 'board_message', LEVEL_ADMIN, [
			'objNotice' => $objNotice, 
			'strUserId' => $strUserId
		]);		
	}
}