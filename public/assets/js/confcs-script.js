$(document).ready(function() {
    requestConfPowerball();
    addBtnEvent();
});

function showConfPowerball(objConfig) {

    if (objConfig.game_bet_permit == 1)
        $("#confpb-bet-check-id").prop('checked', true);
    else $("#confpb-bet-check-id").prop('checked', false);

    $("#confpb-minmoney-input-id").val(objConfig.game_min_bet_money);


}

function requestConfPowerball() {

    let gameId = $(".confsite-game-panel").attr('id');
    var jsonData = { "game_index": gameId };
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
    jsonData.game_index = $(".confsite-game-panel").attr('id');;
    jsonData.game_bet_permit = $("#confpb-bet-check-id").prop('checked') ? 1 : 0;
    jsonData.game_min_bet_money = $("#confpb-minmoney-input-id").val();

    return jsonData;

}

function addBtnEvent() {

    $('#confsite-ok-btn-id').on('click', function() {

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


    $('#confsite-cancel-btn-id').on('click', function() {
        location.reload();
    });

}