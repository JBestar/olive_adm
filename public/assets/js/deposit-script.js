$(document).ready(function() {
    addEventListner();
    requestTotalPage();
    setTimeout(function() {
        pageLoop();
    }, 120000);
});


function pageLoop() {
    requestPageInfo();
    // 1초뒤에 다시 실행
    setTimeout(function() {
        pageLoop();
    }, 120000);

}

function requestPageInfo() {
    requestDepositList();
}

function addEventListner() {
    $("#deposit-list-view-but-id").click(function() {
        requestTotalPage();
    });

    $("#deposit-number-select-id").change(function() {
        requestTotalPage();
    });
}



function showDepositList(arrData) {
    var elemListTb = document.getElementById("bank-deposit-table-id");
    var strBuf = "";
    var curPage = getActivePage();
    var firstIdx = (curPage - 1) * CountPerPage;

    for (nRow in arrData) {
        strBuf += "<tr><td>";
        strBuf += (parseInt(nRow) + firstIdx + 1);
        strBuf += "</td><td>";
        strBuf += "<a onclick='popupMemberEdit(" + arrData[nRow].mb_fid + ")' class='link-member'>"+ arrData[nRow].mb_nickname+ "</a>";
        strBuf += "</td><td>";
        strBuf += arrData[nRow].charge_mb_uid;
        strBuf += "</td><td>";
        strBuf += parseFloat(arrData[nRow].mb_money).toLocaleString() + " 원";
        strBuf += "</td><td>";
        strBuf += arrData[nRow].charge_time_require;
        strBuf += "</td><td>";
        strBuf += parseInt(arrData[nRow].charge_money).toLocaleString() + " 원";
        strBuf += "</td><td>";
        strBuf += arrData[nRow].charge_mb_realname;
        strBuf += "</td><td>";
        strBuf += arrData[nRow].charge_mb_phone;
        strBuf += "</td>";
        if (arrData[nRow].charge_action_state == 1)
            strBuf += "<td>승인대기";
        else if (arrData[nRow].charge_action_state == 2)
            strBuf += "<td style='color:blue;'>충전완료";
        else if (arrData[nRow].charge_action_state == 3)
            strBuf += "<td style='color:red;'>충전취소";
        else if (arrData[nRow].charge_action_state == 4)
            strBuf += "<td style='color:#aab000;'>임시대기";
        else if (arrData[nRow].charge_action_state == 5)
            strBuf += "<td style='color:#aab000;'>직충전";
        else strBuf += "<td>";

        strBuf += "</td><td>";
        if (arrData[nRow].charge_time_process != null)
            strBuf += arrData[nRow].charge_time_process;
        strBuf += "</td><td>";
        if (arrData[nRow].charge_action_uid != null)
            strBuf += arrData[nRow].charge_action_uid;
        strBuf += "</td><td>";
        if (arrData[nRow].charge_action_state == 1) {
            strBuf += "<button name='" + arrData[nRow].charge_fid + "' >승인</button>";
            strBuf += "<button name='" + arrData[nRow].charge_fid + "' >거절</button>";
            strBuf += "<button name='" + arrData[nRow].charge_fid + "' >대기</button>";
        } else if (arrData[nRow].charge_action_state == 2) {
            // strBuf += "<button name='" + arrData[nRow].charge_fid + "' >취소</button>";
        } else if (arrData[nRow].charge_action_state == 3) {
            // strBuf += "<button name='" + arrData[nRow].charge_fid + "' >승인</button>";
        } else if (arrData[nRow].charge_action_state == 4) {
            strBuf += "<button name='" + arrData[nRow].charge_fid + "' >승인</button>";
            strBuf += "<button name='" + arrData[nRow].charge_fid + "' >거절</button>";
        }
        strBuf += "</td></tr>";

    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='11'>자료가 없습니다.</td></tr>";
    }

    elemListTb.innerHTML = strBuf;

    addButtonEvent();
}

function addButtonEvent() {
    var elemListTb = document.getElementById("bank-deposit-table-id");
    var elemTableBtns = elemListTb.getElementsByTagName("button");
    if (elemTableBtns == null)
        return;

    var i;

    for (i = 0; i < elemTableBtns.length; i++) {

        elemTableBtns[i].addEventListener("click", function() {

            if (this.innerHTML.search("취소") >= 0) {
                if (confirm("취소하시겠습니까?")) {
                    var jsonData = { "charge_fid": this.name, "process": 0 };
                    requestProcDeposit(jsonData);
                }
            } else if (this.innerHTML.search("승인") >= 0) {
                if (confirm("승인하시겠습니까?")) {
                    var jsonData = { "charge_fid": this.name, "process": 1 };
                    requestProcDeposit(jsonData);
                }
            } else if (this.innerHTML.search("거절") >= 0) {
                if (confirm("거절하시겠습니까?")) {
                    var jsonData = { "charge_fid": this.name, "process": 2 };
                    requestProcDeposit(jsonData);
                }
            } else if (this.innerHTML.search("대기") >= 0) {
                if (confirm("임시대기시키겠습니까?")) {
                    var jsonData = { "charge_fid": this.name, "process": 3 };
                    requestProcDeposit(jsonData);
                }
            }

        });
    }

}



function requestDepositList() {

    var strUid = $("#deposit-userid-input-id").val();
    var dtStart = $("#deposit-datestart-input-id").val();
    var dtEnd = $("#deposit-dateend-input-id").val();
    var nPage = getActivePage();

    var jsonData = { "count": CountPerPage, "page": nPage, "mb_uid": strUid, "start": dtStart, "end": dtEnd };
    jsonData = JSON.stringify(jsonData);



    $.ajax({
        type: "POST",
        data: { json_: jsonData },
        dataType: "json",
        url: FURL + "/api/depositlist",
        success: function(jResult) {
            //console.log(jResult);
            if (jResult.status == "success") {
                showDepositList(jResult.data);

                $("#bank-deposit-total-id").text(parseInt(jResult.total).toLocaleString() + " 원");
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });

}


function requestTotalPage() {

    var strUid = $("#deposit-userid-input-id").val();
    var dtStart = $("#deposit-datestart-input-id").val();
    var dtEnd = $("#deposit-dateend-input-id").val();
    CountPerPage = $("#deposit-number-select-id").val();

    var jsonData = { "count": CountPerPage, "mb_uid": strUid, "start": dtStart, "end": dtEnd };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: FURL + '/api/depositlistcnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            //console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data.count;
                setFirstPage();
                requestDepositList();
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}


function requestProcDeposit(jsData) {
    if (mAudio != undefined && mAudio != null) {
        mAudio.pause();
    }
    $(".loading").show();
    var jsonData = JSON.stringify(jsData);
    $.ajax({
        type: "POST",
        data: { json_: jsonData },
        dataType: "json",
        url: FURL + "/api/depositproc",
        success: function(jResult) {
            $(".loading").hide();
            // console.log(jResult);
            if (jResult.status == "success") {
                requestEmployeeInfo();
                setTimeout(function() { requestDepositList(); }, 500);
            } else if (jResult.status == "fail") {
                if(jResult.msg){
                    alert(jResult.msg);
                }
                else alert("충전처리가 실패되었습니다.");
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });

}