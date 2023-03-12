$(document).ready(function() {
    requestJoinIp();
    addBtnEvent();
});

function readPasswordToObject() {

    var jsonData = new Object();

    jsonData.password = $("#confsite-password-input-id").val();
    jsonData.password_new = $("#confsite-password-new-input-id").val();
    jsonData.password_newok = $("#confsite-password-newok-input-id").val();

    if ($("#confsite-ip-input-id").length > 0) {
        jsonData.ip_addr = $("#confsite-ip-input-id").val();
        jsonData.ip_check = $("#confsite-ip-check-id").prop('checked') ? 1 : 0;
    }
    return jsonData;

}

function addBtnEvent() {

    $("#confsite-ok-btn-id").click(function() {

        var jsonData = readPasswordToObject();

        if (jsonData.password.length < 1) {
            alert("현재 비밀번호를 입력해주세요.");
            return;
        }

        if (jsonData.password_new.length > 0 && jsonData.password_new != jsonData.password_newok) {
            alert("새 비밀번호를 정확히 입력해주세요.");
            return;
        }

        if (!confirm("저장하시겠습니까?"))
            return;

        jsonData = JSON.stringify(jsonData);

        $.ajax({
            type: "POST",
            dataType: "json",
            url: FURL + "/api/change_password",
            data: { json_: jsonData },
            success: function(jResult) {
                //console.log(jResult);
                if (jResult.status == "success") {
                    alert("정보가 변경되었습니다.");
                } else if (jResult.status == "fail") {
                    alert(jResult.msg);
                }
            },
            error: function(request, status, error) {
                // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            }

        });
    });


    $("#confsite-cancel-btn-id").click(function() {
        location.reload();
    });

    $("#recovery_useregg").click(function() {
        $("#recovery_useregg").addClass("refresh");

        var jsonData = { "self":1 };
        jsonData = JSON.stringify(jsonData);

        $.ajax({
            type: "POST",
            dataType: "json",
            url: FURL + "/userapi/eggcollect",
            data: { json_: jsonData },
            success: function(jResult) {
                $("#recovery_useregg").removeClass("refresh");
                // console.log(jResult);
                if (jResult.status == "success") {
                    $("#confsite-egg-input-id").val(jResult.money);
                    if(jResult.egg > 0)
                        $("#recovery_useregg").show();
                    else 
                        $("#recovery_useregg").hide();
                } else if (jResult.status == "fail") {
                    alert("회수가 실패되었습니다.");
                }
            },
            error: function(request, status, error) {
                $("#recovery_useregg").removeClass("refresh");
                // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            }

        });

    });

}

function setJoinIp(ipInfo) {
    $("#confsite-ip-input-id").val(ipInfo.ip_addr);
    $("#confsite-ip-check-id").prop('checked', ipInfo.ip_check > 0 ? true : false);
    $("#confsite-egg-input-id").val(ipInfo.money);
    if(ipInfo.egg > 0)
        $("#recovery_useregg").show();
    else 
        $("#recovery_useregg").hide();

}

function requestJoinIp() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/empIp",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                setJoinIp(jResult.data);
            } else if (jResult.status == "logout") {

            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}