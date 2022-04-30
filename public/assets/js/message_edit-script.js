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
            // ['table', ['table']],
            // ['insert', ['link', 'picture', 'video']],
            // ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    initMessageEdit();

});

function initMessageEdit() {
    addBtnEvent();
    requestMembers();
}

function readConfigToObject() {
    var jsonData = new Object();

    jsonData.notice_fid = $("#subnavbar-fid-p-id").html();
    jsonData.notice_type = $("#subnavbar-type-p-id").html();
    jsonData.notice_state_active = $("#notice-state-check-id").prop('checked') ? 1 : 0;
    jsonData.notice_title = $("#notice-title-input-id").val();
    jsonData.notice_content = "";
    if($("#notice-content").length > 0){
        jsonData.notice_content = $("#notice-content").summernote('code');
    } else {
        jsonData.notice_content = $("#custom-content").text();
    }
    jsonData.notice_answer = "";
    if ($("#custom-answer").length > 0) {
        jsonData.notice_answer = $("#custom-answer").summernote('code');
        jsonData.notice_state_active = 1;
    }
    jsonData.notice_mb_uid = $("#notice-mbuid-input-id").val();

    return jsonData;

}

function addBtnEvent() {

    $("#notice-save-btn-id").click(function() {

        var jsonData = readConfigToObject();

        if (jsonData.notice_title.length < 1) {
            alert("제목을 입력하세요.");
            return;
        }

        if (jsonData.notice_content.length < 1) {
            alert("내용을 입력하세요.");
            return;
        }

        if (!confirm("발송하시겠습니까?"))
            return;

        if (parseInt(jsonData.notice_fid) > 0) {

            jsonData = JSON.stringify(jsonData);
            $.ajax({
                type: "POST",
                dataType: "json",
                url: FURL + "/api/updatenotice",
                data: { json_: jsonData },
                success: function(jResult) {
                    //console.log(jResult);
                    if (jResult.status == "success") {
                        window.location.replace( FURL +'/board/message');
                    } else if (jResult.status == "logout") {
                        window.location.replace( FURL +'/');
                    } else if (jResult.status == "fail") {

                    }
                },
                error: function(request, status, error) {
                    //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
                }

            });
        } else if (parseInt(jsonData.notice_fid) == 0) {


            jsonData = JSON.stringify(jsonData);
            $.ajax({
                type: "POST",
                dataType: "json",
                url: FURL + "/api/addMessage",
                data: { json_: jsonData },
                success: function(jResult) {
                    //console.log(jResult);
                    if (jResult.status == "success") {
                        window.location.replace( FURL +'/board/message');
                    } else if (jResult.status == "logout") {
                        window.location.replace( FURL +'/');
                    } else if (jResult.status == "fail") {
                        alert("발송자 아이디가 존재하지 않습니다.");
                    }
                },
                error: function(request, status, error) {
                    //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
                }

            });
        }


    });


    $("#notice-cancel-btn-id").click(function() {
        window.location.replace( FURL +'/board/message');
    });


}


function requestMembers() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/allmember",
        success: function(jResult) {
            //console.log(jResult);
            if (jResult.status == "success") {
                showAutoComplete(jResult.data);
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}



function showAutoComplete(arrMember) {
    $(function() {

        var arrData = new Array();
        arrData[0] = "*";
        for (nRow in arrMember) {
            arrData[parseInt(nRow) + 1] = arrMember[nRow].mb_uid;
        }

        $("#notice-mbuid-input-id").autocomplete({
            source: arrData,
            select: function(event, ui) {},
            focus: function(event, ui) {
                return false;
            }

        });
    });

}