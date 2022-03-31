initCalculate();

function initCalculate() {
    addButtonEvent();
    setNavBarElement();
    //
    var nEmpFid = 0;
    var nTbRow = -1;
    requestCalculate(nEmpFid, nTbRow);

}



function showCalcualte(arrCalcData) {
    /*
  var elemBetDataTb = document.getElementById("calculate-table-id");
  var strBuf = "";
  strBuf = "<thead><tr><th>ID</th> <th>닉네임</th><th>본사구분</th><th>충전</th><th>환전</th><th>충환손익</th><th>관리자보유금<br>(하부합산)</th>"
  strBuf += "<th>유저보유금</th><th>배팅<br>(하부포함)</th><th>적중<br>(하부포함)</th><th>배팅손익</th><th>수수료<br>(하부포함)</th><th>최종손익</th></tr><thead>";
  	*/
    var strBuf = "";
    var elemDataTbBody = document.getElementById("calculate-table-tbody-id");
    for (nRow in arrCalcData) {
        strBuf += "<tr";
        if (arrCalcData[nRow].mb_level == LEVEL_COMPANY)
            strBuf += " class=\"tr-company-color\"";
        else if (arrCalcData[nRow].mb_level == LEVEL_AGENCY)
            strBuf += " class=\"tr-agency-color\"";
        else if (arrCalcData[nRow].mb_level == LEVEL_EMPLOYEE)
            strBuf += " class=\"tr-employee-color\"";

        strBuf += "><td>";
        if (arrCalcData[nRow].mb_level > LEVEL_EMPLOYEE)
            strBuf += "<i class=\"glyphicon glyphicon-triangle-right\"></i>"
        strBuf += "<p hidden>" + arrCalcData[nRow].mb_fid + "</p>";
        strBuf += "<p hidden>" + arrCalcData[nRow].mb_emp_fid + "</p>";
        strBuf += "<p hidden>" + arrCalcData[nRow].mb_level + "</p>";

        strBuf += "</td><td>";
        strBuf += arrCalcData[nRow].mb_uid; //
        strBuf += "</td><td>";
        strBuf += arrCalcData[nRow].mb_nickname; //
        strBuf += "</td><td>";
        if (arrCalcData[nRow].mb_level == LEVEL_COMPANY) //
            strBuf += "부본사";
        else if (arrCalcData[nRow].mb_level == LEVEL_AGENCY) //
            strBuf += "총판";
        else if (arrCalcData[nRow].mb_level == LEVEL_EMPLOYEE) //
            strBuf += "매장";
        strBuf += "</td><td>";
        strBuf += parseInt(arrCalcData[nRow].mb_charge).toLocaleString(); //
        strBuf += "</td><td>";
        strBuf += parseInt(arrCalcData[nRow].mb_exchange).toLocaleString();
        strBuf += "</td><td>";
        strBuf += parseInt(arrCalcData[nRow].mb_charge_benefit).toLocaleString();
        strBuf += "</td><td>";
        strBuf += parseInt(arrCalcData[nRow].mb_emp_money).toLocaleString();
        strBuf += "</td><td>";
        strBuf += parseInt(arrCalcData[nRow].mb_user_money).toLocaleString();
        strBuf += "</td><td>";
        strBuf += parseInt(arrCalcData[nRow].mb_bet_money).toLocaleString();
        strBuf += "</td><td>";
        strBuf += parseInt(arrCalcData[nRow].mb_bet_win_money).toLocaleString();
        strBuf += "</td><td>";
        strBuf += parseInt(arrCalcData[nRow].mb_bet_benefit_money).toLocaleString();
        strBuf += "</td><td>";
        strBuf += parseInt(arrCalcData[nRow].mb_rate_money).toLocaleString();
        strBuf += "</td><td>";
        strBuf += parseInt(arrCalcData[nRow].mb_last_money).toLocaleString();
        strBuf += "</td></tr>";
    }

    elemDataTbBody.innerHTML = strBuf;

    addTableEvent();
}


function addButtonEvent() {
    var butView = document.getElementById("calculate-list-view-but-id");
    butView.addEventListener("click", function() {
        var nEmpFid = 0;
        var nTbRow = -1;
        requestCalculate(nEmpFid, nTbRow);

    });

}


//Function to Request Betting History to WebServer
function requestCalculate(nFid, nRow) {

    var dtStart = document.getElementById("calculate-datestart-input-id").value;
    var dtEnd = document.getElementById("calculate-dateend-input-id").value;

    var jsonData = { "mb_fid": nFid, "start": dtStart, "end": dtEnd, "type":  5};
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: '/api/calculategame',
        data: { json_: jsonData },
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            //console.log(jResult);
            if (jResult.status == "success") {
                // setNavBarElement();
                if (nRow < 0) showCalcualte(jResult.data);
                else addRow(nRow, jResult.data);
            } else if (jResult.status == "logout") {
                window.location.replace('/');
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });


}



function addTableEvent() {
    var elemDataTb = document.getElementById("calculate-table-tbody-id");
    var elemRows = elemDataTb.getElementsByTagName("tr");

    for (var i = 0; i < elemRows.length; i++) {
        elemRows[i].addEventListener("click", rowEventHander);
    }
}


function rowEventHander() {
    var nRow = this.rowIndex;

    var elemCells = this.getElementsByTagName("td");
    if (elemCells.length < 1) return;

    var elemP = elemCells[0].getElementsByTagName("p");

    if (elemP.length < 3) return;
    else if (parseInt(elemP[2].innerHTML) < 8) return;

    var nEmpFid = elemP[0].innerHTML;
    var nEmpLevel = elemP[2].innerHTML;

    if (elemCells[0].innerHTML.indexOf("triangle-right") >= 0) {
        elemCells[0].innerHTML = elemCells[0].innerHTML.replace(/triangle-right/gi, 'triangle-bottom');

        if (nEmpFid > 0)
            requestCalculate(nEmpFid, nRow);

    } else if (elemCells[0].innerHTML.indexOf("triangle-bottom") >= 0) {
        elemCells[0].innerHTML = elemCells[0].innerHTML.replace(/triangle-bottom/gi, 'triangle-right');
        removeRow(nRow, nEmpLevel);
    }
}




function addRow(nTbRow, arrCalcData) {
    var elemDataTb = document.getElementById("calculate-table-tbody-id");

    var strBuf = "";
    for (nRow in arrCalcData) {

        var elemNewRow = elemDataTb.insertRow(nTbRow);
        nTbRow++;

        if (arrCalcData[nRow].mb_level == LEVEL_COMPANY)
            elemNewRow.className = "tr-company-color";
        else if (arrCalcData[nRow].mb_level == LEVEL_AGENCY)
            elemNewRow.className = "tr-agency-color";
        else if (arrCalcData[nRow].mb_level == LEVEL_EMPLOYEE)
            elemNewRow.className = "tr-employee-color";

        var elemCell0 = elemNewRow.insertCell(0);
        if (arrCalcData[nRow].mb_level > LEVEL_EMPLOYEE)
            strBuf = "<i class=\"glyphicon glyphicon-triangle-right\"></i>"
        strBuf += "<p hidden>" + arrCalcData[nRow].mb_fid + "</p>";
        strBuf += "<p hidden>" + arrCalcData[nRow].mb_emp_fid + "</p>";
        strBuf += "<p hidden>" + arrCalcData[nRow].mb_level + "</p>";
        elemCell0.innerHTML = strBuf;

        var elemCell1 = elemNewRow.insertCell(1);
        elemCell1.innerHTML = arrCalcData[nRow].mb_uid; //

        var elemCell2 = elemNewRow.insertCell(2);
        elemCell2.innerHTML = arrCalcData[nRow].mb_nickname; //

        var elemCell3 = elemNewRow.insertCell(3);
        if (arrCalcData[nRow].mb_level == LEVEL_COMPANY) //
            elemCell3.innerHTML = "부본사";
        else if (arrCalcData[nRow].mb_level == LEVEL_AGENCY) //
            elemCell3.innerHTML = "총판";
        else if (arrCalcData[nRow].mb_level == LEVEL_EMPLOYEE) //
            elemCell3.innerHTML = "매장";

        var elemCell4 = elemNewRow.insertCell(4);
        elemCell4.innerHTML = parseInt(arrCalcData[nRow].mb_charge).toLocaleString(); //

        var elemCell5 = elemNewRow.insertCell(5);
        elemCell5.innerHTML = parseInt(arrCalcData[nRow].mb_exchange).toLocaleString();

        var elemCell6 = elemNewRow.insertCell(6);
        elemCell6.innerHTML = parseInt(arrCalcData[nRow].mb_charge_benefit).toLocaleString();

        var elemCell7 = elemNewRow.insertCell(7);
        elemCell7.innerHTML = parseInt(arrCalcData[nRow].mb_emp_money).toLocaleString();

        var elemCell8 = elemNewRow.insertCell(8);
        elemCell8.innerHTML = parseInt(arrCalcData[nRow].mb_user_money).toLocaleString();

        var elemCell9 = elemNewRow.insertCell(9);
        elemCell9.innerHTML = parseInt(arrCalcData[nRow].mb_bet_money).toLocaleString();

        var elemCell10 = elemNewRow.insertCell(10);
        elemCell10.innerHTML = parseInt(arrCalcData[nRow].mb_bet_win_money).toLocaleString();

        var elemCell11 = elemNewRow.insertCell(11);
        elemCell11.innerHTML = parseInt(arrCalcData[nRow].mb_bet_benefit_money).toLocaleString();

        var elemCell12 = elemNewRow.insertCell(12);
        elemCell12.innerHTML = parseInt(arrCalcData[nRow].mb_rate_money).toLocaleString();

        var elemCell13 = elemNewRow.insertCell(13);
        elemCell13.innerHTML = parseInt(arrCalcData[nRow].mb_last_money).toLocaleString();

        elemNewRow.addEventListener("click", rowEventHander);

    }

}


function removeRow(nRow, nParentLevel) {
    var elemDataTb = document.getElementById("calculate-table-tbody-id");
    var elemRows = elemDataTb.getElementsByTagName("tr");

    for (var i = 0; i < elemRows.length + 1; i++) {

        if (elemRows[nRow] == undefined) break;

        var elemCells = elemRows[nRow].getElementsByTagName("td");


        var elemPs = elemCells[0].getElementsByTagName("p");
        if (elemPs.length < 3) break;

        var nRowEmpLevel = elemPs[2].innerHTML;

        if (nRowEmpLevel < nParentLevel) {
            elemRows[nRow].removeEventListener("click", rowEventHander);
            elemDataTb.deleteRow(nRow);
        } else break;

    }
}