$(document).ready(function() {
    addEventListner();
    requestTotalPage();
    pbhitoryLoop();
});

function requestPageInfo() {
    requestBetHistory();
}


//Function to Show Betting History
function ShowBetHistory(jsonBetData) {
    var elemBetDataTb = document.getElementById("pbbet-table-id");
    var strBuf = "";

    var strWinMoney = "";
    var strGameType = "";
    for (nRow in jsonBetData) {

        strBuf += "<tr><td>";
        strBuf += jsonBetData[nRow].bet_fid;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].mb_uid;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].mb_nickname;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_time;
        strBuf += "</td><td>";
        strBuf += getGameName(jsonBetData[nRow].bet_game_type);
        strBuf += "</td><td>";
        if (jsonBetData[nRow].game_name != null)
            strBuf += jsonBetData[nRow].game_name;
        else
            strBuf += jsonBetData[nRow].bet_table_code;
        strBuf += "</td><td>";
        strBuf += parseInt(jsonBetData[nRow].bet_money).toLocaleString() + "원";
        strBuf += "</td>";

        strResult = "<td>";
        strWinMoney = "";
        if (parseInt(jsonBetData[nRow].bet_win_money) > parseInt(jsonBetData[nRow].bet_money)) {
            strResult = "<td  class = \"pb-home-table-betstate-earn\">적중";
            strWinMoney = (parseInt(jsonBetData[nRow].bet_win_money) - parseInt(jsonBetData[nRow].bet_money)).toLocaleString() + "원";

        } else if (jsonBetData[nRow].bet_win_money == jsonBetData[nRow].bet_money) {
            strResult = "<td  class = \"pb-home-table-betstate-wait\">비김";
        } else {
            strResult = "<td  class = \"pb-home-table-betstate-loss\">미적중"; //
        }

        strBuf += strResult;
        strBuf += "</td><td>";
        strBuf += strWinMoney;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].point_amount;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].employee_amount;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].agency_amount;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].company_amount;
        strBuf += "</td></tr>";

    }
    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='15'>자료가 없습니다.</td></tr>";
    }
    elemBetDataTb.innerHTML = strBuf;

}

function getGameName(strGameType) {
    var strGameName = "";

    if (strGameType == "0") {
        strGameName = "바카라";
    } else if (strGameType == "1") {
        strGameName = "용호";
    } else if (strGameType == "2") {
        strGameName = "슈퍼 식보";
    } else if (strGameType == "3") {
        strGameName = "룰렛";
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

    if (arrBetAccount == null) return;
    if (arrBetAccount.length != 4) return;

    $("#total-betmoney-id").text(parseInt(arrBetAccount[0]).toLocaleString() + " 원");
    $("#total-winmoney-id").text(parseInt(arrBetAccount[1]).toLocaleString() + " 원");
    $("#total-lossmoney-id").text(parseInt(arrBetAccount[2]).toLocaleString() + " 원");
    $("#total-benefit-id").text(parseInt(arrBetAccount[3]).toLocaleString() + " 원");

}


function addEventListner() {
    var butView = document.getElementById("pbhistory-list-view-but-id");
    butView.addEventListener("click", function() {
        requestTotalPage();
    });

    var selectView = document.getElementById("pbhistory-number-select-id");
    selectView.addEventListener("change", function() {
        requestTotalPage();
    });
}

//Function to Request Betting History to WebServer
function requestBetHistory() {

    var dtStart = document.getElementById("pbhistory-datestart-input-id").value;
    var dtEnd = document.getElementById("pbhistory-dateend-input-id").value;
    var strRound = ""; //document.getElementById("pbhistory-roundid-input-id").value;  
    var strUser = document.getElementById("pbhistory-userid-input-id").value;
    var nMode = document.getElementById("pbhistory-game-select-id").value;
    var nPage = getActivePage();

    var jsonData = { "count": CountPerPage, "page": nPage, "start": dtStart, "end": dtEnd, "user": strUser, "round": strRound, "mode": nMode };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: '/api/slbetlist',
        data: { json_: jsonData },
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            console.log(jResult);
            if (jResult.status == "success") {
                ShowBetHistory(jResult.data);
                ShowBetAccount(jResult.account);
            }
        },
        error: function(request, status, error) {
            console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}


function requestTotalPage() {

    var dtStart = document.getElementById("pbhistory-datestart-input-id").value;
    var dtEnd = document.getElementById("pbhistory-dateend-input-id").value;
    CountPerPage = document.getElementById("pbhistory-number-select-id").value;
    var strRound = ""; //document.getElementById("pbhistory-roundid-input-id").value;  
    var strUser = document.getElementById("pbhistory-userid-input-id").value;
    var nMode = document.getElementById("pbhistory-game-select-id").value;

    var jsonData = { "count": CountPerPage, "start": dtStart, "end": dtEnd, "user": strUser, "round": strRound, "mode": nMode };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: '/api/slbetlistcnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data.count;
                setFirstPage();
                setNavBarElement();
                requestBetHistory();
            }
        },
        error: function(request, status, error) {
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
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