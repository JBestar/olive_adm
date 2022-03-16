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

    for (nRow in arrData) {

        strBuf += "<tr><td>";
        strBuf += arrData[nRow].money_fid;
        strBuf += "</td><td>";
        strBuf += arrData[nRow].mb_nickname;
        strBuf += "</td><td>";
        strBuf += arrData[nRow].money_mb_uid;
        strBuf += "</td><td>";
        strBuf += parseInt(arrData[nRow].mb_money).toLocaleString();
        strBuf += "</td><td>";
        strBuf += parseInt(arrData[nRow].mb_live_money).toLocaleString();
        strBuf += "</td><td>";
        if (arrData[nRow].money_change_type == 1) {
            strBuf += "<span style=\"color:green;\">카지노로 이동</span>";
        } else if (arrData[nRow].money_change_type == 2) {
            strBuf += "<span style=\"color:blue;\">사이트로 이동</span>";
        }
        strBuf += "</td><td>";
        strBuf += parseInt(arrData[nRow].money_amount).toLocaleString();
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
    var butView = document.getElementById("transfer-list-view-but-id");
    butView.addEventListener("click", function() {
        requestTotalPage();
    });

    var selectView = document.getElementById("transfer-number-select-id");
    selectView.addEventListener("change", function() {
        requestTotalPage();
    });

}


//Function to Request Betting History to WebServer
function requestTransferHistory() {

    var dtStart = document.getElementById("transfer-datestart-input-id").value;
    var dtEnd = document.getElementById("transfer-dateend-input-id").value;
    var strUser = document.getElementById("transfer-userid-input-id").value;
    var nMode = document.getElementById("transfer-type-select-id").value;
    var nPage = getActivePage();

    var jsonData = { "count": CountPerPage, "page": nPage, "start": dtStart, "end": dtEnd, "user": strUser, "mode": nMode };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: '/api/translist',
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
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}


function requestTotalPage() {


    var dtStart = document.getElementById("transfer-datestart-input-id").value;
    var dtEnd = document.getElementById("transfer-dateend-input-id").value;
    CountPerPage = document.getElementById("transfer-number-select-id").value;
    var strUser = document.getElementById("transfer-userid-input-id").value;
    var nMode = document.getElementById("transfer-type-select-id").value;

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