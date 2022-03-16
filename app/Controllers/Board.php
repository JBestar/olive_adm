<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\ConfSite_Model;
use App\Models\Member_Model;
use App\Models\Notice_Model;

class Board extends BaseController {

	/**


	 */
	public function index()
	{
		$this->response->redirect( base_url().'board/notice', 'refresh');
		
	}

	public function notice()
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
				$noticeModel = new Notice_Model();
				$arrNotice = $noticeModel->getNotices();

				$arrData['arrNotice'] = $arrNotice;

				echo view('header', array("site_name"=>$strSiteName));	
				echo view('include/sidebar', $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
				echo view('board/notice', $arrData);
				echo view('footer');
			} else  $this->response->redirect( base_url().'pages/nopermit', 'refresh');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}			
	}

	public function notice_edit($strNoticeFid)
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
					$objNotice = $noticeModel->getNoticeByFid($strNoticeFid);					
				}
				$arrData['objNotice'] = $objNotice;

				echo view('header', array("site_name"=>$strSiteName));	
				echo view('include/sidebar', $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
				echo view('board/notice_edit', $arrData);
				echo view('footer');
			} else  $this->response->redirect( base_url().'pages/nopermit', 'refresh');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}			
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
		if(is_login())
		{			
			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['boarddropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['boarddropdown'] = "style=\"display:block\"";
			$arrSidebar['board_message'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			if($objUser->mb_level >= LEVEL_ADMIN){

				$strSiteName = $confsiteModel->getSiteName();
			
				echo view('header', array("site_name"=>$strSiteName));	
				echo view('include/sidebar', $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
				echo view('board/message');
				echo view('footer');
			} else  $this->response->redirect( base_url().'pages/nopermit', 'refresh');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}			
	}

	public function message_edit($strNoticeFid, $strUserFid)
	{		

		if(is_login())
		{
			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['boarddropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['boarddropdown'] = "style=\"display:block\"";
			$arrSidebar['board_message'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objAdmin = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objAdmin->mb_level;

			if($objAdmin->mb_level >= LEVEL_ADMIN){

				$strSiteName = $confsiteModel->getSiteName();
				$objUser = null;
				if($strUserFid > 0)
					$objUser = $memberModel->getInfoByFid($strUserFid);
				
				$objNotice = null;
				if($strNoticeFid > 0)
				{
					$noticeModel = new Notice_Model();
					$objNotice = $noticeModel->getMessageByFid($strNoticeFid);

					if(!is_null($objNotice) && $objNotice->notice_type == 3 && $objNotice->notice_read_count == 0)
					 	$noticeModel->setNoticeRead($objNotice);

				}
				$strUserId = '*';
				if(!is_null($objUser)) $strUserId = $objUser->mb_uid;

				$arrData['objNotice'] = $objNotice;
				$arrData['strUserId'] = $strUserId;

				echo view('header', array("site_name"=>$strSiteName));	
				echo view('include/sidebar', $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objAdmin->mb_level));
				echo view('board/message_edit', $arrData);
				echo view('footer');
			} else  $this->response->redirect( base_url().'pages/nopermit', 'refresh');
			
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}			
	}
}