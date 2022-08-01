

function calcAmount(elemName){
    $(elemName).val(
        $(elemName)
        .val()
        .replace(/[^0-9]/g, "")
    );
    $(elemName).val(
        $(elemName)
        .val()
        .replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")
    );
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


function requestUpdateMember(jsData) {

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

function requestDeleteMember(jsData) {
    
    if (!confirm("하부회원까지 모두 삭제됩니다.\r\n그래도 계속하시겠습니까?"))
        return;
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

function requestLogoutMember(jsData) {

    var jsonData = JSON.stringify(jsData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/logoutmember",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);

            if (jResult.status == "success") {
                alert('로그아웃되었습니다.');

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


function refreshEgg(mbFid, elBtn) {
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


function collectEgg(mbFid) {
    var jsonData = { "mb_fid": mbFid };
    jsonData = JSON.stringify(jsonData);
    $(".loading").show();

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/eggcollect",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            $(".loading").hide();
            if (jResult.status == "success") {
                $("#mm_" + mbFid).text(parseInt(jResult.money).toLocaleString() + "원");
                alert("회수되었습니다.");
            } else if (jResult.status == "fail") {
                alert("게임서버응답이 실패되었습니다.");
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


function requestTrasnfer(jsonData, bReload = true){
    $(".loading").show();
    
    jsonData = JSON.stringify(jsonData);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/transfer",
        data: { json_: jsonData },
        success: function(jResult) {
            $(".loading").hide();
            // console.log(jResult);
            if (jResult.status == "success") {
                if(bReload)
                    location.reload();
                else requestMember();
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            } else if (jResult.status == "fail") {
                if (jResult.msg) {
                    alert(jResult.msg);
                } else alert("조작이 실패되었습니다.")
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });

}

function reqMemSave(objMember, closeDlg = null){

    if (objMember.mb_uid.length < 1) {
        alert("아이디는 필수정보입니다.");
        return;
    }

    if (objMember.admin_level >= LEVEL_ADMIN) {
        if (objMember.mb_pwd.length < 1) {
            alert("비밀번호는 필수정보입니다.");
            return;
        }

        if (objMember.mb_nickname.length < 1) {
            alert("닉네임은 필수정보입니다.");
            return;
        }

        if (objMember.mb_nickname.length < 3 || objMember.mb_nickname.length > 20) {
            alert("닉네임길이는 3~20자리입니다.");
            return;
        }


        if (objMember.mb_phone.length < 1) {
            alert("핸드폰번호는 필수정보입니다.");
            return;
        }

        if (objMember.mb_bank_pwd.length < 1) {
            alert("출금비번은 필수정보입니다.");
            return;
        }

        if (objMember.mb_bank_name.length < 1 || objMember.mb_bank_own.length < 1 || objMember.mb_bank_num.length < 1) {
            alert("계좌정보를 입력해주세요.");
            return;
        }
    }
    if (!confirm("저장하시겠습니까?"))
        return;

    if (parseInt(objMember.mb_fid) > 0) {

        jsonData = JSON.stringify(objMember);
        $.ajax({
            type: "POST",
            dataType: "json",
            url: FURL + "/userapi/modifymember",
            data: { json_: jsonData },
            success: function(jResult) {
                console.log(jResult);
                if (jResult.status == "success") {
                    if(closeDlg != null){
                        closeDlg();
                        requestMember();
                    }
                    else window.location.replace( FURL +'/user/member/0');
                } else if (jResult.status == "logout") {
                    window.location.replace( FURL +'/');
                } else if (jResult.status == "fail") {
                    if (jResult.error == 2)
                        alert("중복된 아이디입니다.");
                    else if (jResult.error == 3)
                        alert("등록된 매장이 아닙니다.");
                    else if (jResult.error == 12)
                        alert("중복된 닉네임입니다.");
                    else alert("수정이 실패되었습니다.");
                } else if (jResult.status == "val_error") {
                    var errString = '';
                    for (property in jResult.error) {
                        errString += `${jResult.error[property]}\n`;
                    }
                    alert(errString);
                } else if (jResult.status == "ratio_error") {
                    alert(jResult.error);
                } else if (jResult.status == "employee_error") {
                    alert("추천인 아이디가 존재하지 않습니다.");
                }
            },
            error: function(request, status, error) {
                // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            }

        });
    } else if (parseInt(objMember.mb_fid) == 0) {

        jsonData = JSON.stringify(objMember);
        $.ajax({
            type: "POST",
            dataType: "json",
            url: FURL + "/userapi/addmember",
            data: { json_: jsonData },
            success: function(jResult) {
                // console.log(jResult);
                if (jResult.status == "success") {
                    if(closeDlg != null){
                        closeDlg();
                        requestMember();
                    }
                    else window.location.replace( FURL +'/user/member/0');
                } else if (jResult.status == "logout") {
                    window.location.replace( FURL +'/');
                } else if (jResult.status == "val_error") {
                    var errorString = '';
                    for (property in jResult.error) {
                        errorString += `${jResult.error[property]}\n`;
                    }
                    alert(errorString);
                } else if (jResult.status == "fail") {
                    if (jResult.error == 2)
                        alert("중복된 아이디입니다.");
                    else if (jResult.error == 12)
                        alert("중복된 닉네임입니다.");
                    else if (jResult.error == 3)
                        alert("등록된 추천인이 아닙니다.");
                    else alert("등록이 실패되었습니다.");
                } else if (jResult.status == "ratio_error") {
                    alert(jResult.error);
                } else if (jResult.status == "employee_error") {
                    alert("추천인 아이디가 존재하지 않습니다.");
                }
            },
            error: function(request, status, error) {
                // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            }

        });
    }

}


function countBlank(fid, chgCnt){
    if(chgCnt == 0){
        return;
    }
    if($("#blank_"+fid).length < 1)
        return;

    let curCnt = parseInt($("#blank_"+fid).val());

    let blankCnt = curCnt + chgCnt;
    if(blankCnt < 0){
        blankCnt = 0;
    }
    $("#blank_"+fid).val(blankCnt);

    var objData = { "mb_fid": fid, "mb_blank_count": blankCnt };
    reqSetBlank(objData, curCnt);
}


function reqSetBlank(objData, beforeCnt) {
    $(".blank-btn_"+objData.mb_fid).attr("disabled", true);

    var jsonData = JSON.stringify(objData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/updatemember",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            $(".blank-btn_"+objData.mb_fid).attr("disabled", false);

            if (jResult.status == "success") {
                $("#blank_"+objData.mb_fid).val(objData.mb_blank_count);
            } else if (jResult.status == "fail") {
                $("#blank_"+objData.mb_fid).val(beforeCnt);
            } else if (jResult.status == "nopermit") {
                alert('변경권한이 없습니다.');
                location.replace( FURL +'/pages/nopermit');
            } else if (jResult.status == "logout") {
                location.replace( FURL +'/');
            }
        },
        error: function(request, status, error) {
            $(".blank-btn_"+objData.mb_fid).attr("disabled", false);
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });

}