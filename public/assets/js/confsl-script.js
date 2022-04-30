$(document).ready(function() {

    addEventListner();
    requestTotalPage();
});


function requestPageInfo() {
    requestGame();
}

function addEventListner() {
    $("#confsite-list-view-but-id").click(function() {
        requestTotalPage();
    });

    $("#confsite-number-select-id").change(function() {
        requestTotalPage();
    });
}

function showGame(list) {
    var html = "";

    if (list) {
        var curPage = getActivePage();
        var firstIdx = (curPage - 1) * CountPerPage;
        var nRow = 0;
        list.forEach((game) => {
            html += "<tr><td>";
            html += (parseInt(nRow) + firstIdx + 1);
            html += "</td><td>";
            html += "<img class = 'zoom' width='25px' height='25px' src='" + game.img + "'></img>";
            html += "</td><td>";
            html += game.provider;
            html += "</td><td>";
            html += game.rname_ko;
            html += "</td><td>";
            html += game.rname;
            html += "</td><td>";
            html += game.game_code;
            html += "</td><td>";
            html += "<select class='' onchange='onChangeHidden(this)' data-name='" + game.rname + "' ";
            if (game.rhidden == 0) {
                html += " style='color:black'> <option selected='' value='0'>보이기</option><option value='1'>감추기</option></select>";
            } else {
                html += " style='color:red'> <option value='0'>보이기</option><option selected='' value='1'>감추기</option></select>";
            }
            html += "</td><td>";
            html += "<select class='' onchange='onChangeMaintain(this)' data-name='" + game.rname + "' ";
            if (game.rmaintain == 0) {
                html += " style='color:black'> <option selected='' value='0'>운영</option><option value='1'>점검</option></select>";
            } else {
                html += " style='color:red'> <option value='0'>운영</option><option selected='' value='1'>점검</option></select>";
            }
            html += "</td><td>";

            html += "<select class='' onchange='onChangeAct(this)' data-fid='" + game.fid + "' ";
            if (game.act == 1) {
                html += " style='color:black'> <option selected='' value='0'>OUR</option><option value='1'>KPLAY</option></select>";
            } else {
                html += " style='color:red'> <option value='0'>OUR</option><option selected='' value='1'>KPLAY</option></select>";
            }
            html += "</td><tr>";
            nRow++;
        });
        if (html.length < 1) {
            html = "<tr><td colspan='6'>자료가 없습니다.</td></tr>";
        }
    }
    $("#confsite-table-data").html(html);

}

function onChangeHidden(objSelect) {

    let iType = $(objSelect).val();
    if (iType == 1)
        $(objSelect).css("color", "red");
    else
        $(objSelect).css("color", "black");

    var jsonData = {
        "hidden": iType == 1 ? 1 : 0,
        "name": $(objSelect).data("name"),
    };
    // console.log(jsonData);
    jsonData = JSON.stringify(jsonData);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/fslotset",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {}
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }
    });

}

function onChangeMaintain(objSelect) {

    let iType = $(objSelect).val();
    if (iType == 1)
        $(objSelect).css("color", "red");
    else
        $(objSelect).css("color", "black");

    var jsonData = {
        "maintain": iType == 1 ? 1 : 0,
        "name": $(objSelect).data("name"),
    };
    // console.log(jsonData);
    jsonData = JSON.stringify(jsonData);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/fslotset",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {}
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }
    });
}

function onChangeAct(objSelect) {

    let iType = $(objSelect).val();
    // console.log(iType);
    if (iType == 1)
        $(objSelect).css("color", "red");
    else
        $(objSelect).css("color", "black");

    var jsonData = {
        "act": iType == 1 ? 0 : 1,
        "fid": $(objSelect).data("fid"),
    };

    jsonData = JSON.stringify(jsonData);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/fslotset",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {}
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });


}

function requestGame() {

    var nPage = getActivePage();
    var sGame = $("#confsite-game-input-id").val();

    var jsonData = {
        "count": CountPerPage,
        "page": nPage,
        "name": sGame,

    };

    jsonData = JSON.stringify(jsonData);
    $(".loading").show();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/fslotlist",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            $(".loading").hide();
            if (jResult.status == "success") {
                showGame(jResult.data);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            $(".loading").hide();
        }

    });

}



//Function to Request Game Result History to WebServer
function requestTotalPage() {
    CountPerPage = $("#confsite-number-select-id").val();
    var sGame = $("#confsite-game-input-id").val();

    var jsonData = {
        "count": CountPerPage,
        "name": sGame,
    };

    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: FURL + '/api/fslotcnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data.count;
                setFirstPage();
                requestGame();
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}