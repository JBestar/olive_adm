$(document).ready(function() {
    setNavBarElement();
    requestConfBetSite();
    addBtnEvent();

});


function showConfSite(arrData) {
    if (arrData.length < 6)
        return;
    
    $("#confev-betsite-input-id").val(arrData[0]);
    $("#confev-userid-input-id").val(arrData[1]);
    $("#confev-userpwd-input-id").val(arrData[2]);
    $("#confev-bet-check-id").prop('checked', arrData[3] > 0 ? true : false);
    $("#confev-bettype-select-id").val(arrData[4]);
    $("#confev-betend-input-id").val(arrData[5]);
    $("#confev-conbet-check-id").prop('checked', arrData[6] > 0 ? true : false);

}


function addBtnEvent() {

    $("#confsite-ok-btn-id").click(function() {

        var objData = new Object();

        objData.site_ev = $("#confev-betsite-input-id").val();
        objData.userid_ev = $("#confev-userid-input-id").val();
        objData.userpwd_ev = $("#confev-userpwd-input-id").val();
        objData.active_ev = $("#confev-bet-check-id").prop('checked') ? 1 : 0;
        objData.type_ev = $("#confev-bettype-select-id").val();
        objData.bet_ev = $("#confev-betend-input-id").val();
        objData.con_ev = $("#confev-conbet-check-id").prop('checked') ? 1 : 0;

        var jsonData = JSON.stringify(objData);

        if (!confirm("저장하시겠습니까?"))
            return;

        $.ajax({
            type: "POST",
            dataType: "json",
            url: FURL + "/api/setEvolSite",
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

}

function requestConfBetSite() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/getEvolSite",
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