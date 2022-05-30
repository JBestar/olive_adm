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
        strBuf += "<a href='"+FURL+"/user/member/";
        strBuf += arrMember[nRow].mb_emp_fid;
        strBuf += "' class='link-member'>"
        strBuf += arrMember[nRow].mb_empname;
        strBuf += "</a>";

        strBuf += "</td> <td>";
        strBuf += "<a href='"+FURL+"/user/member/";
        strBuf += arrMember[nRow].mb_fid;
        strBuf += "' class='link-member'>"
        strBuf += arrMember[nRow].mb_uid;
        if (getMemberLevelString(arrMember[nRow].mb_level) != null)
            strBuf += " | " + getMemberLevelString(arrMember[nRow].mb_level);
        strBuf += "</a>";

        strBuf += "</td> <td>";
        strBuf += arrMember[nRow].mb_nickname;
        if(arrMember[nRow].mb_state_delete > 0){
            strBuf += "<br>(오플라인)";    
        }
        strBuf += "</td> <td>";
        strBuf += parseInt(arrMember[nRow].mb_grade).toLocaleString() + "레벨";
        strBuf += "</td><td> <span id='mm_" + arrMember[nRow].mb_fid + "'>";
        strBuf += (parseInt(arrMember[nRow].mb_money) + parseInt(arrMember[nRow].mb_live_money) + parseInt(arrMember[nRow].mb_slot_money) + parseInt(arrMember[nRow].mb_fslot_money) + parseInt(arrMember[nRow].mb_kgon_money)).toLocaleString() + "원</span>";
        strBuf += '<button class="refresh_btn" onclick="refreshEv(' + arrMember[nRow].mb_fid + ', this);"></button>';
        strBuf += "</td> <td id='mp_" + arrMember[nRow].mb_fid + "'>";
        strBuf += parseInt(arrMember[nRow].mb_point).toLocaleString();
        strBuf += "</td> <td>";
        if (confs.emp_level >= LEVEL_ADMIN) {
            strBuf += arrMember[nRow].mb_ip_last;
            if (arrMember[nRow].block_state == 1) {
                strBuf += "<br><br><button name='" + arrMember[nRow].mb_ip_last + "' >차단해제</button>";
            } else
                strBuf += "<br><br><button name='" + arrMember[nRow].mb_ip_last + "' >IP차단</button>";
            strBuf += "</td> <td>";
        }
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
        
        strBuf += "<a href='"+FURL+"/user/member_edit/" + arrMember[nRow].mb_fid + "' >수정</a>";
        if (confs.emp_level > LEVEL_COMPANY) {
            var strEncodeURI = FURL+"/board/message_edit/0/" + arrMember[nRow].mb_fid;
            strBuf += "<a href='" + strEncodeURI + "' >쪽지</a>";
            strBuf += "<button name='" + arrMember[nRow].mb_fid + "'>삭제</button>   ";
        }
        

        if (confs.emp_level > LEVEL_COMPANY) {
            strBuf += "</td> <td>";
            if(!confs.npg_deny){
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
            }

            if(!confs.bpg_deny){
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
            }
            if(confs.eos5_enable || confs.eos3_enable){
                if (arrMember[nRow].mb_game_eo == 1) {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "'  class='button-active'>EOS파워볼</button>";
                } else {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "' >EOS파워볼</button>";
                }
            }
            if(!confs.cas_deny || confs.kgon_enable){
                if (arrMember[nRow].mb_game_cs == 1) {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "'  class='button-active'>카지노</button>";
                } else {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "' >카지노</button>";
                }
            }
            if(!confs.slot_deny){

                if (arrMember[nRow].mb_game_sl == 1) {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "'  class='button-active'>슬롯</button>";
                } else {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "' >슬롯</button>";
                }
            }
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
        url: FURL + "/userapi/getmembers",
        data: { json_: jsonData },
        success: function(jResult) {
            $(".loading").hide();
            // console.log(jResult);
            if (jResult.status == "success") {
                showMember(jResult.data, jResult.confs);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
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
            requestDeleteMember(jsonData);
        } else if (this.innerHTML.search("IP차단") >= 0) {
            var jsonData = { "block_ip": this.name, "block_state": 1 };
            requestAddBlock(jsonData);
        } else if (this.innerHTML.search("차단해제") >= 0) {
            var jsonData = { "block_ip": this.name, "block_state": 0 };
            requestAddBlock(jsonData);
        } else if (this.innerHTML.search("승인") >= 0) {
            var jsonData = { "mb_fid": this.name, "mb_state_active": 0 };
            requestUpdateMember(jsonData);
        } else if (this.innerHTML.search("차단") >= 0) {
            var jsonData = { "mb_fid": this.name, "mb_state_active": 1 };
            requestUpdateMember(jsonData);
        } else if (this.innerHTML.search("대기") >= 0) {
            var jsonData = { "mb_fid": this.name, "mb_state_active": 1 };
            requestWaitToPermit(this, jsonData);
        } else if (this.innerHTML == "파워볼") {
            if (this.className.search("button-active") >= 0) {
                var jsonData = { "mb_fid": this.name, "mb_game_pb": 0 };
                requestUpdateMember(jsonData);
            } else {
                var jsonData = { "mb_fid": this.name, "mb_game_pb": 1 };
                requestUpdateMember(jsonData);
            }
        } else if (this.innerHTML.search("파워사다리") >= 0) {
            if (this.className.search("button-active") >= 0) {
                var jsonData = { "mb_fid": this.name, "mb_game_ps": 0 };
                requestUpdateMember(jsonData);
            } else {
                var jsonData = { "mb_fid": this.name, "mb_game_ps": 1 };
                requestUpdateMember(jsonData);
            }
        } else if (this.innerHTML.search("보글볼") >= 0) {
            if (this.className.search("button-active") >= 0) {
                var jsonData = { "mb_fid": this.name, "mb_game_bb": 0 };
                requestUpdateMember(jsonData);
            } else {
                var jsonData = { "mb_fid": this.name, "mb_game_bb": 1 };
                requestUpdateMember(jsonData);
            }
        } else if (this.innerHTML.search("보글사다리") >= 0) {
            if (this.className.search("button-active") >= 0) {
                var jsonData = { "mb_fid": this.name, "mb_game_bs": 0 };
                requestUpdateMember(jsonData);
            } else {
                var jsonData = { "mb_fid": this.name, "mb_game_bs": 1 };
                requestUpdateMember(jsonData);
            }
        } else if (this.innerHTML.search("카지노") >= 0) {
            if (this.className.search("button-active") >= 0) {
                var jsonData = { "mb_fid": this.name, "mb_game_cs": 0 };
                requestUpdateMember(jsonData);
            } else {
                var jsonData = { "mb_fid": this.name, "mb_game_cs": 1 };
                requestUpdateMember(jsonData);
            }
        } else if (this.innerHTML.search("슬롯") >= 0) {
            if (this.className.search("button-active") >= 0) {
                var jsonData = { "mb_fid": this.name, "mb_game_sl": 0 };
                requestUpdateMember(jsonData);
            } else {
                var jsonData = { "mb_fid": this.name, "mb_game_sl": 1 };
                requestUpdateMember(jsonData);
            }
        } else if (this.innerHTML === "EOS파워볼") {
            if (this.className.search("button-active") >= 0) {
                var jsonData = { "mb_fid": this.name, "mb_game_eo": 0 };
                requestUpdateMember(jsonData);
            } else {
                var jsonData = { "mb_fid": this.name, "mb_game_eo": 1 };
                requestUpdateMember(jsonData);
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

