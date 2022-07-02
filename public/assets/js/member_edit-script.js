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

    if (objMember.admin_level >= LEVEL_ADMIN) {
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

    if($("#useredit-pbbetpercent-input-id").length > 0){
        objMember.mb_game_pb_percent = $("#useredit-pbbetpercent-input-id").val();
        objMember.mb_game_pb2_percent = $("#useredit-pbbetpercent2-input-id").val();
        objMember.mb_game_ps_percent = $("#useredit-psbetpercent-input-id").val();
    } 
    
    if($("#useredit-bbbetrate-input-id").length > 0){
        objMember.mb_game_bb_ratio = $("#useredit-bbbetrate-input-id").val();
        objMember.mb_game_bb2_ratio = $("#useredit-bbbetrate2-input-id").val();
        objMember.mb_game_bs_ratio = $("#useredit-bsbetrate-input-id").val();
    } 

    if($("#useredit-eobetrate-input-id").length > 0){
        objMember.mb_game_eo_ratio = $("#useredit-eobetrate-input-id").val();
        objMember.mb_game_eo2_ratio = $("#useredit-eobetrate2-input-id").val();
    } 

    if($("#useredit-cobetrate-input-id").length > 0){
        objMember.mb_game_co_ratio = $("#useredit-cobetrate-input-id").val();
        objMember.mb_game_co2_ratio = $("#useredit-cobetrate2-input-id").val();
    } 

    if($("#useredit-bbbetpercent-input-id").length > 0){
        objMember.mb_game_bb_percent = $("#useredit-bbbetpercent-input-id").val();
        objMember.mb_game_bb2_percent = $("#useredit-bbbetpercent2-input-id").val();
        objMember.mb_game_bs_percent = $("#useredit-bsbetpercent-input-id").val();
    } 
    
    if($("#useredit-eobetpercent-input-id").length > 0){
        objMember.mb_game_eo_percent = $("#useredit-eobetpercent-input-id").val();
        objMember.mb_game_eo2_percent = $("#useredit-eobetpercent2-input-id").val();
    } 

    if($("#useredit-cobetpercent-input-id").length > 0){
        objMember.mb_game_co_percent = $("#useredit-cobetpercent-input-id").val();
        objMember.mb_game_co2_percent = $("#useredit-cobetpercent2-input-id").val();
    } 

    if($("#useredit-evbetrate-input-id").length > 0){
        objMember.mb_game_cs_ratio = $("#useredit-evbetrate-input-id").val();
    }

    if($("#useredit-slbetrate-input-id").length > 0){
        objMember.mb_game_sl_ratio = $("#useredit-slbetrate-input-id").val();
    }
    
    if ($("#useredit-offline-check-id").length > 0){
        objMember.mb_state_delete = $("#useredit-offline-check-id").prop('checked') ? 1 : 0;
    } 

    return objMember;

}

function addBtnEvent() {

    $("#useredit-save-btn-id").click(function() {

        var objMember = readConfigToObject();
        reqMemSave(objMember);

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
            alert("충전금액을 입력 해주세요.");
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
            alert("환전금액을 입력 해주세요.");
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
            alert("이송금액을 입력 해주세요.");
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
            alert("환수금액을 입력 해주세요.");
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


