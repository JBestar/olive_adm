<?php
namespace App\Controllers;
use App\Models\SlotPrd_Model;
use App\Models\CasPrd_Model;
use App\Models\ConfSite_Model;

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
		
		$param = [
			'game_name' => "해피볼",
			'game_id' => GAME_HAPPY_BALL,
		];

		$this->load_view_page('bet/pbrealtime', 'bet_realtime', LEVEL_ADMIN, $param);
	}

	public function psrealtime(){
		
		$param = [
			'game_name' => "파워사다리",
			'game_id' => GAME_POWER_LADDER,
		];
		$this->load_view_page('bet/psrealtime', 'bet_realtime', LEVEL_ADMIN, $param);
	}

	public function bbrealtime(){
		
		$param = [
			'game_name' => "보글볼",
			'game_id' => GAME_BOGLE_BALL,
		];

		$this->load_view_page('bet/pbrealtime', 'bet_realtime', LEVEL_ADMIN, $param);
	}

	public function bsrealtime(){
		
		$param = [
			'game_name' => "보글사다리",
			'game_id' => GAME_BOGLE_LADDER,
		];
		$this->load_view_page('bet/psrealtime', 'bet_realtime', LEVEL_ADMIN, $param);
	}

	public function e5realtime(){
		
		$param = [
			'game_name' => "EOS5분",
			'game_id' => GAME_EOS5_BALL,
		];

		$this->load_view_page('bet/pbrealtime', 'bet_realtime', LEVEL_ADMIN, $param);
	}
	
	public function e3realtime(){
		
		$param = [
			'game_name' => "EOS3분",
			'game_id' => GAME_EOS3_BALL,
		];

		$this->load_view_page('bet/pbrealtime', 'bet_realtime', LEVEL_ADMIN, $param);
	}

	public function c5realtime(){
		
		$param = [
			'game_name' => "코인5분",
			'game_id' => GAME_COIN5_BALL,
		];

		$this->load_view_page('bet/pbrealtime', 'bet_realtime', LEVEL_ADMIN, $param);
	}
	
	public function c3realtime(){
		
		$param = [
			'game_name' => "코인3분",
			'game_id' => GAME_COIN3_BALL,
		];

		$this->load_view_page('bet/pbrealtime', 'bet_realtime', LEVEL_ADMIN, $param);
	}

	public function pbhistory(){
		$param = [
			'game_name' => "해피볼",
			'game_id' => GAME_HAPPY_BALL,
		];
		$this->load_view_page('bet/pbhistory', 'bet_history', LEVEL_MIN, $param);
	}

	public function pshistory(){
		$param = [
			'game_name' => "파워사다리",
			'game_id' => GAME_POWER_LADDER,
		];
		$this->load_view_page('bet/pshistory', 'bet_history', LEVEL_MIN, $param);
	}
	
	public function cshistory(){
		$modelCasprd = new CasPrd_Model();
		$arrPrd = [];
		$modelCasprd->gets();

		$confsiteModel = new ConfSite_Model();
		$confs = $this->getSiteConf($confsiteModel);
		if(!$confs["evol_deny"]){
			$arrPrd +=  $modelCasprd->gets(GAME_CASINO_EVOL);
		}
		if(!isEBalMode() && !$confs["cas_deny"]){
			$arrKgon =  $modelCasprd->gets(GAME_CASINO_KGON);
			foreach($arrKgon as $objPrd){
				array_push($arrPrd, $objPrd);
			}
		}

		$param = [
			'prds' => $arrPrd,
			'game_name' => "카지노",
			'game_id' => GAME_CASINO_EVOL,
		];
		$this->load_view_page('bet/cshistory', 'bet_history', 0, $param);
	}
	
	public function ebalhistory(){

		$param = [
			'game_name' => "밸런스내역",
			'game_id' => GAME_CASINO_EVOL,
		];
		$this->load_view_page('ebal/ebalhistory', 'conf_ebal', LEVEL_ADMIN, $param);
	}
	
	public function eordroom(){

		$param = [
			'game_name' => "실시간",
			'game_id' => GAME_CASINO_EVOL,
		];
		$this->load_view_page('ebal/eordroom', 'conf_ebal', LEVEL_ADMIN, $param);
	}

	public function ebethistory(){

		$param = [
			'game_name' => "배팅내역",
			'game_id' => GAME_CASINO_EVOL,
		];
		$this->load_view_page('ebal/ebethistory', 'conf_ebal', LEVEL_ADMIN, $param);
	}

	public function bbhistory(){
		
		$param = [
			'game_name' => "보글볼",
			'game_id' => GAME_BOGLE_BALL,
		];
		$this->load_view_page('bet/pbhistory', 'bet_history', LEVEL_MIN, $param);
	}

	public function bshistory(){
		$param = [
			'game_name' => "보글사다리",
			'game_id' => GAME_BOGLE_LADDER,
		];
		$this->load_view_page('bet/pshistory', 'bet_history', LEVEL_MIN, $param);
	}
	
	public function e5history(){
		
		$param = [
			'game_name' => "EOS5분",
			'game_id' => GAME_EOS5_BALL,
		];
		$this->load_view_page('bet/pbhistory', 'bet_history', LEVEL_MIN, $param);
	}
	
	public function e3history(){
		
		$param = [
			'game_name' => "EOS3분",
			'game_id' => GAME_EOS3_BALL,
		];
		$this->load_view_page('bet/pbhistory', 'bet_history', LEVEL_MIN, $param);
	}

	public function c5history(){
		
		$param = [
			'game_name' => "코인5분",
			'game_id' => GAME_COIN5_BALL,
		];
		$this->load_view_page('bet/pbhistory', 'bet_history', LEVEL_MIN, $param);
	}

	
	public function c3history(){
		
		$param = [
			'game_name' => "코인3분",
			'game_id' => GAME_COIN3_BALL,
		];
		$this->load_view_page('bet/pbhistory', 'bet_history', LEVEL_MIN, $param);
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
		if($_ENV['app.type'] == APPTYPE_4 || $_ENV['app.type'] == APPTYPE_5)
			$gameId = GAME_SLOT_3;
		else if($_ENV['app.type'] == APPTYPE_6 || $_ENV['app.type'] == APPTYPE_7)
			$gameId = GAME_SLOT_4;
		else if($_ENV['app.type'] == APPTYPE_8 || $_ENV['app.type'] == APPTYPE_9)
			$gameId = GAME_SLOT_5;

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
		if($_ENV['app.type'] == APPTYPE_2)
			$arrPrd = $modelSlotprd->gets(GAME_SLOT_2);
		else if($_ENV['app.type'] == APPTYPE_5)
			$arrPrd = $modelSlotprd->gets(GAME_SLOT_3);
		else if($_ENV['app.type'] == APPTYPE_7)
			$arrPrd = $modelSlotprd->gets(GAME_SLOT_4);
		else if($_ENV['app.type'] == APPTYPE_9)
			$arrPrd = $modelSlotprd->gets(GAME_SLOT_5);
		else
			$arrPrd = $modelSlotprd->gets(GAME_SLOT_1);
		
		$param = [
			'game_name' => "슬롯",
			'game_id' => $gameId,
			'prds' => $arrPrd,
		];

		$this->load_view_page('bet/slhistory', 'bet_history', 0, $param);
	}

	public function allcalculate(){
		$param = [
			'game_id' => 0,
		];
		$this->load_view_page('bet/calculate_all', 'bet_calculate', 0, $param);
	}

	public function pbcalculate(){
		
		$param = [
			'game_name' => "해피볼",
			'game_id' => GAME_HAPPY_BALL,
		];
		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_MIN, $param);
	}


	public function pscalculate(){
		$param = [
			'game_name' => "파워사다리",
			'game_id' => GAME_POWER_LADDER,
		];
		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_MIN, $param);
	}
	
	public function cscalculate(){
		$param = [
			'game_name' => "카지노",
			'game_id' => GAME_CASINO_EVOL,
		];
		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_MIN, $param);
	}

	public function xslcalculate(){
		$param = [
			'game_name' => "정품슬롯",
			'game_id' => GAME_SLOT_1,
		];

		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_ADMIN, $param);
	}
	
	public function fslcalculate(){
		$gameId = GAME_SLOT_2;
		if($_ENV['app.type'] == APPTYPE_4)
			$gameId = GAME_SLOT_3;
		else if($_ENV['app.type'] == APPTYPE_6)
			$gameId = GAME_SLOT_4;
		else if($_ENV['app.type'] == APPTYPE_8)
			$gameId = GAME_SLOT_5;

		$param = [
			'game_name' => "네츄럴슬롯",
			'game_id' => $gameId,
		];

		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_ADMIN, $param);
	}

	public function slcalculate(){
		$param = [
			'game_name' => "슬롯",
			'game_id' => GAME_SLOT_12,
		];

		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_MIN, $param);
	}

	public function bbcalculate(){
		$param = [
			'game_name' => "보글볼",
			'game_id' => GAME_BOGLE_BALL,
		];
		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_MIN, $param);
	}

	public function bscalculate(){
		$param = [
			'game_name' => "보글사다리",
			'game_id' => GAME_BOGLE_LADDER,
		];
		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_MIN, $param);
	}
	
	public function e5calculate(){
		$param = [
			'game_name' => "EOS5분",
			'game_id' => GAME_EOS5_BALL,
		];
		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_MIN, $param);
	}
	
	public function e3calculate(){
		$param = [
			'game_name' => "EOS3분",
			'game_id' => GAME_EOS3_BALL,
		];
		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_MIN, $param);
	}
	
	public function c5calculate(){
		$param = [
			'game_name' => "코인5분",
			'game_id' => GAME_COIN5_BALL,
		];
		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_MIN, $param);
	}
	
	public function c3calculate(){
		$param = [
			'game_name' => "코인3분",
			'game_id' => GAME_COIN3_BALL,
		];
		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_MIN, $param);
	}

}