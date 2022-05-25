$(document).ready(function() {

    requestConfBetSite();
    addBtnEvent();

});



function showConfSite(arrData) {
    if (arrData.length < 5)
        return;

    $("#conf-betsite-input-id").val(arrData[0]);
    $("#conf-userid-input-id").val(arrData[1]);
    $("#conf-userpwd-input-id").val(arrData[2]);
    $("#confpb-bet-check-id").prop('checked', arrData[3] > 0 ? true : false);
    $("#conf-bettype-select-id").val(arrData[4]);
    changeBetType();

}


function addBtnEvent() {

    $("#confsite-ok-btn-id").click(function() {

        var objData = new Object();

        objData.site = "";
        if ($("#conf-betsite-input-id").length > 0) {
            objData.site = $("#conf-betsite-input-id").val();
            // if (objData.site.length < 1) {
            //     alert("사이트명을 입력하세요.");
            //     return;
            // }
        }
        objData.userid = $("#conf-userid-input-id").val();
        // if (objData.userid.length < 1) {
        //     alert("계정 아이디를 입력하세요.");
        //     return;
        // }
        objData.userpwd = $("#conf-userpwd-input-id").val();
        // if (objData.userpwd.length < 1) {
        //     alert("계정 비밀번호를 입력하세요.");
        //     return;
        // }
        objData.active = $("#confpb-bet-check-id").prop('checked') ? 1 : 0;
        objData.type = $("#conf-bettype-select-id").val();
        
        var jsonData = JSON.stringify(objData);

        if (!confirm("저장하시겠습니까?"))
            return;

        $.ajax({
            type: "POST",
            dataType: "json",
            url: FURL + "/api/setBetSite",
            data: { json_: jsonData },
            success: function(jResult) {
                // console.log(jResult);
                if (jResult.status == "success") {
                    window.location.reload();
                } else if (jResult.status == "logout") {
                    window.location.replace( FURL +'/');
                } else if (jResult.status == "fail") {
                    alert("저장이 실패되었습니다.");
                } else if (jResult.status == "nopermit") {
                    alert("권한이 없습니다.");
                }
            },
            error: function(request, status, error) {
                // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            }

        });


    });


    $("#confsite-cancel-btn-id").click(function() {
        location.reload();
    });

    
    $("#conf-bettype-select-id").change(function() {
        changeBetType();
    });
}

function changeBetType(){
    if($("#conf-bettype-select-id").val() == "1"){
        $("#conf-pw-label").text("계정 토큰키");
        // $("conf-money-div").show();
    } else{
        $("#conf-pw-label").text("계정 비밀번호");
        // $("conf-money-div").hide();
    } 
}

function requestConfBetSite() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/getBetSite",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                showConfSite(jResult.data);
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            } else if (jResult.status == "nopermit") {
                alert("권한이 없습니다.");
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}