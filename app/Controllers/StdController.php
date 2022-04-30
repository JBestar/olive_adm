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
			$arrData += $this->getSiteConf($confsiteModel);
			
			if ($arrAddData !== null)
				$arrData = $arrData + $arrAddData;
			
			if ($userLevel == 0){
				echo view($url, $arrData);
			}
			else {
				if($objUser->mb_level >= $userLevel){
					echo view($url, $arrData);
				} else  $this->response->redirect( $_ENV['app.furl'].'/pages/nopermit');
			}
		}
		else {
			$this->response->redirect( $_ENV['app.furl'].'/pages/login');
		}
	}
}
