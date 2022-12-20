$(document).ready(function() {
    setNavBarElement();
    // addEventListner();
    // requestConfBetSite();
    ordhitoryLoop();
});

//Function to Show Betting History
function ShowOrdHistory(jsonBetData) {
    var elemBetDataTb = document.getElementById("pbbet-table-id");
    var strBuf = "";
    var strBuf = "";

    if(jsonBetData != null && jsonBetData.length > 0 ){

        var nRow = 0;
        var cntRow = jsonBetData.length;
        var idx = 1; 
        var balPlayer = "", balBanker = "";
        while(nRow < cntRow) {
            strBuf += "<tr><td>";
            strBuf += idx;
            strBuf += "</td><td>";
            strBuf += jsonBetData[nRow].name;
            strBuf += "</td><td>";

            if(jsonBetData[nRow].ord_choice == null ){
                
                strBuf += getEvolSide("Player");
                strBuf += "</td><td></td><td></td></tr>";
            
                strBuf += "<tr><td></td><td></td><td>";
                strBuf += getEvolSide("Banker");
                strBuf += "</td><td></td><td>";
                nRow ++;
            } else if(jsonBetData[nRow].ord_choice == "Player") {
                if(nRow+1 < cntRow && jsonBetData[nRow].tid === jsonBetData[nRow+1].tid && jsonBetData[nRow+1].ord_choice == "Banker"){
                    if(jsonBetData[nRow].ord_amount == jsonBetData[nRow+1].ord_amount){
                        balPlayer = "0";
                        balBanker = "0";
                    } else if(jsonBetData[nRow].ord_amount > jsonBetData[nRow+1].ord_amount){
                        balPlayer = (jsonBetData[nRow].ord_amount - jsonBetData[nRow+1].ord_amount).toLocaleString();
                        balBanker = "0";
                    } else if(jsonBetData[nRow].ord_amount < jsonBetData[nRow+1].ord_amount){
                        balPlayer = "0";
                        balBanker = (jsonBetData[nRow+1].ord_amount - jsonBetData[nRow].ord_amount).toLocaleString();
                    } 
                    
                    if(balPlayer < 1000)
                        balPlayer = 0;

                    if(balBanker < 1000)
                        balBanker = 0;

                    strBuf += getEvolSide("Player");
                    strBuf += "</td><td>"+parseInt(jsonBetData[nRow].ord_amount).toLocaleString();
                    strBuf += "</td><td>"+balPlayer+"</td></tr>";

                    strBuf += "<tr><td></td><td></td><td>";
                    strBuf += getEvolSide("Banker");
                    strBuf += "</td><td>"+parseInt(jsonBetData[nRow+1].ord_amount).toLocaleString();
                    strBuf += "</td><td>"+balBanker;
                    nRow +=2;
                } else {
                    
                    balPlayer = jsonBetData[nRow].ord_amount;
                    if(balPlayer < 1000)
                        balPlayer = 0;

                    strBuf += getEvolSide("Player");
                    strBuf += "</td><td>"+parseInt(jsonBetData[nRow].ord_amount).toLocaleString();
                    strBuf += "</td><td>"+parseInt(balPlayer).toLocaleString();
                    strBuf += "</td></tr>";

                    strBuf += "<tr><td></td><td></td><td>";
                    strBuf += getEvolSide("Banker");
                    strBuf += "</td><td>0</td><td>0";
                    nRow ++;
                }
            } else if(jsonBetData[nRow].ord_choice == "Banker") {
                strBuf += getEvolSide("Player");
                strBuf += "</td><td>0</td><td>0</td></tr>";
            
                balBanker = jsonBetData[nRow].ord_amount;
                if(balBanker < 1000)
                    balBanker = 0;
                strBuf += "<tr><td></td><td></td><td>";
                strBuf += getEvolSide("Banker");
                strBuf += "</td><td>"+parseInt(jsonBetData[nRow].ord_amount).toLocaleString();
                strBuf += "</td><td>"+parseInt(balBanker).toLocaleString();
                nRow ++;
            }
            strBuf += "</td></tr>";

            idx ++;
        }
    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='8'>자료가 없습니다.</td></tr>";
    }
    elemBetDataTb.innerHTML = strBuf;

}

function addEventListner() {

    $("#ebal-start-but-id").click(function() {
        setEbalState(1);
    });
    
    $("#ebal-stop-but-id").click(function() {
        setEbalState(0);
    });
}

//Function to Request Betting History to WebServer
function requestOrdHistory() {

    // $(".loading").show();
    $.ajax({
        url: FURL + '/api/eorderlist',
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            // $(".loading").hide();
            // console.log(jResult);
            if (jResult.status == "success") {
                ShowOrdHistory(jResult.data);
                
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}



function ordhitoryLoop() {

    requestOrdHistory();

    // 10초뒤에 다시 실행
    setTimeout(function() {
        ordhitoryLoop();
        // requestConfBetSite();
    }, 10000);
}

function requestConfBetSite() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/getEvolSite",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                showConfSite(jResult.data);
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            } else if (jResult.status == "nopermit") {
                alert("권한이 없습니다.");
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}

function showConfSite(arrData) {
    if (arrData.length < 6)
        return;

    if(arrData[3] == 1){
        $("#ebal-start-but-id").attr("disabled", true);
        $("#ebal-stop-but-id").attr("disabled", false);

        $("#ebal-start-but-id").css("background", '#85ff8e');
        $("#ebal-stop-but-id").css("background", 'white');

        $('#ebal-balance-id').show();
        if(parseInt(arrData[7]) >= 0)
            $('#ebal-balance-id').text(`보유알: ${arrData[7].toLocaleString()}`);
        else $('#ebal-balance-id').hide();
        
    } else {
        $("#ebal-start-but-id").attr("disabled", false);
        $("#ebal-stop-but-id").attr("disabled", true);

        $("#ebal-start-but-id").css("background", 'white');
        $("#ebal-stop-but-id").css("background", '#ff3a5a');

        $('#ebal-balance-id').hide();

    }

}


function setEbalState(state){
    
    var objData = new Object();

    objData.active_ev = state;
    
    var jsonData = JSON.stringify(objData);

    if (state == 1 && !confirm("시작하시겠습니까?"))
        return;
    else if (state == 0 && !confirm("정지하시겠습니까?"))
        return;

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
                alert("조작이 실패되었습니다.");
            } else if (jResult.status == "nopermit") {
                alert("권한이 없습니다.");
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}