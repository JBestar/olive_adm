<?php

namespace App\Controllers;

use App\Models\ConfSite_Model;
use App\Models\Member_Model;

class User extends BaseController
{

	/**

	 */
	public function index()
	{
				
		if(is_login())
		{
			$this->response->redirect(base_url().'user/member', 'refresh');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
		
	}


	public function company(){
		if(is_login())
		{
			//사이드바 관련 배렬
			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['userdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['userdropdown'] = "style=\"display:block\"";
			$arrSidebar['user_company'] = " sidebar-a-active";

			$arrCompany = array();
			
			//현재 권한에 따른 부본사정보 얻기

			$memberModel = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			
			$strUid = $this->session->username;
			$objAdmin = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objAdmin->mb_level;
				
			if($objAdmin->mb_level > LEVEL_COMPANY ){
				$arrCompany = $memberModel->getMemberByLevel(LEVEL_COMPANY, false);
				$arrData['arrCompany'] = $arrCompany;
				$arrData['nAdminLevel'] = $objAdmin->mb_level;
				$strSiteName = $confsiteModel->getSiteName();

				echo view('header', array("site_name"=>$strSiteName));	
				echo view('include/sidebar', $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objAdmin->mb_level));
				echo view('user/company', $arrData);
				echo view('footer');
				
			} else  $this->response->redirect( base_url().'pages/nopermit', 'refresh');
			
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}

	public function company_edit($strMemberFid){
		if(is_login())
		{
			
			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['userdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['userdropdown'] = "style=\"display:block\"";
			$arrSidebar['user_company'] = " sidebar-a-active";

			$objCompany = null;
			
			$memberModel = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			//현재 권한에 따른 허용상태 얻기
			$strUid = $this->session->username;
			$objAdmin = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objAdmin->mb_level;

			if($objAdmin->mb_level > LEVEL_COMPANY) { 
				if($strMemberFid > 0)
				{
					$objCompany = $memberModel->getMemberByFid($strMemberFid);					
				}
				
				$arrData['objMember'] = $objCompany;
				$arrData['nAdminLevel'] = $objAdmin->mb_level;

				$strSiteName = $confsiteModel->getSiteName();

				echo view('header', array("site_name"=>$strSiteName));	
				echo view('include/sidebar',  $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objAdmin->mb_level));
				echo view('user/company_edit', $arrData);
				echo view('footer');
			} else $this->response->redirect( base_url().'pages/nopermit', 'refresh');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}

	public function agency(){
		if(is_login())
		{
			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['userdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['userdropdown'] = "style=\"display:block\"";
			$arrSidebar['user_agency'] = " sidebar-a-active";

			//현재 권한에 따른 부본사정보 얻기
			$memberModel = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			
			$strUid = $this->session->username;
			$objAdmin = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objAdmin->mb_level;
				
			if($objAdmin->mb_level > LEVEL_AGENCY ){
				$arrAgency = $memberModel->getMemberByEmpFid($objAdmin->mb_fid, LEVEL_AGENCY, $objAdmin->mb_level);
				if(is_null($arrAgency))
					$arrAgency = array();
				foreach($arrAgency as $objMember){
					$objMember->mb_nickname = $memberModel->getFullName($objMember);	
				}
                
				$arrData['arrAgency'] = $arrAgency;
				$arrData['nAdminLevel'] = $objAdmin->mb_level;
				$strSiteName = $confsiteModel->getSiteName();

				echo view('header', array("site_name"=>$strSiteName));	
				echo view('include/sidebar', $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objAdmin->mb_level));
				echo view('user/agency', $arrData);
				echo view('footer');

			}  else  $this->response->redirect( base_url().'pages/nopermit', 'refresh');

		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}

	public function agency_edit($strMemberFid){
		if(is_login())
		{
			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['userdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['userdropdown'] = "style=\"display:block\"";
			$arrSidebar['user_agency'] = " sidebar-a-active";

			$objAgency = null;
			$memberModel = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			//현재 권한에 따른 허용상태 얻기
			$strUid = $this->session->username;
			$objAdmin = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objAdmin->mb_level;

			if($objAdmin->mb_level > LEVEL_AGENCY) {

				if($strMemberFid > 0)
				{
					$objAgency = $memberModel->getMemberByFid($strMemberFid);					
				}
				//부본사 네임들을 가져오기.
				$arrEmpName = $memberModel->getEmployeeNames($objAdmin, LEVEL_COMPANY);
				
				$arrData['objMember'] = $objAgency;
				$arrData['arrEmpName'] = $arrEmpName;
				$arrData['nAdminLevel'] = $objAdmin->mb_level;

				$strSiteName = $confsiteModel->getSiteName();
				
				echo view('header', array("site_name"=>$strSiteName));	
				echo view('include/sidebar',  $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objAdmin->mb_level));
				echo view('user/agency_edit', $arrData);
				echo view('footer');
				
			}  else  $this->response->redirect( base_url().'pages/nopermit', 'refresh');

		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}

	public function employee(){
		if(is_login())
		{
			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['userdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['userdropdown'] = "style=\"display:block\"";
			$arrSidebar['user_employee'] = " sidebar-a-active";

			$memberModel = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			
			$strUid = $this->session->username;
			$objAdmin = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objAdmin->mb_level;
				
			if($objAdmin->mb_level > LEVEL_EMPLOYEE ){
				$arrEmployee = $memberModel->getMemberByEmpFid($objAdmin->mb_fid, LEVEL_EMPLOYEE, $objAdmin->mb_level);
				if(is_null($arrEmployee))
					$arrEmployee = array();
				
				foreach($arrEmployee as $objMember){
					$objMember->mb_nickname = $memberModel->getFullName($objMember);
				}
				$arrData['arrEmployee'] = $arrEmployee;
				$arrData['nAdminLevel'] = $objAdmin->mb_level;
				$strSiteName = $confsiteModel->getSiteName();

				echo view('header', array("site_name"=>$strSiteName));		
				echo view('include/sidebar', $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objAdmin->mb_level));
				echo view('user/employee', $arrData);
				echo view('footer');
			}  else  $this->response->redirect( base_url().'pages/nopermit', 'refresh');

		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}

	public function employee_edit($strMemberFid){
		if(is_login())
		{
			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['userdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['userdropdown'] = "style=\"display:block\"";
			$arrSidebar['user_employee'] = " sidebar-a-active";

			$objEmployee = null;
			
			$memberModel = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			//현재 권한에 따른 허용상태 얻기
			$strUid = $this->session->username;
			$objAdmin = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objAdmin->mb_level;
			if($objAdmin->mb_level > LEVEL_EMPLOYEE) { 
				if($strMemberFid > 0)
				{
					$objEmployee = $memberModel->getMemberByFid($strMemberFid);
					
				}
				//총판 네임들을 가져오기.
				$arrEmpName = $memberModel->getEmployeeNames($objAdmin, LEVEL_AGENCY);

				$arrData['objMember'] = $objEmployee;
				$arrData['arrEmpName'] = $arrEmpName;
				$arrData['nAdminLevel'] = $objAdmin->mb_level;

				$strSiteName = $confsiteModel->getSiteName();

				echo view('header', array("site_name"=>$strSiteName));	
				echo view('include/sidebar',  $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objAdmin->mb_level));
				echo view('user/employee_edit', $arrData);
				echo view('footer');
			}  else  $this->response->redirect( base_url().'pages/nopermit', 'refresh');
			
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}


	public function member(){
		if(is_login())
		{

			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['userdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['userdropdown'] = "style=\"display:block\"";
			$arrSidebar['user_member'] = " sidebar-a-active";

			
			$memberModel = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			
			$strUid = $this->session->username;
			$objAdmin = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objAdmin->mb_level;
			if($objAdmin->mb_level >= LEVEL_EMPLOYEE ){
				
				$arrEmpName = $memberModel->getEmployeeNames($objAdmin, LEVEL_EMPLOYEE);
                $arrData['arrEmpName'] = $arrEmpName;
				$arrData['nAdminLevel'] = $objAdmin->mb_level;
				$strSiteName = $confsiteModel->getSiteName();

				echo view('header', array("site_name"=>$strSiteName));		
				echo view('include/sidebar', $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objAdmin->mb_level));
				echo view('user/member', $arrData);
				echo view('footer');
				
			}  else  $this->response->redirect( base_url().'pages/nopermit', 'refresh');



		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}

	public function member_edit($strMemberFid){
		if(is_login())
		{

			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['userdropdownbtn'] = " main-dropdown-active-btn";
			$arrSidebar['userdropdown'] = "style=\"display:block\"";
			$arrSidebar['user_member'] = " sidebar-a-active";

			$objMember = null;
			
			$memberModel = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			//현재 권한에 따른 허용상태 얻기
			$strUid = $this->session->username;
			
			$objAdmin = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objAdmin->mb_level;

			if($objAdmin->mb_level >= LEVEL_EMPLOYEE) { 
				if($strMemberFid > 0)
				{
					$objMember = $memberModel->getMemberByFid($strMemberFid);					
				}
				
				//매장 네임들을 가져오기.
				$arrEmpName = $memberModel->getEmployeeNames($objAdmin, LEVEL_EMPLOYEE);
				$arrData['objMember'] = $objMember;
				$arrData['arrEmpName'] = $arrEmpName;
				$arrData['nAdminLevel'] = $objAdmin->mb_level;

				$strSiteName = $confsiteModel->getSiteName();

				echo view('header', array("site_name"=>$strSiteName));		
				echo view('include/sidebar',  $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objAdmin->mb_level));
				echo view('user/member_edit', $arrData);
				echo view('footer');
			}  else  $this->response->redirect( base_url().'pages/nopermit', 'refresh');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
	}

}