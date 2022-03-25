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

			$strUid = $this->session->user_id;
			$objUser = $memberModel->getInfo($strUid);
			$arrData['mb_level'] = $objUser->mb_level;
			$strSiteName = $confsiteModel->getSiteName();
			$arrData['site_name'] = $strSiteName;
			if ($arrAddData !== null)
				$arrData = $arrData + $arrAddData;
			
			if ($userLevel === 0){
				echo view($url, $arrData);
			}
			else {
				if($objUser->mb_level >= $userLevel){
					echo view($url, $arrData);
				} else  $this->response->redirect( base_url().'pages/nopermit', 'refresh');
			}
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}
	}
}
