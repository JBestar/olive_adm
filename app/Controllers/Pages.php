<?php 
namespace App\Controllers;
use App\Models\ConfSite_Model;
class Pages extends BaseController
{
    public function index()
	{
		return $this->response->redirect( base_url().'pages/login', 'refresh');
		
	}

	public function login()
	{		
        $model = new ConfSite_Model();
        $siteName = $model->getSiteName();
		return view('/pages/login', ['site_name'=> $siteName]);		
	}


	public function logout()
	{
		$this->session->destroy();
		$this->response->redirect( base_url().'pages/login', 'refresh');
	}

	public function nopermit(){
		//echo view('pages/nopermit');
		$this->response->redirect( base_url().'pages/login', 'refresh');		
	}
}