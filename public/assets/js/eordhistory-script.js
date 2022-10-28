$(document).ready(function() {
    setNavBarElement();
    addEventListner();
    requestTotalPage();
    requestConfBetSite();
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
        strBuf += jsonBetData[nRow].mb_uid;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].ord_tm_req;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].ord_game_type;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].ord_table_name;
        strBuf += "</td><td>";
        strBuf += parseInt(jsonBetData[nRow].ord_amount).toLocaleString() + "원";
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].ord_choice;
        strBuf += "</td>";
        strResult = "<td>";
        if (jsonBetData[nRow].ord_state == 1) {
            strResult = "<td  class = 'pb-home-table-betstate-earn'>베팅";
        } else if (jsonBetData[nRow].ord_state == 5) {
            strResult = "<td  class = 'pb-home-table-betstate-wait'>거절";
        } else {
            strResult = "<td  class = 'pb-home-table-betstate-loss'>대기"; //
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
    
    $("#ebal-start-but-id").click(function() {
        setEbalState(1);
    });
    
    $("#ebal-stop-but-id").click(function() {
        setEbalState(0);
    });
}

//Function to Request Betting History to WebServer
function requestBetHistory() {

    CountPerPage = $("#pbhistory-number-select-id").val();
    var nPage = getActivePage();

    var jsonData = {
        "count": CountPerPage,
        "page": nPage,
    };
    jsonData = JSON.stringify(jsonData);
    $(".loading").show();
    $.ajax({
        url: FURL + '/api/eorderlist',
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

    CountPerPage = $("#pbhistory-number-select-id").val();
    
    var jsonData = {
        "count": CountPerPage,
    };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: FURL + '/api/eordercnt',
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

function requestConfBetSite() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/getEvolSite",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                showConfSite(jResult.data);
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            } else if (jResult.status == "nopermit") {
                alert("권한이 없습니다.");
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}

function showConfSite(arrData) {
    if (arrData.length < 6)
        return;

    if(arrData[3] == 1){
        $("#ebal-start-but-id").attr("disabled", true);
        $("#ebal-stop-but-id").attr("disabled", false);

        $("#ebal-start-but-id").css("background", '#85ff8e');
        $("#ebal-stop-but-id").css("background", 'white');

    } else {
        $("#ebal-start-but-id").attr("disabled", false);
        $("#ebal-stop-but-id").attr("disabled", true);

        $("#ebal-start-but-id").css("background", 'white');
        $("#ebal-stop-but-id").css("background", '#ff3a5a');
    }

}


function setEbalState(state){
    
    var objData = new Object();

    objData.active_ev = state;
    
    var jsonData = JSON.stringify(objData);

    if (state == 1 && !confirm("시작하시겠습니까?"))
        return;
    else if (state == 0 && !confirm("정지하시겠습니까?"))
        return;

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/setEvolSite",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                window.location.reload();
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            } else if (jResult.status == "fail") {
                alert("조작이 실패되었습니다.");
            } else if (jResult.status == "nopermit") {
                alert("권한이 없습니다.");
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}