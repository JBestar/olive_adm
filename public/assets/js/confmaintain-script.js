

addBtnEvent();

function readConfigToObject(){
	
  var objData = new Object();
  
  objData.maintain = document.getElementById("confsite-maintain-select-id").value==1?1:0;
  
  objData.content = $("#confsite-maintain-text-id").val();
  

  return objData;

}

function addBtnEvent(){

  var elemOkBtn = document.getElementById("confsite-ok-btn-id");
  elemOkBtn.addEventListener("click", function() {

	var objData = readConfigToObject();
  jsonData = JSON.stringify(objData);
  
  //console.log(jsonData);
  if(objData.maintain == 1){
    if(!confirm("점검을 진행하시겠습니까?"))
      return;
  } else {
    if(!confirm("정상운영을 진행하시겠습니까?"))
      return;
  }

  
   $.ajax({
       type: "POST",
       dataType: "json",
       url:"/api/saveconfmaintain",
       data: {json_: jsonData},
       success: function(jResult) {
           if(jResult.status == "success")
           {
           		window.location.reload();
           }else if(jResult.status == "logout")
           {
              window.location.replace('/');
           }
           else if(jResult.status == "fail")
           {
               alert("저장이 실패되었습니다.");
           }
       	},
       	error:function(request,status,error){
          //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
    	}

   });
   

 });
  
  
  var elemCancelBtn = document.getElementById("confsite-cancel-btn-id");
  elemCancelBtn.addEventListener("click", function() {
      window.location.reload();
  });

}


