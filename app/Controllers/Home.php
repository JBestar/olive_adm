<?php namespace App\Controllers;

use App\Models\ConfSite_Model;
use App\Models\Member_Model;


class Home extends BaseController
{
	public function index()
	{
		$confsite_model = new ConfSite_Model();  
        $siteName = $confsite_model->getSiteName();  
						
		if(!is_login())
		{
            echo view('home/login', array("site_name"=>$siteName));		
        } else {
            $member_model = new Member_Model();

			$strUid = $this->session->uid;
			$objUser = $member_model->getByUid($strUid);

            $bPermit = true;
			if(is_null($objUser))
				$bPermit = false;
			else if($objUser->mb_level < LEVEL_USER)
				$bPermit = false;

			if($bPermit){
                echo view('home/main', array("site_name"=>$siteName));		
			} else {

				$this->response->redirect('/home/logout');	
			}
        }

    }


	public function logout(){

		$this->session->destroy();
		$this->response->redirect('/');
	}



}