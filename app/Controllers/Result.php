<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\BbRound_Model;
use App\Models\BsRound_Model;
use App\Models\PbRound_Model;
use App\Models\PsRound_Model;
use App\Models\EosRound_Model;

class Result extends StdController {

	/**


	 */
	public function index()
	{
		$this->response->redirect( $_ENV['app.furl'].'/result/pbresult');
		
	}
	private function result_edit_page($betModel, $roundFid, $url, $activePage, $userLevel, $arrAddData = null){
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

			$arrData = [ 'objRound' => $objRound ];
			
			if ($arrAddData !== null)
				$arrData = $arrData + $arrAddData;
			$this->load_view_page($url, $activePage, LEVEL_ADMIN, $arrData);	
		}	
	}
	private function result_change_page($url, $date, $roundNo, $arrAddData = null){
		
		$arrData = [ 'strDate' => $date, 'strRoundNo' => $roundNo ];
			
		if ($arrAddData !== null)
			$arrData = $arrData + $arrAddData;

		$this->load_view_page($url, 'gameedit', LEVEL_ADMIN, $arrData);	
	}
	public function pbresult()
	{	
		$param = [
			'game_name' => "파워볼",
			'game_id' => GAME_POWER_BALL,
		];	
		$this->load_view_page('result/pbresult', 'gameresult', LEVEL_ADMIN, $param);			
	}

	public function pbresult_edit($strRoundFid)
	{
		$param = [
			'game_name' => "파워볼",
			'game_id' => GAME_POWER_BALL,
		];	
		$roundModel = new PbRound_Model();
		$this->result_edit_page(
			$roundModel, 
			$strRoundFid, 
			'result/pbresult_edit', 
			'gameresult',
			LEVEL_ADMIN,
			$param);
		return;	
	}


	public function psresult()
	{
		$param = [
			'game_name' => "파워사다리",
			'game_id' => GAME_POWER_LADDER,
		];
		$this->load_view_page('result/psresult', 'gameresult', LEVEL_ADMIN, $param);	
	}

	public function psresult_edit($strRoundFid)
	{
		$param = [
			'game_name' => "파워사다리",
			'game_id' => GAME_POWER_LADDER,
		];	
		$roundModel = new PsRound_Model();
		$this->result_edit_page(
			$roundModel, 
			$strRoundFid, 
			'result/psresult_edit', 
			'gameresult', 
			LEVEL_ADMIN,
			$param);	
	}

	public function bbresult()
	{
		$param = [
			'game_name' => "보글볼",
			'game_id' => GAME_BOGLE_BALL,
		];
		$this->load_view_page('result/bbresult', 'gameresult', LEVEL_ADMIN, $param);			
	}

	public function bbresult_edit($strRoundFid)
	{
		$param = [
			'game_name' => "보글볼",
			'game_id' => GAME_BOGLE_BALL,
		];

		$roundModel = new BbRound_Model();
		$this->result_edit_page(
			$roundModel, 
			$strRoundFid, 
			'result/bbresult_edit', 
			'gameresult', 
			LEVEL_ADMIN,
			$param);		
	}


	public function bsresult()
	{
		$param = [
			'game_name' => "보글사다리",
			'game_id' => GAME_BOGLE_LADDER,
		];	
		$this->load_view_page('result/psresult', 'gameresult', LEVEL_ADMIN, $param);	
	}

	public function bsresult_edit($strRoundFid)
	{
		$param = [
			'game_name' => "보글사다리",
			'game_id' => GAME_BOGLE_BALL,
		];
		$roundModel = new BsRound_Model();
		$this->result_edit_page(
			$roundModel, 
			$strRoundFid, 
			'result/psresult_edit', 
			'gameresult', 
			LEVEL_ADMIN,
			$param);	
	}

	public function e5result()
	{	
		$param = [
			'game_name' => "EOS5분파워볼",
			'game_id' => GAME_EOS5_BALL,
		];	
		$this->load_view_page('result/pbresult', 'gameresult', LEVEL_ADMIN, $param);			
	}

	public function e5result_edit($strRoundFid)
	{
		$param = [
			'game_name' => "EOS5분파워볼",
			'game_id' => GAME_EOS5_BALL,
		];
		
		$roundModel = new EosRound_Model();
		$roundModel->setType($param['game_id']);
		$this->result_edit_page(
			$roundModel, 
			$strRoundFid, 
			'result/bbresult_edit', 
			'gameresult', 
			LEVEL_ADMIN,
			$param);	
	}
	public function e3result()
	{	
		$param = [
			'game_name' => "EOS3분파워볼",
			'game_id' => GAME_EOS3_BALL,
		];	
		$this->load_view_page('result/pbresult', 'gameresult', LEVEL_ADMIN, $param);			
	}

	public function e3result_edit($strRoundFid)
	{
		$param = [
			'game_name' => "EOS3분파워볼",
			'game_id' => GAME_EOS3_BALL,
		];
		$roundModel = new EosRound_Model();
		$roundModel->setType($param['game_id']);
		$this->result_edit_page(
			$roundModel, 
			$strRoundFid, 
			'result/bbresult_edit', 
			'gameresult', 
			LEVEL_ADMIN,
			$param);	
	}

	public function pbbetchange($strDate, $strRoundNo)
	{
		$param = [
			'game_name' => "파워볼",
			'game_id' => GAME_POWER_BALL,
		];
		$this->result_change_page('result/pbbet_change', $strDate, $strRoundNo, $param);				
	}

	public function psbetchange($strDate, $strRoundNo)
	{
		$param = [
			'game_name' => "파워사다리",
			'game_id' => GAME_POWER_LADDER,
		];
		$this->result_change_page('result/psbet_change', $strDate, $strRoundNo, $param);		
	}

	public function bbbetchange($strDate, $strRoundNo)
	{
		$param = [
			'game_name' => "보글볼",
			'game_id' => GAME_BOGLE_BALL,
		];
		$this->result_change_page('result/pbbet_change', $strDate, $strRoundNo, $param);		
	}

	public function bsbetchange($strDate, $strRoundNo)
	{
		$param = [
			'game_name' => "보글사다리",
			'game_id' => GAME_BOGLE_LADDER,
		];	
		$this->result_change_page('result/psbet_change', $strDate, $strRoundNo, $param);			
	}
	
	public function e5betchange($strDate, $strRoundNo)
	{
		$param = [
			'game_name' => "EOS5분파워볼",
			'game_id' => GAME_EOS5_BALL,
		];	
		$this->result_change_page('result/pbbet_change', $strDate, $strRoundNo, $param);		
	}

	public function e3betchange($strDate, $strRoundNo)
	{
		$param = [
			'game_name' => "EOS3분파워볼",
			'game_id' => GAME_EOS3_BALL,
		];
		$this->result_change_page('result/pbbet_change', $strDate, $strRoundNo, $param);		
	}
}