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
        if (jsonBetData[nRow].bet_mode == "1" || jsonBetData[nRow].bet_mode == "2") {
            strBetMode = "<td class = \"pb-home-table-betmode-power\"> 파워볼";
        } else if (jsonBetData[nRow].bet_mode == "3" || jsonBetData[nRow].bet_mode == "4") {
            strBetMode = "<td class = \"pb-home-table-betmode-normal\"> 일반볼";
        } else if (jsonBetData[nRow].bet_mode == "5" || jsonBetData[nRow].bet_mode == "6" || jsonBetData[nRow].bet_mode == "7" || jsonBetData[nRow].bet_mode == "8") {
            strBetMode = "<td class = \"pb-home-table-betmode-power\"> 파워볼조합";
        } else if (jsonBetData[nRow].bet_mode == "9" || jsonBetData[nRow].bet_mode == "10" || jsonBetData[nRow].bet_mode == "11" || jsonBetData[nRow].bet_mode == "12") {
            strBetMode = "<td class = \"pb-home-table-betmode-normal\"> 일반볼조합";
        } else if (jsonBetData[nRow].bet_mode == "13" || jsonBetData[nRow].bet_mode == "14" || jsonBetData[nRow].bet_mode == "15" || jsonBetData[nRow].bet_mode == "16" ||
            jsonBetData[nRow].bet_mode == "17" || jsonBetData[nRow].bet_mode == "18" || jsonBetData[nRow].bet_mode == "19" || jsonBetData[nRow].bet_mode == "20") {
            strBetMode = "<td class = \"pb-home-table-betmode-line\"> 일반+파워조합";
        } else if (jsonBetData[nRow].bet_mode == "21" || jsonBetData[nRow].bet_mode == "22" || jsonBetData[nRow].bet_mode == "23" ||
            jsonBetData[nRow].bet_mode == "24" || jsonBetData[nRow].bet_mode == "25" || jsonBetData[nRow].bet_mode == "26") {
            strBetMode = "<td class = \"pb-home-table-betmode-line\"> 일반볼대중소";
        }
        strBetTarget = "";
        strResultTarget = "";
        if (jsonBetData[nRow].bet_mode == "1" || jsonBetData[nRow].bet_mode == "3") {
            strBetTarget = jsonBetData[nRow].bet_target == "P" ? "<div  class = \"pb-home-odd-span\">홀</div>" : "<div  class = \"pb-home-even-span\">짝</div>";
            if (jsonBetData[nRow].bet_result == "P")
                strResultTarget = "<div  class = \"pb-home-odd-span\">홀</div>";
            else if (jsonBetData[nRow].bet_result == "B")
                strResultTarget = "<div  class = \"pb-home-even-span\">짝</div>";
        } else if (jsonBetData[nRow].bet_mode == "2" || jsonBetData[nRow].bet_mode == "4") {
            strBetTarget = jsonBetData[nRow].bet_target == "P" ? "<div  class = \"pb-home-odd-span\"><i class=\"glyphicon glyphicon-arrow-down\"></i></div>" : "<div  class = \"pb-home-even-span\"><i class=\"glyphicon glyphicon-arrow-up\"></i></div>";

            if (jsonBetData[nRow].bet_result == "P")
                strResultTarget = "<div  class = \"pb-home-odd-span\"><i class=\"glyphicon glyphicon-arrow-down\"></i></div>";
            else if (jsonBetData[nRow].bet_result == "B")
                strResultTarget = "<div  class = \"pb-home-even-span\"><i class=\"glyphicon glyphicon-arrow-up\"></i></div>";
        } else if (jsonBetData[nRow].bet_mode == "5" || jsonBetData[nRow].bet_mode == "9") {
            strBetTarget = getHtmlByBet("1", "P") + getHtmlByBet("2", "P");
            if (jsonBetData[nRow].bet_result.length > 1) {
                strResultTarget = getHtmlByBet("1", jsonBetData[nRow].bet_result.charAt(0)) + getHtmlByBet("2", jsonBetData[nRow].bet_result.charAt(1));
            }
        } else if (jsonBetData[nRow].bet_mode == "6" || jsonBetData[nRow].bet_mode == "10") {
            strBetTarget = getHtmlByBet("1", "P") + getHtmlByBet("2", "B");
            if (jsonBetData[nRow].bet_result.length > 1) {
                strResultTarget = getHtmlByBet("1", jsonBetData[nRow].bet_result.charAt(0)) + getHtmlByBet("2", jsonBetData[nRow].bet_result.charAt(1));
            }
        } else if (jsonBetData[nRow].bet_mode == "7" || jsonBetData[nRow].bet_mode == "11") {
            strBetTarget = getHtmlByBet("1", "B") + getHtmlByBet("2", "P");
            if (jsonBetData[nRow].bet_result.length > 1) {
                strResultTarget = getHtmlByBet("1", jsonBetData[nRow].bet_result.charAt(0)) + getHtmlByBet("2", jsonBetData[nRow].bet_result.charAt(1));
            }
        } else if (jsonBetData[nRow].bet_mode == "8" || jsonBetData[nRow].bet_mode == "12") {
            strBetTarget = getHtmlByBet("1", "B") + getHtmlByBet("2", "B");
            if (jsonBetData[nRow].bet_result.length > 1) {
                strResultTarget = getHtmlByBet("1", jsonBetData[nRow].bet_result.charAt(0)) + getHtmlByBet("2", jsonBetData[nRow].bet_result.charAt(1));
            }
        } else if (jsonBetData[nRow].bet_mode == "13") {
            strBetTarget = getHtmlByBet("1", "P") + getHtmlByBet("1", "P");
            if (jsonBetData[nRow].bet_result.length > 1) {
                strResultTarget = getHtmlByBet("1", jsonBetData[nRow].bet_result.charAt(0)) + getHtmlByBet("1", jsonBetData[nRow].bet_result.charAt(1));
            }
        } else if (jsonBetData[nRow].bet_mode == "14") {
            strBetTarget = getHtmlByBet("1", "P") + getHtmlByBet("1", "B");
            if (jsonBetData[nRow].bet_result.length > 1) {
                strResultTarget = getHtmlByBet("1", jsonBetData[nRow].bet_result.charAt(0)) + getHtmlByBet("1", jsonBetData[nRow].bet_result.charAt(1));
            }
        } else if (jsonBetData[nRow].bet_mode == "15") {
            strBetTarget = getHtmlByBet("1", "B") + getHtmlByBet("1", "P");
            if (jsonBetData[nRow].bet_result.length > 1) {
                strResultTarget = getHtmlByBet("1", jsonBetData[nRow].bet_result.charAt(0)) + getHtmlByBet("1", jsonBetData[nRow].bet_result.charAt(1));
            }
        } else if (jsonBetData[nRow].bet_mode == "16") {
            strBetTarget = getHtmlByBet("1", "B") + getHtmlByBet("1", "B");
            if (jsonBetData[nRow].bet_result.length > 1) {
                strResultTarget = getHtmlByBet("1", jsonBetData[nRow].bet_result.charAt(0)) + getHtmlByBet("1", jsonBetData[nRow].bet_result.charAt(1));
            }
        } else if (jsonBetData[nRow].bet_mode == "17") {
            strBetTarget = getHtmlByBet("2", "P") + getHtmlByBet("2", "P");
            if (jsonBetData[nRow].bet_result.length > 1) {
                strResultTarget = getHtmlByBet("2", jsonBetData[nRow].bet_result.charAt(0)) + getHtmlByBet("2", jsonBetData[nRow].bet_result.charAt(1));
            }
        } else if (jsonBetData[nRow].bet_mode == "18") {
            strBetTarget = getHtmlByBet("2", "P") + getHtmlByBet("2", "B");
            if (jsonBetData[nRow].bet_result.length > 1) {
                strResultTarget = getHtmlByBet("2", jsonBetData[nRow].bet_result.charAt(0)) + getHtmlByBet("2", jsonBetData[nRow].bet_result.charAt(1));
            }
        } else if (jsonBetData[nRow].bet_mode == "19") {
            strBetTarget = getHtmlByBet("2", "B") + getHtmlByBet("2", "P");
            if (jsonBetData[nRow].bet_result.length > 1) {
                strResultTarget = getHtmlByBet("2", jsonBetData[nRow].bet_result.charAt(0)) + getHtmlByBet("2", jsonBetData[nRow].bet_result.charAt(1));
            }
        } else if (jsonBetData[nRow].bet_mode == "20") {
            strBetTarget = getHtmlByBet("2", "B") + getHtmlByBet("2", "B");
            if (jsonBetData[nRow].bet_result.length > 1) {
                strResultTarget = getHtmlByBet("2", jsonBetData[nRow].bet_result.charAt(0)) + getHtmlByBet("2", jsonBetData[nRow].bet_result.charAt(1));
            }
        } else if (jsonBetData[nRow].bet_mode == "21") {
            strBetTarget = getHtmlByBet("1", "P") + getHtmlByBet("5", "L");
            if (jsonBetData[nRow].bet_result.length > 1) {
                strResultTarget = getHtmlByBet("1", jsonBetData[nRow].bet_result.charAt(0)) + getHtmlByBet("5", jsonBetData[nRow].bet_result.charAt(1));
            }
        } else if (jsonBetData[nRow].bet_mode == "22") {
            strBetTarget = getHtmlByBet("1", "P") + getHtmlByBet("5", "M");
            if (jsonBetData[nRow].bet_result.length > 1) {
                strResultTarget = getHtmlByBet("1", jsonBetData[nRow].bet_result.charAt(0)) + getHtmlByBet("5", jsonBetData[nRow].bet_result.charAt(1));
            }
        } else if (jsonBetData[nRow].bet_mode == "23") {
            strBetTarget = getHtmlByBet("1", "P") + getHtmlByBet("5", "S");
            if (jsonBetData[nRow].bet_result.length > 1) {
                strResultTarget = getHtmlByBet("1", jsonBetData[nRow].bet_result.charAt(0)) + getHtmlByBet("5", jsonBetData[nRow].bet_result.charAt(1));
            }
        } else if (jsonBetData[nRow].bet_mode == "24") {
            strBetTarget = getHtmlByBet("1", "B") + getHtmlByBet("5", "L");
            if (jsonBetData[nRow].bet_result.length > 1) {
                strResultTarget = getHtmlByBet("1", jsonBetData[nRow].bet_result.charAt(0)) + getHtmlByBet("5", jsonBetData[nRow].bet_result.charAt(1));
            }
        } else if (jsonBetData[nRow].bet_mode == "25") {
            strBetTarget = getHtmlByBet("1", "B") + getHtmlByBet("5", "M");
            if (jsonBetData[nRow].bet_result.length > 1) {
                strResultTarget = getHtmlByBet("1", jsonBetData[nRow].bet_result.charAt(0)) + getHtmlByBet("5", jsonBetData[nRow].bet_result.charAt(1));
            }
        } else if (jsonBetData[nRow].bet_mode == "26") {
            strBetTarget = getHtmlByBet("1", "B") + getHtmlByBet("5", "S");
            if (jsonBetData[nRow].bet_result.length > 1) {
                strResultTarget = getHtmlByBet("1", jsonBetData[nRow].bet_result.charAt(0)) + getHtmlByBet("5", jsonBetData[nRow].bet_result.charAt(1));
            }
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
            strResult = "<td  class = \"pb-home-table-betstate-loss\">미적중"; //
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

function getHtmlByBet(strMode, strTarget) {
    var strResult = "";
    if (strMode == "1" || strMode == "3") {
        strResult = strTarget == "P" ? "<div  class = \"pb-home-odd-span\">홀</div> " : "<div  class = \"pb-home-even-span\">짝</div> ";
    } else if (strMode == "2" || strMode == "4") {
        strResult = strTarget == "P" ? "<div  class = \"pb-home-odd-span\"><i class=\"glyphicon glyphicon-arrow-down\"></i></div> " : "<div  class = \"pb-home-even-span\"><i class=\"glyphicon glyphicon-arrow-up\"></i></div> ";
    } else {
        if (strTarget == 'L') strResult = "<div  class = \"pb-home-even-span\">대</div> ";
        else if (strTarget == 'M') strResult = "<div  class = \"pb-home-mid-span\">중</div> ";
        else if (strTarget == 'S') strResult = "<div  class = \"pb-home-odd-span\">소</div> ";
    }
    return strResult;
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
        url: '/pbapi/betlist',
        data: { json_: jsonData },
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            //console.log(jResult);
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
        url: '/pbapi/betlistcnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            //console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data.count;
                setFirstPage();
                requestBetHistory();
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}


function pbhitoryLoop() {

    var currentTime = new Date();

    if (currentTime.getSeconds() == 10 && (currentTime.getMinutes() % 10 == 1 ||
            currentTime.getMinutes() % 10 == 3 || currentTime.getMinutes() % 10 == 6 || currentTime.getMinutes() % 10 == 8)) {
        requestBetHistory();
    }


    // 1초뒤에 다시 실행
    setTimeout(function() {
        pbhitoryLoop();
    }, 1000);

}