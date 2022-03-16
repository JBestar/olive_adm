<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\BbRound_model;
use App\Models\BsRound_model;
use App\Models\Member_Model;
use App\Models\ConfSite_Model;
use App\Models\KsRound_model;
use App\Models\PbRound_model;
use App\Models\PsRound_model;

class Result extends BaseController {

	/**


	 */
	public function index()
	{
		$this->response->redirect( base_url().'result/pbresult', 'refresh');
		
	}

	public function pbresult()
	{		
		if(is_login())
		{			
			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['gameresult'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			$strSiteName = $confsiteModel->getSiteName();

			echo view('header', array("site_name"=>$strSiteName));		
			echo view('include/sidebar', $arrSidebar);
			echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
			echo view('result/pbresult');
			echo view('footer');
			
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}			
	}

	public function pbresult_edit($strRoundFid)
	{		
		if(is_login())
		{			
			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['gameresult'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$pbroundModel = new PbRound_model();

			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			if($objUser->mb_level >= LEVEL_ADMIN){
			
				$objRound = null;

				if($strRoundFid > 0){
					$objRound = $pbroundModel->get($strRoundFid);
					if(is_null($objRound)){
						$this->response->redirect( base_url().'pages/nopermit', 'refresh');		
					}									
				} else if($strRoundFid == 0){
					$objRound = null;					
				} else $this->response->redirect( base_url().'pages/nopermit', 'refresh');		
									
				$strSiteName = $confsiteModel->getSiteName();

				echo view('header', array("site_name"=>$strSiteName));		
				echo view('include/sidebar', $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
				echo view('result/pbresult_edit', array("objRound"=>$objRound));
				echo view('footer');
				
			} else $this->response->redirect( base_url().'pages/nopermit', 'refresh');
			
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}			
	}


	public function psresult()
	{		
		if(is_login())
		{			
			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['gameresult'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			$strSiteName = $confsiteModel->getSiteName();

			echo view('header', array("site_name"=>$strSiteName));		
			echo view('include/sidebar', $arrSidebar);
			echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
			echo view('result/psresult');
			echo view('footer');
			
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}			
	}

	public function psresult_edit($strRoundFid)
	{		
		if(is_login())
		{			
			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['gameresult'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();

			$psroundModel = new PsRound_model();;

			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			if($objUser->mb_level >= LEVEL_ADMIN){
			
				$objRound = null;									
				if($strRoundFid > 0){
					$objRound = $psroundModel->get($strRoundFid);
					if(is_null($objRound)){
						$this->response->redirect( base_url().'pages/nopermit', 'refresh');		
					}									
				} else if($strRoundFid == 0){
					$objRound = null;					
				} else $this->response->redirect( base_url().'pages/nopermit', 'refresh');		
				$strSiteName = $confsiteModel->getSiteName();

				echo view('header', array("site_name"=>$strSiteName));		
				echo view('include/sidebar', $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
				echo view('result/psresult_edit', array("objRound"=>$objRound));
				echo view('footer');
				
			} else $this->response->redirect( base_url().'pages/nopermit', 'refresh');
			
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}			
	}



	public function ksresult()
	{		
		if(is_login())
		{			
			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['gameresult'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			$strSiteName = $confsiteModel->getSiteName();

			echo view('header', array("site_name"=>$strSiteName));		
			echo view('include/sidebar', $arrSidebar);
			echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
			echo view('result/ksresult');
			echo view('footer');
			
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}			
	}


	public function ksresult_edit($strRoundFid)
	{		
		if(is_login())
		{			
			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['gameresult'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$ksroundModel = new KsRound_model();

			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			if($objUser->mb_level >= LEVEL_ADMIN){
			
				$objRound = null;									
				if($strRoundFid > 0){
					$objRound = $ksroundModel->get($strRoundFid);
					if(is_null($objRound)){
						$this->response->redirect( base_url().'pages/nopermit', 'refresh');		
					}									
				} else if($strRoundFid == 0){
					$objRound = null;					
				} else $this->response->redirect( base_url().'pages/nopermit', 'refresh');		
						
				$strSiteName = $confsiteModel->getSiteName();

				echo view('header', array("site_name"=>$strSiteName));		
				echo view('include/sidebar', $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
				echo view('result/ksresult_edit', array("objRound"=>$objRound));
				echo view('footer');
				
			} else $this->response->redirect( base_url().'pages/nopermit', 'refresh');
			
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}			
	}


	public function bbresult()
	{		
		if(is_login())
		{			
			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['gameresult'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			$strSiteName = $confsiteModel->getSiteName();

			echo view('header', array("site_name"=>$strSiteName));		
			echo view('include/sidebar', $arrSidebar);
			echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
			echo view('result/bbresult');
			echo view('footer');
			
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}			
	}

	public function bbresult_edit($strRoundFid)
	{		
		if(is_login())
		{			
			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['gameresult'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$bbroundModel = new BbRound_model();

			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			if($objUser->mb_level >= LEVEL_ADMIN){
			
				$objRound = null;

				if($strRoundFid > 0){
					$objRound = $bbroundModel->get($strRoundFid);
					if(is_null($objRound)){
						$this->response->redirect( base_url().'pages/nopermit', 'refresh');		
					}									
				} else if($strRoundFid == 0){
					$objRound = null;					
				} else $this->response->redirect( base_url().'pages/nopermit', 'refresh');		
									
				$strSiteName = $confsiteModel->getSiteName();

				echo view('header', array("site_name"=>$strSiteName));		
				echo view('include/sidebar', $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
				echo view('result/bbresult_edit', array("objRound"=>$objRound));
				echo view('footer');
				
			} else $this->response->redirect( base_url().'pages/nopermit', 'refresh');
			
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}			
	}


	public function bsresult()
	{		
		if(is_login())
		{			
			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['gameresult'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			$strSiteName = $confsiteModel->getSiteName();

			echo view('header', array("site_name"=>$strSiteName));		
			echo view('include/sidebar', $arrSidebar);
			echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
			echo view('result/bsresult');
			echo view('footer');
			
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}			
	}

	public function bsresult_edit($strRoundFid)
	{		
		if(is_login())
		{			
			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['gameresult'] = " sidebar-a-active";

			$memberModel  = new Member_Model();
			$confsiteModel = new ConfSite_Model();
			$bsroundModel = new BsRound_model();

			$strUid = $this->session->username;
			$objUser = $memberModel->getInfo($strUid);
			$arrSidebar['mb_level'] = $objUser->mb_level;

			if($objUser->mb_level >= LEVEL_ADMIN){
			
				$objRound = null;									
				if($strRoundFid > 0){
					$objRound = $bsroundModel->get($strRoundFid);
					if(is_null($objRound)){
						$this->response->redirect( base_url().'pages/nopermit', 'refresh');		
					}									
				} else if($strRoundFid == 0){
					$objRound = null;					
				} else $this->response->redirect( base_url().'pages/nopermit', 'refresh');		
				$strSiteName = $confsiteModel->getSiteName();

				echo view('header', array("site_name"=>$strSiteName));		
				echo view('include/sidebar', $arrSidebar);
				echo view('include/main_navbar', array("mb_level"=>$objUser->mb_level));
				echo view('result/bsresult_edit', array("objRound"=>$objRound));
				echo view('footer');
				
			} else $this->response->redirect( base_url().'pages/nopermit', 'refresh');
			
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}			
	}


	public function pbbetchange($strDate, $strRoundNo)
	{		
		if(is_login())
		{			
			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['gameedit'] = " sidebar-a-active";

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
				echo view('result/pbbet_change', array("strDate"=>$strDate, "strRoundNo"=>$strRoundNo));
				echo view('footer');
				
			} else $this->response->redirect( base_url().'pages/nopermit', 'refresh');
			
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}			
	}

	public function psbetchange($strDate, $strRoundNo)
	{		
		if(is_login())
		{			
			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['gameedit'] = " sidebar-a-active";

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
				echo view('result/psbet_change', array("strDate"=>$strDate, "strRoundNo"=>$strRoundNo));
				echo view('footer');
				
			} else $this->response->redirect( base_url().'pages/nopermit', 'refresh');
			
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}			
	}


	public function ksbetchange($strDate, $strRoundNo)
	{		
		if(is_login())
		{			
			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['gameedit'] = " sidebar-a-active";

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
				echo view('result/ksbet_change', array("strDate"=>$strDate, "strRoundNo"=>$strRoundNo));
				echo view('footer');
				
			} else $this->response->redirect( base_url().'pages/nopermit', 'refresh');
			
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}			
	}

	public function bbbetchange($strDate, $strRoundNo)
	{		
		if(is_login())
		{			
			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['gameedit'] = " sidebar-a-active";

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
				echo view('result/bbbet_change', array("strDate"=>$strDate, "strRoundNo"=>$strRoundNo));
				echo view('footer');
				
			} else $this->response->redirect( base_url().'pages/nopermit', 'refresh');
			
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}			
	}

	public function bsbetchange($strDate, $strRoundNo)
	{		
		if(is_login())
		{			
			$arrSidebar = getSidebarLinkArray();
			$arrSidebar['gameedit'] = " sidebar-a-active";

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
				echo view('result/bsbet_change', array("strDate"=>$strDate, "strRoundNo"=>$strRoundNo));
				echo view('footer');
				
			} else $this->response->redirect( base_url().'pages/nopermit', 'refresh');
			
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}			
	}
}