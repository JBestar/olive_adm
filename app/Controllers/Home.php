<?php 
namespace App\Controllers;

use App\Models\ConfSite_Model;

class Home extends StdController
{
	public function index()
	{
		if(is_login())
		{		
			return $this->response->redirect(base_url().'home/conf_password', 'refresh');
		}
		else {
			return $this->response->redirect(base_url().'pages/login', 'refresh');
		}	
	}
	
	public function conf_site(){		
		$confsiteModel = new ConfSite_Model();
		$arrConfig = $confsiteModel->findAll();		
		$this->load_view_page('home/conf_site', 'conf_site', LEVEL_ADMIN, ['arrConfig' => $arrConfig]);	
	}

	
	public function conf_maintain(){
		$confsiteModel = new ConfSite_Model();
		$arrConfig = $confsiteModel->getMaintainConfig();	
		$this->load_view_page('home/conf_maintain', 'conf_site', LEVEL_ADMIN, ['arrConfig' => $arrConfig]);
	}

	// 보험설정
	public function conf_betsite(){
		$this->load_view_page('home/conf_betsite', 'conf_site', LEVEL_ADMIN);	
	}

	public function conf_powerball(){
		$param = [
			'game_name' => "파워볼",
			'game_id' => GAME_POWER_BALL,
			'active_pb' => 'active',
			'active_bb' => ''
		];
		$this->load_view_page('home/conf_powerball', 'conf_game', LEVEL_ADMIN, $param);		
	}

	public function conf_powerladder(){
		$param = [
			'game_name' => "파워사다리",
			'game_id' => GAME_POWER_LADDER,
			'active_ps' => 'active',
			'active_bs' => ''
		];
		$this->load_view_page('home/conf_powerladder', 'conf_game', LEVEL_ADMIN, $param);
	}


	public function conf_bogleball(){
		$param = [
			'game_name' => "보글파워볼",
			'game_id' => GAME_BOGLE_BALL,
			'active_pb' => '',
			'active_bb' => 'active'
		];
		$this->load_view_page('home/conf_powerball', 'conf_game', LEVEL_ADMIN, $param);
	}

	
	public function conf_bogleladder(){
		$param = [
			'game_name' => "보글사다리",
			'game_id' => GAME_BOGLE_LADDER,
			'active_ps' => '',
			'active_bs' => 'active'
		];
		$this->load_view_page('home/conf_powerladder', 'conf_game', LEVEL_ADMIN, $param);	
	}

	
	public function conf_evol(){
		$param = [
			'game_name' => "에볼루션",
			'game_id' => GAME_CASINO_EVOL,
			'active_ev' => 'active',
			'active_sl1' => '',
			'active_sl2' => ''
		];
		$this->load_view_page('home/conf_casino', 'conf_game', LEVEL_ADMIN, $param);	
	}

	public function conf_slot_1(){
		$param = [
			'game_name' => "슬롯",
			'game_id' => GAME_SLOT_1,
			'active_ev' => '',
			'active_sl1' => 'active',
			'active_sl2' => ''
		];
		$this->load_view_page('home/conf_casino', 'conf_game', LEVEL_ADMIN, $param);	
	}

	public function conf_slot_2(){
		$param = [
			'game_name' => "네츄럴슬롯",
			'game_id' => GAME_SLOT_2,
			'active_ev' => '',
			'active_sl1' => '',
			'active_sl2' => 'active'
		];
		$this->load_view_page('home/conf_casino', 'conf_game', LEVEL_ADMIN, $param);	
	}

	public function conf_sound(){
		$confsiteModel = new ConfSite_Model();
		$arrConfig = $confsiteModel->gets();
		$this->load_view_page('home/conf_sound', 'conf_other', LEVEL_ADMIN, ['arrConfig', $arrConfig]);	
	}
	public function conf_password(){
		$this->load_view_page('home/conf_password', 'conf_password');
	}
}