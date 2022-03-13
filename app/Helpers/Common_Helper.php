<?php

  	function is_login(){ 
      if(!isset($_SESSION['logged_in']))
        return false;
      else if($_SESSION['logged_in']==TRUE)
        return true;
      else return false;  
  	}

    //사이드바의 선택상태 초기화 배렬을 반환해주는 함수
    function getSidebarClass() {

      return array(
          'side_item_1' => '',
          'side_item_2' => '',
          'side_item_3' => '',
          'side_item_4' => '',
          'side_item_5' => '',
          'side_item_6' => ''
      );
    }

    
    //회차시작시간과 마감시간, 배팅초과시간 계산하는 함수-파워볼, 파워사다리
    function getPbRoundTimes($objConfPb){

      //date_default_timezone_set('Asia/Seoul');
      //$tmNow = mktime('23','59','40','5','25','2021')+TM_OFFSET;
      $tmNow = time()+TM_OFFSET;
      $nYear = date("Y",$tmNow);
      $nMonth = date("m",$tmNow);
      $nDay = date("d",$tmNow);

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

      $arrRoundInfo['round_date'] = $strDate;

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
      $arrRoundInfo['round_end'] = date("Y-m-d H:i:s", $tmRoundEnd);
      
      //회차 시작시간설정
      $tmRoundStart = strtotime("-5 minutes", $tmRoundEnd);
      $arrRoundInfo['round_start'] = date("Y-m-d H:i:s", $tmRoundStart);
      
      $tmBetEnd = 0;
      //베팅 마감시간설정
      if($objConfPb->game_countdown >= 20 && $objConfPb->game_countdown <= 280 ) {
        //$objConfPb->game_countdown += 5;
        $tmBetEnd = strtotime("-".$objConfPb->game_countdown." seconds", $tmRoundEnd);      
      } else $tmBetEnd = strtotime("-1 minutes", $tmRoundEnd); 

      $arrRoundInfo['round_bet_end'] = date("Y-m-d H:i:s", $tmBetEnd);
      
      return $arrRoundInfo;
    }


    
    function calcRoundId($objLastRound, &$arrRoundData) {
      $iResult = 0;   //0:비정상 1:정상
      if($objLastRound->round_date == $arrRoundData['round_date']){
        $arrRoundData['round_id'] = $objLastRound->round_fid + $arrRoundData['round_no'] - $objLastRound->round_num;
        $iResult = 1;
        
      } else if($objLastRound->round_date < $arrRoundData['round_date']){
        
        $date1 = date_create($objLastRound->round_date);
        $date2 = date_create($arrRoundData['round_date']);
        
        $dtDiff = date_diff($date1, $date2);
        $nRoundDiff = $dtDiff->days*288 + $arrRoundData['round_no'] - $objLastRound->round_num;
        $arrRoundData['round_id'] = $objLastRound->round_fid + $nRoundDiff;
        if($nRoundDiff > 0 && $nRoundDiff < 300)
          $iResult = 1;
        
      } else {
        $arrRoundData['round_id'] = $objLastRound->round_fid + 1;
      }
      return $iResult;
    }


?>
