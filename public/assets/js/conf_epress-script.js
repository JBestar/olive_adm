$(document).ready(function() {
    setNavBarElement();
    requestTotalPage();
    requestConf();
    addBtnEvent();
    setInterval(function() {
        requestLogHistory();
    }, 120000);
});

function requestPageInfo() {
    requestLogHistory();
}

function showConf(data) {
    if (data && data.length > 2) {
        $("#confev-autopress-check-id").prop('checked', data[2] == 1 ? true : false);
        $("#confev-bettime-input-id").val(parseInt(data[0])); 
        $("#confev-moneypercent-input-id").val(parseInt(data[1])); 
    }
}

function requestConf() {
    let gameId = $(".confsite-game-panel").attr('id');

    var jsonData = {
        "cat":gameId
    };

    jsonData = JSON.stringify(jsonData);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/getevpress",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                showConf(jResult.data);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });

}


function addBtnEvent() {

    $("#confsite-ok-btn-id").click(function() {

        var objData = new Object();

        objData.enable = $("#confev-autopress-check-id").prop('checked') ? 1 : 0;
        objData.time = $("#confev-bettime-input-id").val();
        objData.money = $("#confev-moneypercent-input-id").val();

        var jsonData = JSON.stringify(objData);

        if (!confirm("저장하시겠습니까?"))
            return;

        $.ajax({
            type: "POST",
            dataType: "json",
            url: FURL + "/api/setevpress",
            data: { json_: jsonData },
            success: function(jResult) {
                // console.log(jResult);
                if (jResult.status == "success") {
                    window.location.reload();
                } else if (jResult.status == "logout") {
                    window.location.replace( FURL +'/');
                } else if (jResult.status == "fail") {
                    alert("저장이 실패되었습니다.");
                } else if (jResult.status == "nopermit") {
                    alert("권한이 없습니다.");
                }
            },
            error: function(request, status, error) {
                // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            }

        });


    });


    $("#confsite-cancel-btn-id").click(function() {
        location.reload();
    });

    $("#pbhistory-list-view-but-id").click(function() {
        requestTotalPage();
    });

    $("#pbhistory-number-select-id").change(function() {
        requestTotalPage();
    });
    
}

function ShowLogHistory(arrInfo){
    var strBuf = "";

    var curPage = getActivePage();
    var firstIdx = (curPage - 1) * CountPerPage;

    for (nRow in arrInfo) {
        strBuf += "<tr><td>";
        strBuf += (parseInt(nRow) + firstIdx + 1);
        strBuf += "</td><td>";
        strBuf += arrInfo[nRow].log_mb_uid;
        strBuf += "</td><td>";
        strBuf += arrInfo[nRow].log_data;
        strBuf += "</td><td>";
        if(parseInt(arrInfo[nRow].log_type) == 2)
            strBuf += "자동";
        else
            strBuf += "회원수정";
        strBuf += "</td><td>";
        strBuf += arrInfo[nRow].log_time;
        strBuf += "</td><td>";
        strBuf += arrInfo[nRow].log_memo;
        strBuf += "</td></tr>";
    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='6'>자료가 없습니다.</td></tr>";
    }
    $("#pbbet-table-id").html(strBuf);

}

function requestLogHistory() {

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
        url: FURL + '/api/eballoglist',
        data: { json_: jsonData },
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            $(".loading").hide();
            // console.log(jResult);
            if (jResult.status == "success") {
                ShowLogHistory(jResult.data);
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}


function requestTotalPage(bReqPage = true) {

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
        url: FURL + '/api/eballogcnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data.count;
                setFirstPage();
                requestLogHistory();
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}