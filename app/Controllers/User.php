<?php

namespace App\Controllers;
use App\Models\Member_Model;

class User extends StdController
{

	/**

	 */
	private function user_edit_page($url, $activePage, $memberFid, $employeeLevel, $userLevel){
		if (is_login() === false){
			return $this->response->redirect( base_url().'pages/login', 'refresh');
		}
		
		$memberModel = new Member_Model();
		$strUid = $this->session->user_id;
		$objAdmin = $memberModel->getInfo($strUid);
		$objMember = null;
		if($memberFid > 0)
		{
			$objMember = $memberModel->getMemberByFid($memberFid);					
		}
		$arrEmpName = null;
		if ($userLevel != LEVEL_COMPANY)
			$arrEmpName = $memberModel->getEmployeeNames($objAdmin, $employeeLevel);
		$this->load_view_page(
			$url, 
			$activePage, 
			$userLevel, 
			[
				'objMember' => $objMember, 
				'arrEmpName' => $arrEmpName,
		]);	
		
	}
	public function index()
	{
		if(is_login())
		{
			$this->response->redirect(base_url().'user/member/0', 'refresh');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
		
	}


	public function company()
	{
		$memberModel = new Member_Model();
		$arrCompany = $memberModel->getMemberByLevel(LEVEL_COMPANY, false);
		if (is_null($arrCompany))
			$arrCompany = [];
		$this->load_view_page(
			'user/company', 
			'user_company', 
			LEVEL_COMPANY, 
			[
				'editUrl' => 'user/company_edit',
				'arrMember' => $arrCompany]);	
	}

	public function company_edit($strMemberFid){
		$this->user_edit_page(
			'user/company_edit', 
			'user_company', 
			$strMemberFid, 
			LEVEL_EMPLOYEE, 
			LEVEL_COMPANY);
	}

	public function agency(){
		if (is_login() === false){
			return $this->response->redirect( base_url().'pages/login', 'refresh');
		}
		$memberModel = new Member_Model();
		$strUid = $this->session->user_id;
		$objAdmin = $memberModel->getInfo($strUid);
		$arrAgency = $memberModel->getMemberByEmpFid($objAdmin->mb_fid, LEVEL_AGENCY, $objAdmin->mb_level);
		if (is_null($arrAgency))
			$arrAgency = [];
		$this->load_view_page(
			'user/agency', 
			'user_agency', 
			LEVEL_AGENCY, 
			[
				'editUrl' => 'user/agency_edit',
				'arrMember' => $arrAgency]);	
	}

	public function agency_edit($strMemberFid)
	{
		$this->user_edit_page(
			'user/agency_edit', 
			'user_agency', 
			$strMemberFid, 
			LEVEL_COMPANY,
			LEVEL_AGENCY);
	}

	public function employee(){
		if (is_login() === false){
			return $this->response->redirect( base_url().'pages/login', 'refresh');
		}
		$memberModel = new Member_Model();
		$strUid = $this->session->user_id;
		$objAdmin = $memberModel->getInfo($strUid);
		$arrEmployee = $memberModel->getMemberByEmpFid($objAdmin->mb_fid, LEVEL_EMPLOYEE, $objAdmin->mb_level);
		if(is_null($arrEmployee))
			$arrEmployee = array();

		foreach($arrEmployee as $objMember){
			$objMember->mb_nickname = $memberModel->getFullName($objMember);
		}
		$this->load_view_page(
			'user/employee', 
			'user_employee', 
			LEVEL_EMPLOYEE, 
			[
				'editUrl' => 'user/employee_edit',
				'arrMember' => $arrEmployee, 
			]);
	}

	public function employee_edit($strMemberFid){
		$this->user_edit_page(
			'user/employee_edit', 
			'user_employee', 
			$strMemberFid, 
			LEVEL_AGENCY,
			LEVEL_EMPLOYEE);
	}


	// public function member(){
	// 	if (is_login() === false){
	// 		return $this->response->redirect( base_url().'pages/login', 'refresh');
	// 	}
	// 	$this->load_view_page(
	// 		'user/member', 
	// 		'user_member', 
	// 		LEVEL_EMPLOYEE, 
	// 		['emp_uid' => ""]);
	// }

	function member($strEmpFid){
		if (is_login() === false){
			return $this->response->redirect(base_url().'pages/login', 'refresh');
		}
		$memberModel = new Member_Model();
		$objEmp = $memberModel->find($strEmpFid);
		$strEmpUid = "";
		if ($objEmp != null){
			$strEmpUid = $objEmp['mb_uid'];
		}
		$this->load_view_page(
			'user/member', 
			'user_member', 
			LEVEL_EMPLOYEE, 
			['emp_uid' => $strEmpUid]);
	}

	public function member_edit($strMemberFid){
		$this->user_edit_page(
			'user/member_edit', 
			'user_member', 
			$strMemberFid, 
			LEVEL_EMPLOYEE,
			LEVEL_EMPLOYEE);		
	}
}