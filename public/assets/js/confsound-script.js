

$(document).ready(function(){
  initConfig();
});

function initConfig(){

	requestSoundInfo();

	addEventListner();
  
}

function addEventListner(){
	var elemAlarmSelect1 = document.getElementById("confsound-alarm1-select-id");
    elemAlarmSelect1.addEventListener("change", function() {
		setUserSound(this.value, -1);
	});	

    var elemAlarmSelect2 = document.getElementById("confsound-alarm2-select-id");
    elemAlarmSelect2.addEventListener("change", function() {
		setChargeSound(this.value, -1);
	});	

    var elemAlarmSelect3 = document.getElementById("confsound-alarm3-select-id");
    elemAlarmSelect3.addEventListener("change", function() {
		setExchangeSound(this.value, -1);
	});	

    var elemAlarmSelect4 = document.getElementById("confsound-alarm4-select-id");
    elemAlarmSelect4.addEventListener("change", function() {
		setMessageSound(this.value, -1);
	});	


	var elemOkBtn = document.getElementById("confsite-ok-btn-id");
	elemOkBtn.addEventListener("click", function() {
		saveSoundInfo();
	});

	var elemCancelBtn = document.getElementById("confsite-cancel-btn-id");
	elemCancelBtn.addEventListener("click", function() {
	    location.reload();
	});
}




function showSoundInfo(arrSoundData){
	setUserSound(arrSoundData[0][0], arrSoundData[0][1]);
	setChargeSound(arrSoundData[1][0], arrSoundData[1][1]);
	setExchangeSound(arrSoundData[2][0], arrSoundData[2][1]);
	setMessageSound(arrSoundData[3][0], arrSoundData[3][1]);

	$("#confsound-alarm1-select-id").val(arrSoundData[0][0]).prop("selected", true);
	$("#confsound-alarm2-select-id").val(arrSoundData[1][0]).prop("selected", true);
	$("#confsound-alarm3-select-id").val(arrSoundData[2][0]).prop("selected", true);
	$("#confsound-alarm4-select-id").val(arrSoundData[3][0]).prop("selected", true);
}


function setUserSound(strSound, nVolume){

	elemAlarmPlayer = document.getElementById("confsound-alarm1-audio-id");
	elemAlarmSource = $("#confsound-alarm1-source-id");

	if(parseInt(nVolume) > 0){
		volume = 1;
		if(parseInt(nVolume) <= 100)
			volume = nVolume/100.0;
    	
    	elemAlarmPlayer.volume = volume;
    } 
    
	elemAlarmSource.attr("src", "/assets/sound/"+strSound);
	elemAlarmPlayer.load();

	if(nVolume < 0)
		elemAlarmPlayer.play(); 
}

function setChargeSound(strSound, nVolume){

	elemAlarmPlayer = document.getElementById("confsound-alarm2-audio-id");
	elemAlarmSource = $("#confsound-alarm2-source-id");

	if(parseInt(nVolume) > 0){
		volume = 1;
		if(parseInt(nVolume) <= 100)
			volume = nVolume/100.0;
    	
    	elemAlarmPlayer.volume = volume;
    } 
    
	elemAlarmSource.attr("src", "/assets/sound/"+strSound);
	elemAlarmPlayer.load();

	if(nVolume < 0)
		elemAlarmPlayer.play(); 
}


function setExchangeSound(strSound, nVolume){

	elemAlarmPlayer = document.getElementById("confsound-alarm3-audio-id");
	elemAlarmSource = $("#confsound-alarm3-source-id");

	if(parseInt(nVolume) > 0){
		volume = 1;
		if(parseInt(nVolume) <= 100)
			volume = nVolume/100.0;
    	
    	elemAlarmPlayer.volume = volume;
    } 
    
	elemAlarmSource.attr("src", "/assets/sound/"+strSound);
	elemAlarmPlayer.load();

	if(nVolume < 0)
		elemAlarmPlayer.play(); 
}


function setMessageSound(strSound, nVolume){

	elemAlarmPlayer = document.getElementById("confsound-alarm4-audio-id");
	elemAlarmSource = $("#confsound-alarm4-source-id");

	if(parseInt(nVolume) > 0){
		volume = 1;
		if(parseInt(nVolume) <= 100)
			volume = nVolume/100.0;
    	
    	elemAlarmPlayer.volume = volume;
    } 
    
	elemAlarmSource.attr("src", "/assets/sound/"+strSound);
	elemAlarmPlayer.load();

	if(nVolume < 0)
		elemAlarmPlayer.play(); 
}






function requestSoundInfo() {
  
  $.ajax({
       type: "POST",       
       dataType: "json",
       url:"/api/getsoundconf",
       success: function(jResult) {
           //console.log(jResult);            
           if(jResult.status == "success")
           {
              showSoundInfo(jResult.data);
           }
           else if(jResult.status == "fail")
           {

           } else if(jResult.status == "logout")
           {
                window.location.replace("/");
           }
        },
        error:function(request,status,error){
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
       }
     });
  
}




function saveSoundInfo() {
  	
	
	$("#confsound-alarm2-select-id").val();
	$("#confsound-alarm3-select-id").val();
	$("#confsound-alarm4-select-id").val();

	var arrData = [];

	var arrInfo = [];
	arrInfo[0] = $("#confsound-alarm1-select-id").val();
	arrInfo[1] = parseInt(document.getElementById("confsound-alarm1-audio-id").volume * 100);
	arrData.push(arrInfo);

	arrInfo = [];
	arrInfo[0] = $("#confsound-alarm2-select-id").val();
	arrInfo[1] = parseInt(document.getElementById("confsound-alarm2-audio-id").volume * 100);
	arrData.push(arrInfo);

	arrInfo = [];
	arrInfo[0] = $("#confsound-alarm3-select-id").val();
	arrInfo[1] = parseInt(document.getElementById("confsound-alarm3-audio-id").volume * 100);
	arrData.push(arrInfo);

	arrInfo = [];
	arrInfo[0] = $("#confsound-alarm4-select-id").val();
	arrInfo[1] = parseInt(document.getElementById("confsound-alarm4-audio-id").volume * 100);
	arrData.push(arrInfo);


	var jsonData = JSON.stringify(arrData);
	console.log(jsonData);
	
  	$.ajax({
       type: "POST",  
       data: {json_: jsonData},     
       dataType: "json",
       url:"/api/savesoundconf",
       success: function(jResult) {
           //console.log(jResult);            
           if(jResult.status == "success")
           {
           	  alert("저장되었습니다.");
              showSoundInfo(jResult.data);
           } else if(jResult.status == "fail")
           {

           } else if(jResult.status == "logout")
           {
                window.location.replace("/");
           }
        },
        error:function(request,status,error){
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
       }
     });
  	
}