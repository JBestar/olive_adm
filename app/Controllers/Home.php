<?php 
namespace App\Controllers;

use App\Models\ConfSite_Model;
use App\Models\SlotPrd_Model;
use App\Models\CasRoom_Model;

class Home extends StdController
{
	public function index()
	{
		if(is_login())
		{		
			return $this->response->redirect($_ENV['app.furl'].'home/conf_password');
		}
		else {
			return $this->response->redirect($_ENV['app.furl'].'pages/login');
		}	
	}
	
	public function conf_site(){
		$confsiteModel = new ConfSite_Model();
		$arrConfig = $confsiteModel->where('conf_id < '.CONF_QNA_DENY)->findAll();		
		$this->load_view_page('home/conf_siteinfo', 'conf_site', LEVEL_ADMIN, ['arrConfig' => $arrConfig]);	
	}

	
	public function conf_maintain(){
		$confsiteModel = new ConfSite_Model();
		$objConfig = $confsiteModel->getMaintainConfig();	
		$this->load_view_page('home/conf_maintain', 'conf_site', LEVEL_ADMIN, ['objConfig' => $objConfig]);
	}

	// 보험설정
	public function conf_betsite(){
		$this->load_view_page('home/conf_betsite', 'conf_site', LEVEL_ADMIN);	
	}

	public function conf_sound(){
		$this->load_view_page('home/conf_sound', 'conf_site', LEVEL_ADMIN);	
	}

	public function conf_clean(){
		$this->load_view_page('home/conf_clean', 'conf_site', LEVEL_ADMIN);	
	}

	public function conf_powerball(){
		$confsiteModel = new ConfSite_Model();
		$confsiteModel->readBetConf();
		$param = [
			'game_name' => "해피볼",
			'game_id' => GAME_HAPPY_BALL
		];
		$this->load_view_page('home/conf_powerball', 'conf_game', LEVEL_ADMIN, $param);		
	}

	public function conf_powerladder(){
		$param = [
			'game_name' => "파워사다리",
			'game_id' => GAME_POWER_LADDER
		];
		$this->load_view_page('home/conf_powerladder', 'conf_game', LEVEL_ADMIN, $param);
	}


	public function conf_bogleball(){
		$confsiteModel = new ConfSite_Model();
		$confsiteModel->readBetConf();
		
		$param = [
			'game_name' => "보글볼",
			'game_id' => GAME_BOGLE_BALL
		];
		$this->load_view_page('home/conf_powerball', 'conf_game', LEVEL_ADMIN, $param);
	}

	
	public function conf_bogleladder(){
		$param = [
			'game_name' => "보글사다리",
			'game_id' => GAME_BOGLE_LADDER
		];
		$this->load_view_page('home/conf_powerladder', 'conf_game', LEVEL_ADMIN, $param);	
	}

	public function conf_eos5ball(){
		$confsiteModel = new ConfSite_Model();
		$confsiteModel->readBetConf();
		
		$param = [
			'game_name' => "EOS5분",
			'game_id' => GAME_EOS5_BALL
		];
		$this->load_view_page('home/conf_powerball', 'conf_game', LEVEL_ADMIN, $param);
	}

	public function conf_eos3ball(){
		$confsiteModel = new ConfSite_Model();
		$confsiteModel->readBetConf();
		
		$param = [
			'game_name' => "EOS3분",
			'game_id' => GAME_EOS3_BALL
		];
		$this->load_view_page('home/conf_powerball', 'conf_game', LEVEL_ADMIN, $param);
	}

	public function conf_coin5ball(){
		$confsiteModel = new ConfSite_Model();
		$confsiteModel->readBetConf();
		
		$param = [
			'game_name' => "코인5분",
			'game_id' => GAME_COIN5_BALL
		];
		$this->load_view_page('home/conf_powerball', 'conf_game', LEVEL_ADMIN, $param);
	}

	public function conf_coin3ball(){
		$confsiteModel = new ConfSite_Model();
		$confsiteModel->readBetConf();
		
		$param = [
			'game_name' => "코인3분",
			'game_id' => GAME_COIN3_BALL
		];
		$this->load_view_page('home/conf_powerball', 'conf_game', LEVEL_ADMIN, $param);
	}
	
	public function conf_evol(){
		$param = [
			'game_name' => "에볼루션",
			'game_id' => GAME_CASINO_EVOL
		];
		$this->load_view_page('home/conf_evol', 'conf_game', LEVEL_ADMIN, $param);	
	}

	public function conf_ebal(){
		$arrType = [
			"10"=> "EVOL365",
			"11"=> "LUCKY",
			"12"=> "VEDA",
			"1"=> "NINE",
			"2"=> "AMAZON",
			"3"=> "AIRLINE",
			"4"=> "NINEBAR",
			"5"=> "CHROMA",
			"6"=> "CITY",
			"7"=> "CJ"
		];

		$param = [
			'game_name' => "밸런스설정",
			'game_id' => GAME_CASINO_EVOL,
			'gamd_types' => $arrType
		];
		$this->load_view_page('ebal/conf_ebal', 'conf_ebal', LEVEL_ADMIN, $param);	
	}

	public function conf_eroom(){
		$param = [
			'game_name' => "방설정",
			'game_id' => GAME_CASINO_EVOL,
		];
		$this->load_view_page('ebal/conf_eroom', 'conf_ebal', LEVEL_ADMIN, $param);	
	}

	public function conf_casino(){
		$param = [
			'game_name' => "호텔카지노",
			'game_id' => GAME_CASINO_KGON
		];
		$this->load_view_page('home/conf_casino', 'conf_game', LEVEL_ADMIN, $param);	
	}

	public function conf_slot_1(){
		$param = [
			'game_name' => "정품슬롯",
			'game_id' => GAME_SLOT_THEPLUS
		];
		$this->load_view_page('home/conf_slot', 'conf_game', LEVEL_ADMIN, $param);	
	}

	public function conf_slot_2(){
		$slprdModel = new SlotPrd_Model();

		$gameId = GAME_SLOT_GSPLAY;
		if($_ENV['app.type'] == APPTYPE_4 || $_ENV['app.type'] == APPTYPE_5)
			$gameId = GAME_SLOT_GOLD;
		else if($_ENV['app.type'] == APPTYPE_6 || $_ENV['app.type'] == APPTYPE_7)
			$gameId = GAME_SLOT_KGON;
		else if($_ENV['app.type'] == APPTYPE_8 || $_ENV['app.type'] == APPTYPE_9)
			$gameId = GAME_SLOT_STAR;

		$param = [
			'game_name' => "네츄럴슬롯",
			'game_id' => $gameId,
			'game_prds' => $slprdModel->getByCode($gameId, $gameId == GAME_SLOT_GSPLAY), 
		];
		$this->load_view_page('home/conf_fslot', 'conf_game', LEVEL_ADMIN, $param);	
	}

	
	public function conf_password(){
		$this->load_view_page('home/conf_password', 'conf_password');
	}

}