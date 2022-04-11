$(document).ready(function() {
    addEventListner();
    requestTotalPage();
});

function requestPageInfo() {
    requestMember();
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
        strBuf += arrMember[nRow].log_mb_uid;
        strBuf += "</td> <td>";
        strBuf += arrMember[nRow].mb_nickname;
        strBuf += "</td> <td>";
        strBuf += arrMember[nRow].log_ip;
        strBuf += "</td> <td>";
        strBuf += arrMember[nRow].log_time;
        strBuf += "</td> <td>";
        if (arrMember[nRow].block_state == 1) {
            strBuf += "<button name='" + arrMember[nRow].log_ip + "' >차단해제</button>";
        } else
            strBuf += "<button name='" + arrMember[nRow].log_ip + "' >IP차단</button>";
        strBuf += "</td></tr>";
    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='6'>자료가 없습니다.</td></tr>";
    }

    document.getElementById("user-member-table-id").innerHTML = strBuf;
    addBtnEvent();
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
    var dtStart = $("#userpanel-datestart-input-id").val();
    var dtEnd = $("#userpanel-dateend-input-id").val();

    var jsonData = {
        "count": CountPerPage,
        "page": nPage,
        "mb_uid": strUid,
        "start": dtStart,
        "end": dtEnd
    };


    jsonData = JSON.stringify(jsonData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/userapi/loglist",
        data: { json_: jsonData },
        success: function(jResult) {
            //console.log(jResult);
            if (jResult.status == "success") {
                showMember(jResult.data);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });

}



//Function to Request Game Result History to WebServer
function requestTotalPage() {
    CountPerPage = $("#userpanel-number-select-id").val();
    var strUid = $("#userpanel-userid-input-id").val();
    var dtStart = $("#userpanel-datestart-input-id").val();
    var dtEnd = $("#userpanel-dateend-input-id").val();

    var jsonData = {
        "count": CountPerPage,
        "mb_uid": strUid,
        "start": dtStart,
        "end": dtEnd
    };

    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: '/userapi/logcnt',
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

function requestAddBlock(jsData) {

    var jsonData = JSON.stringify(jsData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/userapi/add_block",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);

            if (jResult.status == "success") {
                location.replace('/user/member_block');
            } else if (jResult.status == "fail") {

            } else if (jResult.status == "nopermit") {
                alert('변경권한이 없습니다.');
                location.replace('/pages/nopermit');
            } else if (jResult.status == "logout") {
                location.replace('/');
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });

}


function addButtonElementListener(buttonElement) {
    buttonElement.addEventListener("click", function() {

        if (this.innerHTML.search("IP차단") >= 0) {
            var jsonData = { "block_ip": this.name, "block_state": 1 };
            requestAddBlock(jsonData);
        } else if (this.innerHTML.search("차단해제") >= 0) {
            var jsonData = { "block_ip": this.name, "block_state": 0 };
            requestAddBlock(jsonData);
        }
    });
}

function addBtnEvent() {
    /* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */

    var elemTable = document.getElementById("user-member-table-id");
    var elemTableBtns = elemTable.getElementsByTagName("button");
    if (elemTableBtns == null)
        return;

    var i;
    for (i = 0; i < elemTableBtns.length; i++) {
        addButtonElementListener(elemTableBtns[i]);
    }
}