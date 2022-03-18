<?php

namespace App\Controllers;

use App\Models\ConfSite_Model;
use App\Models\Member_Model;

class StdController extends BaseController
{
    protected function load_view_page($url, $activePage, $userLevel = 0, $arrAddData = null)
	{
		if(is_login())
		{

			$arrData = getSidebarLinkArray();
			$arrData['confdropdownbtn'] = " main-dropdown-active-btn";
			$arrData['confdropdown'] = "style=\"display:block\"";
			$arrData[$activePage] = " sidebar-a-active";
			
			
			$memberModel = new Member_Model();
			$confsiteModel = new ConfSite_Model();

			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrData['mb_level'] = $objUser->mb_level;
			$strSiteName = $confsiteModel->getSiteName();
			$arrData['site_name'] = $strSiteName;
			if ($arrAddData !== null)
				$arrData = $arrData + $arrAddData;
			// echo view('header', array("site_name"=>$strSiteName));	
			// echo view('include/sidebar', $arrData);
			// echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
			
			if ($userLevel === 0){
				echo view($url, $arrData);
			}
			else {
				if($objUser->mb_level >= $userLevel){
					echo view($url, $arrData);
				} else  $this->response->redirect( base_url().'pages/nopermit', 'refresh');
			}
			// echo view('footer');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}
	}
}
