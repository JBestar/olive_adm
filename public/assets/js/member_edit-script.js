$(document).ready(function() {
    addBtnEvent();
});

function readConfigToObject() {

    var objMember = new Object();
    objMember.admin_level = $("#subnavbar-emplevel-p-id").text();

    objMember.mb_fid = $("#subnavbar-fid-p-id").text();
    objMember.mb_uid = $("#useredit-id-input-id").val();
    objMember.mb_grade = $("#useredit-level-select-id").val();
    objMember.mb_nickname = $("#useredit-nickname-input-id").val();
    objMember.mb_color = $("#useredit-color-input-id").val();

    if (objMember.admin_level > LEVEL_COMPANY) {
        objMember.mb_pwd = $("#useredit-pwd-input-id").val();
        objMember.mb_emp_uid = $("#useredit-sort-select-id").val();
        if ($("#useredit-phone-input-id").length > 0)
            objMember.mb_phone = $("#useredit-phone-input-id").val();
        if ($("#useredit-bankname-input-id").length > 0)
            objMember.mb_bank_name = $("#useredit-bankname-input-id").val();
        if ($("#useredit-bankaccount-input-id").length> 0)
            objMember.mb_bank_own = $("#useredit-bankaccount-input-id").val();
        if ($("#useredit-bankserial-input-id").length > 0)
            objMember.mb_bank_num = $("#useredit-bankserial-input-id").val();
        if ($("#useredit-bankpwd-input-id").length > 0)
            objMember.mb_bank_pwd = $("#useredit-bankpwd-input-id").val();
        var nAmount = parseInt($("#useredit-money-input-id").val().replace(/,/g, ""));
        if (isNaN(nAmount) || nAmount == "") {
            nAmount = 0;
        }
        objMember.mb_money = nAmount;
        var nAmount = parseInt($("#useredit-point-input-id").val().replace(/,/g, ""));
        if (isNaN(nAmount) || nAmount == "") {
            nAmount = 0;
        }
        objMember.mb_point = nAmount;

    }
    if($("#useredit-pbbetrate-input-id").length > 0){
        objMember.mb_game_pb_ratio = $("#useredit-pbbetrate-input-id").val();
        objMember.mb_game_pb2_ratio = $("#useredit-pbbetrate2-input-id").val();
        objMember.mb_game_ps_ratio = $("#useredit-psbetrate-input-id").val();
    }
    else{
        objMember.mb_game_pb_ratio = 0;
        objMember.mb_game_pb2_ratio = 0;
        objMember.mb_game_ps_ratio = 0;
    }  

    if($("#useredit-pbbetpercent-input-id").length > 0){
        objMember.mb_game_pb_percent = $("#useredit-pbbetpercent-input-id").val();
        objMember.mb_game_pb2_percent = $("#useredit-pbbetpercent2-input-id").val();
        objMember.mb_game_ps_percent = $("#useredit-psbetpercent-input-id").val();
    } else{
        objMember.mb_game_pb_percent = 100;
        objMember.mb_game_pb2_percent = 100;
        objMember.mb_game_ps_percent = 100;
    }
    
    if($("#useredit-bbbetrate-input-id").length > 0){
        objMember.mb_game_bb_ratio = $("#useredit-bbbetrate-input-id").val();
        objMember.mb_game_bb2_ratio = $("#useredit-bbbetrate2-input-id").val();
        objMember.mb_game_bs_ratio = $("#useredit-bsbetrate-input-id").val();
    } else {
        objMember.mb_game_bb_ratio = 0;
        objMember.mb_game_bb2_ratio = 0;
        objMember.mb_game_bs_ratio = 0;
    }

    if($("#useredit-bbbetpercent-input-id").length > 0){
        objMember.mb_game_bb_percent = $("#useredit-bbbetpercent-input-id").val();
        objMember.mb_game_bb2_percent = $("#useredit-bbbetpercent2-input-id").val();
        objMember.mb_game_bs_percent = $("#useredit-bsbetpercent-input-id").val();
    } else {
        objMember.mb_game_bb_percent = 100;
        objMember.mb_game_bb2_percent = 100;
        objMember.mb_game_bs_percent = 100;
    }

    if($("#useredit-evbetrate-input-id").length > 0){
        objMember.mb_game_cs_ratio = $("#useredit-evbetrate-input-id").val();
    } else objMember.mb_game_cs_ratio = 0;

    if($("#useredit-slbetrate-input-id").length > 0){
        objMember.mb_game_sl_ratio = $("#useredit-slbetrate-input-id").val();
    } else objMember.mb_game_sl_ratio = 0;
    
    if ($("#useredit-offline-check-id").length > 0){
        objMember.mb_state_delete = $("#useredit-offline-check-id").prop('checked') ? 1 : 0;
    } else objMember.mb_state_delete = 0;

    return objMember;

}

function addBtnEvent() {

    $("#useredit-save-btn-id").click(function() {

        var objMember = readConfigToObject();

        if (objMember.mb_uid.length < 1) {
            alert("아이디는 필수정보입니다.");
            return;
        }

        if (objMember.admin_level > LEVEL_COMPANY) {
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
                        window.location.replace( FURL +'/user/member/0');
                    } else if (jResult.status == "logout") {
                        window.location.replace( FURL +'/');
                    } else if (jResult.status == "fail") {
                        if (jResult.error == 2)
                            alert("중복된 아이디입니다.");
                        else if (jResult.error == 3)
                            alert("등록된 매장이 아닙니다.");
                        else if (jResult.error == 11)
                            alert("중복된 닉네임입니다.");
                        else alert("수정이 실패되었습니다.");
                    } else if (jResult.status == "val_error") {
                        var errString = '';
                        for (property in jResult.error) {
                            errString += `${jResult.error[property]}\n`;
                        }
                        alert(errString);
                    } else if (jResult.status == "pb_ratio_error") {
                        alert("파워볼 배당율이 추천인설정값 " + jResult.error + "보다 클수 없습니다.");
                    } else if (jResult.status == "ps_ratio_error") {
                        alert("파워사다리 배당율이 추천인설정값 " + jResult.error + "보다 클수 없습니다.");
                    } else if (jResult.status == "ev_ratio_error") {
                        alert("카지노 배당율이 추천인설정값 " + jResult.error + "보다 클수 없습니다.");
                    } else if (jResult.status == "sl_ratio_error") {
                        alert("슬롯 배당율이 추천인설정값 " + jResult.error + "보다 클수 없습니다.");
                    } else if (jResult.status == "bb_ratio_error") {
                        alert("보글볼 배당율이 추천인설정값 " + jResult.error + "보다 클수 없습니다.");
                    } else if (jResult.status == "bs_ratio_error") {
                        alert("보글사다리 배당율이 추천인설정값 " + jResult.error + "보다 클수 없습니다.");
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
                    //console.log(jResult);
                    if (jResult.status == "success") {
                        window.location.replace( FURL +'/user/member/0');
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
                        else if (jResult.error == 11)
                            alert("중복된 닉네임입니다.");
                        else if (jResult.error == 3)
                            alert("등록된 추천인이 아닙니다.");
                        else alert("등록이 실패되었습니다.");
                    } else if (jResult.status == "pb_ratio_error") {
                        alert("파워볼 배당율이 추천인설정값 " + jResult.error + "보다 클수 없습니다.");
                    } else if (jResult.status == "ps_ratio_error") {
                        alert("파워사다리 배당율이 추천인설정값 " + jResult.error + "보다 클수 없습니다.");
                    } else if (jResult.status == "ev_ratio_error") {
                        alert("카지노 배당율이 추천인설정값 " + jResult.error + "보다 클수 없습니다.");
                    } else if (jResult.status == "sl_ratio_error") {
                        alert("슬롯 배당율이 추천인설정값 " + jResult.error + "보다 클수 없습니다.");
                    } else if (jResult.status == "bb_ratio_error") {
                        alert("보글볼 배당율이 추천인설정값 " + jResult.error + "보다 클수 없습니다.");
                    } else if (jResult.status == "bs_ratio_error") {
                        alert("보글사다리 배당율이 추천인설정값 " + jResult.error + "보다 클수 없습니다.");
                    } else if (jResult.status == "employee_error") {
                        alert("추천인 아이디가 존재하지 않습니다.");
                    }
                },
                error: function(request, status, error) {
                    // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                }

            });
        }

    });


    $("#useredit-cancel-btn-id").click(function() {
        window.location.replace( FURL +'/user/member/0');
    });

    
    $("#useredit-withdraw-money-id").click(function() {
        if (!confirm("보유금액을 회수하시겠습니까?"))
            return;

        requestWithdraw(0);
    });

    $("#useredit-withdraw-point-id").click(function() {
        if (!confirm("보유포인트를 회수하시겠습니까?"))
            return;

        requestWithdraw(1)
    });
        
    $("#useredit-give-but-id").click(function() {
        var nAmount = parseInt($("#useredit-transfer-input-id").val().replace(/,/g, ""));
        if (isNaN(nAmount) || nAmount == "") {
            nAmount = 0;
        }
        if (nAmount == 0) {
            confirmAlert("충전금액을 입력 해주세요.");
            return false;
        }

        if (!confirm(nAmount.toLocaleString() + "원을 직충전하시겠습니까?"))
            return;
    
        var jsonData = {
            'mb_fid': $("#subnavbar-fid-p-id").html(),
            'amount': nAmount,
            'type':0
        }
        requestTrasnfer(jsonData);
    });
    
    //직환전
    $("#useredit-withdraw-but-id").click(function() {
        var nAmount = parseInt($("#useredit-transfer-input-id").val().replace(/,/g, ""));
        if (isNaN(nAmount) || nAmount == "") {
            nAmount = 0;
        }
        if (nAmount == 0) {
            confirmAlert("환전금액을 입력 해주세요.");
            return false;
        }

        if (!confirm(nAmount.toLocaleString() + "원을 직환전하시겠습니까?"))
            return;
    
        var jsonData = {
            'mb_fid': $("#subnavbar-fid-p-id").html(),
            'amount': nAmount,
            'type':1
        }
        requestTrasnfer(jsonData);

    });
    
    $("#useredit-transfer-but-id").click(function() {
        var nAmount = parseInt($("#useredit-transfer-input-id").val().replace(/,/g, ""));
        if (isNaN(nAmount) || nAmount == "") {
            nAmount = 0;
        }
        if (nAmount == 0) {
            confirmAlert("이송금액을 입력 해주세요.");
            return false;
        }

        if (!confirm(nAmount.toLocaleString() + "원을 회원에게 이송하시겠습니까?"))
            return;

        var jsonData = {
            'mb_fid': $("#subnavbar-fid-p-id").html(),
            'amount': nAmount,
            'type':2
        }
        requestTrasnfer(jsonData);

    });
    
    
    $("#useredit-return-but-id").click(function() {
        var nAmount = parseInt($("#useredit-transfer-input-id").val().replace(/,/g, ""));
        if (isNaN(nAmount) || nAmount == "") {
            nAmount = 0;
        }
        if (nAmount == 0) {
            confirmAlert("환수금액을 입력 해주세요.");
            return false;
        }

        if (!confirm(nAmount.toLocaleString() + "원을 회원에게서 환수하시겠습니까?"))
            return;

        var jsonData = {
            'mb_fid': $("#subnavbar-fid-p-id").html(),
            'amount': nAmount,
            'type':3
        }
        requestTrasnfer(jsonData);

    });


}

function requestTrasnfer(jsonData){
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
                location.reload();
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
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });

}

function requestWithdraw(iType){
    var jsonData = {
        'mb_fid': $("#subnavbar-fid-p-id").html(),
        'type': iType
    }
    $(".loading").show();

    jsonData = JSON.stringify(jsonData);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/withdraw",
        data: { json_: jsonData },
        success: function(jResult) {
            $(".loading").hide();
            // console.log(jResult);
            if (jResult.status == "success") {
                location.reload();
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            } else if (jResult.status == "fail") {
                alert("회수가 실패되었습니다.")
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}


$(function() {
    $("#useredit-transfer-input-id").on("propertychange change keyup paste input", function() {
        calcAmount("#useredit-transfer-input-id");
    });
    $("#useredit-money-input-id").on("propertychange change keyup paste input", function() {
        calcAmount("#useredit-money-input-id");
    });
    $("#useredit-point-input-id").on("propertychange change keyup paste input", function() {
        calcAmount("#useredit-point-input-id");
    });
    // 1만원
    $("#money_1").on("click", function(e) {
        e.preventDefault();
        tr_price(10000);
    });
    // 3만원
    $("#money_2").on("click", function(e) {
        e.preventDefault();
        tr_price(30000);
    });
    // 5만원
    $("#money_3").on("click", function(e) {
        e.preventDefault();
        tr_price(50000);
    });
    // 10만원
    $("#money_4").on("click", function(e) {
        e.preventDefault();
        tr_price(100000);
    });
    // 50만원
    $("#money_5").on("click", function(e) {
        e.preventDefault();
        tr_price(500000);
    });
    // 100만원
    $("#money_6").on("click", function(e) {
        e.preventDefault();
        tr_price(1000000);
    });

});


function tr_price(price) {
    if (price == 0) {
        $("#useredit-transfer-input-id").val("0");
    } else {
        tmp_price = parseInt($("#useredit-transfer-input-id").val().replace(/,/g, ""));

        if (isNaN(tmp_price) == false) {
            price += tmp_price;
        }

        $("#useredit-transfer-input-id").val(price);
        calcAmount("#useredit-transfer-input-id");
    }
}


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
