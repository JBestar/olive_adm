$(document).ready(function() {
    addBtnEvent();
});

function readConfigToObject() {

    var objMember = new Object();
    objMember.admin_level = $("#subnavbar-emplevel-p-id").text();

    objMember.mb_fid = document.getElementById("subnavbar-fid-p-id").innerHTML;
    objMember.mb_uid = document.getElementById("useredit-id-input-id").value;
    var elemLevelSelect = document.getElementById("useredit-level-select-id");
    objMember.mb_grade = elemLevelSelect.options[elemLevelSelect.selectedIndex].value;
    objMember.mb_nickname = document.getElementById("useredit-nickname-input-id").value;

    if (objMember.admin_level > 99) {
        objMember.mb_pwd = document.getElementById("useredit-pwd-input-id").value;
        objMember.mb_emp_uid = document.getElementById("useredit-sort-select-id").value;
        var elePhoneInput = document.getElementById("useredit-phone-input-id");
        if (typeof(elePhoneInput) != undefined && elePhoneInput != null)
            objMember.mb_phone = elePhoneInput.value;
        var eleBankNameInput = document.getElementById("useredit-bankname-input-id");
        if (typeof(eleBankNameInput) != undefined && eleBankNameInput != null)
            objMember.mb_bank_name = eleBankNameInput.value;
        var eleBankAccountInput = document.getElementById("useredit-bankaccount-input-id");
        if (typeof(eleBankAccountInput) != undefined && eleBankAccountInput != null)
            objMember.mb_bank_own = eleBankAccountInput.value;
        var eleBankNumInput = document.getElementById("useredit-bankserial-input-id");
        if (typeof(eleBankNumInput) != undefined && eleBankNumInput != null)
            objMember.mb_bank_num = eleBankNumInput.value;
        var eleBankPwdInput = document.getElementById("useredit-bankpwd-input-id");
        if (typeof(eleBankPwdInput) != undefined && eleBankPwdInput != null)
            objMember.mb_bank_pwd = eleBankPwdInput.value;
        objMember.mb_money = document.getElementById("useredit-money-input-id").value;
        objMember.mb_point = document.getElementById("useredit-point-input-id").value;
    }
    objMember.mb_game_pb_ratio = document.getElementById("useredit-pbbetrate-input-id").value;
    objMember.mb_game_pb2_ratio = document.getElementById("useredit-pbbetrate2-input-id").value;
    objMember.mb_game_ps_ratio = document.getElementById("useredit-psbetrate-input-id").value;
    objMember.mb_game_cs_ratio = document.getElementById("useredit-evbetrate-input-id").value;
    objMember.mb_game_sl_ratio = document.getElementById("useredit-slbetrate-input-id").value;
    objMember.mb_game_bb_ratio = document.getElementById("useredit-bbbetrate-input-id").value;
    objMember.mb_game_bb2_ratio = document.getElementById("useredit-bbbetrate2-input-id").value;
    objMember.mb_game_bs_ratio = document.getElementById("useredit-bsbetrate-input-id").value;

    objMember.mb_game_pb_percent = document.getElementById("useredit-pbbetpercent-input-id").value;
    objMember.mb_game_pb2_percent = document.getElementById("useredit-pbbetpercent2-input-id").value;
    objMember.mb_game_ps_percent = document.getElementById("useredit-psbetpercent-input-id").value;
    objMember.mb_game_bb_percent = document.getElementById("useredit-bbbetpercent-input-id").value;
    objMember.mb_game_bb2_percent = document.getElementById("useredit-bbbetpercent2-input-id").value;
    objMember.mb_game_bs_percent = document.getElementById("useredit-bsbetpercent-input-id").value;

    return objMember;

}

function addBtnEvent() {

    var elemOkBtn = document.getElementById("useredit-save-btn-id");
    elemOkBtn.addEventListener("click", function() {

        var objMember = readConfigToObject();
        //console.log(objMember);


        if (objMember.mb_uid.length < 1) {
            alert("아이디는 필수정보입니다.");
            return;
        }

        if (objMember.admin_level > 99) {
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
                url: "/userapi/modifymember",
                data: { json_: jsonData },
                success: function(jResult) {
                    console.log(jResult);
                    if (jResult.status == "success") {
                        window.location.replace('/user/member/0');
                    } else if (jResult.status == "logout") {
                        window.location.replace('/');
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
                        alert("파워볼 배당율이 매장설정값 " + jResult.error + "보다 높게 설정되었습니다.");
                    } else if (jResult.status == "ps_ratio_error") {
                        alert("파워사다리 배당율이 매장설정값 " + jResult.error + "보다 높게 설정되었습니다.");
                    } else if (jResult.status == "ev_ratio_error") {
                        alert("에볼루션 배당율이 본사설정값 " + jResult.error + "보다 높게 설정되었습니다.");
                    } else if (jResult.status == "bb_ratio_error") {
                        alert("보글볼 배당율이 매장설정값 " + jResult.error + "보다 높게 설정되었습니다.");
                    } else if (jResult.status == "bs_ratio_error") {
                        alert("보글사다리 배당율이 매장설정값 " + jResult.error + "보다 높게 설정되었습니다.");
                    } else if (jResult.status == "employee_error") {
                        alert("추천인 아이디가 존재하지 않습니다.");
                    }
                },
                error: function(request, status, error) {
                    console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                }

            });
        } else if (parseInt(objMember.mb_fid) == 0) {

            jsonData = JSON.stringify(objMember);
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "/userapi/addmember",
                data: { json_: jsonData },
                success: function(jResult) {
                    //console.log(jResult);
                    if (jResult.status == "success") {
                        window.location.replace('/user/member/0');
                    } else if (jResult.status == "logout") {
                        window.location.replace('/');
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
                            alert("등록된 매장이 아닙니다.");
                        else alert("등록이 실패되었습니다.");
                    } else if (jResult.status == "pb_ratio_error") {
                        alert("파워볼 배당율이 매장설정값 " + jResult.error + "보다 높게 설정되었습니다.");
                    } else if (jResult.status == "ps_ratio_error") {
                        alert("파워사다리 배당율이 매장설정값 " + jResult.error + "보다 높게 설정되었습니다.");
                    } else if (jResult.status == "ev_ratio_error") {
                        alert("에볼루션 배당율이 본사설정값 " + jResult.error + "보다 높게 설정되었습니다.");
                    } else if (jResult.status == "bb_ratio_error") {
                        alert("보글볼 배당율이 매장설정값 " + jResult.error + "보다 높게 설정되었습니다.");
                    } else if (jResult.status == "bs_ratio_error") {
                        alert("보글사다리 배당율이 매장설정값 " + jResult.error + "보다 높게 설정되었습니다.");
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


    var elemCancelBtn = document.getElementById("useredit-cancel-btn-id");
    elemCancelBtn.addEventListener("click", function() {
        window.location.replace('/user/member/0');
    });

}