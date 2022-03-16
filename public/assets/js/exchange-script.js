$(document).ready(function() {
    addEventListner();
    requestTotalPage();
});

function requestPageInfo() {
    requestMoneyHistory();
}

//Function to Show Betting History
function showMoneyHistory(jsonBetData) {
    var elemBetDataTb = document.getElementById("bank-exchange-table-id");
    var strBuf = "";

    var nCurBetCnt = 0;
    var nCurBetMoney = 0;
    var strBetMode = "";
    var strBetTarget = "";
    var strResultTarget = "";
    var strWinMoney = "";
    var strResult = "";

    for (nRow in jsonBetData) {

        strBuf += "<tr><td>";
        strBuf += jsonBetData[nRow].money_fid;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].mb_nickname;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].money_mb_uid;
        strBuf += "</td><td>";
        strBuf += parseInt(jsonBetData[nRow].mb_money).toLocaleString();
        strBuf += "</td><td>";
        strBuf += parseInt(jsonBetData[nRow].money_amount).toLocaleString();
        strBuf += "</td><td>";
        strBuf += parseInt(jsonBetData[nRow].money_before).toLocaleString();
        strBuf += "</td><td>";
        strBuf += parseInt(jsonBetData[nRow].money_after).toLocaleString();
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].money_update_time;
        strBuf += "</td><td>";
        if (jsonBetData[nRow].money_change_type == 1) {
            strBuf += "입금";
        } else if (jsonBetData[nRow].money_change_type == 2) {
            strBuf += "출금";
        } else if (jsonBetData[nRow].money_change_type == 3) {
            strBuf += "포인트 전환";
        } else if (jsonBetData[nRow].money_change_type == 4) {
            strBuf += "파워볼 배팅";
        } else if (jsonBetData[nRow].money_change_type == 5) {
            strBuf += "파워볼 자동배팅";
        } else if (jsonBetData[nRow].money_change_type == 6) {
            strBuf += "파워볼 정산";
        } else if (jsonBetData[nRow].money_change_type == 7) {
            strBuf += "파워사다리 배팅";
        } else if (jsonBetData[nRow].money_change_type == 8) {
            strBuf += "파워사다리 자동배팅";
        } else if (jsonBetData[nRow].money_change_type == 9) {
            strBuf += "파워사다리 정산";
        } else if (jsonBetData[nRow].money_change_type == 10) {
            strBuf += "키노사다리 배팅";
        } else if (jsonBetData[nRow].money_change_type == 11) {
            strBuf += "키노사다리 자동배팅";
        } else if (jsonBetData[nRow].money_change_type == 12) {
            strBuf += "키노사다리 정산";
        } else if (jsonBetData[nRow].money_change_type == 13) {
            strBuf += "보글볼 배팅";
        } else if (jsonBetData[nRow].money_change_type == 15) {
            strBuf += "보글볼 정산";
        } else if (jsonBetData[nRow].money_change_type == 16) {
            strBuf += "보글사다리 배팅";
        } else if (jsonBetData[nRow].money_change_type == 18) {
            strBuf += "보글사다리 정산";
        } else if (jsonBetData[nRow].money_change_type == 21) {
            strBuf += "입금취소";
        } else if (jsonBetData[nRow].money_change_type == 22) {
            strBuf += "출금취소";
        }
        strBuf += "</td><td>";
        if (jsonBetData[nRow].money_bet_round > 0)
            strBuf += jsonBetData[nRow].money_bet_round;
        strBuf += "</td></tr>";

    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='10'>자료가 없습니다.</td></tr>";
    }
    elemBetDataTb.innerHTML = strBuf;

}



function addEventListner() {
    var butView = document.getElementById("exchange-list-view-but-id");
    butView.addEventListener("click", function() {
        requestTotalPage();
    });

    var selectView = document.getElementById("exchange-number-select-id");
    selectView.addEventListener("change", function() {
        requestTotalPage();
    });
}

//Function to Request Betting History to WebServer
function requestMoneyHistory() {

    var dtStart = document.getElementById("exchange-datestart-input-id").value;
    var dtEnd = document.getElementById("exchange-dateend-input-id").value;
    var strUser = document.getElementById("exchange-userid-input-id").value;
    var nMode = document.getElementById("exchange-game-select-id").value;
    var nPage = getActivePage();

    var jsonData = { "count": CountPerPage, "page": nPage, "start": dtStart, "end": dtEnd, "user": strUser, "mode": nMode };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: '/api/moneyhistory',
        data: { json_: jsonData },
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            //console.log(jResult);
            if (jResult.status == "success") {
                showMoneyHistory(jResult.data);
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}


function requestTotalPage() {


    var dtStart = document.getElementById("exchange-datestart-input-id").value;
    var dtEnd = document.getElementById("exchange-dateend-input-id").value;
    CountPerPage = document.getElementById("exchange-number-select-id").value;
    var strUser = document.getElementById("exchange-userid-input-id").value;
    var nMode = document.getElementById("exchange-game-select-id").value;

    var jsonData = { "count": CountPerPage, "start": dtStart, "end": dtEnd, "user": strUser, "mode": nMode };
    jsonData = JSON.stringify(jsonData);


    $.ajax({
        url: '/api/moneyhistorycnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            //console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data.count;
                setFirstPage();
                requestMoneyHistory();
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}


function exchangeLoop() {

    requestMoneyHistory();

    // 1초뒤에 다시 실행
    setTimeout(function() {
        exchangeLoop();
    }, 300000);

}