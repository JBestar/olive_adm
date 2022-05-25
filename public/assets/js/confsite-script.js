$(document).ready(function() {
    $('textarea[name="editordata"]').summernote({
        // height: 300, // 에디터 높이
        minHeight: 100, // 최소 높이
        // maxHeight: null, // 최대 높이
        focus: false, // 에디터 로딩후 포커스를 맞출지 여부
        lang: "ko-KR", // 한글 설정
        placeholder: '', //placeholder 설정
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
        ]
    });
    
    addBtnEvent();

});

function readConfigToObject() {

    var jsonData = new Object();

    jsonData.sitename = $("#confsite-sitename-input-id").val();
    jsonData.domainname = $("#confsite-domainname-input-id").val();
    jsonData.homepage = $("#confsite-homepage-input-id").val();
    jsonData.adminpage = $("#confsite-adminpage-input-id").val();
    jsonData.bank = $("#confsite-bankname-input-id").val().trim() + "#" + $("#confsite-bankown-input-id").val().trim() +
        "#" + $("#confsite-banknum-input-id").val().trim();
    jsonData.depositenotice = $("#confsite-deposite-text-id").summernote('code');
    jsonData.depositenotice_ok = $("#confsite-deposite-check-id").prop('checked') ? 1 : 0;
    jsonData.mainnotice = $("#confsite-mainnotice-text-id").val();
    jsonData.mainnotice_ok = $("#confsite-mainnotice-check-id").prop('checked') ? 1 : 0;
    jsonData.urgentnotice = $("#confsite-urgentnotice-text-id").summernote('code');
    jsonData.urgentnotice_ok = $("#confsite-urgentnotice-check-id").prop('checked') ? 1 : 0;
    jsonData.bankmacro = $("#confsite-bankmacro-text-id").summernote('code');
    jsonData.multilog_ok = $("#confsite-multilog-check-id").prop('checked') ? 1 : 0;

    return jsonData;

}

function addBtnEvent() {

    $("#confsite-ok-btn-id").click(function() {

        if (!confirm("저장하시겠습니까?"))
            return;

        var jsonData = readConfigToObject();
        jsonData = JSON.stringify(jsonData);

        $.ajax({
            type: "POST",
            dataType: "json",
            url: FURL + "/api/saveconfsite",
            data: { json_: jsonData },
            success: function(jResult) {
                // console.log(jResult);
                if (jResult.status == "success") {
                    window.location.reload();
                } else if (jResult.status == "logout") {
                    window.location.replace( FURL +'/');
                } else if (jResult.status == "fail") {
                    alert("저장이 실패되었습니다.");
                }
            },
            error: function(request, status, error) {
                // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            }

        });
    });


    $("#confsite-cancel-btn-id").click(function() {
        window.location.reload();
    });

}


function cleanDb(iType) {

    if (!confirm("디비정리를 하시겠습니까?"))
        return;
    var objData = { "clean": iType };

    requestCleanDb(objData);

}



function requestCleanDb(objData) {
    if (objData == null)
        return;
    $("#cleanDb-but").attr("disabled", true);
    var jsonData = JSON.stringify(objData);
    $(".loading").show();

    $.ajax({
        type: "POST",
        dataType: "json",
        data: { json_: jsonData },
        url: FURL + "/api/cleanDb",
        success: function(jResult) {
            $(".loading").hide();
            $("#cleanDb-but").attr("disabled", false);
            // console.log(jResult);
            if (jResult.status == "success") {
                alert("디비정리가 완료되었습니다.!");
                location.reload();
            } else if (jResult.status == "fail") {
                if (jResult.data == 2)
                    alert("권한이 없습니다.");
                else alert("거절되었습니다.");
            } else if (jResult.status == "logout") {
                location.reload();
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            $("#cleanDb-but").attr("disabled", false);
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
    });

}