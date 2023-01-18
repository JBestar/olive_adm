$(document).ready(function() {
    setNavBarElement();
    addEventListner();
    requestPageInfo();
    setTimeout(function() {
        requestTotalPage(false);
    }, 1000);
    setTimeout(function() {
        pbhitoryLoop();
    }, 300000);
});

function requestPageInfo() {
    requestBetHistory();
}


//Function to Show Betting History
function ShowBetHistory(jsonBetData) {
    var elemBetDataTb = document.getElementById("pbbet-table-id");
    var strBuf = "";
    var strWinMoney = "";
    var curPage = getActivePage();
    var firstIdx = (curPage - 1) * CountPerPage;

    for (nRow in jsonBetData) {

        strBuf += "<tr><td>";
        strBuf += (parseInt(nRow) + firstIdx + 1);
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_mb_uid;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_time;
        strBuf += "</td><td>";
        if(jsonBetData[nRow].prd_name != null)
            strBuf += jsonBetData[nRow].prd_name;
        strBuf += "</td><td>";
        strBuf += getGameName(jsonBetData[nRow].bet_game_type);
        strBuf += "</td><td>";
        if (jsonBetData[nRow].game_name != null)
            strBuf += jsonBetData[nRow].game_name;
        else if(jsonBetData[nRow].bet_game_id > 0){
            strBuf += jsonBetData[nRow].bet_round_no;
        } else
            strBuf += jsonBetData[nRow].bet_table_code;
        strBuf += "</td><td>";
        strBuf += parseInt(jsonBetData[nRow].bet_money).toLocaleString() + "원";
        strBuf += "</td><td>";
        strBuf += parseInt(jsonBetData[nRow].bet_win_money).toLocaleString() + "원";
        strBuf += "</td>";
        strResult = "<td>";
        // strWinMoney = "";
        if (parseInt(jsonBetData[nRow].bet_win_money) > parseInt(jsonBetData[nRow].bet_money)) {
            strResult = "<td  class = 'pb-home-table-betstate-earn'>적중";
            // strWinMoney = (parseInt(jsonBetData[nRow].bet_win_money) - parseInt(jsonBetData[nRow].bet_money)).toLocaleString() + "원";
        } else if (jsonBetData[nRow].bet_win_money == jsonBetData[nRow].bet_money) {
            strResult = "<td  class = 'pb-home-table-betstate-wait'>타이";
        } else {
            strResult = "<td  class = 'pb-home-table-betstate-loss'>미적중"; //
        }
        strBuf += strResult;
        strBuf += "</td><td>";
        if (jsonBetData[nRow].rw_point != null)
            strBuf += jsonBetData[nRow].rw_point;
        else strBuf += "0";
        strBuf += "</td></tr>";

    }
    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='15'>자료가 없습니다.</td></tr>";
    }
    elemBetDataTb.innerHTML = strBuf;

}

function getGameName(strGameType) {
    var strGameName = "";

    if (strGameType == "1") {
        strGameName = "바카라";
    } else if (strGameType == "2") {
        strGameName = "룰렛";
    } else if (strGameType == "3") {
        strGameName = "식보";
    } else if (strGameType == "4") {
        strGameName = "바카라보";
    } else if (strGameType == "5") {
        strGameName = "용호";
    } else if (strGameType == "6") {
        strGameName = "판탄";
    } else {
        strGameName = strGameType;
    }

    return strGameName;

}


function ShowBetAccount(arrBetAccount) {

    $("#total-betmoney-id").text("0");
    $("#total-winmoney-id").text("0");
    $("#total-lossmoney-id").text("0");
    $("#total-benefit-id").text("0");

    if (arrBetAccount == null) {
        $(".pbresult-list-page-div p").css('display', 'none');
        return;
    }
    if (arrBetAccount.length != 4) return;
    $(".pbresult-list-page-div p").css('display', 'block');

    $("#total-betmoney-id").text(parseInt(arrBetAccount[0]).toLocaleString() + " 원");
    $("#total-winmoney-id").text(parseInt(arrBetAccount[1]).toLocaleString() + " 원");
    $("#total-lossmoney-id").text(parseInt(arrBetAccount[2]).toLocaleString() + " 원");
    $("#total-benefit-id").text(parseInt(arrBetAccount[3]).toLocaleString() + " 원");

}


function addEventListner() {

    $("#pbhistory-list-view-but-id").click(function() {
        requestTotalPage();
    });

    $("#pbhistory-number-select-id").change(function() {
        requestTotalPage();
    });
}

//Function to Request Betting History to WebServer
function requestBetHistory() {

    var dtStart = $("#pbhistory-datestart-input-id").val();
    var dtEnd = $("#pbhistory-dateend-input-id").val();
    CountPerPage = $("#pbhistory-number-select-id").val();
    var strUser = $("#pbhistory-userid-input-id").val();
    var nMode = 0;
    if($("#pbhistory-game-select-id").length > 0)
        nMode = $("#pbhistory-game-select-id").val();

    var nPage = getActivePage();
    var strEmp = "";
    if ($("#pbhistory-empid-input-id").length > 0) {
        strEmp = $("#pbhistory-empid-input-id").val();
    }

    var jsonData = {
        "count": CountPerPage,
        "page": nPage,
        "start": dtStart,
        "end": dtEnd,
        "emp": strEmp,
        "user": strUser,
        "mode": nMode
    };
    jsonData = JSON.stringify(jsonData);
    $(".loading").show();
    $.ajax({
        url: FURL + '/api/csbetlist',
        data: { json_: jsonData },
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            $(".loading").hide();
            // console.log(jResult);
            if (jResult.status == "success") {
                ShowBetHistory(jResult.data);
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}


function requestTotalPage(bReqPage = true) {

    var dtStart = $("#pbhistory-datestart-input-id").val();
    var dtEnd = $("#pbhistory-dateend-input-id").val();
    CountPerPage = $("#pbhistory-number-select-id").val();
    var strUser = $("#pbhistory-userid-input-id").val();
    var nMode = 0;
    if($("#pbhistory-game-select-id").length > 0)
        nMode = $("#pbhistory-game-select-id").val();
    var strEmp = "";
    if ($("#pbhistory-empid-input-id").length > 0) {
        strEmp = $("#pbhistory-empid-input-id").val();
    }
    var jsonData = {
        "count": CountPerPage,
        "start": dtStart,
        "end": dtEnd,
        "emp": strEmp,
        "user": strUser,
        "mode": nMode
    };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: FURL + '/api/csbetlistcnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data.count;
                setFirstPage();
                if(bReqPage)
                    requestBetHistory();
                ShowBetAccount(jResult.account);
                
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}


function pbhitoryLoop() {

    requestBetHistory();

    // 1초뒤에 다시 실행
    setTimeout(function() {
        pbhitoryLoop();
    }, 300000);
}