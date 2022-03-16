
addBtnEvent();

function readPasswordToObject(){
	
  var jsonData = new Object();

  jsonData.password = document.getElementById("confsite-password-input-id").value;
  jsonData.password_new = document.getElementById("confsite-password-new-input-id").value;
  jsonData.password_newok = document.getElementById("confsite-password-newok-input-id").value;
  
  return jsonData;

}

function addBtnEvent(){

var elemOkBtn = document.getElementById("confsite-ok-btn-id");
elemOkBtn.addEventListener("click", function() {

  

	var jsonData = readPasswordToObject();

  if(jsonData.password.length < 1){
    alert("이전 비밀번호를 입력하세요.");
    return;
  }

  if(jsonData.password_new.length < 1){
    alert("새 비밀번호를 입력하세요.");
    return;
  }

  if( jsonData.password_new != jsonData.password_newok){
    alert("새 비밀번호를 정확히 입력하세요.");
    return;    
  }

  if(!confirm("비밀번호를 변경하시겠습니까?"))
    return;

  jsonData = JSON.stringify(jsonData);

   $.ajax({
       type: "POST",
       dataType: "json",
       url:"/api/changepassword",
       data: {json_: jsonData},
       success: function(jResult) {
       	//console.log(jResult);
           if(jResult.status == "success")
           {
           		alert("비밀번호가 변경되었습니다.");
           }else if(jResult.status == "mistake")
           {
              alert("이전비밀번호가 틀립니다.");
           }
           else if(jResult.status == "fail")
           {
               alert("비밀번호 변경이 실패되었습니다.");
           }
       	},
       	error:function(request,status,error){
          //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
    	}

   });
 });


  var elemCancelBtn = document.getElementById("confsite-cancel-btn-id");
  elemCancelBtn.addEventListener("click", function() {
    window.location.replace('/home/conf_password');
  });

}


