var mGameId = 5;
$(document).ready(function() {
    requestConfPowerball();
    addBtnEvent();
});

function showConfPowerball(objConfig) {

    if (objConfig.game_bet_permit == 1)
        document.getElementById("confpb-bet-check-id").checked = true;


    document.getElementById("confpb-endsec-input-id").value = objConfig.game_time_countdown;
    document.getElementById("confpb-delaysec-input-id").value = objConfig.game_time_delay;
    document.getElementById("confpb-minmoney-input-id").value = objConfig.game_min_bet_money;
    document.getElementById("confpb-maxmoney-input-id").value = objConfig.game_max_bet_money;
    document.getElementById("confpb-percent-input-id").value = objConfig.game_percent_1;
    document.getElementById("confpb-percent2-input-id").value = objConfig.game_percent_2;
    document.getElementById("confpb-ratio1-input-id").value = objConfig.game_ratio_1;
    document.getElementById("confpb-ratio2-input-id").value = objConfig.game_ratio_2;
    document.getElementById("confpb-ratio3-input-id").value = objConfig.game_ratio_3;
    document.getElementById("confpb-ratio4-input-id").value = objConfig.game_ratio_4;
    document.getElementById("confpb-ratio5-input-id").value = objConfig.game_ratio_5;
    document.getElementById("confpb-ratio6-input-id").value = objConfig.game_ratio_6;
    document.getElementById("confpb-ratio7-input-id").value = objConfig.game_ratio_7;
    document.getElementById("confpb-ratio8-input-id").value = objConfig.game_ratio_8;
    document.getElementById("confpb-ratio9-input-id").value = objConfig.game_ratio_9;
    document.getElementById("confpb-ratio10-input-id").value = objConfig.game_ratio_10;
    document.getElementById("confpb-ratio11-input-id").value = objConfig.game_ratio_11;
    document.getElementById("confpb-ratio12-input-id").value = objConfig.game_ratio_12;
    document.getElementById("confpb-ratio13-input-id").value = objConfig.game_ratio_13;
    document.getElementById("confpb-ratio14-input-id").value = objConfig.game_ratio_14;
    document.getElementById("confpb-ratio15-input-id").value = objConfig.game_ratio_15;
    document.getElementById("confpb-ratio16-input-id").value = objConfig.game_ratio_16;
    document.getElementById("confpb-ratio17-input-id").value = objConfig.game_ratio_17;
    document.getElementById("confpb-ratio18-input-id").value = objConfig.game_ratio_18;
    document.getElementById("confpb-ratio19-input-id").value = objConfig.game_ratio_19;
    document.getElementById("confpb-ratio20-input-id").value = objConfig.game_ratio_20;
    document.getElementById("confpb-ratio21-input-id").value = objConfig.game_ratio_21;
    document.getElementById("confpb-ratio22-input-id").value = objConfig.game_ratio_22;
    document.getElementById("confpb-ratio23-input-id").value = objConfig.game_ratio_23;
    document.getElementById("confpb-ratio24-input-id").value = objConfig.game_ratio_24;
    document.getElementById("confpb-ratio25-input-id").value = objConfig.game_ratio_25;
    document.getElementById("confpb-ratio26-input-id").value = objConfig.game_ratio_26;



}

function requestConfPowerball() {


    var jsonData = { "game_index": mGameId };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/api/conf_game",
        data: { json_: jsonData },
        success: function(jResult) {
            //console.log(jResult);
            if (jResult.status == "success") {
                showConfPowerball(jResult.data);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });

}

function readConfigToObject() {

    var jsonData = new Object();
    jsonData.game_index = mGameId;
    jsonData.game_bet_permit = document.getElementById("confpb-bet-check-id").checked ? 1 : 0;
    jsonData.game_time_countdown = document.getElementById("confpb-endsec-input-id").value;
    jsonData.game_time_delay = document.getElementById("confpb-delaysec-input-id").value;
    jsonData.game_min_bet_money = document.getElementById("confpb-minmoney-input-id").value;
    jsonData.game_max_bet_money = document.getElementById("confpb-maxmoney-input-id").value;
    jsonData.game_percent_1 = document.getElementById("confpb-percent-input-id").value;
    jsonData.game_percent_2 = document.getElementById("confpb-percent2-input-id").value;
    jsonData.game_ratio_1 = document.getElementById("confpb-ratio1-input-id").value;
    jsonData.game_ratio_2 = document.getElementById("confpb-ratio2-input-id").value;
    jsonData.game_ratio_3 = document.getElementById("confpb-ratio3-input-id").value;
    jsonData.game_ratio_4 = document.getElementById("confpb-ratio4-input-id").value;
    jsonData.game_ratio_5 = document.getElementById("confpb-ratio5-input-id").value;
    jsonData.game_ratio_6 = document.getElementById("confpb-ratio6-input-id").value;
    jsonData.game_ratio_7 = document.getElementById("confpb-ratio7-input-id").value;
    jsonData.game_ratio_8 = document.getElementById("confpb-ratio8-input-id").value;
    jsonData.game_ratio_9 = document.getElementById("confpb-ratio9-input-id").value;
    jsonData.game_ratio_10 = document.getElementById("confpb-ratio10-input-id").value;
    jsonData.game_ratio_11 = document.getElementById("confpb-ratio11-input-id").value;
    jsonData.game_ratio_12 = document.getElementById("confpb-ratio12-input-id").value;
    jsonData.game_ratio_13 = document.getElementById("confpb-ratio13-input-id").value;
    jsonData.game_ratio_14 = document.getElementById("confpb-ratio14-input-id").value;
    jsonData.game_ratio_15 = document.getElementById("confpb-ratio15-input-id").value;
    jsonData.game_ratio_16 = document.getElementById("confpb-ratio16-input-id").value;
    jsonData.game_ratio_17 = document.getElementById("confpb-ratio17-input-id").value;
    jsonData.game_ratio_18 = document.getElementById("confpb-ratio18-input-id").value;
    jsonData.game_ratio_19 = document.getElementById("confpb-ratio19-input-id").value;
    jsonData.game_ratio_20 = document.getElementById("confpb-ratio20-input-id").value;
    jsonData.game_ratio_21 = document.getElementById("confpb-ratio21-input-id").value;
    jsonData.game_ratio_22 = document.getElementById("confpb-ratio22-input-id").value;
    jsonData.game_ratio_23 = document.getElementById("confpb-ratio23-input-id").value;
    jsonData.game_ratio_24 = document.getElementById("confpb-ratio24-input-id").value;
    jsonData.game_ratio_25 = document.getElementById("confpb-ratio25-input-id").value;
    jsonData.game_ratio_26 = document.getElementById("confpb-ratio26-input-id").value;


    return jsonData;

}

function addBtnEvent() {

    var elemOkBtn = document.getElementById("confsite-ok-btn-id");
    elemOkBtn.addEventListener("click", function() {

        if (!confirm("저장하시겠습니까?"))
            return;

        var jsonData = readConfigToObject();
        jsonData = JSON.stringify(jsonData);

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/api/saveconfgame",
            data: { json_: jsonData },
            success: function(jResult) {
                if (jResult.status == "success") {
                    location.reload();
                } else if (jResult.status == "logout") {
                    window.location.replace('/');
                } else if (jResult.status == "fail") {
                    alert("저장이 실패되었습니다.");
                } else if (jResult.status == "nopermit") {
                    alert("권한이 없습니다.");
                }
            },
            error: function(request, status, error) {
                //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            }

        });
    });


    var elemCancelBtn = document.getElementById("confsite-cancel-btn-id");
    elemCancelBtn.addEventListener("click", function() {
        location.reload();
    });

}