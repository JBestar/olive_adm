$(document).ready(function() {
    addBtnEvent();
});


function addBtnEvent() {
    /* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */

    var elemTable = document.getElementById("user-employee-table-id");
    var elemTableBtns = elemTable.getElementsByTagName("button");
    if (elemTableBtns == null)
        return;

    var i;

    for (i = 0; i < elemTableBtns.length; i++) {

        elemTableBtns[i].addEventListener("click", function() {

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
            } else if (this.innerHTML.search("키노사다리") >= 0) {
                if (this.className.search("button-active") >= 0) {
                    var jsonData = { "mb_fid": this.name, "mb_game_ks": 0 };
                    requestUpdateCompany(jsonData);
                } else {
                    var jsonData = { "mb_fid": this.name, "mb_game_ks": 1 };
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
                    var jsonData = { "mb_fid": this.name, "mb_game_ev": 0 };
                    requestUpdateCompany(jsonData);
                } else {
                    var jsonData = { "mb_fid": this.name, "mb_game_ev": 1 };
                    requestUpdateCompany(jsonData);
                }
            }
        });
    }

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
                window.location.reload();
            } else if (jResult.status == "fail") {
                alert('변경이 실패되었습니다.');
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
            $(elemBut).attr('disabled', false);
            //console.log(jResult);
            if (jResult.status == "success") {
                window.location.reload();
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
                window.location.reload();
            } else if (jResult.status == "fail") {
                alert('변경이 실패되었습니다.');
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