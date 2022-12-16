<?php

  	function is_login(){ 
      if(!isset($_SESSION['logged_in']))
        return false;
      else if($_SESSION['logged_in']==TRUE)
        return true;
      else return false;  
  	}


    //사이드바의 선택상태 초기화 배렬을 반환해주는 함수
    function getSidebarLinkArray(){

      return [
               'mb_level' => '0',
               'confdropdownbtn' => " main-dropdown-active-btn",
               'confdropdown' => "style=\"display:block\"", 
               'conf_site' => '',
               'conf_game' => '',
               'conf_other' => '',
               'conf_password' => '',
               'conf_ebal' => '',
               'userdropdownbtn' => " main-dropdown-active-btn",
               'userdropdown' => "style=\"display:block\"", 
               'user_member' => '',
               'user_ctrl' => '',
               'user_log' => '',
               'user_block' => '',
               'bankdropdownbtn' => " main-dropdown-active-btn",
               'bankdropdown' => "style=\"display:block\"", 
               'bank_deposit' => '',
               'bank_withdraw' => '',
               'bank_point' => '',
               'bank_exchange' => '',
               'bank_transfer' => '',
               'resultdropdownbtn' => " main-dropdown-active-btn",
               'resultdropdown' => "style=\"display:block\"",
               'gameresult' => '',
               'gameedit' => '',
               'betdropdownbtn' => " main-dropdown-active-btn",
               'betdropdown' => "style=\"display:block\"", 
               'bet_realtime' => '',
               'bet_history' => '',
               'bet_calculate' => '',
               'boarddropdownbtn' => " main-dropdown-active-btn",
               'boarddropdown' => "style=\"display:block\"", 
               'board_notice' => '',
               'board_event' => '',
               'board_message' => ''
      ];

    }
    
    //회차유효성 체크 함수
    function isEnablePbRound(&$arrRoundInfo, $roundMin){
      
      if($arrRoundInfo['round_num'] < 1 || $arrRoundInfo['round_num'] > 1440/$roundMin)
        return false;
      $tempDate = explode('-', $arrRoundInfo['round_date']);
  
      if(!checkdate($tempDate[1], $tempDate[2], $tempDate[0]))
        return false;

      $nRoundNo = $arrRoundInfo['round_num'];
      $nSumMinutes = $nRoundNo * $roundMin ;
      $nHour = $nSumMinutes / 60;
      $nHour = (int)$nHour;
      $nMinute = $nSumMinutes % 60;
      
      $strNowDate = $arrRoundInfo['round_date'];
      
      $strRoundEnd = $strNowDate." ".$nHour.":".$nMinute.":"."0";
      $tmRoundEnd = strtotime($strRoundEnd);
      
      //현재시간설정      
      $tmRoundCurrent = time();
      
      if($tmRoundCurrent < $tmRoundEnd)
        return false;

      //회차 마감시간            
      $arrRoundInfo['round_time'] = date("Y-m-d H:i:s", $tmRoundEnd);;        
      
      return true;
    }


    //회차번호로부터 회차시작시간과 마감시간, 배팅초과시간 계산하는 함수-파워볼, 파워사다리
    function getPbRoundInfo(){

      $tmNow = time()+TM_OFFSET;
      
      $nHour = date("G",$tmNow);
      $nMin = date("i",$tmNow);

      $nSumMinutes = $nHour * 60 + $nMin ;
      $nRoundNo = floor($nSumMinutes / 5) ;
      $nRoundNo = $nRoundNo % 288 + 1;
      $arrRoundInfo['round_no'] = $nRoundNo;

      $strDate = "";
      if($nSumMinutes < 1440){
        $strDate = date( 'Y-m-d', $tmNow );
      }
      else {
        $strDate = date('Y-m-d', strtotime("+1 day", $tmNow));
      }

      $nSumMinutes = $nRoundNo * 5 ;
      $nHour = $nSumMinutes / 60;
      $nHour = floor($nHour);
      $nMinute = $nSumMinutes % 60;

      //현재시간설정      
      $tmRoundCurrent = date("Y-m-d H:i:s", $tmNow);        
      $arrRoundInfo['round_current'] = $tmRoundCurrent;

      //회차 마감시간설정
      $strRoundEnd = $strDate." ".$nHour.":".$nMinute.":"."0";
      $tmRoundEnd = strtotime($strRoundEnd);
      $tmRoundEnd = strtotime("-".TM_OFFSET." seconds", $tmRoundEnd);
      $arrRoundInfo['round_end'] = date("Y-m-d H:i:s", $tmRoundEnd);
      
      //회차 시작시간설정
      $tmRoundStart = strtotime("-5 minutes", $tmRoundEnd);
      $arrRoundInfo['round_start'] = date("Y-m-d H:i:s", $tmRoundStart);
      
      return $arrRoundInfo;
    }

     
    //회차번호로부터 회차시작시간과 마감시간, 배팅초과시간 계산하는 함수-보글파워볼, 보글사다리
    function getBRoundInfo($roundMin){

      $tmNow = time();
      
      $nHour = date("G",$tmNow);
      $nMin = date("i",$tmNow);
      
      $nSumMinutes = $nHour * 60 + $nMin ;
      $nRoundNo = floor($nSumMinutes / $roundMin) ;
      $nRoundNo = $nRoundNo % (1440/$roundMin) + 1;
      $arrRoundInfo['round_no'] = $nRoundNo;

      $strDate = date( 'Y-m-d', $tmNow );

      $nSumMinutes = $nRoundNo * $roundMin ;
      $nHour = $nSumMinutes / 60;
      $nHour = floor($nHour);
      $nMinute = $nSumMinutes % 60;

      //현재시간설정      
      $tmRoundCurrent = date("Y-m-d H:i:s", $tmNow);        
      $arrRoundInfo['round_current'] = $tmRoundCurrent;

      //회차 마감시간설정
      $strRoundEnd = $strDate." ".$nHour.":".$nMinute.":"."0";
      $tmRoundEnd = strtotime($strRoundEnd);
      $arrRoundInfo['round_end'] = date("Y-m-d H:i:s", $tmRoundEnd);
      
      //회차 시작시간설정
      $tmRoundStart = strtotime("-".$roundMin." minutes", $tmRoundEnd);
      $arrRoundInfo['round_start'] = date("Y-m-d H:i:s", $tmRoundStart);
      
      return $arrRoundInfo;
    }

    
    //베팅시간으로부터 정확한 회차날자 얻기
    function getRoundDate($strDateTime){
      //2021-01-33 23:33:33
      if(strlen($strDateTime) < 1)
        return "";
      
      $tmDate = strtotime($strDateTime);
      $strDate = date("Y-m-d", $tmDate);

      return $strDate;

    }

    function getBetTimeRange($arrReqData){
      $sCon = "";
      if (strlen($arrReqData['start']) <=10 && strlen($arrReqData['end']) <= 10) {
          $sCon = " bet_time >= '".$arrReqData['start']." 00:00:00' AND bet_time <= '".$arrReqData['end']." 23:59:59' ";
      } else {
          $sCon = " bet_time >= '".$arrReqData['start'].":00' AND bet_time <= '".$arrReqData['end'].":59' ";
      }
      return $sCon;

    }

    
    function getTimeRange($key, $arrReqData){
      $sCon = "";
      if (strlen($arrReqData['start']) <=10 && strlen($arrReqData['end']) <= 10) {
          $sCon = $key." >= '".$arrReqData['start']." 00:00:00' AND ".$key." <= '".$arrReqData['end']." 23:59:59' ";
      } else {
          $sCon = $key." >= '".$arrReqData['start'].":00' AND ".$key." <= '".$arrReqData['end'].":59' ";
      }
      return $sCon;

    }

    
    function getMemberState($objMember, $iGame){
      if(is_null($objMember))
            return false;
        else if($objMember->mb_state_active != STATE_ACTIVE)
            return false;
        else if($iGame > 0 && getStateByGame($objMember, $iGame) != PERMIT_OK) {
            return false;  
        }
        return true;
    }

    function getStateByGame($objMember, $iGame){

      switch($iGame){
          case GAME_POWER_BALL: 
          case GAME_HAPPY_BALL: return $objMember->mb_game_pb;
          case GAME_POWER_LADDER: return $objMember->mb_game_ps;
          case GAME_CASINO: return $objMember->mb_game_cs;
          case GAME_BOGLE_BALL: return $objMember->mb_game_bb;
          case GAME_BOGLE_LADDER: return $objMember->mb_game_bs;
          case GAME_SLOT_1: 
          case GAME_SLOT_2: 
          case GAME_SLOT_3: return $objMember->mb_game_sl;
          default: break;
      } 
      return 0;
    }
    
    function allMoney($member){
      $nMoney = 0;
      if(is_null($member))
        return $nMoney;

      $nMoney = $member->mb_money + $member->mb_live_money + $member->mb_slot_money + $member->mb_fslot_money + $member->mb_kgon_money + $member->mb_gslot_money;
      return $nMoney;
    }
    
    function siteFurl(){
      return $_ENV['app.furl']."/";
    }

    function site_furl($url){
      if(substr($url, 0, 1) == "/")
        return $_ENV['app.furl'].$url;
      else return $_ENV['app.furl']."/".$url;
    }

    function diffDt($dt1, $dt2){
      return abs( strtotime($dt1) - strtotime($dt2) );
    }
?>
