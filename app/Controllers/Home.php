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
			return $this->response->redirect($_ENV['app.furl'].'/home/conf_password');
		}
		else {
			return $this->response->redirect($_ENV['app.furl'].'/pages/login');
		}	
	}
	
	public function conf_site(){
		$confsiteModel = new ConfSite_Model();
		$arrConfig = $confsiteModel->where('conf_id <= '.CONF_AUTOAPPS)->findAll();		
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
		$arrSound = [
			"sound1.mp3"=> "알림음1",
			"sound2.mp3"=> "알림음2",
			"sound3.mp3"=> "알림음3",
			"sound4.mp3"=> "알림음4",
			"sound5.mp3"=> "알림음5",
			"sound6.mp3"=> "알림음6",
			"sound7.mp3"=> "알림음7",
			"sound8.mp3"=> "알림음8",
			"sound9.mp3"=> "알림음9",
			"sound10.mp3"=> "알림음10",
			"sound11.mp3"=> "알림음11",
			"sound12.mp3"=> "알림음12",
		];
		
		$param = [
			'sounds' => $arrSound,
		];

		$this->load_view_page('home/conf_sound', 'conf_site', LEVEL_ADMIN, $param);	
	}

	public function conf_message(){
		
		$this->load_view_page('home/conf_message', 'conf_site', LEVEL_ADMIN);	
	}

	public function conf_domain(){
		
		$this->load_view_page('home/conf_domain', 'conf_site', LEVEL_ADMIN);	
	}

	public function conf_clean(){
		$this->load_view_page('home/conf_clean', 'conf_site', LEVEL_ADMIN);	
	}

	public function conf_powerball(){
		$confsiteModel = new ConfSite_Model();
		$confsiteModel->readBetConf();
		$param = [
			'game_name' => "PBG파워볼",
			'game_id' => GAME_PBG_BALL
		];
		$this->load_view_page('home/conf_powerball', 'conf_game', LEVEL_ADMIN, $param);		
	}

	public function conf_evolball(){
		$param = [
			'game_name' => "에볼파워볼",
			'game_id' => GAME_EVOL_BALL
		];
		$this->load_view_page('home/conf_powerball', 'conf_game', LEVEL_ADMIN, $param);
	}

	public function conf_speedkeno(){
		$param = [
			'game_name' => "스피드키노",
			'game_id' => GAME_SPKN_BALL
		];
		$this->load_view_page('home/conf_keno', 'conf_game', LEVEL_ADMIN, $param);
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

	public function conf_rand5ball(){
		$confsiteModel = new ConfSite_Model();
		$confsiteModel->readBetConf();
		
		$param = [
			'game_name' => "랜덤5분",
			'game_id' => GAME_RAND5_BALL
		];
		$this->load_view_page('home/conf_powerball', 'conf_game', LEVEL_ADMIN, $param);
	}

	public function conf_rand3ball(){
		$confsiteModel = new ConfSite_Model();
		$confsiteModel->readBetConf();
		
		$param = [
			'game_name' => "랜덤3분",
			'game_id' => GAME_RAND3_BALL
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

	public function conf_hold(){
		$param = [
			'game_name' => "홀덤",
			'game_id' => GAME_HOLD_CMS
		];
		$this->load_view_page('home/conf_evol', 'conf_game', LEVEL_ADMIN, $param);	
	}

	public function conf_ebal(){
		$arrType = [
			"10"=> "EVOL365",
			// "11"=> "LUCKY",
			"12"=> "VEDA",
			"1"=> "NINE",
			"2"=> "AMAZON",
			"3"=> "AIRLINE",
			"4"=> "NINEBAR",
			"5"=> "CHROMA",
			"6"=> "CITY",
			"7"=> "CJ",
			"8"=> "K-82",
			"9"=> "투다리",
			"13"=> "로이스",
			"14"=> "MISSION",
			// "15"=> "MONEY",
			"16"=> "알로하",
			"17"=> "KING",
			"18"=> "글로리",
			"19"=> "제로",
			// "20"=> "LUX",
			"21"=> "비네티안",
			"22"=> "드래곤",
			"23"=> "STAR",
			"24"=> "편집샵",
			"25"=> "라방",
		];
		$confsiteModel = new ConfSite_Model();

		$param = [
			'game_title' => "에볼밸런스",
			'game_name' => "밸런스설정",
			'game_id' => GAME_AUTO_EVOL,
			'gamd_types' => $arrType,
			'ebal_cnt' => $_ENV['app.ebal'],
			'evpress' => $confsiteModel->getEvpressState(),
		];
		$this->load_view_page('ebal/conf_ebal', 'conf_ebal', LEVEL_ADMIN, $param);	
	}

	public function conf_eroom(){
		$confsiteModel = new ConfSite_Model();

		$param = [
			'game_title' => "에볼밸런스",
			'game_name' => "방설정",
			'game_id' => GAME_AUTO_EVOL,
			'evpress' => $confsiteModel->getEvpressState(),
		];
		$this->load_view_page('ebal/conf_eroom', 'conf_ebal', LEVEL_ADMIN, $param);	
	}

	public function conf_epress(){
		$confsiteModel = new ConfSite_Model();
		$param = [
			'game_title' => "에볼밸런스",
			'game_name' => "누르기설정",
			'game_id' => GAME_AUTO_EVOL,
			'evpress' => $confsiteModel->getEvpressState(),
		];
		if(!$param['evpress'])
			$this->response->redirect($_ENV['app.furl'].'/home/conf_ebal');
		else {
			
			$this->load_view_page('ebal/conf_epress', 'conf_ebal', LEVEL_ADMIN, $param);	
		}
	}
	public function conf_epresslog(){
		$confsiteModel = new ConfSite_Model();
		$param = [
			'game_title' => "에볼밸런스",
			'game_name' => "누르기내역",
			'game_id' => GAME_AUTO_EVOL,
			'evpress' => $confsiteModel->getEvpressState(),
		];
		if(!$param['evpress'])
			$this->response->redirect($_ENV['app.furl'].'/home/conf_ebal');
		else {
			
			$this->load_view_page('ebal/conf_epresslog', 'conf_ebal', LEVEL_ADMIN, $param);	
		}
	}

	public function conf_pbal(){
		$arrType = [
			"10"=> "PRAG365",
			"12"=> "VEDA",
		];
		$confsiteModel = new ConfSite_Model();

		$param = [
			'game_title' => "프라그밸런스",
			'game_name' => "밸런스설정",
			'game_id' => GAME_AUTO_PRAG,
			'gamd_types' => $arrType,
			'ebal_cnt' => $_ENV['app.pbal'],
			'evpress' => $confsiteModel->getEvpressState(),
		];
		$this->load_view_page('ebal/conf_ebal', 'conf_pbal', LEVEL_ADMIN, $param);	
	}

	public function conf_proom(){
		$confsiteModel = new ConfSite_Model();

		$param = [
			'game_title' => "프라그밸런스",
			'game_name' => "방설정",
			'game_id' => GAME_AUTO_PRAG,
			'evpress' => $confsiteModel->getEvpressState(),
		];
		$this->load_view_page('ebal/conf_eroom', 'conf_pbal', LEVEL_ADMIN, $param);	
	}

	public function conf_ppress(){
		$confsiteModel = new ConfSite_Model();
		$param = [
			'game_title' => "프라그밸런스",
			'game_name' => "누르기설정",
			'game_id' => GAME_AUTO_PRAG,
			'evpress' => $confsiteModel->getEvpressState(),
		];
		if(!$param['evpress'])
			$this->response->redirect($_ENV['app.furl'].'/home/conf_ebal');
		else {
			
			$this->load_view_page('ebal/conf_epress', 'conf_ebal', LEVEL_ADMIN, $param);	
		}
	}
	public function conf_ppresslog(){
		$confsiteModel = new ConfSite_Model();
		$param = [
			'game_title' => "프라그밸런스",
			'game_name' => "누르기내역",
			'game_id' => GAME_AUTO_PRAG,
			'evpress' => $confsiteModel->getEvpressState(),
		];
		if(!$param['evpress'])
			$this->response->redirect($_ENV['app.furl'].'/home/conf_ebal');
		else {
			
			$this->load_view_page('ebal/conf_epresslog', 'conf_ebal', LEVEL_ADMIN, $param);	
		}
	}


	public function conf_casino(){
		$gameId = GAME_CASINO_KGON;
		if($_ENV['app.casino'] == APP_CASINO_STAR)
			$gameId = GAME_CASINO_STAR;

		$param = [
			'game_name' => "정품카지노",
			'game_id' => $gameId
		];
		$this->load_view_page('home/conf_casino', 'conf_game', LEVEL_ADMIN, $param);	
	}

	public function conf_slot_1(){
		$gameId = GAME_SLOT_THEPLUS;
		if($_ENV['app.slot'] == APP_SLOT_KGON)
			$gameId = GAME_SLOT_KGON;
		else if($_ENV['app.slot'] == APP_SLOT_STAR)
			$gameId = GAME_SLOT_STAR;

		$param = [
			'game_name' => "정품슬롯",
			'game_id' => $gameId
		];
		$this->load_view_page('home/conf_slot', 'conf_game', LEVEL_ADMIN, $param);	
	}

	public function conf_slot_2(){
		$slprdModel = new SlotPrd_Model();

		$gameId = GAME_SLOT_GSPLAY;
		if($_ENV['app.fslot'] == APP_FSLOT_GOLD)
			$gameId = GAME_SLOT_GOLD;

		$param = [
			'game_name' => "네츄럴슬롯",
			'game_id' => $gameId,
			'game_prds' => $slprdModel->getByCode($gameId), 
		];
		$this->load_view_page('home/conf_fslot', 'conf_game', LEVEL_ADMIN, $param);	
	}

	
	public function conf_password(){
		$this->load_view_page('home/conf_password', 'conf_password');
	}

	public function conf_follow(){
		if (is_login() === false){
			return $this->response->redirect($_ENV['app.furl'].'/pages/login');
		}
		$strUid = $this->session->user_id;
		$objMember = $this->modelMember->getInfo($strUid, true);
		$param = [
			'mb_follow_check' => false,
			'mb_follow_id' => "",
			'mb_follow_percent' => 100,
		];

		if(!is_null($objMember) && strlen($objMember->mb_follow_ev) > 0){

			$info = explode(":", $objMember->mb_follow_ev);
			if(count($info) >= 2){
				$param['mb_follow_check'] = intval($info[0]) == STATE_ACTIVE;
				$param['mb_follow_id'] = $info[1];
				if(count($info) >= 3)
					$param['mb_follow_percent'] = intval($info[2]);
			}
			
		}

		$this->load_view_page('home/conf_follow', 'conf_follow', LEVEL_MIN, $param);
	}
		
	public function upload()
	{

		$objResult = new \StdClass;
		$objResult->status = "fail";
		$url = "";
		
		try
		{
			if(count($_FILES) > 0){
				$iResult = 0;

				$file = reset($_FILES);
				
				$file_name = $file['name'];
				$file_size = $file['size'];
				$file_tmp = $file['tmp_name'];
				// $file_type= $file['type'];
				$arrExt = explode('.', $file_name);
				$file_ext=strtolower(end($arrExt));
				// $file_error = $file['error'];

				// writeLog("file_name=".$file_name);
				// writeLog("file_size=".$file_size);
				// writeLog("file_tmp=".$file_tmp);

				$errors = "Upload Error!!";
				
				$extensions= array("exe", "msi", "cmd", "bat", "vbs", "dll", "com");

				if(strlen($file_name) < 1){
					$iResult = 2;
					$errors="File not found";
				}  else if(in_array($file_ext, $extensions) === true){
					$iResult = 3;
					$errors="Extension not allowed";
				} else if($file_size > 524288000){
					$iResult = 4;
					$errors='File size must be exactely 500 MB';
				} else if($file_size < 1){
					$iResult = 4;
					$errors='File size is zero';
				} else if(empty($file_tmp)){
					$iResult = 5;
					$errors='File temporary path is empty';
				}
				
				if($iResult == 0){
					if(!file_exists(DOWNLOADROOT)){
						mkdir(DOWNLOADROOT);
					}

					if(!file_exists(DOWNLOADROOT."image".DIRECTORY_SEPARATOR)){
						mkdir(DOWNLOADROOT."image".DIRECTORY_SEPARATOR);
					}

					$settingPath = DOWNLOADROOT."image".DIRECTORY_SEPARATOR;

					$file_name = date("Y-m-d_H-i-s").".".$file_ext;
					$filePath = $settingPath;
					$filePath .= $file_name;
					// writeLog("filePath=".$filePath);

					move_uploaded_file($file_tmp, $filePath);
					
					$iResult = 1;	
					$objResult->status = "success";			
		
					$url = BASEURL.$_ENV['app.furl']."/".DOWNLOADDIR."/image/".$file_name;
				}
				
			}
		}
		catch (\Exception $e)
		{
			$errors = $e->getMessage();
		}
		$objResult->url = $url;
		echo json_encode($objResult);	
	}
}