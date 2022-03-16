
initMessageEdit();

function initMessageEdit(){
  addBtnEvent();
  requestMembers();
}
function readConfigToObject(){
  
  var jsonData = new Object();

  jsonData.notice_fid = document.getElementById("subnavbar-fid-p-id").innerHTML;
  jsonData.notice_type = document.getElementById("subnavbar-type-p-id").innerHTML;
  jsonData.notice_state_active = document.getElementById("notice-state-check-id").checked?1:0;
  jsonData.notice_title = document.getElementById("notice-title-input-id").value;
  jsonData.notice_content = document.getElementById("notice-content-text-id").value;
  jsonData.notice_answer = "";
  var elemNoticeAnswer = document.getElementById("notice-answer-text-id");
  if(elemNoticeAnswer != null){
      jsonData.notice_answer = elemNoticeAnswer.value;
      jsonData.notice_state_active = 1;
  }
  jsonData.notice_mb_uid = document.getElementById("notice-mbuid-input-id").value;  
    
  return jsonData;

}

function addBtnEvent(){

  var elemOkBtn = document.getElementById("notice-save-btn-id");
  elemOkBtn.addEventListener("click", function() {

  var jsonData = readConfigToObject();
  //console.log(jsonData);
  

  if(jsonData.notice_title.length < 1){
    alert("제목을 입력하세요.");
    return;
  }

  if(jsonData.notice_content.length < 1 ){
    alert("내용을 입력하세요.");
    return;
  }

  if(!confirm("발송하시겠습니까?"))
    return;

  if(parseInt(jsonData.notice_fid) > 0){
     
     jsonData = JSON.stringify(jsonData);
   $.ajax({
       type: "POST",
       dataType: "json",
       url:"/api/updatenotice",
       data: {json_: jsonData},
       success: function(jResult) {
        //console.log(jResult);
           if(jResult.status == "success") {
             window.location.replace('/board/message');
           }else if(jResult.status == "logout") {
              window.location.replace('/');
           }else if(jResult.status == "fail") {
               
           }
        },
        error:function(request,status,error){
          //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
      }

   });
  } else if(parseInt(jsonData.notice_fid) == 0){
    
    
    jsonData = JSON.stringify(jsonData);
   $.ajax({
       type: "POST",
       dataType: "json",
       url:"/api/addMessage",
       data: {json_: jsonData},
       success: function(jResult) {
        //console.log(jResult);
           if(jResult.status == "success")
           {
              window.location.replace('/board/message');
           }else if(jResult.status == "logout")
           {
              window.location.replace('/');
           }
           else if(jResult.status == "fail")
           {
               alert("발송자 아이디가 존재하지 않습니다.");
           } 
        },
        error:function(request,status,error){
          //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
      }

   });
  }
  
   
 });


  var elemCancelBtn = document.getElementById("notice-cancel-btn-id");
  elemCancelBtn.addEventListener("click", function() {
    window.location.replace('/board/message');
  });


}


function requestMembers(){
  $.ajax({
       type: "POST",
       dataType: "json",
       url:"/userapi/allmember",
       success: function(jResult) {
        //console.log(jResult);
           if(jResult.status == "success")
           {
              showAutoComplete(jResult.data);
           }else if(jResult.status == "logout")
           {
              window.location.replace('/');
           }
           else if(jResult.status == "fail")
           {
               
           } 
        },
        error:function(request,status,error){
          //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
      }

   });
}



function showAutoComplete(arrMember){
  $(function() {

    var arrData = new Array();
    arrData[0] = "*";
    for (nRow in arrMember ){
      arrData[parseInt(nRow)+1] =  arrMember[nRow].mb_uid;
    }
    //console.log(arrData);

    $("#notice-mbuid-input-id").autocomplete({

        source: arrData,

        select: function(event, ui) {

            //console.log(ui.item);

        },

        focus: function(event, ui) {

            return false;

        }

    });
  });

}