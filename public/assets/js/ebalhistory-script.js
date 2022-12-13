$(document).ready(function() {
    setNavBarElement();
    addEventListner();
    requestTotalPage();
    setTimeout(function() {
        pbhitoryLoop();
    }, 60000);
});

function requestPageInfo() {
    requestBetHistory();
}


//Function to Show Betting History
function ShowBetHistory(jsonBetData) {
    var elemBetDataTb = document.getElementById("pbbet-table-id");
    var strBuf = "";
    var curPage = getActivePage();
    var firstIdx = (curPage - 1) * CountPerPage;

    let playerAmount = 0, bankerAmount = 0, betAmount = 0, winAmount = 0;

    for (nRow in jsonBetData) {

        strBuf += "<tr><td>";
        strBuf += (parseInt(nRow) + firstIdx + 1);
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_site_name + "<br>" + jsonBetData[nRow].bet_site_uid ;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_tm_req;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_game_type;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_table_name;
        strBuf += "</td><td>";
        if (parseInt(jsonBetData[nRow].bet_type) <= 1)
            strBuf += "밸런스";
        else if(parseInt(jsonBetData[nRow].bet_type) == 2)
            strBuf += "팅김방지";
        strBuf += "</td><td>";
        playerAmount = parseInt(jsonBetData[nRow].bet_player);
        bankerAmount = parseInt(jsonBetData[nRow].bet_banker);
        betAmount = parseInt(jsonBetData[nRow].bet_amount);
        winAmount = parseInt(jsonBetData[nRow].bet_win_amount);
        if (parseInt(jsonBetData[nRow].bet_type) <= 1)
            strBuf += playerAmount.toLocaleString() + " / " + bankerAmount.toLocaleString();
        strBuf += "</td><td>";
        strBuf += betAmount.toLocaleString() + "원";
        strBuf += "</td><td>";
        strBuf += getEvolSide(jsonBetData[nRow].bet_choice);
        strBuf += "</td><td>";
        strBuf += getEvolSide(jsonBetData[nRow].bet_result);
        strBuf += "</td><td>";
        strBuf += winAmount.toLocaleString() + "원";
        strBuf += "</td><td>";
        strResult = "";

        if (parseInt(jsonBetData[nRow].bet_type) == 0){
            if(jsonBetData[nRow].bet_result == "Player")
                strResult = (bankerAmount-Math.floor(playerAmount/1000)*1000 - betAmount + winAmount).toLocaleString();
            else if(jsonBetData[nRow].bet_result == "Banker")
                strResult = (playerAmount-Math.floor(bankerAmount/1000)*950 - betAmount + winAmount).toLocaleString();
            else strResult = 0;
        } else if(parseInt(jsonBetData[nRow].bet_type) == 1) {
            if(jsonBetData[nRow].bet_result == "Player")
                strResult = (bankerAmount%1000 ).toLocaleString();
            else if(jsonBetData[nRow].bet_result == "Banker")
                strResult = (playerAmount%1000 + Math.floor(playerAmount/1000) * 50 ).toLocaleString();
            else strResult = 0;
        } 
        
        strBuf += strResult;
        strBuf += "</td></tr>";

    }
    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='8'>자료가 없습니다.</td></tr>";
    }
    elemBetDataTb.innerHTML = strBuf;

}


function ShowBetAccount(arrBetAccount) {

    $("#total-betmoney-id").text("0");
    $("#total-balmoney-id").text("0");
    $("#total-bankermoney-id").text("0");
    $("#total-conmoney-id").text("0");
    $("#total-profit-id").text("0");

    if (arrBetAccount == null) {
        $(".pbresult-list-page-div p").css('display', 'none');
        return;
    }
    if (arrBetAccount.length != 6) return;
    $(".pbresult-list-page-div p").css('display', 'block');

    $("#total-betmoney-id").text(parseInt(arrBetAccount[0]).toLocaleString() + " 원");
    $("#total-balmoney-id").text(parseInt(arrBetAccount[1]).toLocaleString() + " 원");
    $("#total-bankermoney-id").text(parseInt(arrBetAccount[2]).toLocaleString() + " 원");
    $("#total-profit-id").text(parseInt(arrBetAccount[3]).toLocaleString() + " 원");
    $("#total-conmoney-id").text(parseInt(arrBetAccount[4]).toLocaleString() + " / " + parseInt(arrBetAccount[5]).toLocaleString());

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
    var strRoom = $("#pbhistory-room-input-id").val();
    var strbet = $("#pbhistory-bet-select-id").val();
    var nPage = getActivePage();

    var jsonData = {
        "count": CountPerPage,
        "page": nPage,
        "start": dtStart,
        "end": dtEnd,
        "user": strUser,
        "room": strRoom,
        "bet": strbet,
    };
    jsonData = JSON.stringify(jsonData);
    $(".loading").show();
    $.ajax({
        url: FURL + '/api/ebalbetlist',
        data: { json_: jsonData },
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            $(".loading").hide();
            // console.log(jResult);
            if (jResult.status == "success") {
                ShowBetHistory(jResult.data);
                ShowBetAccount(jResult.account)
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}


function requestTotalPage() {

    var dtStart = $("#pbhistory-datestart-input-id").val();
    var dtEnd = $("#pbhistory-dateend-input-id").val();
    CountPerPage = $("#pbhistory-number-select-id").val();
    var strUser = $("#pbhistory-userid-input-id").val();
    var strRoom = $("#pbhistory-room-input-id").val();
    var strbet = $("#pbhistory-bet-select-id").val();
    
    var jsonData = {
        "count": CountPerPage,
        "start": dtStart,
        "end": dtEnd,
        "user": strUser,
        "room": strRoom,
        "bet": strbet,
    };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: FURL + '/api/ebalbetcnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data.count;
                setFirstPage();
                requestBetHistory();
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
    }, 60000);
}