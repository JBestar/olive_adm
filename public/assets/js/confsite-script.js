


  $(document).ready(function(){

    addBtnEvent();

  });

function readConfigToObject(){
	
  var jsonData = new Object();

  jsonData.sitename = document.getElementById("confsite-sitename-input-id").value;
  jsonData.domainname = document.getElementById("confsite-domainname-input-id").value;
  jsonData.homepage = document.getElementById("confsite-homepage-input-id").value;
  jsonData.adminpage = document.getElementById("confsite-adminpage-input-id").value;
  jsonData.bank = document.getElementById("confsite-bank-input-id").value;
  //jsonData.mainnotice = document.getElementById("confsite-mainnotice-text-id").value;
  jsonData.depositenotice = document.getElementById("confsite-deposite-text-id").value;
  jsonData.withdrawnotice = document.getElementById("confsite-withdraw-text-id").value;
  jsonData.bankmacro = $("#confsite-bankmacro-text-id").val();
  //jsonData.mainnoticeok = document.getElementById("confsite-mainnotice-check-id").checked?1:0;

  return jsonData;

}

function addBtnEvent(){

var elemOkBtn = document.getElementById("confsite-ok-btn-id");
elemOkBtn.addEventListener("click", function() {

  if(!confirm("저장하시겠습니까?"))
    return;

	var jsonData = readConfigToObject();
  jsonData = JSON.stringify(jsonData);
  //console.log(jsonData);
  
   $.ajax({
       type: "POST",
       dataType: "json",
       url:"/api/saveconfsite",
       data: {json_: jsonData},
       success: function(jResult) {
       	//console.log(jResult);
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

      
  function cleanDb(iType){

      if(!confirm("디비정리를 하시겠습니까?"))
            return;
      var objData = { "clean":iType};
          
      requestCleanDb(objData);
          
  }



  function requestCleanDb(objData){
      if(objData == null)
          return;

      var jsonData = JSON.stringify(objData);

      $.ajax({
          type: "POST",
          dataType: "json",
          data: {json_: jsonData},
          url:"/api/cleanDb",
          success: function(jResult) {
              //console.log(jResult);
              if(jResult.status == "success")
              {
                  alert("디비정리가 완료되었습니다.!");
                  location.reload();
              } else if(jResult.status == "fail"){
                  if(jResult.data == 2)
                      alert("권한이 없습니다.");
                  else alert("거절되었습니다.");
              } else if(jResult.status == "logout"){
                  location.reload();
              }
          },
          error:function(request,status,error){
              //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
          }
      });
      
  }
