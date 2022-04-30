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
    addBtnEvent();
});

function readConfigToObject() {

    var jsonData = new Object();

    jsonData.notice_fid = $("#subnavbar-fid-p-id").html();
    jsonData.notice_type = 1;
    jsonData.notice_state_active = $("#notice-state-check-id").prop('checked') ? 1 : 0;
    jsonData.notice_title = $("#notice-title-input-id").val();
    jsonData.notice_content = $("#notice-content").summernote('code');

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

        if (!confirm("저장하시겠습니까?"))
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
                        window.location.replace( FURL +'/board/notice');
                    } else if (jResult.status == "logout") {
                        window.location.replace( FURL +'/');
                    } else if (jResult.status == "fail") {
                        alert("수정이 실패되었습니다.");
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
                url: FURL + "/api/addnotice",
                data: { json_: jsonData },
                success: function(jResult) {
                    //console.log(jResult);
                    if (jResult.status == "success") {
                        window.location.replace( FURL +'/board/notice');
                    } else if (jResult.status == "logout") {
                        window.location.replace( FURL +'/');
                    } else if (jResult.status == "fail") {
                        alert("저장이 실패되었습니다.");
                    }
                },
                error: function(request, status, error) {
                    //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
                }

            });
        }


    });


    $("#notice-cancel-btn-id").click(function() {
        window.location.replace( FURL +'/board/notice');
    });

}