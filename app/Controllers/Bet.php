<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\ConfSite_Model;
use App\Models\Member_Model;

class Bet extends BaseController {

	/**

	 */
	public function index()
	{
				
		if(is_login())
		{
			$this->response->redirect(base_url().'bet/pbrealtime', 'refresh');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
		
	}


	public function pbrealtime(){
		if(is_login())
		{

			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['betdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['betdropdown'] = "style=\"display:block\"";
			$arrSidebar['bet_realtime'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			if($objUser->mb_level > LEVEL_COMPANY ){
				$strSiteName = $confsiteModel->getSiteName();

				echo view('header', array("site_name"=>$strSiteName));	
				echo view('include/sidebar', $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
				echo view('bet/pbrealtime');
				echo view('footer');
			} else  $this->response->redirect( base_url().'pages/nopermit', 'refresh');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}

	public function psrealtime(){
		if(is_login())
		{

			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['betdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['betdropdown'] = "style=\"display:block\"";
			$arrSidebar['bet_realtime'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;
			if($objUser->mb_level > LEVEL_COMPANY ){
				$strSiteName = $confsiteModel->getSiteName();

				echo view('header', array("site_name"=>$strSiteName));		
				echo view('include/sidebar', $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
				echo view('bet/psrealtime');
				echo view('footer');
			}else  $this->response->redirect( base_url().'pages/nopermit', 'refresh');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}


	public function ksrealtime(){
		if(is_login())
		{

			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['betdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['betdropdown'] = "style=\"display:block\"";
			$arrSidebar['bet_realtime'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			if($objUser->mb_level > LEVEL_COMPANY ){
				$strSiteName = $confsiteModel->getSiteName();

				echo view('header', array("site_name"=>$strSiteName));		
				echo view('include/sidebar', $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
				echo view('bet/ksrealtime');
				echo view('footer');
			} else  $this->response->redirect( base_url().'pages/nopermit', 'refresh');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}

	public function bbrealtime(){
		if(is_login())
		{

			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['betdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['betdropdown'] = "style=\"display:block\"";
			$arrSidebar['bet_realtime'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			if($objUser->mb_level > LEVEL_COMPANY ){
				$strSiteName = $confsiteModel->getSiteName();

				echo view('header', array("site_name"=>$strSiteName));	
				echo view('include/sidebar', $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
				echo view('bet/bbrealtime');
				echo view('footer');
			} else  $this->response->redirect( base_url().'pages/nopermit', 'refresh');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}

	public function bsrealtime(){
		if(is_login())
		{

			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['betdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['betdropdown'] = "style=\"display:block\"";
			$arrSidebar['bet_realtime'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;
			if($objUser->mb_level > LEVEL_COMPANY ){
				$strSiteName = $confsiteModel->getSiteName();

				echo view('header', array("site_name"=>$strSiteName));		
				echo view('include/sidebar', $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
				echo view('bet/bsrealtime');
				echo view('footer');
			}else  $this->response->redirect( base_url().'pages/nopermit', 'refresh');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}



	public function pbhistory(){
		if(is_login())
		{

			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['betdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['betdropdown'] = "style=\"display:block\"";
			$arrSidebar['bet_history'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			$strSiteName = $confsiteModel->getSiteName();

			echo view('header', array("site_name"=>$strSiteName));		
			echo view('include/sidebar', $arrSidebar);
			echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
			echo view('bet/pbhistory', array("mb_level"=>$objUser->mb_level));
			echo view('footer');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}

	public function pshistory(){
		if(is_login())
		{

			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['betdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['betdropdown'] = "style=\"display:block\"";
			$arrSidebar['bet_history'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			$strSiteName = $confsiteModel->getSiteName();

			echo view('header', array("site_name"=>$strSiteName));		
			echo view('include/sidebar', $arrSidebar);
			echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
			echo view('bet/pshistory', array("mb_level"=>$objUser->mb_level));
			echo view('footer');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}


	public function kshistory(){
		if(is_login())
		{

			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['betdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['betdropdown'] = "style=\"display:block\"";
			$arrSidebar['bet_history'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			$strSiteName = $confsiteModel->getSiteName();

			echo view('header', array("site_name"=>$strSiteName));		
			echo view('include/sidebar', $arrSidebar);
			echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
			echo view('bet/kshistory', array("mb_level"=>$objUser->mb_level));
			echo view('footer');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}

	
	public function cshistory(){
		if(is_login())
		{

			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['betdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['betdropdown'] = "style=\"display:block\"";
			$arrSidebar['bet_history'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			$strSiteName = $confsiteModel->getSiteName();

			echo view('header', array("site_name"=>$strSiteName));		
			echo view('include/sidebar', $arrSidebar);
			echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
			echo view('bet/cshistory', array("mb_level"=>$objUser->mb_level));
			echo view('footer');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}
	
	
	public function bbhistory(){
		if(is_login())
		{

			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['betdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['betdropdown'] = "style=\"display:block\"";
			$arrSidebar['bet_history'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			$strSiteName = $confsiteModel->getSiteName();

			echo view('header', array("site_name"=>$strSiteName));		
			echo view('include/sidebar', $arrSidebar);
			echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
			echo view('bet/bbhistory', array("mb_level"=>$objUser->mb_level));
			echo view('footer');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}

	public function bshistory(){
		if(is_login())
		{

			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['betdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['betdropdown'] = "style=\"display:block\"";
			$arrSidebar['bet_history'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			$strSiteName = $confsiteModel->getSiteName();

			echo view('header', array("site_name"=>$strSiteName));		
			echo view('include/sidebar', $arrSidebar);
			echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
			echo view('bet/bshistory', array("mb_level"=>$objUser->mb_level));
			echo view('footer');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}



	public function allcalculate(){
		if(is_login())
		{

			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['betdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['betdropdown'] = "style=\"display:block\"";
			$arrSidebar['bet_calculate'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			$strSiteName = $confsiteModel->getSiteName();

			echo view('header', array("site_name"=>$strSiteName));		
			echo view('include/sidebar', $arrSidebar);
			echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
			echo view('bet/allcalculate');
			echo view('footer');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}

	public function pbcalculate(){
		if(is_login())
		{

			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['betdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['betdropdown'] = "style=\"display:block\"";
			$arrSidebar['bet_calculate'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			$strSiteName = $confsiteModel->getSiteName();

			echo view('header', array("site_name"=>$strSiteName));		
			echo view('include/sidebar', $arrSidebar);
			echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
			echo view('bet/pbcalculate');
			echo view('footer');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}


	public function pscalculate(){
		if(is_login())
		{

			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['betdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['betdropdown'] = "style=\"display:block\"";
			$arrSidebar['bet_calculate'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			$strSiteName = $confsiteModel->getSiteName();

			echo view('header', array("site_name"=>$strSiteName));		
			echo view('include/sidebar', $arrSidebar);
			echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
			echo view('bet/pscalculate');
			echo view('footer');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}

	public function kscalculate(){
		if(is_login())
		{

			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['betdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['betdropdown'] = "style=\"display:block\"";
			$arrSidebar['bet_calculate'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			$strSiteName = $confsiteModel->getSiteName();

			echo view('header', array("site_name"=>$strSiteName));		
			echo view('include/sidebar', $arrSidebar);
			echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
			echo view('bet/kscalculate');
			echo view('footer');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}
	
	public function cscalculate(){
		if(is_login())
		{

			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['betdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['betdropdown'] = "style=\"display:block\"";
			$arrSidebar['bet_calculate'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			$strSiteName = $confsiteModel->getSiteName();

			echo view('header', array("site_name"=>$strSiteName));		
			echo view('include/sidebar', $arrSidebar);
			echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
			echo view('bet/cscalculate');
			echo view('footer');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}
	
	public function bbcalculate(){
		if(is_login())
		{

			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['betdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['betdropdown'] = "style=\"display:block\"";
			$arrSidebar['bet_calculate'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			$strSiteName = $confsiteModel->getSiteName();

			echo view('header', array("site_name"=>$strSiteName));		
			echo view('include/sidebar', $arrSidebar);
			echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
			echo view('bet/bbcalculate');
			echo view('footer');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}


	public function bscalculate(){
		if(is_login())
		{

			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['betdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['betdropdown'] = "style=\"display:block\"";
			$arrSidebar['bet_calculate'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			$strSiteName = $confsiteModel->getSiteName();

			echo view('header', array("site_name"=>$strSiteName));		
			echo view('include/sidebar', $arrSidebar);
			echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
			echo view('bet/bscalculate');
			echo view('footer');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}


}