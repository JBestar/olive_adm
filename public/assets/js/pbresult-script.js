$(document).ready(function() {
    addEventListner();
    requestTotalPage();
    pbresultLoop();
});

function requestPageInfo() {
    requestGameResult();
}

//Function to Show Betting History
function ShowGameResult(jsonRoundResults) {
    var elemResultTb = document.getElementById("pbresult-list-table-id");

    var strBuf = "";

    for (nRow in jsonRoundResults) {

        strBuf += "<tr><td>";
        strBuf += jsonRoundResults[nRow].round_date;
        strBuf += "</td><td>";
        strBuf += jsonRoundResults[nRow].round_fid;
        strBuf += "</td><td>";
        strBuf += jsonRoundResults[nRow].round_num;
        strBuf += "</td><td>";
        strBuf += jsonRoundResults[nRow].round_power;
        strBuf += "</td><td>";
        if (jsonRoundResults[nRow].round_result_1 == 'P')
            strBuf += "<span class='pbresult-rule-odd-span'>홀</span>";
        else if (jsonRoundResults[nRow].round_result_1 == 'B')
            strBuf += "<span class='pbresult-rule-even-span'>짝</span>";
        strBuf += "</td><td>";
        if (jsonRoundResults[nRow].round_result_2 == 'P')
            strBuf += "<span class='pbresult-rule-odd-span'><i class='glyphicon glyphicon-arrow-down'></i></span>";
        else if (jsonRoundResults[nRow].round_result_2 == 'B')
            strBuf += "<span class='pbresult-rule-even-span'><i class='glyphicon glyphicon-arrow-up'></i></span>";
        strBuf += "</td><td>";

        strBuf += jsonRoundResults[nRow].round_normal;
        strBuf += "</td><td>";
        if (jsonRoundResults[nRow].round_result_3 == 'P')
            strBuf += "<span class='pbresult-rule-odd-span'>홀</span>";
        else if (jsonRoundResults[nRow].round_result_3 == 'B')
            strBuf += "<span class='pbresult-rule-even-span'>짝</span>";
        strBuf += "</td><td>";
        if (jsonRoundResults[nRow].round_result_4 == 'P')
            strBuf += "<span class='pbresult-rule-odd-span'><i class='glyphicon glyphicon-arrow-down'></i></span>";
        else if (jsonRoundResults[nRow].round_result_4 == 'B')
            strBuf += "<span class='pbresult-rule-even-span'><i class='glyphicon glyphicon-arrow-up'></i></span>";
        strBuf += "</td><td>";
        if (jsonRoundResults[nRow].round_result_5 == "S")
            strBuf += "<span class='pbresult-rule-odd-span'>소</span>";
        else if (jsonRoundResults[nRow].round_result_5 == "M")
            strBuf += "<span class='pbresult-rule-medium-span'>중</span>";
        else if (jsonRoundResults[nRow].round_result_5 == "L")
            strBuf += "<span class='pbresult-rule-even-span'>대</span>";
        strBuf += "</td><td>";
        strBuf += "<a href='"+FURL+"/result/pbresult_edit/" + jsonRoundResults[nRow].round_fid + "' >수정</a>";
        strBuf += "<a href='"+FURL+"/result/pbbetchange/" + jsonRoundResults[nRow].round_date + "/" + jsonRoundResults[nRow].round_num + "' >적특</a>";
        strBuf += "</td></tr>";

    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='10'>자료가 없습니다.</td></tr>";
    }

    elemResultTb.innerHTML = strBuf;
}


function addEventListner() {
    $("#pbresult-list-view-but-id").click(function() {
        requestTotalPage();
    });

    $("#pbresult-number-select-id").change(function() {
        requestTotalPage();
    });
}


//Function to Request Game Result History to WebServer
function requestGameResult() {
    var dtStart = document.getElementById("pbresult-datestart-input-id").value;
    var dtEnd = document.getElementById("pbresult-dateend-input-id").value;
    var nRound = document.getElementById("pbresult-round-input-id").value;
    var nPage = getActivePage();

    var jsonData = { "count": CountPerPage, "page": nPage, "start": dtStart, "end": dtEnd, "round": nRound };
    jsonData = JSON.stringify(jsonData);
    $(".loading").show();
    $.ajax({
        url: FURL + '/pbapi/result',
        data: { json_: jsonData },
        type: 'post',
        dataType: 'json',
        success: function(jResult) {
            $(".loading").hide();
            //console.log(jResult);
            if (jResult.status == "success") {
                ShowGameResult(jResult.data);
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}



//Function to Request Game Result History to WebServer
function requestTotalPage() {

    var dtStart = document.getElementById("pbresult-datestart-input-id").value;
    var dtEnd = document.getElementById("pbresult-dateend-input-id").value;
    CountPerPage = document.getElementById("pbresult-number-select-id").value;
    var nRound = document.getElementById("pbresult-round-input-id").value;
    var jsonData = { "count": CountPerPage, "start": dtStart, "end": dtEnd, "round": nRound };
    jsonData = JSON.stringify(jsonData);


    $.ajax({
        url: FURL + '/pbapi/resultcnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            //console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data.count;
                setFirstPage();
                setNavBarElement();
                requestGameResult();
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}



function pbresultLoop() {

    var currentTime = new Date();

    if (currentTime.getSeconds() == 10 && (currentTime.getMinutes() % 10 == 3 || currentTime.getMinutes() % 10 == 8)) {
        requestGameResult();
    }


    // 1초뒤에 다시 실행
    setTimeout(function() {
        pbresultLoop();
    }, 1000);

}