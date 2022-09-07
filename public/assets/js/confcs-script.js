$(document).ready(function() {
    setNavBarElement();
    requestConfGame();
    addBtnEvent();
});

function showConfGame(objConfig, objAgent) {

    if (objConfig.game_bet_permit == 1)
        $("#confpb-bet-check-id").prop('checked', true);
    else $("#confpb-bet-check-id").prop('checked', false);

    if (objAgent != null) {
        $("#confpb-agent-code-id").val(objAgent.code);
        $("#confpb-agent-egg-id").val(parseInt(objAgent.egg).toLocaleString());
        
        if(objAgent.useregg != null)
            $("#confpb-user-egg-id").val(parseInt(objAgent.useregg).toLocaleString());
        else $("#confpb-user-egg-id").val(0);
    }
}

function requestConfGame() {

    let gameId = $(".confsite-game-panel").attr('id');
    var jsonData = { "game_index": gameId };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/conf_game",
        data: { json_: jsonData },
        success: function(jResult) {
            $("#refresh_egg").removeClass("refresh");
            $("#refresh_useregg").removeClass("refresh");

            // console.log(jResult);
            if (jResult.status == "success") {
                showConfGame(jResult.data, jResult.agent);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            $("#refresh_egg").removeClass("refresh");
            $("#refresh_useregg").removeClass("refresh");
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });

}

function requestRecoveryEgg() {
    $("#recovery_useregg").addClass("refresh");

    let gameId = $(".confsite-game-panel").attr('id');
    var jsonData = { "game_index": gameId };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/eggrecovery",
        data: { json_: jsonData },
        success: function(jResult) {
            $("#recovery_useregg").removeClass("refresh");

            // console.log(jResult);
            if (jResult.status == "success") {
                $("#confpb-agent-egg-id").val(parseInt(jResult.egg).toLocaleString());
                $("#confpb-user-egg-id").val(parseInt(jResult.useregg).toLocaleString());

            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            $("#recovery_useregg").removeClass("refresh");
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });

}

function readConfigToObject() {

    var jsonData = new Object();
    jsonData.game_index = $(".confsite-game-panel").attr('id');;
    jsonData.game_bet_permit = $("#confpb-bet-check-id").prop('checked') ? 1 : 0;
    // jsonData.game_min_bet_money = $("#confpb-minmoney-input-id").val();

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
            url: FURL + "/api/saveconfgame",
            data: { json_: jsonData },
            success: function(jResult) {
                if (jResult.status == "success") {
                    location.reload();
                } else if (jResult.status == "logout") {
                    window.location.replace( FURL +'/');
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

    
    $('#refresh_egg').on('click', function() {
        $(this).addClass("refresh");
        requestConfGame();
    });

    $('#refresh_useregg').on('click', function() {
        $(this).addClass("refresh");
        requestConfGame();
    });

    $('#recovery_useregg').on('click', function() {
        if(confirm("알회수 시간이 오래 걸릴 수 있습니다. 그래도 계속하시겠습니까?"))
            requestRecoveryEgg();
    });

    $('#confsite-agent-btn-id').on('click', function() {
        
        let gameId = $(".confsite-game-panel").attr('id');
        var openWindow = window.open("about:blank");
        if(gameId == 4){
            openWindow.location.href = "https://www.hpplaycasion.com/";
        } else if(gameId == 7){
            openWindow.location.href = "http://system-theplus.com/login";
        } else if(gameId == 8){
            openWindow.location.href = "http://agent.gsplay-777.com/agent";
        } else if(gameId == 3){
            openWindow.location.href = "https://v1.kgonapi.com";
        }
        
    });
    
}