var mObjUser = null;
var mAudio = null;

$(document).ready(function() {
    initMainNavbar();
});

function initMainNavbar() {
    mAudio = new Audio();
    addNavbarButtonEvent();

    requestMemberInfo();
    mainNavbarLoop();
}



function requestMemberInfo() {

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/userapi/assets",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                mObjUser = jResult.data;
                showMemberInfo(jResult.data);
                requestEmployeeInfo();
                setTimeout(function() { requestBetInfo(); }, 1000);

            } else if (jResult.status == "fail") {
                alert('로그인정보를 가져오는데 실패했습니다. \n 다시 가입해 주세요.');
            } else if (jResult.status == "logout") {
                window.location.replace("/");
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
    });

}



function requestEmployeeInfo() {
    if (mObjUser == null) return;
    else if (mObjUser.mb_level <= LEVEL_COMPANY) return;

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/userapi/empinfo",
        success: function(jResult) {
            //console.log(jResult);            
            if (jResult.status == "success") {
                showEmpInfo(jResult.data, jResult.sound);
            } else if (jResult.status == "fail") {

            } else if (jResult.status == "logout") {
                window.location.replace("/");
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
    });

}


function requestBetInfo() {
    if (mObjUser == null) return;
    else if (mObjUser.mb_level <= LEVEL_COMPANY) return;

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/userapi/empbetinfo",
        success: function(jResult) {
            //console.log(jResult);            
            if (jResult.status == "success") {
                showEmpBetInfo(jResult.data);
            } else if (jResult.status == "fail") {

            } else if (jResult.status == "logout") {
                window.location.replace("/");
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
    });

}

function requestAlarmState() {

    var blarmState = document.getElementById("main-navbar-alarm-check-id").checked;

    var jsData = { "mb_state_alarm": blarmState ? 1 : 0 }
    var jsonData = JSON.stringify(jsData);
    $.ajax({
        type: "POST",
        data: { json_: jsonData },
        dataType: "json",
        url: "/api/changealarmstate",
        success: function(jResult) {
            //console.log(jResult);            
            if (jResult.status == "success") {

            } else if (jResult.status == "fail") {

            } else if (jResult.status == "logout") {
                window.location.replace("/");
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
    });

}

function showMemberInfo(objUser) {

    if (objUser == undefined || objUser == null)
        return;

    var strBuf = "";
    if (objUser.mb_level > LEVEL_COMPANY)
        strBuf = "관리자";
    else if (objUser.mb_level == LEVEL_COMPANY)
        strBuf = "부본사";
    else if (objUser.mb_level == LEVEL_AGENCY)
        strBuf = "총판";
    else if (objUser.mb_level == LEVEL_EMPLOYEE)
        strBuf = "매장";
    else
        strBuf = "회원";


    $("#main-navbar-level-id").text(strBuf);


    strBuf = objUser.mb_nickname;
    $("#main-navbar-emp-div-id").text(strBuf);


    if (objUser.mb_level < LEVEL_ADMIN) {

        strBuf = parseInt(objUser.mb_money).toLocaleString() + " 원";
        $("#main-navbar-emp_money-id").text(strBuf);

        strBuf = parseInt(objUser.mb_point).toLocaleString() + " P";
        $("#main-navbar-emp_point-id").text(strBuf);

        strBuf = parseInt(objUser.mb_money_charge).toLocaleString() + " 원";
        $("#main-navbar-emp_charge-id").text(strBuf);

        strBuf = parseInt(objUser.mb_money_exchange).toLocaleString() + " 원";
        $("#main-navbar-emp_exchange-id").text(strBuf);

        strBuf = objUser.mb_game_pb_ratio + " % | ";
        strBuf += objUser.mb_game_pb2_ratio + " %";
        $("#main-navbar-emp_pbrate-id").text(strBuf);

        strBuf = objUser.mb_game_cs_ratio + " %";
        $("#main-navbar-emp_evrate-id").text(strBuf);

        strBuf = objUser.mb_game_sl_ratio + " %";
        $("#main-navbar-emp_slrate-id").text(strBuf);

        strBuf = objUser.mb_game_ps_ratio + " %";
        $("#main-navbar-emp_psrate-id").text(strBuf);

        strBuf = objUser.mb_game_bb_ratio + " % | ";
        strBuf += objUser.mb_game_bb2_ratio + " %";
        $("#main-navbar-emp_bbrate-id").text(strBuf);

        strBuf = objUser.mb_game_bs_ratio + " %";
        $("#main-navbar-emp_bsrate-id").text(strBuf);
    }

    document.getElementById("main-navbar-alarm-check-id").checked = objUser.mb_state_alarm == 1 ? true : false;

}

function showEmpInfo(objEmpInfo, arrSoundInfo) {
    if (objEmpInfo == undefined || objEmpInfo == null)
        return;

    var strBuf = objEmpInfo.newmessage + " 통";
    $("#main-navbar-newmessage-id").text(strBuf);


    var nWaitCnt = 0;
    nWaitCnt += parseInt(objEmpInfo.waituser);
    nWaitCnt += parseInt(objEmpInfo.waitemployee);
    nWaitCnt += parseInt(objEmpInfo.waitagency);
    nWaitCnt += parseInt(objEmpInfo.waitcompany);
    strBuf = nWaitCnt + " 명";
    $("#main-navbar-user_wait-id").text(strBuf);


    if (objEmpInfo.waitcompany > 0)
        $("#main-navbar-user_wait-id").attr("href", "/user/company");
    else if (objEmpInfo.waitagency > 0)
        $("#main-navbar-user_wait-id").attr("href", "/user/agency");
    else if (objEmpInfo.waitemployee > 0)
        $("#main-navbar-user_wait-id").attr("href", "/user/employee");
    else
        $("#main-navbar-user_wait-id").attr("href", "/user/member/0");

    strBuf = objEmpInfo.waitcharge + " 대기";
    $("#main-navbar-charge_wait-id").text(strBuf);

    strBuf = objEmpInfo.waitexchange + " 대기";
    $("#main-navbar-exchange_wait-id").text(strBuf);


    strBuf = parseInt(objEmpInfo.emp_money).toLocaleString() + " 원";
    $("#main-navbar-emp_money-id").text(strBuf);

    strBuf = parseInt(objEmpInfo.emp_point).toLocaleString() + " P";
    $("#main-navbar-emp_point-id").text(strBuf);

    strBuf = parseInt(objEmpInfo.emp_money_charge).toLocaleString() + " 원";
    $("#main-navbar-emp_charge-id").text(strBuf);

    strBuf = parseInt(objEmpInfo.emp_money_exchange).toLocaleString() + " 원";
    $("#main-navbar-emp_exchange-id").text(strBuf);


    if (document.getElementById("main-navbar-alarm-check-id").checked) {

        var nVolume = 1;

        if (nWaitCnt > 0) {
            mAudio.src = '/assets/sound/' + arrSoundInfo[0][0];

            if (parseInt(arrSoundInfo[0][1]) <= 100) {
                nVolume = arrSoundInfo[0][1] / 100.0;
            }

            mAudio.volume = nVolume;
            mAudio.load();
            mAudio.play();

        } else if (objEmpInfo.waitcharge > 0) {
            mAudio.src = '/assets/sound/' + arrSoundInfo[1][0];
            if (parseInt(arrSoundInfo[1][1]) <= 100) {
                nVolume = arrSoundInfo[1][1] / 100.0;
            }
            mAudio.volume = nVolume;
            mAudio.load();
            mAudio.play();

        } else if (objEmpInfo.waitexchange > 0) {
            mAudio.src = '/assets/sound/' + arrSoundInfo[2][0];
            if (parseInt(arrSoundInfo[2][1]) <= 100) {
                nVolume = arrSoundInfo[2][1] / 100.0;
            }

            mAudio.volume = nVolume;
            mAudio.load();
            mAudio.play();

        } else if (objEmpInfo.newmessage > 0) {
            mAudio.src = '/assets/sound/' + arrSoundInfo[3][0];
            if (parseInt(arrSoundInfo[3][1]) <= 100) {
                nVolume = arrSoundInfo[3][1] / 100.0;
            }

            mAudio.volume = nVolume;
            mAudio.load();
            mAudio.play();

        }

    }


}


function showEmpBetInfo(arrBetInfo) {
    if (arrBetInfo == undefined || arrBetInfo == null)
        return;

    $("#main-navbar-pbbet-id").text(parseInt(arrBetInfo[0][0]).toLocaleString() + " 원");
    $("#main-navbar-pbbetearn-id").text(parseInt(arrBetInfo[0][1]).toLocaleString() + " 원");
    //$("#main-navbar-pbbetpress-id").text(parseInt(arrBetInfo[0][2]).toLocaleString() + " 원");

    $("#main-navbar-pb2bet-id").text(parseInt(arrBetInfo[1][0]).toLocaleString() + " 원");
    $("#main-navbar-pb2betearn-id").text(parseInt(arrBetInfo[1][1]).toLocaleString() + " 원");
    //$("#main-navbar-pb2betpress-id").text(parseInt(arrBetInfo[1][2]).toLocaleString() + " 원");


    $("#main-navbar-psbet-id").text(parseInt(arrBetInfo[2][0]).toLocaleString() + " 원");
    $("#main-navbar-psbetearn-id").text(parseInt(arrBetInfo[2][1]).toLocaleString() + " 원");
    //$("#main-navbar-psbetpress-id").text(parseInt(arrBetInfo[2][2]).toLocaleString() + " 원");

    $("#main-navbar-bbbet-id").text(parseInt(arrBetInfo[3][0]).toLocaleString() + " 원");
    $("#main-navbar-bbbetearn-id").text(parseInt(arrBetInfo[3][1]).toLocaleString() + " 원");

    $("#main-navbar-bb2bet-id").text(parseInt(arrBetInfo[4][0]).toLocaleString() + " 원");
    $("#main-navbar-bb2betearn-id").text(parseInt(arrBetInfo[4][1]).toLocaleString() + " 원");

    $("#main-navbar-bsbet-id").text(parseInt(arrBetInfo[5][0]).toLocaleString() + " 원");
    $("#main-navbar-bsbetearn-id").text(parseInt(arrBetInfo[5][1]).toLocaleString() + " 원");

}



function mainNavbarLoop() {


    if (mObjUser != null) {
        if (parseInt(mObjUser.mb_level) > LEVEL_COMPANY)
            requestBetInfo();

    }

    // 1초뒤에 다시 실행
    setTimeout(function() {
        mainNavbarLoop();
    }, 300000);

}


function addNavbarButtonEvent() {


    var elemAlarmCheck = document.getElementById("main-navbar-alarm-check-id");

    elemAlarmCheck.addEventListener("click", function() {
        //var elemAlarmPlayer = document.getElementById("main-navbar-alarm-player-id");
        if (!elemAlarmCheck.checked) {
            //elemAlarmPlayer.pause();
            mAudio.pause();
        }

        requestAlarmState();
    });


    /**DropDown Menu***/
    var mainNavbarDropDown = document.getElementById("main-navbar-dropdown-container-id");
    var btnNavbarEmp = document.getElementById("main-navbar-emp-div-id");
    btnNavbarEmp.onclick = function() {
        if (mainNavbarDropDown.style.display == "none")
            mainNavbarDropDown.style.display = "block";
        else mainNavbarDropDown.style.display = "none";
    }


    var btnDropdownLogout = document.getElementById("main-navbar-dropdown-logout-id");
    btnDropdownLogout.onclick = function() {
        window.location.replace("/pages/logout");

    }


    window.onclick = function(event) {
        if (event.target != btnNavbarEmp && mainNavbarDropDown.style.display == "block") {
            mainNavbarDropDown.style.display = "none";
        }
    }



}