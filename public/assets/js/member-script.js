$(document).ready(function() {
    addEventListner();
    requestTotalPage();
});

function requestPageInfo() {
    requestMember();
}
function getMemberLevelString(nLevel){
    if (nLevel == LEVEL_ADMIN)
        return "관리자";
    else if (nLevel == LEVEL_COMPANY)
        return "부본";
    else if (nLevel == LEVEL_AGENCY)
        return "총판";
    else if (nLevel == LEVEL_EMPLOYEE)
        return "매장";
    
    return null;
}
function updateMember(arrMember, nAdminLevel){
    var tableEle = document.getElementById("user-member-table-id");
    
    var trEleList = tableEle.getElementsByTagName("tr");
    
    var curPage = getActivePage();
    var firstIdx = (curPage - 1) * CountPerPage;
    for (var nRow in trEleList){
        if (trEleList[nRow].getAttribute('id') != arrMember.mb_fid){
            continue;
        }
        strBuf = "<td>";
        strBuf += (parseInt(nRow) + firstIdx + 1);
        strBuf += "</td> <td>";
        if (nAdminLevel == LEVEL_ADMIN){
            strBuf += "<a href=\"/user/member/";
            strBuf += arrMember.mb_emp_fid;
            strBuf += "\" class=\"class1\">"
            strBuf += arrMember.mb_empname;
            strBuf += "</a>";
        }
        else {
            strBuf += arrMember.mb_empname;
        }
        
        strBuf += "</td> <td>";
        if (nAdminLevel == LEVEL_ADMIN)
        {
            strBuf += "<a href=\"/user/member/";
            strBuf += arrMember.mb_fid;
            strBuf += "\" class=\"class1\">"
            strBuf += arrMember.mb_uid;
            if (getMemberLevelString(arrMember.mb_level) != null)
                strBuf += " | " + getMemberLevelString(arrMember.mb_level);
            strBuf += "</a>";
        }
        else {
            strBuf += arrMember.mb_uid;
        }
        
        strBuf += "</td> <td>";
        strBuf += arrMember.mb_nickname;
        strBuf += "</td> <td>";
        strBuf += parseInt(arrMember.mb_grade).toLocaleString() + "레벨";
        strBuf += "</td> <td>";
        strBuf += parseInt(arrMember.mb_money_charge).toLocaleString() + "원";
        strBuf += "</td> <td>";
        strBuf += parseInt(arrMember.mb_money_exchange).toLocaleString() + "원";
        strBuf += "</td> <td>";
        strBuf += parseInt(arrMember.mb_money).toLocaleString() + "원";
        strBuf += "</td> <td>";
        strBuf += parseInt(arrMember.mb_point).toLocaleString();
        strBuf += "</td> <td>";
        strBuf += parseInt(arrMember.mb_live_money).toLocaleString() + "원";
        strBuf += "</td> <td>";
        strBuf += parseInt(arrMember.mb_slot_money).toLocaleString() + "원";
        strBuf += "</td> <td>";
        if (arrMember.mb_state_active == 1) {
            strBuf += "<button name=\"" + arrMember.mb_fid + "\"  class=\"button-active\">승인</button>";
        } else if (arrMember.mb_state_active == 2) {
            strBuf += "<button name=\"" + arrMember.mb_fid + "\" >대기</button>";
        } else {
            strBuf += "<button name=\"" + arrMember.mb_fid + "\" >차단</button>";
        }
        strBuf += "<a href=\"/user/member_edit/" + arrMember.mb_fid + "\" >수정</a>";
        if (nAdminLevel > LEVEL_COMPANY) {
            var strEncodeURI = "/board/message_edit/0/" + arrMember.mb_fid;
            strBuf += "<a href=\"" + strEncodeURI + "\" >쪽지</a>";
            strBuf += "<button name=\"" + arrMember.mb_fid + "\">삭제</button>   ";
        }
        strBuf += "</td> <td>";
        if (arrMember.mb_game_pb == 1) {
            strBuf += "<button name=\"" + arrMember.mb_fid + "\"  class=\"button-active\">파워볼</button>";
        } else {
            strBuf += "<button name=\"" + arrMember.mb_fid + "\" >파워볼</button>";
        }
        if (arrMember.mb_game_ps == 1) {
            strBuf += "<button name=\"" + arrMember.mb_fid + "\"  class=\"button-active\">파워사다리</button>";
        } else {
            strBuf += "<button name=\"" + arrMember.mb_fid + "\" >파워사다리</button>";
        }
        if (arrMember.mb_game_bb == 1) {
            strBuf += "<button name=\"" + arrMember.mb_fid + "\"  class=\"button-active\">보글볼</button>";
        } else {
            strBuf += "<button name=\"" + arrMember.mb_fid + "\" >보글볼</button>";
        }
        if (arrMember.mb_game_bs == 1) {
            strBuf += "<button name=\"" + arrMember.mb_fid + "\"  class=\"button-active\">보글사다리</button>";
        } else {
            strBuf += "<button name=\"" + arrMember.mb_fid + "\" >보글사다리</button>";
        }
        if (arrMember.mb_game_cs == 1) {
            strBuf += "<button name=\"" + arrMember.mb_fid + "\"  class=\"button-active\">카지노</button>";
        } else {
            strBuf += "<button name=\"" + arrMember.mb_fid + "\" >카지노</button>";
        }
        if (arrMember.mb_game_sl == 1) {
            strBuf += "<button name=\"" + arrMember.mb_fid + "\"  class=\"button-active\">슬롯</button>";
        } else {
            strBuf += "<button name=\"" + arrMember.mb_fid + "\" >슬롯</button>";
        }
        strBuf += "</td>";
        trEleList[nRow].innerHTML = strBuf;
        addBtnEventByEle(trEleList[nRow]);
        break;
    }
    

}
function showMember(arrMember, nAdminLevel) {

    var strBuf = "";

    var curPage = getActivePage();
    var firstIdx = (curPage - 1) * CountPerPage;

    for (var nRow in arrMember) {
        strBuf += "<tr id =\"" + arrMember[nRow].mb_fid +"\"";
        if (arrMember[nRow].mb_color != null)
            strBuf += " bgcolor=\"" + arrMember[nRow].mb_color + "\"";
        strBuf += ">";
        strBuf += "<td>";
        strBuf += (parseInt(nRow) + firstIdx + 1);
        strBuf += "</td> <td>";
        if (nAdminLevel == LEVEL_ADMIN){
            strBuf += "<a href=\"/user/member/";
            strBuf += arrMember[nRow].mb_emp_fid;
            strBuf += "\" class=\"class1\">"
            strBuf += arrMember[nRow].mb_empname;
            strBuf += "</a>";
        }
        else {
            strBuf += arrMember[nRow].mb_empname;
        }
        
        strBuf += "</td> <td>";
        if (nAdminLevel == LEVEL_ADMIN)
        {
            strBuf += "<a href=\"/user/member/";
            strBuf += arrMember[nRow].mb_fid;
            strBuf += "\" class=\"class1\">"
            strBuf += arrMember[nRow].mb_uid;
            if (getMemberLevelString(arrMember[nRow].mb_level) != null)
                strBuf += " | " + getMemberLevelString(arrMember[nRow].mb_level);
            strBuf += "</a>";
        }
        else {
            strBuf += arrMember[nRow].mb_uid;
        }
        
        strBuf += "</td> <td>";
        strBuf += arrMember[nRow].mb_nickname;
        strBuf += "</td> <td>";
        strBuf += parseInt(arrMember[nRow].mb_grade).toLocaleString() + "레벨";
        strBuf += "</td> <td>";
        strBuf += parseInt(arrMember[nRow].mb_money_charge).toLocaleString() + "원";
        strBuf += "</td> <td>";
        strBuf += parseInt(arrMember[nRow].mb_money_exchange).toLocaleString() + "원";
        strBuf += "</td> <td>";
        strBuf += parseInt(arrMember[nRow].mb_money).toLocaleString() + "원";
        strBuf += "</td> <td>";
        strBuf += parseInt(arrMember[nRow].mb_point).toLocaleString();
        strBuf += "</td> <td>";
        strBuf += parseInt(arrMember[nRow].mb_live_money).toLocaleString() + "원";
        strBuf += "</td> <td>";
        strBuf += parseInt(arrMember[nRow].mb_slot_money).toLocaleString() + "원";
        strBuf += "</td> <td>";
        if (arrMember[nRow].mb_state_active == 1) {
            strBuf += "<button name=\"" + arrMember[nRow].mb_fid + "\"  class=\"button-active\">승인</button>";
        } else if (arrMember[nRow].mb_state_active == 2) {
            strBuf += "<button name=\"" + arrMember[nRow].mb_fid + "\" >대기</button>";
        } else {
            strBuf += "<button name=\"" + arrMember[nRow].mb_fid + "\" >차단</button>";
        }
        strBuf += "<a href=\"/user/member_edit/" + arrMember[nRow].mb_fid + "\" >수정</a>";
        if (nAdminLevel > LEVEL_COMPANY) {
            var strEncodeURI = "/board/message_edit/0/" + arrMember[nRow].mb_fid;
            strBuf += "<a href=\"" + strEncodeURI + "\" >쪽지</a>";
            strBuf += "<button name=\"" + arrMember[nRow].mb_fid + "\">삭제</button>   ";
        }
        strBuf += "</td> <td>";
        if (arrMember[nRow].mb_game_pb == 1) {
            strBuf += "<button name=\"" + arrMember[nRow].mb_fid + "\"  class=\"button-active\">파워볼</button>";
        } else {
            strBuf += "<button name=\"" + arrMember[nRow].mb_fid + "\" >파워볼</button>";
        }
        if (arrMember[nRow].mb_game_ps == 1) {
            strBuf += "<button name=\"" + arrMember[nRow].mb_fid + "\"  class=\"button-active\">파워사다리</button>";
        } else {
            strBuf += "<button name=\"" + arrMember[nRow].mb_fid + "\" >파워사다리</button>";
        }
        if (arrMember[nRow].mb_game_bb == 1) {
            strBuf += "<button name=\"" + arrMember[nRow].mb_fid + "\"  class=\"button-active\">보글볼</button>";
        } else {
            strBuf += "<button name=\"" + arrMember[nRow].mb_fid + "\" >보글볼</button>";
        }
        if (arrMember[nRow].mb_game_bs == 1) {
            strBuf += "<button name=\"" + arrMember[nRow].mb_fid + "\"  class=\"button-active\">보글사다리</button>";
        } else {
            strBuf += "<button name=\"" + arrMember[nRow].mb_fid + "\" >보글사다리</button>";
        }
        if (arrMember[nRow].mb_game_cs == 1) {
            strBuf += "<button name=\"" + arrMember[nRow].mb_fid + "\"  class=\"button-active\">카지노</button>";
        } else {
            strBuf += "<button name=\"" + arrMember[nRow].mb_fid + "\" >카지노</button>";
        }
        if (arrMember[nRow].mb_game_sl == 1) {
            strBuf += "<button name=\"" + arrMember[nRow].mb_fid + "\"  class=\"button-active\">슬롯</button>";
        } else {
            strBuf += "<button name=\"" + arrMember[nRow].mb_fid + "\" >슬롯</button>";
        }
        strBuf += "</td></tr>";
    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='11'>자료가 없습니다.</td></tr>";
    }

    document.getElementById("user-member-table-id").innerHTML = strBuf;
    addBtnEvent();
}



function addEventListner() {
    var butView = document.getElementById("userpanel-list-view-but-id");
    butView.addEventListener("click", function() {

        requestTotalPage();
    });

    var selectLevel = document.getElementById("userpanel-level-select-id");
    selectLevel.addEventListener("change", function() {
        requestTotalPage();
    });

    var selectView = document.getElementById("userpanel-number-select-id");
    selectView.addEventListener("change", function() {

        requestTotalPage();
    });
}



function requestMember() {

    var nPage = getActivePage();
    var userGrad = document.getElementById("userpanel-level-select-id").value;
    var strMbUid = document.getElementById("userpanel-userid-input-id").value;
    var empIdEle = document.getElementById("userpanel-empid-input-id");
    var strEmpUid = "";
    if (typeof (empIdEle) != undefined && empIdEle != null)
        strEmpUid = empIdEle.value;
    var jsonData = { "count": CountPerPage, "page": nPage, "mb_grade": userGrad, "mb_uid": strMbUid, "mb_emp_uid":strEmpUid};


    jsonData = JSON.stringify(jsonData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/userapi/getmembers",
        data: { json_: jsonData },
        success: function(jResult) {
            //console.log(jResult);
            if (jResult.status == "success") {
                showMember(jResult.data, jResult.level);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });

}



//Function to Request Game Result History to WebServer
function requestTotalPage() {
    CountPerPage = document.getElementById("userpanel-number-select-id").value;
    var userGrad = document.getElementById("userpanel-level-select-id").value;
    var strMbUid = document.getElementById("userpanel-userid-input-id").value;
    var empIdEle = document.getElementById("userpanel-empid-input-id");
    var strEmpUid = "";
    if (typeof (empIdEle) != undefined && empIdEle != null)
        strEmpUid = empIdEle.value;
    var jsonData = { "count": CountPerPage, "mb_grade": userGrad, "mb_uid": strMbUid, "mb_emp_uid":strEmpUid};

    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: '/userapi/getmembercnt',
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
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}


function addButtonElementListener(buttonElement){
    buttonElement.addEventListener("click", function() {

        if (this.innerHTML.search("삭제") >= 0) {
            if (!confirm("삭제하시겠습니까?"))
                return;
            var jsonData = { "mb_fid": this.name };
            requestDeleteCompany(jsonData);
        } else if (this.innerHTML.search("승인") >= 0) {
            var jsonData = { "mb_fid": this.name, "mb_state_active": 0 };
            requestUpdateCompany(jsonData);
        } else if (this.innerHTML.search("차단") >= 0) {
            var jsonData = { "mb_fid": this.name, "mb_state_active": 1 };
            requestUpdateCompany(jsonData);
        } else if (this.innerHTML.search("대기") >= 0) {
            var jsonData = { "mb_fid": this.name, "mb_state_active": 1 };
            requestWaitToPermit(this, jsonData);
        } else if (this.innerHTML.search("파워볼") >= 0) {
            if (this.className.search("button-active") >= 0) {
                var jsonData = { "mb_fid": this.name, "mb_game_pb": 0 };
                requestUpdateCompany(jsonData);
            } else {
                var jsonData = { "mb_fid": this.name, "mb_game_pb": 1 };
                requestUpdateCompany(jsonData);
            }
        } else if (this.innerHTML.search("파워사다리") >= 0) {
            if (this.className.search("button-active") >= 0) {
                var jsonData = { "mb_fid": this.name, "mb_game_ps": 0 };
                requestUpdateCompany(jsonData);
            } else {
                var jsonData = { "mb_fid": this.name, "mb_game_ps": 1 };
                requestUpdateCompany(jsonData);
            }
        } else if (this.innerHTML.search("보글볼") >= 0) {
            if (this.className.search("button-active") >= 0) {
                var jsonData = { "mb_fid": this.name, "mb_game_bb": 0 };
                requestUpdateCompany(jsonData);
            } else {
                var jsonData = { "mb_fid": this.name, "mb_game_bb": 1 };
                requestUpdateCompany(jsonData);
            }
        } else if (this.innerHTML.search("보글사다리") >= 0) {
            if (this.className.search("button-active") >= 0) {
                var jsonData = { "mb_fid": this.name, "mb_game_bs": 0 };
                requestUpdateCompany(jsonData);
            } else {
                var jsonData = { "mb_fid": this.name, "mb_game_bs": 1 };
                requestUpdateCompany(jsonData);
            }
        } else if (this.innerHTML.search("카지노") >= 0) {
            if (this.className.search("button-active") >= 0) {
                var jsonData = { "mb_fid": this.name, "mb_game_cs": 0 };
                requestUpdateCompany(jsonData);
            } else {
                var jsonData = { "mb_fid": this.name, "mb_game_cs": 1 };
                requestUpdateCompany(jsonData);
            }
        } else if (this.innerHTML.search("슬롯") >= 0) {
            if (this.className.search("button-active") >= 0) {
                var jsonData = { "mb_fid": this.name, "mb_game_sl": 0 };
                requestUpdateCompany(jsonData);
            } else {
                var jsonData = { "mb_fid": this.name, "mb_game_sl": 1 };
                requestUpdateCompany(jsonData);
            }
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

function addBtnEventByEle(element){

    var btnEleList = element.getElementsByTagName("button");
    if (btnEleList == null)
        return;

    for (var index in btnEleList) {
        addButtonElementListener(btnEleList[index]);
    }
}


function requestWaitToPermit(elemBut, jsData) {

    if (mAudio != undefined && mAudio != null) {
        mAudio.pause();
    }

    $(elemBut).attr('disabled', true);
    jsonData = JSON.stringify(jsData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/userapi/wait_permit",
        data: { json_: jsonData },
        success: function(jResult) {
            //console.log(jResult);
            $(elemBut).attr('disabled', false);

            if (jResult.status == "success") {
                requestEmployeeInfo();
                requestMember();

            } else if (jResult.status == "usererror") {
                alert('회원정보가 정확하지 않습니다.\n 다시 확인해주세요');
            } else if (jResult.status == "fail") {
                alert('회원승인이 실패되었습니다.');
            } else if (jResult.status == "nopermit") {
                alert('변경권한이 없습니다.');
                window.location.replace('/pages/nopermit');
            } else if (jResult.status == "logout") {
                window.location.replace('/');
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}


function requestUpdateCompany(jsData) {

    var jsonData = JSON.stringify(jsData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/userapi/updatemember",
        data: { json_: jsonData },
        success: function(jResult) {
            //console.log(jResult);

            if (jResult.status == "success") {
                // requestMember();
                updateMember(jResult.data, jResult.level);

            } else if (jResult.status == "fail") {

            } else if (jResult.status == "nopermit") {
                alert('변경권한이 없습니다.');
                location.replace('/pages/nopermit');
            } else if (jResult.status == "logout") {
                location.replace('/');
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });

}

function requestDeleteCompany(jsData) {

    var jsonData = JSON.stringify(jsData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/userapi/deletemember",
        data: { json_: jsonData },
        success: function(jResult) {
            //console.log(jResult);

            if (jResult.status == "success") {
                requestMember();
                //window.location.reload();
            } else if (jResult.status == "fail") {

            } else if (jResult.status == "nopermit") {
                alert('변경권한이 없습니다.');
                window.location.replace('/pages/nopermit');
            } else if (jResult.status == "logout") {
                window.location.replace('/');
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });

}