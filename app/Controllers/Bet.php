<?php
namespace App\Controllers;

class Bet extends StdController {

	/**

	 */
	public function index()
	{
				
		if(is_login())
		{
			$this->response->redirect(base_url().'bet/pbrealtime', 'refresh');
		}
		else {
			$this->response->redirect( base_url().'pages/login', 'refresh');
		}	
		
	}

	public function pbrealtime(){
		$this->load_view_page('bet/pbrealtime', 'bet_realtime', LEVEL_COMPANY);
	}

	public function psrealtime(){
		$this->load_view_page('bet/psrealtime', 'bet_realtime', LEVEL_COMPANY);
	}

	public function ksrealtime(){
		$this->load_view_page('bet/ksrealtime', 'bet_realtime', LEVEL_COMPANY);
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

	public function kshistory(){
		$this->load_view_page('bet/kshistory', 'bet_history');	
	}
	
	public function cshistory(){
		$this->load_view_page('bet/cshistory', 'bet_history');
	}
	
	
	public function bbhistory(){
		$this->load_view_page('bet/bbhistory', 'bet_history');
	}

	public function bshistory(){
		$this->load_view_page('bet/bshistory', 'bet_history');
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

	public function kscalculate(){
		$this->load_view_page('bet/kscalculate', 'bet_calculate');
	}
	
	public function cscalculate(){
		$this->load_view_page('bet/cscalculate', 'bet_calculate');
	}
	
	public function bbcalculate(){
		$this->load_view_page('bet/bbcalculate', 'bet_calculate');
	}

	public function bscalculate(){
		$this->load_view_page('bet/bscalculate', 'bet_calculate');
	}
}