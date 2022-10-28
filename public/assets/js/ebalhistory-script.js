$(document).ready(function() {
    setNavBarElement();
    addEventListner();
    requestTotalPage();
    
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
        strBuf += parseInt(jsonBetData[nRow].bet_amount).toLocaleString() + "원";
        strBuf += "</td><td>";
        strBuf += parseInt(jsonBetData[nRow].bet_win_amount).toLocaleString() + "원";
        strBuf += "</td>";
        strResult = "<td>";
        if (parseInt(jsonBetData[nRow].bet_win_amount) > parseInt(jsonBetData[nRow].bet_amount)) {
            strResult = "<td  class = 'pb-home-table-betstate-earn'>적중";
        } else if (jsonBetData[nRow].bet_win_amount == jsonBetData[nRow].bet_amount) {
            strResult = "<td  class = 'pb-home-table-betstate-wait'>타이";
        } else {
            strResult = "<td  class = 'pb-home-table-betstate-loss'>미적중"; //
        }
        strBuf += strResult;
        strBuf += "</td></tr>";

    }
    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='8'>자료가 없습니다.</td></tr>";
    }
    elemBetDataTb.innerHTML = strBuf;

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
    var nPage = getActivePage();

    var jsonData = {
        "count": CountPerPage,
        "page": nPage,
        "start": dtStart,
        "end": dtEnd,
        "user": strUser,
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
    
    var jsonData = {
        "count": CountPerPage,
        "start": dtStart,
        "end": dtEnd,
        "user": strUser,
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

