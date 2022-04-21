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
            html += game.name_ko;
            html += "</td><td>";
            html += game.name;
            html += "</td><td>";
            html += game.game_code;
            html += "</td><td>";

            html += "<select class='' onchange='onChangeApiType(this)' ";
            html += "' data-fid=" + game.fid + "' ";
            if (game.act == 1) {
                html += " style='color:black'> <option selected='' value='0'>OUR</option><option value='1'>KPLAY</option></select>";
            } else {
                html += " style='color:red'> <option value='0'>OUR</option><option selected='' value='1'>KPLAY</option></select>";
            }
            html += "</td><tr>";
            nRow++;
        });

    }
    $("#confsite-table-data").html(html);

}

function onChangeApiType(objSelect) {

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
        url: "/api/fslotset",
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
        url: "/api/fslotlist",
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
        url: '/api/fslotcnt',
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