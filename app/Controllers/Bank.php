<?php
namespace App\Controllers;

class Bank extends StdController {

	/**

	 */
	public function index()
	{
				
		if(is_login())
		{
			$this->response->redirect('bank/deposit', 'refresh');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
		
	}

	public function deposit(){
		$this->load_view_page('bank/deposit', 'bank_deposit', LEVEL_ADMIN);
	}

	public function withdraw(){
		$this->load_view_page('bank/withdraw', 'bank_withdraw', LEVEL_ADMIN);
	}

	public function exchange(){
		$this->load_view_page('bank/exchange', 'bank_exchange', LEVEL_EMPLOYEE);
	}

	public function transfer(){
		$this->load_view_page('bank/transfer', 'bank_transfer', LEVEL_EMPLOYEE);
	}
}