var mBetData = null;
initBetChange();

function initBetChange() {
    requestBetHistory();
    addEventListner();
}


//Function to Show Betting History
function ShowBetHistory(jsonBetData) {
    var elemBetDataTb = document.getElementById("pbbetchange-table-id");
    var strBuf = "";

    var nCurBetCnt = 0;
    var nCurBetMoney = 0;
    var strBetMode = "";
    var strBetTarget = "";
    var strResultTarget = "";
    var strWinMoney = "";
    var strResult = "";
    //if(jsonBetHistory.data.length > 0)
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
        if (jsonBetData[nRow].bet_state == "4") {
            strBuf += "<button name=\"" + jsonBetData[nRow].bet_fid + "\">복구처리</button>   ";
        } else {
            strBuf += "<button name=\"" + jsonBetData[nRow].bet_fid + "\">결과처리</button>   ";
            strBuf += "<button name=\"" + jsonBetData[nRow].bet_fid + "\">무효처리</button>   ";
        }
        strBuf += "</td></tr>";

    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='13'>자료가 없습니다.</td></tr>";
    }
    elemBetDataTb.innerHTML = strBuf;


    addTableBtnEvent();
}

function addTableBtnEvent() {
    var elemTable = document.getElementById("pbbetchange-table-id");
    var elemTableBtns = elemTable.getElementsByTagName("button");
    if (elemTableBtns == null)
        return;

    var i;
    for (i = 0; i < elemTableBtns.length; i++) {
        elemTableBtns[i].addEventListener("click", function() {

            if (this.innerHTML.search("결과처리") >= 0 || this.innerHTML.search("복구처리") >= 0) {
                var jsData = [this.name];
                requestBetProcess(jsData);
            } else if (this.innerHTML.search("무효처리") >= 0) {
                var jsData = [this.name];
                requestBetIgnore(jsData);
            }
        });
    }
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

function addEventListner() {
    var butView = document.getElementById("pbbetchange-view-but-id");
    butView.addEventListener("click", function() {
        requestBetHistory();
    });

    var butAllIgnore = document.getElementById("pbbetchange-ignore-but-id");
    butAllIgnore.addEventListener("click", function() {
        if (mBetData == null) return;
        if (mBetData.length < 1) return;

        var jsData = new Array();
        for (nRow in mBetData)
            jsData[nRow] = mBetData[nRow].bet_fid;

        requestBetIgnore(jsData);
    });


    var butAllProcess = document.getElementById("pbbetchange-process-but-id");
    butAllProcess.addEventListener("click", function() {
        if (mBetData == null) return;
        if (mBetData.length < 1) return;

        var jsData = new Array();
        for (nRow in mBetData)
            jsData[nRow] = mBetData[nRow].bet_fid;

        requestBetProcess(jsData);
    });
}


//Function to Request Betting History to WebServer
function requestBetHistory() {

    var dtStart = document.getElementById("pbbetchange-date-input-id").value;
    var dtEnd = document.getElementById("pbbetchange-date-input-id").value;
    var nCount = 1000;
    var strRound = document.getElementById("pbbetchange-round-input-id").value;
    var nMode = 0;
    var nPage = 1;
    var strUser = "";

    if (strRound < 1) return;

    var jsonData = { "count": nCount, "page": nPage, "start": dtStart, "end": dtEnd, "user": strUser, "round": strRound, "mode": nMode };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: '/bsapi/betlist',
        data: { json_: jsonData },
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            //console.log(jResult);
            if (jResult.status == "success") {
                mBetData = jResult.data;
                ShowBetHistory(jResult.data);
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}


function requestBetIgnore(jsData) {
    var jsonData = JSON.stringify(jsData);

    $.ajax({
        url: '/bsapi/betignore',
        data: { json_: jsonData },
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            if (jResult.status == "success") {
                requestBetHistory();
            }
        },
        error: function(request, status, error) {
            //  console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}


function requestBetProcess(jsData) {

    var jsonData = JSON.stringify(jsData);

    $.ajax({
        url: '/bsapi/betprocess',
        data: { json_: jsonData },
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            //console.log(jResult);
            if (jResult.status == "success") {
                requestBetHistory();
            }
        },
        error: function(request, status, error) {
            //  console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}