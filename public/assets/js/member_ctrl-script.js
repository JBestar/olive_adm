var mOrdItem = '';
var mOrdDir = '';

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

    return "";
}


function showMember(arrMember, confs) {

    var strBuf = "";

    var curPage = getActivePage();
    var firstIdx = (curPage - 1) * CountPerPage;
    for (var nRow in arrMember) {
        strBuf += "<tr id ='" + arrMember[nRow].mb_fid + "'";
        if (arrMember[nRow].mb_color != null)
            strBuf += "bgcolor='" + arrMember[nRow].mb_color + "' ";
        strBuf += ">";
        strBuf += "<td>";
        strBuf += (parseInt(nRow) + firstIdx + 1);
        strBuf += "</td> <td>";
        strBuf += getMemberLevelString(arrMember[nRow].mb_level);
        strBuf += "</td> <td>";
        strBuf += "<a href='"+FURL+"/user/member_ctrl/";
        strBuf += arrMember[nRow].mb_emp_fid;
        strBuf += "' class='link-member'>"
        strBuf += arrMember[nRow].mb_empname;
        strBuf += "</a>";
       
        strBuf += "</td> <td>";
        strBuf += "<a href='"+FURL+"/user/member_ctrl/";
        strBuf += arrMember[nRow].mb_fid;
        strBuf += "' class='link-member'>"
        strBuf += arrMember[nRow].mb_uid;
        strBuf += "</a>";
        strBuf += "</td> <td>";
        strBuf += arrMember[nRow].mb_nickname;
        if(arrMember[nRow].mb_state_delete > 0){
            strBuf += "<br>(오플라인)";    
        }
        strBuf += "</td> <td>";
        strBuf += parseInt(arrMember[nRow].mb_grade).toLocaleString() + "레벨";
        strBuf += "</td> <td>";
        strBuf += "<button name='" + arrMember[nRow].mb_fid + "' >충전</button>";
        strBuf += "</td> <td>";
        strBuf += "<button name='" + arrMember[nRow].mb_fid + "' >환전</button>";
        strBuf += "</td> <td>";
        strBuf += arrMember[nRow].mb_phone;

        strBuf += "</td><td> <span id='mm_" + arrMember[nRow].mb_fid + "'>";
        strBuf += parseInt(arrMember[nRow].mb_money).toLocaleString() + "</span>";
        strBuf += '<button class="refresh_btn" onclick="refreshEv(' + arrMember[nRow].mb_fid + ', this);"></button>';
        strBuf += "</td> <td id='mp_" + arrMember[nRow].mb_fid + "'>";
        strBuf += parseInt(arrMember[nRow].mb_point).toLocaleString();
        strBuf += "</td> <td>";
        if(arrMember[nRow].bet_sum == null)
            arrMember[nRow].bet_sum = 0;
        strBuf += parseInt(arrMember[nRow].bet_sum).toLocaleString();
        strBuf += "</td> <td>";
        if(arrMember[nRow].win_sum == null)
            arrMember[nRow].win_sum = 0;
        strBuf += parseInt(arrMember[nRow].win_sum).toLocaleString();
        strBuf += "</td> <td>";
        if(arrMember[nRow].rw_point == null)
            arrMember[nRow].rw_point = 0;
        strBuf += parseInt(arrMember[nRow].rw_point).toLocaleString();
        strBuf += "</td> <td>";
        if(arrMember[nRow].chg_point == null)
            arrMember[nRow].chg_point = 0;
        strBuf += parseInt(arrMember[nRow].chg_point).toLocaleString();
        strBuf += "</td> <td>";
        strBuf += arrMember[nRow].mb_time_join;
        strBuf += "</td> <td>";
        strBuf += "<button name='" + arrMember[nRow].mb_fid + "' >상세</button>";
        strBuf += "</td> <td>";
        strBuf += "<a href='"+FURL+"/user/member_edit/" + arrMember[nRow].mb_fid + "' >수정</a>";
        strBuf += "</td> <td>";
        strBuf += "<button name='" + arrMember[nRow].mb_fid + "'>삭제</button>   ";
        strBuf += "</td> <td>";
        strBuf += "<button name='" + arrMember[nRow].mb_fid + "'";
        if (confs.emp_level < LEVEL_ADMIN) {
            strBuf += " disabled = 'true' ";
        }
        if (arrMember[nRow].mb_state_active == 1) {
            strBuf += " class='button-active'>승인</button>";
        } else if (arrMember[nRow].mb_state_active == 2) {
            strBuf += ">대기</button>";
        } else {
            strBuf += ">차단</button>";
        }
        strBuf += "</td> <td>";
        strBuf += "<button name='" + arrMember[nRow].mb_fid + "'>강제아웃</button>   ";
        strBuf += "</td></tr>";
    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='21'>자료가 없습니다.</td></tr>";
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

    $(".user-table .sort-by").click(function() {
        let thName = $(this).text();
        
        let item = '';
        let dir = '';
        switch(thName){
            case "등급": item='mb_level'; break;
            case "추천인": item='mb_emp_fid'; break;
            case "아이디": item='mb_uid'; break;
            case "닉네임": item='mb_nickname'; break;
            case "레벨": item='mb_grade'; break;
            case "연락처": item='mb_phone'; break;
            case "보유금액": item='mb_money'; break;
            case "포인트": item='mb_point'; break;
            case "총베팅금": item='bet_sum'; break;
            case "총획득금": item='win_sum'; break;
            case "총롤링금": item='rw_point'; break;
            case "환전롤링금": item='chg_point'; break;
            case "등록날짜": item='mb_time_join'; break;
            default:break;
        }

        if(item.length > 0){
            let bAsc = $(this).hasClass('asc');
            let bDesc = $(this).hasClass('asc');
            $(".user-table .sort-by").removeClass('asc');
            $(".user-table .sort-by").removeClass('desc');

            if(bAsc){
                $(this).removeClass('asc');
                $(this).addClass('desc');
                dir = "desc";
            } else if(bDesc){
                $(this).removeClass('desc');
                $(this).addClass('asc');
                dir = "asc";
            } else {
                $(this).addClass('asc');
                dir = "asc";
            }
            mOrdItem = item;
            mOrdDir = dir;
            console.log(item + ", " + dir);
            requestMember();
        }

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
        "order":mOrdItem,
        "dir":mOrdDir,
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
        url: FURL + "/userapi/getmemberlist",
        data: { json_: jsonData },
        success: function(jResult) {
            $(".loading").hide();
            console.log(jResult);
            if (jResult.status == "success") {
                showMember(jResult.data, jResult.confs);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
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
        url: FURL + '/userapi/getmembercnt',
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
        } else if (this.innerHTML.search("승인") >= 0) {
            var jsonData = { "mb_fid": this.name, "mb_state_active": 0 };
            requestUpdateCompany(jsonData);
        } else if (this.innerHTML.search("차단") >= 0) {
            var jsonData = { "mb_fid": this.name, "mb_state_active": 1 };
            requestUpdateCompany(jsonData);
        } else if (this.innerHTML.search("대기") >= 0) {
            var jsonData = { "mb_fid": this.name, "mb_state_active": 1 };
            requestWaitToPermit(this, jsonData);
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
        url: FURL + "/userapi/wait_permit",
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
                window.location.replace( FURL +'/pages/nopermit');
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
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
        url: FURL + "/userapi/updatemember",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);

            if (jResult.status == "success") {
                requestMember();
                // updateMember(jResult.data, jResult.level);
            } else if (jResult.status == "fail") {

            } else if (jResult.status == "nopermit") {
                alert('변경권한이 없습니다.');
                location.replace( FURL +'/pages/nopermit');
            } else if (jResult.status == "logout") {
                location.replace( FURL +'/');
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
        url: FURL + "/userapi/add_block",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);

            if (jResult.status == "success") {
                location.replace( FURL +'/user/member_block');
                // updateMember(jResult.data, jResult.level);
            } else if (jResult.status == "fail") {

            } else if (jResult.status == "nopermit") {
                alert('변경권한이 없습니다.');
                location.replace( FURL +'/pages/nopermit');
            } else if (jResult.status == "logout") {
                location.replace( FURL +'/');
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
        url: FURL + "/userapi/deletemember",
        data: { json_: jsonData },
        success: function(jResult) {
            //console.log(jResult);

            if (jResult.status == "success") {
                requestMember();
                //window.location.reload();
            } else if (jResult.status == "fail") {

            } else if (jResult.status == "nopermit") {
                alert('변경권한이 없습니다.');
                window.location.replace( FURL +'/pages/nopermit');
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });

}

function refreshEv(mbFid, elBtn) {
    var jsonData = { "mb_fid": mbFid };
    jsonData = JSON.stringify(jsonData);
    $(elBtn).addClass("refresh");

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/egginfo",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            $(elBtn).removeClass("refresh");

            if (jResult.status == "success") {
                $("#mm_" + mbFid).text(parseInt(jResult.money).toLocaleString() + "원");
                $("#mp_" + mbFid).text(parseInt(jResult.point).toLocaleString());

            } else if (jResult.status == "fail") {

            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            }
        },
        error: function(request, status, error) {
            $(elBtn).removeClass("refresh");
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });

}


