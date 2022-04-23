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
        strBuf += arrData[nRow].mb_nickname;
        strBuf += "</td><td>";
        strBuf += arrData[nRow].money_mb_uid;
        strBuf += "</td><td>";
        strBuf += parseInt(arrData[nRow].mb_money).toLocaleString();
        strBuf += "</td><td>";
        strBuf += parseInt(arrData[nRow].mb_live_money).toLocaleString();
        strBuf += "</td><td>";
        strBuf += parseInt(arrData[nRow].mb_slot_money).toLocaleString();
        strBuf += "</td><td>";

        switch (arrData[nRow].money_change_type) {
            case '1':
                strBuf += "<span style='color:green;'>사이트 => 카지노</span>";
                break;
            case '2':
                strBuf += "<span style='color:green;'>카지노 => 사이트</span>";
                break;
            case '3':
                strBuf += "<span style='color:blue;'>사이트 => 슬롯</span>";
                break;
            case '4':
                strBuf += "<span style='color:blue;'>슬롯 => 사이트</span>";
                break;
            case '5':
                strBuf += "<span style='color:red;'>사이트 => 슬롯</span>";
                break;
            case '6':
                strBuf += "<span style='color:red;'>슬롯 => 사이트</span>";
                break;
            default:
                strBuf += parseInt(arrData[nRow].money_change_type).toLocaleString();
                break;
        }
        strBuf += "</td><td>";
        strBuf += Math.abs(parseInt(arrData[nRow].money_amount)).toLocaleString();
        strBuf += "</td><td>";
        strBuf += parseInt(arrData[nRow].money_site_before).toLocaleString();
        strBuf += "</td><td>";
        strBuf += parseInt(arrData[nRow].money_site_after).toLocaleString();
        strBuf += "</td><td>";
        strBuf += parseInt(arrData[nRow].money_live_before).toLocaleString();
        strBuf += "</td><td>";
        strBuf += parseInt(arrData[nRow].money_live_after).toLocaleString();
        strBuf += "</td><td>";
        strBuf += arrData[nRow].money_update_time;
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
        url: '/api/translist',
        data: { json_: jsonData },
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            $(".loading").hide();
            //console.log(jResult);
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
        url: '/api/translistcnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            //console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data.count;
                setFirstPage();
                requestTransferHistory();
            }
        },
        error: function(request, status, error) {
            console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
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