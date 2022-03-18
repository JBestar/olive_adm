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

class Result extends StdController {

	/**


	 */
	public function index()
	{
		$this->response->redirect( base_url().'result/pbresult', 'refresh');
		
	}
	private function result_edit_page($betModel, $roundFid, $url, $activePage, $userLevel){
		$objRound = null;
		$memberModel  = new Member_Model();
		$strUid = $this->session->username;
		$objUser = $memberModel->getInfo($strUid);
		if($objUser->mb_level >= $userLevel)
		{
			$objRound = null;
			if($roundFid > 0){
				$objRound = $betModel->get($roundFid);
				if(is_null($objRound)){
					$this->response->redirect( base_url().'pages/nopermit', 'refresh');		
				}									
			} else if($roundFid == 0){
				$objRound = null;					
			} else $this->response->redirect( base_url().'pages/nopermit', 'refresh');	
			$this->load_view_page($url, $activePage, LEVEL_ADMIN, [
				'objRound' => $objRound
			]);	
		}	
	}
	private function result_change_page($url, $date, $roundNo){
		$this->load_view_page($url, 'gameedit', LEVEL_ADMIN, [
			'strDate' => $date, 
			'strRoundNo' => $roundNo
		]);	
	}
	public function pbresult()
	{		
		$this->load_view_page('result/pbresult', 'gameresult');			
	}

	public function pbresult_edit($strRoundFid)
	{
		$pbroundModel = new PbRound_model();
		$this->result_edit_page(
			$pbroundModel, 
			$strRoundFid, 
			'result/pbresult_edit', 
			'gameresult',
			LEVEL_ADMIN);
		return;	
	}


	public function psresult()
	{
		$this->load_view_page('result/psresult', 'gameresult');		
	}

	public function psresult_edit($strRoundFid)
	{
		$psroundModel = new PsRound_model();
		$this->result_edit_page(
			$psroundModel, 
			$strRoundFid, 
			'result/psresult_edit', 
			'gameresult', 
			LEVEL_ADMIN);	
	}



	public function ksresult()
	{
		$this->load_view_page('result/ksresult', 'gameresult');			
	}


	public function ksresult_edit($strRoundFid)
	{		
		$ksroundModel = new KsRound_model();
		$this->result_edit_page(
			$ksroundModel, 
			$strRoundFid, 
			'result/ksresult_edit', 
			'gameresult', 
			LEVEL_ADMIN);		
	}


	public function bbresult()
	{
		$this->load_view_page('result/bbresult', 'gameresult');			
	}

	public function bbresult_edit($strRoundFid)
	{
		$bbroundModel = new BbRound_model();
		$this->result_edit_page(
			$bbroundModel, 
			$strRoundFid, 
			'result/bbresult_edit', 
			'gameresult', 
			LEVEL_ADMIN);		
	}


	public function bsresult()
	{
		$this->load_view_page('result/bsresult', 'gameresult');
	}

	public function bsresult_edit($strRoundFid)
	{
		$bsroundModel = new BsRound_model();
		$this->result_edit_page(
			$bsroundModel, 
			$strRoundFid, 
			'result/bsresult_edit', 
			'gameresult', 
			LEVEL_ADMIN);	
	}


	public function pbbetchange($strDate, $strRoundNo)
	{
		$this->result_change_page('result/pbbet_change', $strDate, $strRoundNo);				
	}

	public function psbetchange($strDate, $strRoundNo)
	{
		$this->result_change_page('result/psbet_change', $strDate, $strRoundNo);		
	}


	public function ksbetchange($strDate, $strRoundNo)
	{		
		$this->result_change_page('result/ksbet_change', $strDate, $strRoundNo);		
	}

	public function bbbetchange($strDate, $strRoundNo)
	{
		$this->result_change_page('result/bbbet_change', $strDate, $strRoundNo);		
	}

	public function bsbetchange($strDate, $strRoundNo)
	{
		$this->result_change_page('result/bsbet_change', $strDate, $strRoundNo);			
	}
}