<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\BbRound_Model;
use App\Models\BsRound_Model;
use App\Models\PbRound_Model;
use App\Models\PsRound_Model;

class Result extends StdController {

	/**


	 */
	public function index()
	{
		$this->response->redirect( $_ENV['app.furl'].'/result/pbresult');
		
	}
	private function result_edit_page($betModel, $roundFid, $url, $activePage, $userLevel){
		if (is_login() === false){
			return $this->response->redirect( $_ENV['app.furl'].'/pages/login');
		}
		$objRound = null;
		
		$strUid = $this->session->user_id;
		$objUser = $this->modelMember->getInfo($strUid);
		if($objUser->mb_level >= $userLevel)
		{
			$objRound = null;
			if($roundFid > 0){
				$objRound = $betModel->get($roundFid);
				if(is_null($objRound)){
					$this->response->redirect( $_ENV['app.furl'].'/pages/nopermit');		
				}									
			} else if($roundFid == 0){
				$objRound = null;					
			} else $this->response->redirect( $_ENV['app.furl'].'/pages/nopermit');	
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
		$pbroundModel = new PbRound_Model();
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
		$psroundModel = new PsRound_Model();
		$this->result_edit_page(
			$psroundModel, 
			$strRoundFid, 
			'result/psresult_edit', 
			'gameresult', 
			LEVEL_ADMIN);	
	}

	public function bbresult()
	{
		$this->load_view_page('result/bbresult', 'gameresult');			
	}

	public function bbresult_edit($strRoundFid)
	{
		$bbroundModel = new BbRound_Model();
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
		$bsroundModel = new BsRound_Model();
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