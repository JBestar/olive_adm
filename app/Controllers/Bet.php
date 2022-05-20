<?php
namespace App\Controllers;
use App\Models\SlotPrd_Model;
use App\Models\CasPrd_Model;

class Bet extends StdController {

	/**

	 */
	public function index()
	{
				
		if(is_login())
		{
			$this->response->redirect($_ENV['app.furl'].'/bet/pbrealtime', 'refresh');
		}
		else {
			$this->response->redirect( $_ENV['app.furl'].'/pages/login', 'refresh');
		}	
		
	}

	public function pbrealtime(){
		$this->load_view_page('bet/pbrealtime', 'bet_realtime', LEVEL_COMPANY);
	}

	public function psrealtime(){
		$this->load_view_page('bet/psrealtime', 'bet_realtime', LEVEL_COMPANY);
	}

	public function bbrealtime(){
		$this->load_view_page('bet/bbrealtime', 'bet_realtime', LEVEL_COMPANY);
	}

	public function bsrealtime(){
		$this->load_view_page('bet/bsrealtime', 'bet_realtime', LEVEL_COMPANY);
	}

	public function pbhistory(){
		$this->load_view_page('bet/pbhistory', 'bet_history');
	}

	public function pshistory(){
		$this->load_view_page('bet/pshistory', 'bet_history');
	}
	
	public function cshistory(){
		$modelCasprd = new CasPrd_Model();
		$arrPrd = $modelCasprd->gets();

		$param = [
			'prds' => $arrPrd
		];
		$this->load_view_page('bet/cshistory', 'bet_history', 0, $param);
	}
	
	
	public function bbhistory(){
		$this->load_view_page('bet/bbhistory', 'bet_history');
	}

	public function bshistory(){
		$this->load_view_page('bet/bshistory', 'bet_history');
	}
	
	public function xslhistory(){
		$modelSlotprd = new SlotPrd_Model();
		$gameId = GAME_SLOT_1;
		$arrPrd = $modelSlotprd->gets($gameId);

		$param = [
			'game_name' => "정품슬롯",
			'game_id' => $gameId,
			'prds' => $arrPrd,
		];

		$this->load_view_page('bet/slhistory', 'bet_history', LEVEL_ADMIN, $param);
	}

	public function fslhistory(){
		
		$modelSlotprd = new SlotPrd_Model();
		$gameId = GAME_SLOT_2;
		$arrPrd = $modelSlotprd->gets($gameId);

		$param = [
			'game_name' => "네츄럴슬롯",
			'game_id' => $gameId,
			'prds' => $arrPrd,
		];

		$this->load_view_page('bet/slhistory', 'bet_history', LEVEL_ADMIN, $param);
	}

	public function slhistory(){
		
		$modelSlotprd = new SlotPrd_Model();
		$gameId = GAME_SLOT_12;
		if($_ENV['app.type'] == APPTYPE_2){
			$arrPrd = $modelSlotprd->gets(GAME_SLOT_2);
		} else
			$arrPrd = $modelSlotprd->gets(GAME_SLOT_1);
		// $arrPrd2 = $modelSlotprd->gets(GAME_SLOT_2);
		// foreach ($arrPrd2 as $objPrd){
		// 	$arrPrd[] = $objPrd;
		// }
		$param = [
			'game_name' => "슬롯",
			'game_id' => $gameId,
			'prds' => $arrPrd,
		];

		$this->load_view_page('bet/slhistory', 'bet_history', 0, $param);
	}

	public function allcalculate(){
		$this->load_view_page('bet/allcalculate', 'bet_calculate');
	}

	public function pbcalculate(){
		$this->load_view_page('bet/pbcalculate', 'bet_calculate');
	}


	public function pscalculate(){
		$this->load_view_page('bet/pscalculate', 'bet_calculate');
	}
	
	public function cscalculate(){
		$this->load_view_page('bet/cscalculate', 'bet_calculate');
	}

	public function xslcalculate(){
		$param = [
			'game_name' => "정품슬롯",
			'game_id' => GAME_SLOT_1,
		];

		$this->load_view_page('bet/slcalculate', 'bet_calculate', LEVEL_ADMIN, $param);
	}
	
	public function fslcalculate(){
		$param = [
			'game_name' => "네츄럴슬롯",
			'game_id' => GAME_SLOT_2,
		];

		$this->load_view_page('bet/slcalculate', 'bet_calculate', LEVEL_ADMIN, $param);
	}

	public function slcalculate(){
		$param = [
			'game_name' => "슬롯",
			'game_id' => GAME_SLOT_12,
		];

		$this->load_view_page('bet/slcalculate', 'bet_calculate', 0, $param);
	}

	public function bbcalculate(){
		$this->load_view_page('bet/bbcalculate', 'bet_calculate');
	}

	public function bscalculate(){
		$this->load_view_page('bet/bscalculate', 'bet_calculate');
	}
}