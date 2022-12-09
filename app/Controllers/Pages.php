<?php 
namespace App\Controllers;
use App\Models\ConfSite_Model;
class Pages extends BaseController
{
    public function index()
	{
		return $this->response->redirect( $_ENV['app.furl'].'/pages/login');
		
	}

	public function login()
	{		
		$this->response->redirect("/");
        // $model = new ConfSite_Model();
        // $siteName = $model->getSiteName();
		// return view('/pages/login', ['site_name'=> $siteName]);		
	}


	public function logout()
	{
		$sess_id = $this->session->session_id;
		writeLog("[page] logout (".$sess_id.")");

		$this->sess_destroy();
		$this->response->redirect( $_ENV['app.furl'].'/pages/login');
	}

	public function nopermit(){
		//echo view('pages/nopermit');
		$this->response->redirect( $_ENV['app.furl'].'/pages/login');		
	}
}