$(document).ready(function() {
    addEventListner();
    requestTotalPage();
});

function requestPageInfo() {
    requestMember();
}

function getMemberLevelString(nLevel) {
    if (nLevel >= LEVEL_ADMIN)
        return "관리자";
    else if (nLevel == LEVEL_COMPANY)
        return "부본";
    else if (nLevel == LEVEL_AGENCY)
        return "총판";
    else if (nLevel == LEVEL_EMPLOYEE)
        return "매장";

    return null;
}


function showMember(arrMember, nAdminLevel) {

    var strBuf = "";

    var curPage = getActivePage();
    var firstIdx = (curPage - 1) * CountPerPage;
    var strApp = $("#main-container-id").data('app');
    for (var nRow in arrMember) {
        strBuf += "<tr id ='" + arrMember[nRow].mb_fid + "'";
        if (arrMember[nRow].mb_color != null)
            strBuf += " bgcolor='" + arrMember[nRow].mb_color + "'";
        strBuf += ">";
        strBuf += "<td>";
        strBuf += (parseInt(nRow) + firstIdx + 1);
        strBuf += "</td> <td>";
        if (nAdminLevel >= LEVEL_ADMIN) {
            strBuf += "<a href='/user/member/";
            strBuf += arrMember[nRow].mb_emp_fid;
            strBuf += "' class='link-member'>"
            strBuf += arrMember[nRow].mb_empname;
            strBuf += "</a>";
        } else {
            strBuf += arrMember[nRow].mb_empname;
        }

        strBuf += "</td> <td>";
        if (nAdminLevel >= LEVEL_ADMIN) {
            strBuf += "<a href='/user/member/";
            strBuf += arrMember[nRow].mb_fid;
            strBuf += "' class='link-member'>"
            strBuf += arrMember[nRow].mb_uid;
            if (getMemberLevelString(arrMember[nRow].mb_level) != null)
                strBuf += " | " + getMemberLevelString(arrMember[nRow].mb_level);
            strBuf += "</a>";
        } else {
            strBuf += arrMember[nRow].mb_uid;
        }

        strBuf += "</td> <td>";
        strBuf += arrMember[nRow].mb_nickname;
        strBuf += "</td> <td>";
        strBuf += parseInt(arrMember[nRow].mb_grade).toLocaleString() + "레벨";
        strBuf += "</td> <td id='mm_" + arrMember[nRow].mb_fid + "'>";
        strBuf += parseInt(arrMember[nRow].mb_money).toLocaleString() + "원";
        strBuf += "</td> <td id='mp_" + arrMember[nRow].mb_fid + "'>";
        strBuf += parseInt(arrMember[nRow].mb_point).toLocaleString();
        strBuf += "</td> <td>";
        strBuf += "<span id='ev_" + arrMember[nRow].mb_fid + "'>에볼: " + parseInt(arrMember[nRow].mb_live_money).toLocaleString() + "</span><br>";

        if (strApp == APPTYPE_0)
            strBuf += "<span id='sl_" + arrMember[nRow].mb_fid + "'>슬롯: " + parseInt(arrMember[nRow].mb_slot_money).toLocaleString() + "</span><br>";
        if (strApp == APPTYPE_0)
            strBuf += "<span id='fsl_" + arrMember[nRow].mb_fid + "'>네츄럴슬롯: " + parseInt(arrMember[nRow].mb_fslot_money).toLocaleString() + "</span>";
        else if (strApp == APPTYPE_2)
            strBuf += "<span id='fsl_" + arrMember[nRow].mb_fid + "'>슬롯: " + parseInt(arrMember[nRow].mb_fslot_money).toLocaleString() + "</span>";
        else if (strApp == APPTYPE_1)
            strBuf += "<span id='tsl_" + arrMember[nRow].mb_fid + "'>슬롯: " + (parseInt(arrMember[nRow].mb_slot_money) + parseInt(arrMember[nRow].mb_fslot_money)).toLocaleString() + "</span>";
        strBuf += '<br><button class="refresh_btn" onclick="refreshEv(' + arrMember[nRow].mb_fid + ');"></button>';


        strBuf += "</td> <td>";
        if (nAdminLevel >= LEVEL_ADMIN) {
            strBuf += arrMember[nRow].mb_ip_last;
            if (arrMember[nRow].block_state == 1) {
                strBuf += "<br><br><button name='" + arrMember[nRow].mb_ip_last + "' >차단해제</button>";
            } else
                strBuf += "<br><br><button name='" + arrMember[nRow].mb_ip_last + "' >IP차단</button>";
            strBuf += "</td> <td>";
        }
        if (arrMember[nRow].mb_state_active == 1) {
            strBuf += "<button name='" + arrMember[nRow].mb_fid + "'  class='button-active'>승인</button>";
        } else if (arrMember[nRow].mb_state_active == 2) {
            strBuf += "<button name='" + arrMember[nRow].mb_fid + "' >대기</button>";
        } else {
            strBuf += "<button name='" + arrMember[nRow].mb_fid + "' >차단</button>";
        }
        strBuf += "<a href='/user/member_edit/" + arrMember[nRow].mb_fid + "' >수정</a>";
        if (nAdminLevel > LEVEL_COMPANY) {
            var strEncodeURI = "/board/message_edit/0/" + arrMember[nRow].mb_fid;
            strBuf += "<a href='" + strEncodeURI + "' >쪽지</a>";
            strBuf += "<button name='" + arrMember[nRow].mb_fid + "'>삭제</button>   ";
        }
        strBuf += "</td> <td>";
        if (arrMember[nRow].mb_game_pb == 1) {
            strBuf += "<button name='" + arrMember[nRow].mb_fid + "'  class='button-active'>파워볼</button>";
        } else {
            strBuf += "<button name='" + arrMember[nRow].mb_fid + "' >파워볼</button>";
        }
        if (arrMember[nRow].mb_game_ps == 1) {
            strBuf += "<button name='" + arrMember[nRow].mb_fid + "'  class='button-active'>파워사다리</button>";
        } else {
            strBuf += "<button name='" + arrMember[nRow].mb_fid + "' >파워사다리</button>";
        }
        if (arrMember[nRow].mb_game_bb == 1) {
            strBuf += "<button name='" + arrMember[nRow].mb_fid + "'  class='button-active'>보글볼</button>";
        } else {
            strBuf += "<button name='" + arrMember[nRow].mb_fid + "' >보글볼</button>";
        }
        if (arrMember[nRow].mb_game_bs == 1) {
            strBuf += "<button name='" + arrMember[nRow].mb_fid + "'  class='button-active'>보글사다리</button>";
        } else {
            strBuf += "<button name='" + arrMember[nRow].mb_fid + "' >보글사다리</button>";
        }
        if (arrMember[nRow].mb_game_cs == 1) {
            strBuf += "<button name='" + arrMember[nRow].mb_fid + "'  class='button-active'>카지노</button>";
        } else {
            strBuf += "<button name='" + arrMember[nRow].mb_fid + "' >카지노</button>";
        }
        if (arrMember[nRow].mb_game_sl == 1) {
            strBuf += "<button name='" + arrMember[nRow].mb_fid + "'  class='button-active'>슬롯</button>";
        } else {
            strBuf += "<button name='" + arrMember[nRow].mb_fid + "' >슬롯</button>";
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
    $("#userpanel-list-view-but-id").click(function() {
        requestTotalPage();
    });

    $("#userpanel-level-select-id").change(function() {
        requestTotalPage();
    });

    $("#userpanel-state-select-id").change(function() {
        requestTotalPage();
    });

    $("#userpanel-number-select-id").change(function() {
        requestTotalPage();
    });
}



function requestMember() {

    var nPage = getActivePage();
    var userGrad = $("#userpanel-level-select-id").val();
    var strMbUid = $("#userpanel-userid-input-id").val();
    var iState = $("#userpanel-state-select-id").val();
    var empIdEle = document.getElementById("userpanel-empid-input-id");
    var strEmpUid = "";
    if (typeof(empIdEle) != undefined && empIdEle != null)
        strEmpUid = empIdEle.value;
    var jsonData = {
        "count": CountPerPage,
        "page": nPage,
        "mb_grade": userGrad,
        "mb_uid": strMbUid,
        "mb_emp_uid": strEmpUid,
        "mb_state": iState
    };


    jsonData = JSON.stringify(jsonData);
    $(".loading").show();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/userapi/getmembers",
        data: { json_: jsonData },
        success: function(jResult) {
            //console.log(jResult);            
            $(".loading").hide();
            //console.log(jResult);
            if (jResult.status == "success") {
                showMember(jResult.data, jResult.level);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            $(".loading").hide();
        }

    });

}



//Function to Request Game Result History to WebServer
function requestTotalPage() {
    CountPerPage = $("#userpanel-number-select-id").val();
    var userGrad = $("#userpanel-level-select-id").val();
    var strMbUid = $("#userpanel-userid-input-id").val();
    var iState = $("#userpanel-state-select-id").val();
    var empIdEle = document.getElementById("userpanel-empid-input-id");
    var strEmpUid = "";
    if (typeof(empIdEle) != undefined && empIdEle != null)
        strEmpUid = empIdEle.value;
    var jsonData = {
        "count": CountPerPage,
        "mb_grade": userGrad,
        "mb_uid": strMbUid,
        "mb_emp_uid": strEmpUid,
        "mb_state": iState,
    };

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
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}


function addButtonElementListener(buttonElement) {
    buttonElement.addEventListener("click", function() {

        if (this.innerHTML.search("삭제") >= 0) {
            if (!confirm("삭제하시겠습니까?"))
                return;
            var jsonData = { "mb_fid": this.name };
            requestDeleteCompany(jsonData);
        } else if (this.innerHTML.search("IP차단") >= 0) {
            var jsonData = { "block_ip": this.name, "block_state": 1 };
            requestAddBlock(jsonData);
        } else if (this.innerHTML.search("차단해제") >= 0) {
            var jsonData = { "block_ip": this.name, "block_state": 0 };
            requestAddBlock(jsonData);
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
            // console.log(jResult);

            if (jResult.status == "success") {
                requestMember();
                // updateMember(jResult.data, jResult.level);
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
                // updateMember(jResult.data, jResult.level);
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

function refreshEv(mbFid) {
    var jsonData = { "mb_fid": mbFid };
    jsonData = JSON.stringify(jsonData);
    $(".loading").show();

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/userapi/egg_ev",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);

            if (jResult.status == "success") {
                $("#ev_" + mbFid).text("에볼: " + parseInt(jResult.live_money).toLocaleString());
                $("#mm_" + mbFid).text(parseInt(jResult.money).toLocaleString() + "원");
                $("#mp_" + mbFid).text(parseInt(jResult.point).toLocaleString());

            } else if (jResult.status == "fail") {

            } else if (jResult.status == "logout") {
                window.location.replace('/');
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });

    setTimeout(function() {
        refreshSl(mbFid);
    }, 500);

    setTimeout(function() {
        refreshFsl(mbFid);
    }, 1000);
}


function refreshSl(mbFid) {
    var jsonData = { "mb_fid": mbFid };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/userapi/egg_sl",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);

            if (jResult.status == "success") {
                $("#sl_" + mbFid).text("슬롯: " + parseInt(jResult.slot_money).toLocaleString());
                $("#tsl_" + mbFid).text("슬롯: " + (parseInt(jResult.slot_money) + parseInt(jResult.fslot_money)).toLocaleString());

            } else if (jResult.status == "fail") {

            } else if (jResult.status == "logout") {
                window.location.replace('/');
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}


function refreshFsl(mbFid) {
    var jsonData = { "mb_fid": mbFid };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/userapi/egg_fsl",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            $(".loading").hide();

            if (jResult.status == "success") {
                $("#fsl_" + mbFid).text("슬롯: " + parseInt(jResult.slot_money).toLocaleString());
                $("#tsl_" + mbFid).text("슬롯: " + (parseInt(jResult.slot_money) + parseInt(jResult.fslot_money)).toLocaleString());

            } else if (jResult.status == "fail") {

            } else if (jResult.status == "logout") {
                window.location.replace('/');
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}