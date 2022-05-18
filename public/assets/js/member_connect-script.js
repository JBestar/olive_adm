$(document).ready(function() {
    addEventListner();
    requestTotalPage();
    setTimeout(function() {
        hitoryLoop();
    }, 120000);
});

function requestPageInfo() {
    requestMember();
}

function hitoryLoop() {
    requestPageInfo();
    // 120초뒤에 다시 실행
    setTimeout(function() {
        hitoryLoop();
    }, 120000);

}

function showMember(arrMember) {

    var strBuf = "";

    var curPage = getActivePage();
    var firstIdx = (curPage - 1) * CountPerPage;

    for (var nRow in arrMember) {
        strBuf += "<tr>";

        strBuf += "<td>";
        strBuf += (parseInt(nRow) + firstIdx + 1);
        strBuf += "</td> <td>";
        strBuf += arrMember[nRow].sess_mb_uid;
        strBuf += "</td> <td>";
        strBuf += arrMember[nRow].mb_nickname;
        strBuf += "</td> <td>";
        strBuf += arrMember[nRow].sess_ip;
        strBuf += "</td> <td>";
        strBuf += arrMember[nRow].sess_join;
        strBuf += "</td> <td>";
        strBuf += arrMember[nRow].sess_update;
        strBuf += "</td></tr>";
    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='6'>자료가 없습니다.</td></tr>";
    }

    $("#user-member-table-id").html(strBuf);
}



function addEventListner() {
    $("#userpanel-list-view-but-id").click(function() {
        requestTotalPage();
    });

    $("#userpanel-number-select-id").change(function() {
        requestTotalPage();
    });
}



function requestMember() {

    var nPage = getActivePage();
    var strUid = $("#userpanel-userid-input-id").val();

    var jsonData = {
        "count": CountPerPage,
        "page": nPage,
        "mb_uid": strUid,
    };

    jsonData = JSON.stringify(jsonData);
    $(".loading").show();

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/conlist",
        data: { json_: jsonData },
        success: function(jResult) {
            $(".loading").hide();
            //console.log(jResult);
            if (jResult.status == "success") {
                showMember(jResult.data);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });

}



//Function to Request Game Result History to WebServer
function requestTotalPage() {
    CountPerPage = $("#userpanel-number-select-id").val();
    var strUid = $("#userpanel-userid-input-id").val();

    var jsonData = {
        "count": CountPerPage,
        "mb_uid": strUid,
    };

    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: FURL + '/userapi/concnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data.count;
                setFirstPage();
                requestMember();
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}
