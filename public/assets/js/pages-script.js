
$(document).ready(function(){

 /*Attempt register user jquery ajax*/
   $('#login-okbtn-id').click(function(){ 
   		login();
    });

     $("#login-user-input-id").keypress(function(event){
	  var nKeyCode = event.which || event.keyCode;
	  if(nKeyCode == 13)
	  	login();

	});

   
   $("#login-pwd-input-id").keypress(function(event){
	  var nKeyCode = event.which || event.keyCode;
	  if(nKeyCode == 13)
	  	login();

	});


});

   function login(){
   	var user_name = $('#login-modal-panel-id').find('#login-user-input-id').val();

       var user_password = $('#login-modal-panel-id').find('#login-pwd-input-id').val();

       if(user_name==""||user_password=="")
           return;

       var login_data = { "username":user_name, "password":user_password};
       var jsonData = JSON.stringify(login_data);

       //console.log(jsonData);

       $.ajax({
	       type: "POST",
	       dataType: "json",
	       url:"/api/login",
	       data: {json_: jsonData},
	       success: function(jResult) {
	       	//console.log(jResult);
	           if(jResult.status == "success")
	           {
	           		//console.log(jResult.data.redirect);
	           	   window.location.replace(jResult.data.redirect);

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