<?php
namespace App\Models;
use CodeIgniter\Model;

class ApiLive_model extends Model {
	
	private $mSnoopy ;



	function __construct()
	{
		parent::__construct();

		$this->mSnoopy = new \snoopy;

	}

	public function createPlayer($objLiveConf, $objReqUser, $nPlayerId){
	/*
	요청
	http://live99n.com/createplayer.html?adminid=xz1234567&playerid=5250005&playername=e01&bankcd=하나&banknum=111-5252-3332&bankname=지실장&playerphone=010-5252-4321
	
	응답	
	//000 SUCCESS 2020-12-10 오후 8:00:27 00 UAN226NBPA
	<xiresponse>  
		<respcode>000</respcode>  
		<resultcode>SUCCESS</resultcode>  
		<resptime>2020-12-10 오후 8:00:27</resptime>  
		<status>00</status>  
		<referenceid>UAN226NBPA</referenceid>  
	</xiresponse>

	*/
		//결과 0:자료오유 1:성공 2:실패
		if(is_null($objLiveConf) || is_null($objReqUser))
			return 0;


		$strUrl = "http://live99n.com/createplayer.html?";
		//에이전트아이디
		if(strlen(trim($objLiveConf->conf_content)) > 0){
			$strUrl .= "adminid=".$objLiveConf->conf_content;	
		} else return 0;
		//플레이어 아이디
		if($nPlayerId > $objLiveConf->conf_active){
			$strUrl .= "&playerid=".$nPlayerId;
		} else return 0;
		//유저네임
		if(strlen(trim($objReqUser->mb_nickname)) > 0){
			$strUrl .= "&playername=".$objReqUser->mb_nickname;
		} else return 0;
		//뱅크네임
		if(strlen(trim($objReqUser->mb_bank_name)) > 0){
			$strUrl .= "&bankcd=".$objReqUser->mb_bank_name;
		} else return 0;
		//계좌번호
		if(strlen(trim($objReqUser->mb_bank_num)) > 0){
			$strUrl .= "&banknum=".$objReqUser->mb_bank_num;	//숫자 형식 검사
		} else return 0;
		//예금주
		if(strlen(trim($objReqUser->mb_bank_own)) > 0){
			$strUrl .= "&bankname=".$objReqUser->mb_bank_own;	
		} else return 0;
		//전화번호(필수사항이 아님)
		/*
		if(isset($objReqUser->mb_phone)){
			$strUrl .= "&playerphone=".$objReqUser->mb_phone;	
		} else return false;
		*/
		
		$this->mSnoopy->fetch($strUrl);
		$strResult = $this->mSnoopy->results;
		/*
		$strResult =
			"<?xml version='1.0' encoding='UTF-8' ?> 
			<xiresponse>  
				<respcode>000</respcode>  
				<resultcode>SUCCESS</resultcode>  
				<resptime>2020-12-10 오후 8:00:27</resptime>  
				<status>00</status>  
				<referenceid>UAN226NBPA</referenceid>  
			</xiresponse>";
		*/
		$iResult = 2;
		$xmlData = false;
		//var_dump($strResult);

		if(isset($strResult) && strpos($strResult, "xml") !== false)
			$xmlData=simplexml_load_string($strResult);


		if($xmlData !== false)
		{
			$arrData = array_change_key_case((array)$xmlData,  CASE_LOWER);

			if(array_key_exists('respcode', $arrData) && trim($arrData['respcode']) === "000"){
				$iResult = 1;
			}

		}
		return $iResult;
	}

	public function createGameId($objLiveConf, $nPlayerId){
	/*
	요청
	http://evolution.live99n.com/cp.html?adminid=xz1234567&playerid=5250005
	
	응답
	"<?xml version="1.0" encoding="UTF-8" ?>  
	<XIResponse>  
		<RespCode>000</RespCode>  
		<resultCode>SUCCESS</resultCode>  
		<RespTime>2020-12-10 오후 8:18:28</RespTime>  
		<ErrMsg></ErrMsg>  
	</XIResponse>"

	*/
		//결과 0:자료오유 1:성공 2:실패
		$strUrl = "http://evolution.live99n.com/cp.html?";
		//에이전트아이디
		if(strlen(trim($objLiveConf->conf_content))>0){
			$strUrl .= "adminid=".$objLiveConf->conf_content;	
		} else return 0;
		//플레이어 아이디
		if($nPlayerId > $objLiveConf->conf_active){
			$strUrl .= "&playerid=".$nPlayerId;
		} else return 0;

		$this->mSnoopy->fetch($strUrl);
		$strResult = $this->mSnoopy->results;
		/*
		$strResult = 
			"<?xml version=\"1.0\" encoding=\"UTF-8\" ?>  
			<XIResponse>  
				<RespCode>000</RespCode>  
				<resultCode>SUCCESS</resultCode>  
				<RespTime>2020-12-10 오후 8:18:28</RespTime>  
				<ErrMsg></ErrMsg>  
			</XIResponse>";
		*/
		$iResult = 2;
		$xmlData = false;
		if(isset($strResult))
			$xmlData=simplexml_load_string($strResult);

		if($xmlData !== false)
		{
			$arrData = array_change_key_case((array)$xmlData,  CASE_LOWER);

			if(array_key_exists('respcode', $arrData) && $arrData['respcode'] === "000"){
				$iResult = 1;
			}

		}
		return $iResult;

	}

	/*
	public function playGame(){
	
	요청
	http://evolution.live99n.com/go.html?adminid=xz1234567&playerid=5250005
	
	응답
	//https://agiroy.evo-games.com/entry?params=QVVUSF9UT0tFTj04MDUxYTIxZTVkNzMwY2IxZjJhMTBlNWQ1ZWYwMzliZTM0MTQ4N2QxCnNpdGU9MQ&JSESSIONID=8051a21e5d730cb1f2a10e5d5ef039be341487d1

	

		$strUrl = "http://evolution.live99n.com/go.html?";
		$strUrl .= "adminid=xz1234567";
		$strUrl .= "&playerid=5250005";

		$this->mSnoopy->maxredirs = 0;
		$this->mSnoopy->fetch($strUrl);

		$strResult = $this->mSnoopy->results;
		$arrHeaders = $this->mSnoopy->headers;

		$strLocation = "";
		$strFind = "Location:";
		foreach ($arrHeaders as $objResult) {

			if(is_string($objResult)){
		
				$nStartPos = strpos($objResult, $strFind);
				if($nStartPos !== false){
				
					$nStartPos += strlen($strFind); 				
					$strLocation = substr($objResult, $nStartPos);
	      			$strLocation = trim($strLocation);
      				break;
      			}
			}

		}

		return $strLocation;

	}



	public function usermoney(){
	
	요청
	http://evolution.live99n.com/go.html?adminid=xz1234567&playerid=5250005
	
	응답
	<xiresponse>  
		<respcode>000</respcode>  
		<resptime>2020-12-11 오전 11:57:14</resptime>  
		<errmsg>SUCCESS</errmsg>  
		<playerinformation>  
			<playeraccount>5250005EV390351</playeraccount>  
			<playerbalance>0</playerbalance>  
		</playerinformation>  
	</xiresponse>
	
		$strUrl = "http://evolution.live99n.com/bp.html?";
		$strUrl .= "adminid=xz1234567";
		$strUrl .= "&playerid=5250005";

		$this->mSnoopy->fetch($strUrl);
		$strResult = $this->mSnoopy->results;

		$xmlData=simplexml_load_string($strResult);

		return $xmlData;

	}

	

	public function deposit(){
	
	요청
	http://evolution.live99n.com/dp.html?adminid=xz1234567&playerid=5250005&amount=10000
	
	응답
	<xiresponse>  
		<respcode>000</respcode>  
		<resptime>2020-12-11 오후 12:34:34</resptime>  
		<errmsg></errmsg>  
		<referenceid>9746268074</referenceid>  
	</xiresponse>
	
		$strUrl = "http://evolution.live99n.com/dp.html?";
		$strUrl .= "adminid=xz1234567";
		$strUrl .= "&playerid=5250005";
		$strUrl .= "&amount=10";

		$this->mSnoopy->fetch($strUrl);
		$strResult = $this->mSnoopy->results;

		$xmlData=simplexml_load_string($strResult);

		return $xmlData;
	}


	public function withdraw(){
	/*
	요청
	http://evolution.live99n.com/wp.html?adminid=xz1234567&playerid=5250005&amount=10000
	
	응답
	<xiresponse>  
		<respcode>000</respcode>  
		<resptime>2020-12-11 오후 12:41:36</resptime>  
		<errmsg></errmsg>  
		<referenceid>7771171148</referenceid>  
	</xiresponse>
	
		$strUrl = "http://evolution.live99n.com/wp.html?";
		$strUrl .= "adminid=xz1234567";
		$strUrl .= "&playerid=5250005";
		$strUrl .= "&amount=100";

		$this->mSnoopy->fetch($strUrl);
		$strResult = $this->mSnoopy->results;

		$xmlData=simplexml_load_string($strResult);

		return $xmlData;
	}
	*/

	public function gamehistory(){
	/*
	요청
	http://evolution.live99n.com/gh_history.html?adminid=xz1234567&historyidx=1
	
	응답
	<playerbetinfo>
		<playerbetdetail>
			<idx>NULL</idx>						//인덱스번호
			<round_no>NULL</round_no>			//배팅라운드번호
			<agent_id>NULL</agent_id>			//에이전트 아이디
			<player_id>NULL</player_id>			//유저아이디
			<game_id>NULL</game_id>				//Account 계정 게임아이디
			<gametype>NULL</gametype>			//게임종류
												//테이블번호 / 없는경우 공란
			<betmoney>0</betmoney>				//배팅액
			<resultmoney>0</resultmoney>		//획득액
			<netloss>0</netloss>				//차익
			<gamedate>NULL</gamedate>			//배팅시간
			<resultmsg>not game data</resultmsg>//시작금액
												//종료금액
		</playerbetdetail>
	</playerbetinfo>	

	idx는 제일 마지막에 오는 번호 준다.

	*/
		$strUrl = "http://evolution.live99n.com/gh_history.html?";
		$strUrl .= "adminid=asd1235321";
		$strUrl .= "&historyidx=12881018108760";
		
		$this->mSnoopy->fetch($strUrl);
		$strResult = $this->mSnoopy->results;
		/*
		$strResult = "
		<playerbetinfo>
			<playerbetdetail><idx>11211062180823</idx><round_no>164f9f0808210a1e7c420166</round_no><agent_id>9A1LINE</agent_id><player_id>5250005</player_id><game_id>5250005EV390351</game_id><gametype>baccarat   </gametype><table_code>Speed Baccarat A</table_code><betmoney>1000</betmoney><resultmoney>0		</resultmoney><netloss>1000	</netloss><betchoice></betchoice><start_balance></start_balance><end_balance></end_balance><gamedate>2020-12-11 18:21:03</gamedate><resultmsg>Success</resultmsg></playerbetdetail>
			<playerbetdetail><idx>11211052468909</idx><round_no>164f9f009b84077ca2f58985</round_no><agent_id>9A1LINE</agent_id><player_id>5250005</player_id><game_id>5250005EV390351</game_id><gametype>baccarat   </gametype><table_code>Speed Baccarat A</table_code><betmoney>3000</betmoney><resultmoney>5850	</resultmoney><netloss>-2850</netloss><betchoice></betchoice><start_balance></start_balance><end_balance></end_balance><gamedate>2020-12-11 18:20:31</gamedate><resultmsg>Success</resultmsg></playerbetdetail>
			<playerbetdetail><idx>11211054892233</idx><round_no>164f9ef8db37adc4a68807cf</round_no><agent_id>9A1LINE</agent_id><player_id>5250005</player_id><game_id>5250005EV390351</game_id><gametype>baccarat   </gametype><table_code>Speed Baccarat A</table_code><betmoney>2000</betmoney><resultmoney>0		</resultmoney><netloss>2000	</netloss><betchoice></betchoice><start_balance></start_balance><end_balance></end_balance><gamedate>2020-12-11 18:19:58</gamedate><resultmsg>Success</resultmsg></playerbetdetail>
			
		</playerbetinfo>
		";
		*/
		$xmlData = false;
		$arrData = array();

		if(isset($strResult))
			$xmlData=simplexml_load_string($strResult);
		
		if($xmlData !== false)
		{
			//$arrData = array_change_key_case((array)$xmlData,  CASE_LOWER);

			$jsonString = json_encode($xmlData);    
			$arrData = json_decode($jsonString, TRUE);
			$arrData = array_change_key_case($arrData,  CASE_LOWER);

			echo "<br>";
			$arrBetData = $arrData['playerbetdetail'];
			if(array_key_exists("idx", $arrBetData)) {
				echo $arrBetData['idx'];
				echo "<br>";
			} else {
				foreach ($arrBetData as $objBetData) {
					echo $objBetData['idx'];
					echo "<br>";
				}
			}


		}

		return $arrData;
	}

	public function agentmoney(){
	/*
	요청
	http://evolution.live99n.com/ag_money.html?adminid=xz1234567
	
	응답
	<xiresponse>  
		<respcode>000</respcode>  
		<resptime>2020-12-11 오후 4:02:01</resptime>  
		<errmsg>SUCCESS.</errmsg>  
		<agentinformation>  
			<agentbalance>99990050</agentbalance>  
		</agentinformation>  
	</xiresponse>
	*/
		$strUrl = "http://evolution.live99n.com/ag_money.html?";
		$strUrl .= "adminid=xz1234567";
		
		$this->mSnoopy->fetch($strUrl);
		$strResult = $this->mSnoopy->results;

		$xmlData=simplexml_load_string($strResult);

		return $xmlData;
	}

	/*
	public function agentsummary(){
	
	요청
	http://evolution.live99n.com/gh_summary.html?adminid=xz1234567&typeid=agent&rdate=2020-12-12
	응답
	<playerbetinfo>
		<playerbetdetail>
			<agent_id>9A1LINE</agent_id>	//에이전트아이디
			<betmoney>0</betmoney>			//배팅액
			<resultmoney>0</resultmoney>	//획득액
			<netloss>0</netloss>			//차익
			<gamedate>2020-12-11</gamedate>	//날짜
			<resultmsg>Success</resultmsg>
		</playerbetdetail>
	</playerbetinfo>
	

		$strUrl = "http://evolution.live99n.com/gh_summary.html?";
		$strUrl .= "adminid=xz1234567";
		$strUrl .= "&typeid=agent";
		$strUrl .= "&rdate=2020-12-11";
		
		$this->mSnoopy->fetch($strUrl);
		$strResult = $this->mSnoopy->results;

		$xmlData=simplexml_load_string($strResult);

		return $xmlData;
	}
	*/




}

/* 게임 리력 분석

	<playerbetinfo>
		<playerbetdetail>
			<idx>11206037848360</idx>
			<round_no>164f9a638dbe18481ebb6ec7</round_no>
			<agent_id>9A1LINE</agent_id>
			<player_id>5250005</player_id>
			<game_id>5250005EV390351</game_id>
			<gametype>dragontiger</gametype>			//드래곤 & 타이거
			<table_code>Dragon Tiger</table_code>
			<betmoney>1000</betmoney>
			<resultmoney>2000</resultmoney>
			<netloss>-1000</netloss>
			<betchoice></betchoice>
			<start_balance></start_balance>
			<end_balance></end_balance>
			<gamedate>2020-12-11 16:56:00</gamedate>
			<resultmsg>Success</resultmsg>
		</playerbetdetail>
		<playerbetdetail>
			<idx>11205275724633</idx>
			<round_no>164f9999fdc9a11874f95ffb</round_no>
			<agent_id>9A1LINE</agent_id>
			<player_id>5250005</player_id>
			<game_id>5250005EV390351</game_id>
			<gametype>baccarat</gametype>				//바카라
			<table_code>Speed Baccarat A</table_code>
			<betmoney>1000</betmoney>
			<resultmoney>0</resultmoney>
			<netloss>1000</netloss>
			<betchoice></betchoice>
			<start_balance></start_balance>
			<end_balance></end_balance>
			<gamedate>2020-12-11 16:41:33</gamedate>
			<resultmsg>Success</resultmsg>
		</playerbetdetail>
	</playerbetinfo>

<playerbetinfo>
	<playerbetdetail><idx>11211062180823</idx><round_no>164f9f0808210a1e7c420166</round_no><agent_id>9A1LINE</agent_id><player_id>5250005</player_id><game_id>5250005EV390351</game_id><gametype>baccarat   </gametype><table_code>Speed Baccarat A</table_code><betmoney>1000</betmoney><resultmoney>0		</resultmoney><netloss>1000	</netloss><betchoice></betchoice><start_balance></start_balance><end_balance></end_balance><gamedate>2020-12-11 18:21:03</gamedate><resultmsg>Success</resultmsg></playerbetdetail>
	<playerbetdetail><idx>11211052468909</idx><round_no>164f9f009b84077ca2f58985</round_no><agent_id>9A1LINE</agent_id><player_id>5250005</player_id><game_id>5250005EV390351</game_id><gametype>baccarat   </gametype><table_code>Speed Baccarat A</table_code><betmoney>3000</betmoney><resultmoney>5850	</resultmoney><netloss>-2850</netloss><betchoice></betchoice><start_balance></start_balance><end_balance></end_balance><gamedate>2020-12-11 18:20:31</gamedate><resultmsg>Success</resultmsg></playerbetdetail>
	<playerbetdetail><idx>11211054892233</idx><round_no>164f9ef8db37adc4a68807cf</round_no><agent_id>9A1LINE</agent_id><player_id>5250005</player_id><game_id>5250005EV390351</game_id><gametype>baccarat   </gametype><table_code>Speed Baccarat A</table_code><betmoney>2000</betmoney><resultmoney>0		</resultmoney><netloss>2000	</netloss><betchoice></betchoice><start_balance></start_balance><end_balance></end_balance><gamedate>2020-12-11 18:19:58</gamedate><resultmsg>Success</resultmsg></playerbetdetail>
	<playerbetdetail><idx>11211053616306</idx><round_no>164f9ef144d241e33f664106</round_no><agent_id>9A1LINE</agent_id><player_id>5250005</player_id><game_id>5250005EV390351</game_id><gametype>baccarat   </gametype><table_code>Speed Baccarat A</table_code><betmoney>1000</betmoney><resultmoney>0		</resultmoney><netloss>1000	</netloss><betchoice></betchoice><start_balance></start_balance><end_balance></end_balance><gamedate>2020-12-11 18:19:25</gamedate><resultmsg>Success</resultmsg></playerbetdetail>
	<playerbetdetail><idx>11206418256740</idx><round_no>164f9aea45792187218405c0</round_no><agent_id>9A1LINE</agent_id><player_id>5250005</player_id><game_id>5250005EV390351</game_id><gametype>sicbo      </gametype><table_code>Super Sic Bo    </table_code><betmoney>400</betmoney><resultmoney>400	</resultmoney><netloss>0	</netloss><betchoice></betchoice><start_balance></start_balance><end_balance></end_balance><gamedate>2020-12-11 17:05:44</gamedate><resultmsg>Success</resultmsg></playerbetdetail>
	<playerbetdetail><idx>11206037848360</idx><round_no>164f9a638dbe18481ebb6ec7</round_no><agent_id>9A1LINE</agent_id><player_id>5250005</player_id><game_id>5250005EV390351</game_id><gametype>dragontiger</gametype><table_code>Dragon Tiger    </table_code><betmoney>1000</betmoney><resultmoney>2000	</resultmoney><netloss>-1000</netloss><betchoice></betchoice><start_balance></start_balance><end_balance></end_balance><gamedate>2020-12-11 16:56:00</gamedate><resultmsg>Success</resultmsg></playerbetdetail>
	<playerbetdetail><idx>11205275724633</idx><round_no>164f9999fdc9a11874f95ffb</round_no><agent_id>9A1LINE</agent_id><player_id>5250005</player_id><game_id>5250005EV390351</game_id><gametype>baccarat   </gametype><table_code>Speed Baccarat A</table_code><betmoney>1000</betmoney><resultmoney>0		</resultmoney><netloss>1000	</netloss><betchoice></betchoice><start_balance></start_balance><end_balance></end_balance><gamedate>2020-12-11 16:41:33</gamedate><resultmsg>Success</resultmsg></playerbetdetail>
</playerbetinfo>
*/

/*
array(1) {
  ["playerbetdetail"]=>
  array(11) {
    ["idx"]=>
    string(4) "NULL"
    ["round_no"]=>
    string(4) "NULL"
    ["agent_id"]=>
    string(4) "NULL"
    ["player_id"]=>
    string(4) "NULL"
    ["game_id"]=>
    string(4) "NULL"
    ["gametype"]=>
    string(4) "NULL"
    ["betmoney"]=>
    string(1) "0"
    ["resultmoney"]=>
    string(1) "0"
    ["netloss"]=>
    string(1) "0"
    ["gamedate"]=>
    string(4) "NULL"
    ["resultMsg"]=>
    string(13) "not game data"
  }
}


array(1) {
  ["playerbetdetail"]=>
  array(7) {
    [0]=>
    array(15) {
      ["idx"]=>
      string(14) "11205275724633"
      ["round_no"]=>
      string(24) "164f9999fdc9a11874f95ffb"
      ["agent_id"]=>
      string(7) "9A1LINE"
      ["player_id"]=>
      string(7) "5250005"
      ["game_id"]=>
      string(15) "5250005EV390351"
      ["gametype"]=>
      string(8) "baccarat"
      ["table_code"]=>
      string(16) "Speed Baccarat A"
      ["betmoney"]=>
      string(4) "1000"
      ["resultmoney"]=>
      string(1) "0"
      ["netloss"]=>
      string(4) "1000"
      ["betchoice"]=>
      array(0) {
      }
      ["start_balance"]=>
      array(0) {
      }
      ["end_balance"]=>
      array(0) {
      }
      ["gamedate"]=>
      string(19) "2020-12-11 16:41:33"
      ["resultMsg"]=>
      string(7) "Success"
    }
    [1]=>
    array(15) {
      ["idx"]=>
      string(14) "11206037848360"
      ["round_no"]=>
      string(24) "164f9a638dbe18481ebb6ec7"
      ["agent_id"]=>
      string(7) "9A1LINE"
      ["player_id"]=>
      string(7) "5250005"
      ["game_id"]=>
      string(15) "5250005EV390351"
      ["gametype"]=>
      string(11) "dragontiger"
      ["table_code"]=>
      string(12) "Dragon Tiger"
      ["betmoney"]=>
      string(4) "1000"
      ["resultmoney"]=>
      string(4) "2000"
      ["netloss"]=>
      string(5) "-1000"
      ["betchoice"]=>
      array(0) {
      }
      ["start_balance"]=>
      array(0) {
      }
      ["end_balance"]=>
      array(0) {
      }
      ["gamedate"]=>
      string(19) "2020-12-11 16:56:00"
      ["resultMsg"]=>
      string(7) "Success"
    }
    [2]=>
    array(15) {
      ["idx"]=>
      string(14) "11206418256740"
      ["round_no"]=>
      string(24) "164f9aea45792187218405c0"
      ["agent_id"]=>
      string(7) "9A1LINE"
      ["player_id"]=>
      string(7) "5250005"
      ["game_id"]=>
      string(15) "5250005EV390351"
      ["gametype"]=>
      string(5) "sicbo"
      ["table_code"]=>
      string(12) "Super Sic Bo"
      ["betmoney"]=>
      string(3) "400"
      ["resultmoney"]=>
      string(3) "400"
      ["netloss"]=>
      string(1) "0"
      ["betchoice"]=>
      array(0) {
      }
      ["start_balance"]=>
      array(0) {
      }
      ["end_balance"]=>
      array(0) {
      }
      ["gamedate"]=>
      string(19) "2020-12-11 17:05:44"
      ["resultMsg"]=>
      string(7) "Success"
    }
    [3]=>
    array(15) {
      ["idx"]=>
      string(14) "11211052468909"
      ["round_no"]=>
      string(24) "164f9f009b84077ca2f58985"
      ["agent_id"]=>
      string(7) "9A1LINE"
      ["player_id"]=>
      string(7) "5250005"
      ["game_id"]=>
      string(15) "5250005EV390351"
      ["gametype"]=>
      string(8) "baccarat"
      ["table_code"]=>
      string(16) "Speed Baccarat A"
      ["betmoney"]=>
      string(4) "3000"
      ["resultmoney"]=>
      string(4) "5850"
      ["netloss"]=>
      string(5) "-2850"
      ["betchoice"]=>
      array(0) {
      }
      ["start_balance"]=>
      array(0) {
      }
      ["end_balance"]=>
      array(0) {
      }
      ["gamedate"]=>
      string(19) "2020-12-11 18:20:31"
      ["resultMsg"]=>
      string(7) "Success"
    }
    [4]=>
    array(15) {
      ["idx"]=>
      string(14) "11211053616306"
      ["round_no"]=>
      string(24) "164f9ef144d241e33f664106"
      ["agent_id"]=>
      string(7) "9A1LINE"
      ["player_id"]=>
      string(7) "5250005"
      ["game_id"]=>
      string(15) "5250005EV390351"
      ["gametype"]=>
      string(8) "baccarat"
      ["table_code"]=>
      string(16) "Speed Baccarat A"
      ["betmoney"]=>
      string(4) "1000"
      ["resultmoney"]=>
      string(1) "0"
      ["netloss"]=>
      string(4) "1000"
      ["betchoice"]=>
      array(0) {
      }
      ["start_balance"]=>
      array(0) {
      }
      ["end_balance"]=>
      array(0) {
      }
      ["gamedate"]=>
      string(19) "2020-12-11 18:19:25"
      ["resultMsg"]=>
      string(7) "Success"
    }
    [5]=>
    array(15) {
      ["idx"]=>
      string(14) "11211054892233"
      ["round_no"]=>
      string(24) "164f9ef8db37adc4a68807cf"
      ["agent_id"]=>
      string(7) "9A1LINE"
      ["player_id"]=>
      string(7) "5250005"
      ["game_id"]=>
      string(15) "5250005EV390351"
      ["gametype"]=>
      string(8) "baccarat"
      ["table_code"]=>
      string(16) "Speed Baccarat A"
      ["betmoney"]=>
      string(4) "2000"
      ["resultmoney"]=>
      string(1) "0"
      ["netloss"]=>
      string(4) "2000"
      ["betchoice"]=>
      array(0) {
      }
      ["start_balance"]=>
      array(0) {
      }
      ["end_balance"]=>
      array(0) {
      }
      ["gamedate"]=>
      string(19) "2020-12-11 18:19:58"
      ["resultMsg"]=>
      string(7) "Success"
    }
    [6]=>
    array(15) {
      ["idx"]=>
      string(14) "11211062180823"
      ["round_no"]=>
      string(24) "164f9f0808210a1e7c420166"
      ["agent_id"]=>
      string(7) "9A1LINE"
      ["player_id"]=>
      string(7) "5250005"
      ["game_id"]=>
      string(15) "5250005EV390351"
      ["gametype"]=>
      string(8) "baccarat"
      ["table_code"]=>
      string(16) "Speed Baccarat A"
      ["betmoney"]=>
      string(4) "1000"
      ["resultmoney"]=>
      string(1) "0"
      ["netloss"]=>
      string(4) "1000"
      ["betchoice"]=>
      array(0) {
      }
      ["start_balance"]=>
      array(0) {
      }
      ["end_balance"]=>
      array(0) {
      }
      ["gamedate"]=>
      string(19) "2020-12-11 18:21:03"
      ["resultMsg"]=>
      string(7) "Success"
    }
  }
}
*/



/*

array(1) {
  ["playerbetdetail"]=>
  array(2) {
    [0]=>
    object(SimpleXMLElement)#25 (16) {
      ["idx"]=>
      string(14) "11211054892233"
      ["history_idx"]=>
      string(14) "11211054892233"
      ["round_no"]=>
      string(24) "164f9ef8db37adc4a68807cf"
      ["agent_id"]=>
      string(7) "9A1LINE"
      ["player_id"]=>
      string(7) "5250005"
      ["game_id"]=>
      string(15) "5250005EV390351"
      ["gametype"]=>
      string(8) "baccarat"
      ["table_code"]=>
      string(16) "Speed Baccarat A"
      ["betmoney"]=>
      string(4) "2000"
      ["resultmoney"]=>
      string(1) "0"
      ["netloss"]=>
      string(4) "2000"
      ["betchoice"]=>
      object(SimpleXMLElement)#24 (0) {
      }
      ["start_balance"]=>
      object(SimpleXMLElement)#27 (0) {
      }
      ["end_balance"]=>
      object(SimpleXMLElement)#28 (0) {
      }
      ["gamedate"]=>
      string(19) "2020-12-11 18:19:58"
      ["resultMsg"]=>
      string(7) "Success"
    }
    [1]=>
    object(SimpleXMLElement)#26 (16) {
      ["idx"]=>
      string(14) "11211062180823"
      ["history_idx"]=>
      string(14) "11211062180823"
      ["round_no"]=>
      string(24) "164f9f0808210a1e7c420166"
      ["agent_id"]=>
      string(7) "9A1LINE"
      ["player_id"]=>
      string(7) "5250005"
      ["game_id"]=>
      string(15) "5250005EV390351"
      ["gametype"]=>
      string(8) "baccarat"
      ["table_code"]=>
      string(16) "Speed Baccarat A"
      ["betmoney"]=>
      string(4) "1000"
      ["resultmoney"]=>
      string(1) "0"
      ["netloss"]=>
      string(4) "1000"
      ["betchoice"]=>
      object(SimpleXMLElement)#28 (0) {
      }
      ["start_balance"]=>
      object(SimpleXMLElement)#27 (0) {
      }
      ["end_balance"]=>
      object(SimpleXMLElement)#24 (0) {
      }
      ["gamedate"]=>
      string(19) "2020-12-11 18:21:03"
      ["resultMsg"]=>
      string(7) "Success"
    }
  }
}


*/

