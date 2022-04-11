$(document).ready(function() {

    requestConfBetSite();
    addBtnEvent();

});



function showConfSite(arrData) {
    if (arrData.length < 4)
        return;

    $("#conf-betsite-input-id").val(arrData[0]);
    $("#conf-userid-input-id").val(arrData[1]);
    $("#conf-userpwd-input-id").val(arrData[2]);
    $("#confpb-bet-check-id").prop('checked', arrData[3] > 0 ? true : false);
    // $("#conf-pball-input-id").val(arrData[3]);
    // $("#conf-pladder-input-id").val(arrData[4]);
    // $("#conf-bball-input-id").val(arrData[5]);
    // $("#conf-bladder-input-id").val(arrData[6]);

}


function addBtnEvent() {

    $("#confsite-ok-btn-id").click(function() {

        var objData = new Object();

        objData.site = "";
        if ($("#conf-betsite-input-id").length > 0) {
            objData.site = $("#conf-betsite-input-id").val();
            if (objData.site.length < 1) {
                alert("사이트명을 입력하세요.");
                return;
            }
        }
        objData.userid = $("#conf-userid-input-id").val();
        if (objData.userid.length < 1) {
            alert("계정 아이디를 입력하세요.");
            return;
        }
        objData.userpwd = $("#conf-userpwd-input-id").val();
        if (objData.userpwd.length < 1) {
            alert("계정 비밀번호를 입력하세요.");
            return;
        }
        objData.active = $("#confpb-bet-check-id").prop('checked') ? 1 : 0;
        // objData.pball = $("#conf-pball-input-id").val();
        // if (objData.pball.length < 1) {
        //     alert("파워볼 누르기율을 입력하세요.");
        //     return;
        // }
        // objData.pladder = $("#conf-pladder-input-id").val();
        // if (objData.pladder.length < 1) {
        //     alert("파워사다리 누르기율을 입력하세요.");
        //     return;
        // }
        // objData.bball = $("#conf-bball-input-id").val();
        // if (objData.bball.length < 1) {
        //     alert("보글파워볼 누르기율을 입력하세요.");
        //     return;
        // }
        // objData.bladder = $("#conf-bladder-input-id").val();
        // if (objData.bladder.length < 1) {
        //     alert("보글사다리 누르기율을 입력하세요.");
        //     return;
        // }
        var jsonData = JSON.stringify(objData);


        if (!confirm("저장하시겠습니까?"))
            return;

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/api/setBetSite",
            data: { json_: jsonData },
            success: function(jResult) {
                // console.log(jResult);
                if (jResult.status == "success") {
                    window.location.reload();
                } else if (jResult.status == "logout") {
                    window.location.replace('/');
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

}


function requestConfBetSite() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/api/getBetSite",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                showConfSite(jResult.data);
            } else if (jResult.status == "logout") {
                window.location.replace('/');
            } else if (jResult.status == "nopermit") {
                alert("권한이 없습니다.");
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}