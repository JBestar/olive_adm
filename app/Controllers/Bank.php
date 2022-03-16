<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\ConfSite_Model;
use App\Models\Member_Model;

class Bank extends BaseController {

	/**

	 */
	public function index()
	{
				
		if(is_login())
		{
			$this->response->redirect('bank/deposit', 'refresh');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
		
	}


	public function deposit(){
		if(is_login())
		{

			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['bankdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['bankdropdown'] = "style=\"display:block\"";
			$arrSidebar['bank_deposit'] = " sidebar-a-active";

			$memberModel = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);			
			$arrSidebar['mb_level'] = $objUser->mb_level;

			if($objUser->mb_level >= LEVEL_ADMIN){
				$strSiteName = $confsiteModel->getSiteName();

				echo view('header', array("site_name"=>$strSiteName));	
				echo view('include/sidebar', $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
				echo view('bank/deposit');
				echo view('footer');
			} else {
				$this->response->redirect( base_url().'pages/nopermit', 'refresh');
			}
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}


	public function withdraw(){
		if(is_login())
		{

			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['bankdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['bankdropdown'] = "style=\"display:block\"";
			$arrSidebar['bank_withdraw'] = " sidebar-a-active";

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
				echo view('bank/withdraw');
				echo view('footer');
			} else  $this->response->redirect( base_url().'pages/nopermit', 'refresh');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}

	public function exchange(){
		if(is_login())
		{

			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['bankdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['bankdropdown'] = "style=\"display:block\"";
			$arrSidebar['bank_exchange'] = " sidebar-a-active";
			
			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			if($objUser->mb_level >= LEVEL_EMPLOYEE){

				$strSiteName = $confsiteModel->getSiteName();

				echo view('header', array("site_name"=>$strSiteName));	
				echo view('include/sidebar', $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
				echo view('bank/exchange');
				echo view('footer');
			} else  $this->response->redirect( base_url().'pages/nopermit', 'refresh');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}

		public function transfer(){
		if(is_login())
		{

			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['bankdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['bankdropdown'] = "style=\"display:block\"";
			$arrSidebar['bank_transfer'] = " sidebar-a-active";
			
			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			if($objUser->mb_level >= LEVEL_EMPLOYEE){

				$strSiteName = $confsiteModel->getSiteName();

				echo view('header', array("site_name"=>$strSiteName));	
				echo view('include/sidebar', $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
				echo view('bank/transfer');
				echo view('footer');
			} else  $this->response->redirect( base_url().'pages/nopermit', 'refresh');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}



}