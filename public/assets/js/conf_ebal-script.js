$(document).ready(function() {
    setNavBarElement();
    requestConfBetSite(true);
    addBtnEvent();
    if($("#confev-bet-allcheck-id").length > 0)
        requestConfBetState();
});


function showConfSite(arrData, all) {
    if (arrData.length < 3)
        return;

    let data = arrData[0];
    if(all){
        $("#confev-bet-check-id").prop('checked', data[3] > 0 ? true : false);
        $("#confev-betsite-input-id").val(data[0]);
        $("#confev-userid-input-id").val(data[1]);
        $("#confev-userpwd-input-id").val(data[2]);
        $("#confev-bettype-select-id").val(data[4]);
        $("#confev-betend-input-id").val(data[5]);
        $("#confev-conbet-check-id").prop('checked', data[6] > 0 ? true : false);
        $("#confev-betmin-input-id").val(data[8]);
        $("#confev-betmax-input-id").val(data[9]);
        $("#confev-conbet-input-id").val(data[11]);
        $("#confev-maxuser-input-id").val(data[12]);
        $("#confev-signal-check-id").prop('checked', data[13] > 0 ? true : false);
        $("#confev-multiroom-check-id").prop('checked', data[15] > 0 ? true : false);
    }
    
    $('#confev-balance-input-id').val('');
    $('#confev-balance-label-id').text('');

    $('#confev-conuser-input-id').text('(접속자수: '+data[14]+")");
    if(parseInt(data[7]) >= 0){
        $('#confev-balance-input-id').val(data[7].toLocaleString());
        setTimeout(() =>{
            this.requestConfBetSite();
        }, 10000);
    } else {
        setTimeout(() =>{
            this.requestConfBetSite();
        }, 5000);
    }

    if(parseInt(data[7]) < -2){
        $('#confev-balance-label-id').text(`( 정지됨 )`);
    } else if(data[10].length > 0)
        $('#confev-balance-label-id').text(`( ${data[10]} )`);



    if($("#confev-betsite-input-id2").length > 0){
        data = arrData[1];
        if(all){
            $("#confev-bet-check-id2").prop('checked', data[3] > 0 ? true : false);
            $("#confev-betsite-input-id2").val(data[0]);
            $("#confev-userid-input-id2").val(data[1]);
            $("#confev-userpwd-input-id2").val(data[2]);
            $("#confev-bettype-select-id2").val(data[4]);
            $("#confev-betmode-select-id").val(data[5]);
            $("#confev-conbet-check-id2").prop('checked', data[6] > 0 ? true : false);
            $("#confev-signal-check-id2").prop('checked', data[13] > 0 ? true : false);
            $("#confev-multiroom-check-id2").prop('checked', data[15] > 0 ? true : false);
        }
            
        $('#confev-balance-input-id2').val('');
        $('#confev-balance-label-id2').text('');
    
        if(parseInt(data[7]) >= 0){
            $('#confev-balance-input-id2').val(data[7].toLocaleString());
        }
    
        if(parseInt(data[7]) < -2){
            $('#confev-balance-label-id2').text(`( 정지됨 )`);
        } else if(data[10].length > 0)
            $('#confev-balance-label-id2').text(`( ${data[10]} )`);
    

        //site3
        data = arrData[2];
        if(all){
            $("#confev-bet-check-id3").prop('checked', data[3] > 0 ? true : false);
            $("#confev-betsite-input-id3").val(data[0]);
            $("#confev-userid-input-id3").val(data[1]);
            $("#confev-userpwd-input-id3").val(data[2]);
            $("#confev-bettype-select-id3").val(data[4]);
            $("#confev-conbet-check-id3").prop('checked', data[6] > 0 ? true : false);
            $("#confev-signal-check-id3").prop('checked', data[13] > 0 ? true : false);
            $("#confev-multiroom-check-id3").prop('checked', data[15] > 0 ? true : false);
        }
        
        $('#confev-balance-input-id3').val('');
        $('#confev-balance-label-id3').text('');
    
        if(parseInt(data[7]) >= 0){
            $('#confev-balance-input-id3').val(data[7].toLocaleString());
        }
    
        if(parseInt(data[7]) < -2){
            $('#confev-balance-label-id3').text(`( 정지됨 )`);
        } else if(data[10].length > 0)
            $('#confev-balance-label-id3').text(`( ${data[10]} )`);
            
    }
    

}


function addBtnEvent() {

    $("#confsite-ok-btn-id").click(function() {

        var objData = new Object();

        objData.site_ev = $("#confev-betsite-input-id").val().trim();
        objData.userid_ev = $("#confev-userid-input-id").val().trim();
        objData.userpwd_ev = $("#confev-userpwd-input-id").val().trim();
        objData.active_ev = $("#confev-bet-check-id").prop('checked') ? 1 : 0;
        objData.type_ev = $("#confev-bettype-select-id").val();
        objData.bet_ev = $("#confev-betend-input-id").val();
        objData.bet_min = $("#confev-betmin-input-id").val();
        objData.bet_max = $("#confev-betmax-input-id").val();
        objData.con_ev = $("#confev-conbet-check-id").prop('checked') ? 1 : 0;
        objData.con_min = $("#confev-conbet-input-id").val();
        objData.user_max = $("#confev-maxuser-input-id").val();
        objData.is_signal = 0;
        if($("#confev-signal-check-id").length > 0)
            objData.is_signal = $("#confev-signal-check-id").prop('checked') ? 1 : 0;
        objData.multiroom = $("#confev-multiroom-check-id").prop('checked') ? 1 : 0;

        if($("#confev-betsite-input-id2").length > 0){
            objData.betmode_ev = $("#confev-betmode-select-id").val();

            objData.site_ev2 = $("#confev-betsite-input-id2").val().trim();
            objData.userid_ev2 = $("#confev-userid-input-id2").val().trim();
            objData.userpwd_ev2 = $("#confev-userpwd-input-id2").val().trim();
            objData.active_ev2 = $("#confev-bet-check-id2").prop('checked') ? 1 : 0;
            objData.type_ev2 = $("#confev-bettype-select-id2").val();
            objData.con_ev2 = $("#confev-conbet-check-id2").prop('checked') ? 1 : 0;
            objData.is_signal2 = $("#confev-signal-check-id2").prop('checked') ? 1 : 0;
            objData.multiroom2 = $("#confev-multiroom-check-id2").prop('checked') ? 1 : 0;

            objData.site_ev3 = $("#confev-betsite-input-id3").val().trim();
            objData.userid_ev3 = $("#confev-userid-input-id3").val().trim();
            objData.userpwd_ev3 = $("#confev-userpwd-input-id3").val().trim();
            objData.active_ev3 = $("#confev-bet-check-id3").prop('checked') ? 1 : 0;
            objData.type_ev3 = $("#confev-bettype-select-id3").val();
            objData.con_ev3 = $("#confev-conbet-check-id3").prop('checked') ? 1 : 0;
            objData.is_signal3 = $("#confev-signal-check-id3").prop('checked') ? 1 : 0;
            objData.multiroom3 = $("#confev-multiroom-check-id3").prop('checked') ? 1 : 0;

            if(objData.site_ev.length > 0 && objData.userid_ev.length> 0 && objData.site_ev === objData.site_ev2 && objData.userid_ev == objData.userid_ev2){
                showAlert("보험계정1과 보험계정2를 다르게 입력해주세요", 0);
                return;
            }

            if(objData.site_ev.length > 0 && objData.userid_ev.length> 0 && objData.site_ev === objData.site_ev3 && objData.userid_ev == objData.userid_ev3){
                showAlert("보험계정1과 보험계정3을 다르게 입력해주세요", 0);
                return;
            }

            if(objData.site_ev2.length > 0 && objData.userid_ev2.length> 0 && objData.site_ev2 === objData.site_ev3 && objData.userid_ev2 == objData.userid_ev3){
                showAlert("보험계정2과 보험계정3을 다르게 입력해주세요", 0);
                return;
            }
        }


        var jsonData = JSON.stringify(objData);

        if (!confirm("저장하시겠습니까?"))
            return;
        if($("#confev-bet-allcheck-id").length > 0)
            setEbalState($("#confev-bet-allcheck-id").prop('checked') ? 1 : 0);

        $.ajax({
            type: "POST",
            dataType: "json",
            url: FURL + "/api/setEvolSite",
            data: { json_: jsonData },
            success: function(jResult) {
                // console.log(jResult);
                if (jResult.status == "success") {
                    window.location.reload();
                } else if (jResult.status == "logout") {
                    window.location.replace( FURL +'/');
                } else if (jResult.status == "fail") {
                    showAlert("저장이 실패되었습니다.", 0);
                } else if (jResult.status == "nopermit") {
                    showAlert("권한이 없습니다.", 0);
                }
            },
            error: function(request, status, error) {
                // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            }

        });

    });

    $("#confsite-cancel-btn-id").click(function() {
        location.reload();
    });

}

function requestConfBetSite(all=false) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/getEvolSite",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                showConfSite(jResult.data, all);
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            } else if (jResult.status == "nopermit") {
                showAlert("권한이 없습니다.", 0);
            }
        },
        error: function(request, status, error) {
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}


function requestConfBetState() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/getEvolState",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                $("#confev-bet-allcheck-id").prop('checked', jResult.data > 0 ? true : false);
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            } else if (jResult.status == "nopermit") {
                showAlert("권한이 없습니다.", 0);
            }
        },
        error: function(request, status, error) {
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}


function setEbalState(state){
    
    var objData = new Object();

    objData.active_ev = state;
    
    var jsonData = JSON.stringify(objData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/setEvolState",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                // window.location.reload();
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            } else if (jResult.status == "fail") {
                showAlert("조작이 실패되었습니다.", 0);
            } else if (jResult.status == "nopermit") {
                showAlert("권한이 없습니다.", 0);
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}
