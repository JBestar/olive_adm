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


	public function conf_betsite(){
		$this->load_view_page('home/conf_betsite', 'conf_site', LEVEL_ADMIN, ['conf_site' => " sidebar-a-active"]);	
	}
	

	public function conf_powerball(){
		$this->load_view_page('home/conf_powerball', 'conf_game', LEVEL_ADMIN);		
	}

	public function conf_kenoladder(){
		$this->load_view_page('home/conf_kenoladder', 'conf_game', LEVEL_ADMIN);
	}

	public function conf_powerladder(){
		$this->load_view_page('home/conf_powerladder', 'conf_game', LEVEL_ADMIN);
	}


	public function conf_bogleball(){
		$this->load_view_page('home/conf_bogleball', 'conf_game', LEVEL_ADMIN);
	}

	
	public function conf_bogleladder(){
		$this->load_view_page('home/conf_bogleladder', 'conf_game', LEVEL_ADMIN);	
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