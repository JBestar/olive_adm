$(document).ready(function() {
    addEventListner();
    requestTotalPage();
});

function requestPageInfo() {
    requestTransferHistory();
}

//Function to Show Betting History
function showMoneyHistory(arrData) {
    var elemBetDataTb = document.getElementById("transfer-table-id");
    var strBuf = "";
    var curPage = getActivePage();
    var firstIdx = (curPage - 1) * CountPerPage;

    for (nRow in arrData) {

        strBuf += "<tr><td>";
        strBuf += (parseInt(nRow) + firstIdx + 1);
        strBuf += "</td><td>";
        strBuf += arrData[nRow].trans_mb_uid;
        strBuf += "</td><td>";
        
        switch (parseInt(arrData[nRow].trans_type)) {
            case 1:
            case 3:
            case 15:
                strBuf += "<span style='color:#00ff00;'>사이트 => 카지노</span>";
                break;
            case 2:
            case 4:
            case 16:
                strBuf += "<span style='color:#00aa00;'>카지노 => 사이트</span>";
                break;
            case 5:
            case 7:
            case 9:
            case 11:
                strBuf += "<span style='color:#0000ff;'>사이트 => 슬롯</span>";
                break;
            case 6:
            case 8:
            case 10:
            case 12:
                strBuf += "<span style='color:#0000aa;'>슬롯 => 사이트</span>";
                break;
            case 13:
                strBuf += "<span style='color:#ff0000;'>사이트 => 홀덤</span>";
                break;
            case 14:
                strBuf += "<span style='color:#aa0000;'>홀덤 => 사이트</span>";
                break;
            default:
                strBuf += parseInt(arrData[nRow].trans_type);
                break;
        }
        strBuf += "</td><td>";
        strBuf += Math.abs(parseInt(arrData[nRow].trans_amount)).toLocaleString();
        strBuf += "</td><td>";
        strBuf += Math.floor(arrData[nRow].money_before).toLocaleString();
        strBuf += "</td><td>";
        strBuf += Math.floor(arrData[nRow].money_after).toLocaleString();
        strBuf += "</td><td>";
        strBuf += Math.floor(arrData[nRow].egg_before).toLocaleString();
        strBuf += "</td><td>";
        strBuf += Math.floor(arrData[nRow].egg_after).toLocaleString();
        strBuf += "</td><td>";
        strBuf += arrData[nRow].trans_time;
        strBuf += "</td></tr>";

    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='12'>자료가 없습니다.</td></tr>";
    }

    elemBetDataTb.innerHTML = strBuf;

}


function addEventListner() {
    $("#transfer-list-view-but-id").click(function() {
        requestTotalPage();
    });

    $("#transfer-number-select-id").change(function() {
        requestTotalPage();
    });

}


//Function to Request Betting History to WebServer
function requestTransferHistory() {

    var dtStart = $("#transfer-datestart-input-id").val();
    var dtEnd = $("#transfer-dateend-input-id").val();
    var strUser = $("#transfer-userid-input-id").val();
    var nMode = $("#transfer-type-select-id").val();
    var nPage = getActivePage();

    var jsonData = { "count": CountPerPage, "page": nPage, "start": dtStart, "end": dtEnd, "user": strUser, "mode": nMode };
    jsonData = JSON.stringify(jsonData);
    $(".loading").show();
    $.ajax({
        url: FURL + '/api/translist',
        data: { json_: jsonData },
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            $(".loading").hide();
            // console.log(jResult);
            if (jResult.status == "success") {
                showMoneyHistory(jResult.data);
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}


function requestTotalPage() {


    var dtStart = $("#transfer-datestart-input-id").val();
    var dtEnd = $("#transfer-dateend-input-id").val();
    CountPerPage = $("#transfer-number-select-id").val();
    var strUser = $("#transfer-userid-input-id").val();
    var nMode = $("#transfer-type-select-id").val();

    var jsonData = { "count": CountPerPage, "start": dtStart, "end": dtEnd, "user": strUser, "mode": nMode };
    jsonData = JSON.stringify(jsonData);


    $.ajax({
        url: FURL + '/api/translistcnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data.count;
                setFirstPage();
                requestTransferHistory();
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}


function transferLoop() {

    requestTransferHistory();

    // 1초뒤에 다시 실행
    setTimeout(function() {
        transferLoop();
    }, 300000);

}