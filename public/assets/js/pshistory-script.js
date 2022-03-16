$(document).ready(function() {
    addEventListner();
    requestTotalPage();
    pshitoryLoop();
});

function requestPageInfo() {
    requestBetHistory();
}

//Function to Show Betting History
function ShowBetHistory(jsonBetData) {
    var elemBetDataTb = document.getElementById("pbbet-table-id");
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
        strBuf += jsonBetData[nRow].bet_fid;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_time;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_round_no;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_mb_uid;
        strBuf += "</td>";

        strBetMode = "<td>";
        if (jsonBetData[nRow].bet_mode == "1") {
            strBetMode = "<td class = \"pb-home-table-betmode-power\"> 좌우";
        } else if (jsonBetData[nRow].bet_mode == "2") {
            strBetMode = "<td class = \"pb-home-table-betmode-line\"> 줄수";
        } else if (jsonBetData[nRow].bet_mode == "3") {
            strBetMode = "<td class = \"pb-home-table-betmode-normal\"> 홀짝";
        }


        strBetTarget = "";
        strResultTarget = "";
        if (jsonBetData[nRow].bet_mode == "1") {
            strBetTarget = jsonBetData[nRow].bet_target == "P" ? "<div  class = \"pb-home-odd-span\">좌</div>" : "<div  class = \"pb-home-even-span\">우</div>";
            if (jsonBetData[nRow].bet_result == "P")
                strResultTarget = "<div  class = \"pb-home-odd-span\">좌</div>";
            else if (jsonBetData[nRow].bet_result == "B")
                strResultTarget = "<div  class = \"pb-home-even-span\">우</div>";
        } else if (jsonBetData[nRow].bet_mode == "2") {
            strBetTarget = jsonBetData[nRow].bet_target == "P" ? "<div  class = \"pb-home-odd-span\">3</div>" : "<div  class = \"pb-home-even-span\">4</div>";
            if (jsonBetData[nRow].bet_result == "P")
                strResultTarget = "<div  class = \"pb-home-odd-span\">3</div>";
            else if (jsonBetData[nRow].bet_result == "B")
                strResultTarget = "<div  class = \"pb-home-even-span\">4</div>";
        } else if (jsonBetData[nRow].bet_mode == "3") {
            strBetTarget = jsonBetData[nRow].bet_target == "P" ? "<div  class = \"pb-home-odd-span\">홀</div>" : "<div  class = \"pb-home-even-span\">짝</div>";
            if (jsonBetData[nRow].bet_result == "P")
                strResultTarget = "<div  class = \"pb-home-odd-span\">홀</div>";
            else if (jsonBetData[nRow].bet_result == "B")
                strResultTarget = "<div  class = \"pb-home-even-span\">짝</div>";
        }

        strBuf += strBetMode;
        strBuf += "</td><td>";
        strBuf += parseInt(jsonBetData[nRow].bet_money).toLocaleString();
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_ratio;
        strBuf += "</td><td>";
        strBuf += strBetTarget;
        strBuf += "</td><td>";
        strBuf += strResultTarget;
        strBuf += "</td><td>";

        strWinMoney = "";
        strResult = "<td>";
        if (jsonBetData[nRow].bet_state == "1") {
            strResult = "<td  class = \"pb-home-table-betstate-wait\">대기중";
        } else if (jsonBetData[nRow].bet_state == "2") {
            strResult = "<td  class = \"pb-home-table-betstate-loss\">미적중";
        } else if (jsonBetData[nRow].bet_state == "3") {
            strResult = "<td  class = \"pb-home-table-betstate-earn\">적중";
            strWinMoney = parseInt(jsonBetData[nRow].bet_win_money - jsonBetData[nRow].bet_money).toLocaleString() + "원";
        } else if (jsonBetData[nRow].bet_state == "4") {
            strResult = "<td  class = \"pb-home-table-betstate-wait\">무효";
        }

        strBuf += strWinMoney;
        strBuf += "</td>";
        strBuf += strResult;
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
    var strRound = document.getElementById("pbhistory-roundid-input-id").value;
    var strUser = document.getElementById("pbhistory-userid-input-id").value;
    var nMode = document.getElementById("pbhistory-game-select-id").value;
    var nPage = getActivePage();

    var jsonData = { "count": CountPerPage, "page": nPage, "start": dtStart, "end": dtEnd, "user": strUser, "round": strRound, "mode": nMode };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: '/psapi/betlist',
        data: { json_: jsonData },
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                ShowBetHistory(jResult.data);
                ShowBetAccount(jResult.account);
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}


function requestTotalPage() {


    var dtStart = document.getElementById("pbhistory-datestart-input-id").value;
    var dtEnd = document.getElementById("pbhistory-dateend-input-id").value;
    CountPerPage = document.getElementById("pbhistory-number-select-id").value;
    var strRound = document.getElementById("pbhistory-roundid-input-id").value;
    var strUser = document.getElementById("pbhistory-userid-input-id").value;
    var nMode = document.getElementById("pbhistory-game-select-id").value;

    var jsonData = { "count": CountPerPage, "start": dtStart, "end": dtEnd, "user": strUser, "round": strRound, "mode": nMode };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: '/psapi/betlistcnt',
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
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}


function pshitoryLoop() {

    var currentTime = new Date();

    if (currentTime.getSeconds() == 10 && (currentTime.getMinutes() % 10 == 1 ||
            currentTime.getMinutes() % 10 == 3 || currentTime.getMinutes() % 10 == 6 || currentTime.getMinutes() % 10 == 8)) {
        requestBetHistory();
    }


    // 1초뒤에 다시 실행
    setTimeout(function() {
        pshitoryLoop();
    }, 1000);

}