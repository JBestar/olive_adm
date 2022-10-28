<?php 
namespace App\Controllers;

use App\Models\ConfSite_Model;
use App\Models\SlotPrd_Model;

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
		$arrConfig = $confsiteModel->findAll();		
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
			'game_name' => "파워볼",
			'game_id' => GAME_POWER_BALL
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
			'game_name' => "보글파워볼",
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
		$param = [
			'game_name' => "밸런스설정",
			'game_id' => GAME_CASINO_EVOL
		];
		$this->load_view_page('ebal/conf_ebal', 'conf_ebal', LEVEL_ADMIN, $param);	
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
			'game_id' => GAME_SLOT_1
		];
		$this->load_view_page('home/conf_slot', 'conf_game', LEVEL_ADMIN, $param);	
	}

	public function conf_slot_2(){
		$slprdModel = new SlotPrd_Model();

		$param = [
			'game_name' => "네츄럴슬롯",
			'game_id' => GAME_SLOT_2,
			'game_prds' => $slprdModel->getByCode(GAME_SLOT_2), 
		];
		$this->load_view_page('home/conf_fslot', 'conf_game', LEVEL_ADMIN, $param);	
	}

	
	public function conf_password(){
		$this->load_view_page('home/conf_password', 'conf_password');
	}

}