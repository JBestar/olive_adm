        // // 타이머 팝업
        function toast(msg, duration = 2000) {

            $('.warning_ico').html(msg);
            $('.alert_bg').show();
            $('#time_alert').show(500, 'easeInOutBack');
            setTimeout(function() {
                $('.alert_bg').fadeOut();
                $('.time_alert').fadeOut();
                return true;
            }, duration);
        }

        // 영어/숫자만 입력
        $(".english").keyup(function(event) {
            if (!(event.keyCode >= 37 && event.keyCode <= 40)) {
                var inputVal = $(this).val();

                var regex = /[^a-z0-9]/gi;
                if (regex.test(inputVal)) {
                    $(this).val(inputVal.replace(/[^a-z0-9]/gi, ''));
                    if ($(this).attr('id') === 'user_id') {
                        $('#login_alert').text('영어와 숫자만 입력가능합니다.');
                    } else if ($(this).attr('id') === 'proposer') {
                        $('#prop_alert').text('영어와 숫자만 입력가능합니다.');
                    } else if ($(this).attr('id') === 'login_email_m') {
                        $('.login_alert').show();
                    } else if ($(this).attr('id') === 'login_email') {
                        confirmAlert('영어와 숫자만 입력가능합니다.');
                    }
                } else {
                    $(this).val(inputVal.replace(/[^a-z0-9]/gi, ''));
                }
            }
        });

        $(".english_p").keyup(function(event) {
            if (!(event.keyCode >= 37 && event.keyCode <= 40)) {
                var inputVal = $(this).val();
                var first_len = inputVal.length;
                var reVal = inputVal.replace(/[^a-z0-9]/gi, '');
                var re_len = reVal.length;

                if (first_len !== re_len) {
                    $(this).val(reVal);
                    if (pop_open !== 'true') {
                        confirmAlert('영어와 숫자만 입력 가능합니다', 'handleWindowsKeyboard()')
                    }
                }
            }
        });

        let handleWindowsKeyboardTimer;
        /**
         * in windows, the "enter" key will trigger Korean character one more time.
         * it causes the alert to show 2 times.
         * this function solve that problem.
         *
         */
        function handleWindowsKeyboard() {
            pop_open = 'true';

            window.clearTimeout(handleWindowsKeyboardTimer);
            handleWindowsKeyboardTimer = window.setTimeout(function() {
                pop_open = 'false';
            }, 200);
        }

        // 영어/숫자/특수 키만 입력
        $(".english_s").keyup(function(event) {
            if (!(event.keyCode >= 37 && event.keyCode <= 40)) {
                var inputVal = $(this).val();
                //console.log(inputVal);
                $(this).val(inputVal.replace(/[^a-z0-9~!@#$%^&*_:;,.=+-]/gi, ''));
            }
        });

        // 한글만 입력
        $(".korean").keyup(function(event) {
            if (!(event.keyCode >= 37 && event.keyCode <= 40)) {
                var inputVal = $(this).val();

                // 한글 영문인경우 아래 주석 사용
                //$(this).val(inputVal.replace(/[^(ㄱ-힣a-zA-Z)]/gi, ''));
                //$(this).val(inputVal.replace(/[^(ㄱ-힣)]/gi, ''));
                $(this).val(inputVal.replace(/[^(ㄱ-힣0-9)]/gi, ''));
            }
        });

        function aes_encrypt(key, iv, data) {
            var keyBytes = CryptoJS.enc.Hex.parse(key);
            var ivBytes = CryptoJS.enc.Hex.parse(iv);

            var encrypted_str = CryptoJS.AES.encrypt(data, keyBytes, {
                iv: ivBytes,
                padding: CryptoJS.pad.ZeroPadding
            }).ciphertext.toString(CryptoJS.enc.Base64);

            return encrypted_str;
        }

        // 금액 , 표시
        function numberWithCommas(x) {
            if (isNaN(x)) {
                return '0'
            }
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function removeComma(str) {
            n = parseInt(str.replace(/,/g, ""));
            return n;
        }

        function commas(t) {

            var x = t.value;
            x = x.replace(/,/gi, '');

            var regexp = /^[0-9]*$/;
            if (!regexp.test(x)) {
                $(t).val("");
                alert("숫자만 입력 가능합니다.");
            } else {
                x = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $(t).val(x);
            }
        }


        function isMobile() {    
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        }

        // 02d
        function pad(n, width) {
            n = n + '';
            return n.length >= width ? n : new Array(width - n.length + 1).join('0') + n;
        }

        // html 태그 제거
        function no_tag(tmp) {
            if (tmp == null)
                return "";
            tmp = tmp.replace(/<(\/?)p>/gi, ""); //p태그 제거
            tmp = tmp.replace(/(\n|\r\n)/g, ' ');
            tmp = tmp.replace(/<br>/g, ' ');

            return tmp;
        }

        function no_all_tag(tmp) {
            tmp = tmp.replace(/(<([^>]+)>)/ig, "");
            return tmp;
        }

        function number_format(number) {
            // number=number.replace(/\,/g,"");
            // nArr = String(number).split('').join(',').split('');
            // for( var i=nArr.length-1, j=1; i>=0; i--, j++)  if( j%6 != 0 && j%2 == 0) nArr[i] = '';

            // return nArr.join('');
            if (isNaN(number)) {
                return '0'
            }
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function double_quote_to_quotes(tmp) {
            tmp = tmp.replace(/"/g, /'/g);

            return tmp;
        }

        function checkNumber(event) {

            event = event || window.event;
            var keyID = (event.which) ? event.which : event.keyCode;
            if ((keyID >= 48 && keyID <= 57) || (keyID >= 96 && keyID <= 105) || keyID == 8 || keyID == 46 || keyID == 37 || keyID == 39) {
                console.log("checkNumber 1")
                return;
            } else {
                console.log("checkNumber 2")
                    // return false;
                event.target.value = event.target.value.replace(/[^0-9]/g, "");
            }
        }

        // 한글 입력 방지
        function removeChar(event) {

            event = event || window.event;
            var keyID = (event.which) ? event.which : event.keyCode;
            if (keyID == 8 || keyID == 46 || keyID == 37 || keyID == 39)
                return;
            else
                event.target.value = event.target.value.replace(/[^0-9]/g, "");

            // 미니게임 하단
            if ($("#bet_money").is(":focus")) {

                // console.error('11111');

                var bet_money = $('#bet_money').val().replace(/,/g, '');
                var match_money = parseInt(bet_money * select_rate);
                if (isNaN(match_money)) {
                    match_money = 0;
                }
                $('#hit_money_input').val(numberWithCommas(match_money));
                return;
            }

            // PC 카트
            if ($("#input_money").is(":focus")) {

                // console.error('22222');

                var bet_money = parseInt($("input#input_money").val().replace(/,/g, ''));
                var total_rate = parseFloat($("#total_rate").text());

                var reward = bet_money * total_rate;
                reward = Math.floor(reward / 1) * 1; // 소수점 버리고 1원단위절사

                $("#hit_money").text(numberWithCommas(reward));
            }

            // 모바일 카트
            if ($("#input_money_m").is(":focus")) {

                // console.error('33333');

                var bet_money = parseInt($("input#input_money_m").val().replace(/,/g, ''));
                var total_rate = parseFloat($("#total_rate_m").text());

                var reward = bet_money * total_rate;
                reward = Math.floor(reward / 1) * 1; // 소수점 버리고 1원단위절사

                $("#hit_money_m").text(numberWithCommas(reward));
            }
        }

        // 빈값 check
        function is_empty(value) {
            if (value == "" || value == null || value == undefined || (value != null && typeof value == "object" && !Object.keys(value).length)) {
                return true
            } else {
                return false
            }
        }
        // 쿠키 사용하기
        function setCookie(cookie_name, value, days) {
            var exdate = new Date();
            exdate.setDate(exdate.getDate() + days);
            // 설정 일수만큼 현재시간에 만료값으로 지정

            var cookie_value = escape(value) + ((days == null) ? '' : '; expires=' + exdate.toUTCString());
            document.cookie = cookie_name + '=' + cookie_value;
        }

        function getCookie(cookie_name) {
            var x, y;
            var val = document.cookie.split(';');

            for (var i = 0; i < val.length; i++) {
                x = val[i].substr(0, val[i].indexOf('='));
                y = val[i].substr(val[i].indexOf('=') + 1);
                x = x.replace(/^\s+|\s+$/g, ''); // 앞과 뒤 공배 제거
                if (x == cookie_name) {
                    return unescape(y); // unescape로 디코딩
                }
            }
        }

        function byte_length(str) {

            var count = 0;
            var ch = '';

            for (var i = 0; i < str.length; i++) {
                ch = str.charAt(i);
                if (escape(ch).length == 6) {
                    count++;
                }
                count++;
            }
            return count;
        }

        function reloadPage() {
            setTimeout(() => {
                window.location.reload()
            }, 1000);
        }

        function updateQueryStringParameter(uri, key, value) {
            var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
            var separator = uri.indexOf('?') !== -1 ? "&" : "?";
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + "=" + value + '$2');
            } else {
                return uri + separator + key + "=" + value;
            }
        }


        function setFocus(obj) {
            if (obj) {
                setTimeout(() => {
                    obj.focus()
                }, 1);
            }
        }

        function isEmptyObj(obj) {
            // 객체 타입체크
            if (obj.constructor !== Object) {
                return false;
            }

            // property 체크
            for (let prop in obj) {
                if (obj.hasOwnProperty(prop)) {
                    return false;
                }
            }

            return true;
        }

        function ObjKeys(obj) {
            return Object.keys(obj).map(function(key) {
                return key;
            });
        }


        history.replaceState({
            data: 'replace'
        }, '', '/');

        const PROPOSER_TYPE_NONE = 0;
        const PROPOSER_TYPE_PARTNER = 1;
        const PROPOSER_TYPE_MEMBER = 2;
        const PROPOSER_TYPE_P_OR_M = 3;
        const PROPOSER_TYPE_P_AND_M = 4;

        // 초기값
        var show_view = "login";
        var chat_open = 'false';
        var pop_open = 'false';
        var proposer_type = PROPOSER_TYPE_P_OR_M;
        var partner_use = '0';
        var aes_key = null;
        var aes_iv = null;
        var op_id = null;



        $("#user_id").focus();

        //////////////////////////////////////////////////
        // 공통 
        //////////////////////////////////////////////////

        // Alert 버튼
        function confirmAlert(msg, callback = null) {
            pop_open = 'true';
            if (callback != null) {
                $('#confirm_alert').attr('callback', callback);
            }
            $('.question_ico').html(msg);
            $('.alert_bg').show();
            $('#confirm_alert').show(500, 'easeInOutBack');
            $('#confirm_ok').focus();
        }

        // Alert 닫기
        function closeAlert() {
            pop_open = 'false';
            $('.alert_bg').hide();
            $('.alert_wrap').hide();

            var callbackName = $('#confirm_alert').attr('callback');
            if (callbackName) {
                eval(callbackName);
            }
        }

        function setAesData(key, iv, type, p_use, open_id) {
            aes_key = key;
            aes_iv = iv;
            proposer_type = type;
            partner_use = p_use;
            op_id = open_id;
        }

        // Close 버튼 
        $('.join_close_btn').on('click', function(e) {
            e.preventDefault();

            var type_check = $(this).val();

            if (type_check == 'script_type2') {
                $('.join_wrap').slideUp();
                $('.login_wrap').fadeIn().slideDown();
            } else {
                $(this).parent().hide();
                $('.login_area').show();
            }

            show_view = "login";
            reset_input();
            $('#user_id').focus();
        });

        // 입력 필드 삭제 
        function reset_input() {
            $("#user_id").val('');
            $("#user_pw").val('');
            $("#proposer").val('');
            $("#input_id").val('');
            $("#input_nickname").val('');
            $("#input_pw").val('');
            $("#input_pw_check").val('');
            $("#user_name").val('');
            $("#user_phone").val('');
            $("#bank_name").val('');
            $("#bank_account").val('');
            $("#bank_pw").val('');
            if ($("#security_id").length > 0) {
                $("#security_id").val('');
            }
        }

        // 엔터키 처리 
        $(document).keypress(function(e) {
            if (e.which == 13 || e.keyCode == 13) {

                if (pop_open == 'true') {
                    $("#confirm_ok").click();
                } else if (chat_open == 'true') {
                    $("#send_customer").click();
                } else if (show_view == "login") {
                    $("#btnLogin").click();
                } else if (show_view == "proposer") {
                    if ($("#proposer").is(':focus')) {
                        $(".step01 .join01_btn").click()
                    } else if ($(".step01 .join01_btn").is(':focus')) {
                        $(".step01 .join01_btn").click()
                    } else {
                        $(".step01 .prev_btn").click()
                    }
                } else if (show_view == "user_info") {
                    $("#btn_next").click();
                } else if (show_view == "account") {
                    $("#btn_done").click();
                } else if (show_view == "done") {
                    $("#btn_login").click();
                }
            }
        });


        //////////////////////////////////////////////////
        // 로그인 화면 
        //////////////////////////////////////////////////

        // 로그인 버튼 
        $("#btnLogin").click(function(e) {
            e.preventDefault();

            var user_id = $("#user_id").val();
            var user_pw = $("#user_pw").val();

            if (user_id == "") {
                confirmAlert('ID를 입력해 주세요.');
                return false;
            }

            if (user_pw == "") {
                confirmAlert('패스워드를 입력해 주세요.');
                return false;
            }

            if ($("#security_id").length > 0) {
                var security_id = $("#security_id").val();
                if (security_id == "") {
                    confirmAlert('보안문자를 입력해 주세요.');
                    $("#security_id").focus();
                    return false;
                }
            }

            var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
            var csrfHash = $('.txt_csrfname').val(); // CSRF hash

            var data = '';

            if ($("#security_id").length > 0) {
                data = JSON.stringify({
                    'user_id': user_id,
                    'user_pw': user_pw,
                    'security_id': security_id
                });
            } else {
                data = JSON.stringify({
                    'user_id': user_id,
                    'user_pw': user_pw
                });
            }

            var encrypted_data = aes_encrypt(aes_key, aes_iv, data);
            if (encrypted_data == "" || encrypted_data == null) {
                confirmAlert('로그인에 실패하였습니다.');
                return false;
            }
            var auth_val = CryptoJS.SHA256(user_id + ":" + user_pw).toString(CryptoJS.enc.Hex);

            $.ajax({
                type: 'POST',
                url: '/login',
                dataType: 'json',
                data: {
                    'login_info': encrypted_data,
                    [csrfName]: csrfHash
                },
                beforeSend: function(xhr) {
                    if (auth_val.length > 0) {
                        xhr.setRequestHeader("Authentication", auth_val);
                    }
                },
                success: function(data) {
                    // Update CSRF hash
                    $('.txt_csrfname').val(data.token);

                    if ((data.result == 'success') || (data.result == true)) {
                        window.location.href = "/home";
                    } else {
                        if (data.msg.indexOf("보안문자") != -1) {
                            confirmAlert(data.msg, 'reloadPage()');
                        } else {
                            confirmAlert(data.msg, 'reloadPage()');
                        }
                    }
                },
                error: function(request, status, error) {

                    confirmAlert("관리자에게 문의 바랍니다.\n" + request.status, 'reloadPage()');
                }
            });
        });

        function reloadPage() {
            window.location.reload();
        }

        //////////////////////////////////////////////////
        //  회원가입
        //////////////////////////////////////////////////
        $('.join_btn').on('click', function(e) {
            e.preventDefault();

            show_view = "proposer";

            var type_check = $(this).val();

            if (type_check == 'script_type2') {
                $('.login_wrap').slideUp();
                $('.join01').fadeIn().slideDown();
            } else {
                $('.login_area').hide();
                if (proposer_type == PROPOSER_TYPE_NONE || partner_use == '1') {
                    if (op_id && op_id != "") {
                        $("#proposer").val(op_id);
                    }
                    $('.step02').show();
                } else {
                    $('.step01').show();
                }
            }

            $('#proposer').focus();
        });

        //////////////////////////////////////////////////
        //  STEP1 : 추천인 코드 입력
        //////////////////////////////////////////////////

        // 추천인 코드 : 이전 
        $('.step01 .prev_btn').on('click', function(e) {
            e.preventDefault();

            show_view = "login";

            $('.step01').hide();
            $('.login_area').show();
            $('#user_id').focus();

            reset_input();
        });

        // 추천인 코드 : START
        $('.step01 .join01_btn').on('click', function(e) {
            e.preventDefault();

            var proposer = $("#proposer").val();
            var type_check = $(this).val();

            if (proposer == "") {
                confirmAlert('추천인 코드를 입력해 주세요.');
                return;
            }

            var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
            var csrfHash = $('.txt_csrfname').val(); // CSRF hash

            $.ajax({
                type: 'POST',
                url: '/checkProposer',
                dataType: 'json',
                data: {
                    'proposer': proposer,
                    [csrfName]: csrfHash
                },
                success: function(data) {
                    // Update CSRF hash
                    $('.txt_csrfname').val(data.token);

                    if ((data.result == 'success') || (data.result == true)) {
                        show_view = "user_info";

                        if (type_check == 'script_type2') {
                            $('.join_wrap').slideUp();
                            $('.join02').fadeIn().slideDown();
                        } else {
                            $('.step01').hide();
                            $('.step02').show();
                        }

                        $('#input_id').focus();
                    } else {
                        confirmAlert(data.msg);
                    }
                },
                error: function() {
                    confirmAlert("관리자에게 문의 바랍니다.");
                }
            });
        });


        //////////////////////////////////////////////////
        //  STEP2 : 아이디, 닉네임, 비밀번호
        //////////////////////////////////////////////////

        // 아이디 텍스트 입력 
        $("#input_id").focusout(function() {
            var input_id = $("#input_id").val();

            if (input_id.length < 5) {
                $("#id_desc").html("<span style='color:red'>아이디를 입력해 주세요.(5~12)</span>");
                return;
            }

            var data = JSON.stringify({
                'member_id': input_id
            });
            var encrypted_data = aes_encrypt(aes_key, aes_iv, data);
            if (encrypted_data == "" || encrypted_data == null) {
                $("#id_desc").html("<span style='color:red'>아이디를 입력해 주세요.(5~12)</span>");
                return;
            }

            var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
            var csrfHash = $('.txt_csrfname').val(); // CSRF hash

            $.ajax({
                type: 'POST',
                url: '/checkAccountInformation',
                dataType: 'json',
                data: {
                    'user_info': encrypted_data,
                    [csrfName]: csrfHash
                },
                success: function(data) {
                    // Update CSRF hash
                    $('.txt_csrfname').val(data.token);

                    if ((data.result == 'success') || (data.result == true)) {
                        $("#id_desc").text('사용 가능한 아이디 입니다.');
                    } else {
                        $("#id_desc").html("<span style='color:red'>" + data.msg + "</span>");
                    }
                },
                error: function() {
                    $("#id_desc").html("<span style='color:red'>관리자에게 문의 바랍니다.</span>");
                }
            });
        });

        // 닉네임 텍스트 입력 
        $("#input_nickname").focusout(function() {
            var input_nickname = $("#input_nickname").val();

            if (input_nickname.length < 2) {
                $("#nickname_desc").html("<span style='color:red'>닉네임을 입력해 주세요.(2~12)</span>");
                return;
            }

            var data = JSON.stringify({
                'nickname': input_nickname
            });
            var encrypted_data = aes_encrypt(aes_key, aes_iv, data);
            if (encrypted_data == "" || encrypted_data == null) {
                $("#nickname_desc").html("<span style='color:red'>닉네임을 입력해 주세요.(2~12)</span>");
                return;
            }

            var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
            var csrfHash = $('.txt_csrfname').val(); // CSRF hash

            $.ajax({
                type: 'POST',
                url: '/checkAccountInformation',
                dataType: 'json',
                data: {
                    'user_info': encrypted_data,
                    [csrfName]: csrfHash
                },
                success: function(data) {
                    // Update CSRF hash
                    $('.txt_csrfname').val(data.token);

                    if ((data.result == 'success') || (data.result == true)) {
                        $("#nickname_desc").text("사용 가능한 닉네임입니다.");
                    } else {
                        $("#nickname_desc").html("<span style='color:red'>" + data.msg + "</span>");
                    }
                },
                error: function() {
                    $("#nickname_desc").html("<span style='color:red'>관리자에게 문의 바랍니다.</span>");
                }
            });
        });

        // 아이디, 닉네임, 비밀 번호 입력 : 이전 
        $('.step02 .prev_btn').on('click', function(e) {
            e.preventDefault();
            show_view = "proposer";
            var type_check = $(this).val();

            if (type_check == 'script_type2') {
                $('.join_wrap').slideUp();
                $('.join01').fadeIn().slideDown();
            } else {
                $('.step02').hide();
                if (proposer_type == PROPOSER_TYPE_NONE || partner_use == '1') {
                    show_view = "login";

                    $('.step01').hide();
                    $('.login_area').show();
                    $('#user_id').focus();

                    reset_input(true);
                } else {
                    $('.step01').show();
                }
            }
        });

        // 아이디, 닉네임, 비밀 번호 입력 : 다음
        $('.step02 .next_btn').on('click', function(e) {
            e.preventDefault();

            var input_id = $("#input_id").val();
            var input_nickname = $("#input_nickname").val();
            var input_pw = $("#input_pw").val();
            var input_pw_check = $("#input_pw_check").val();
            var type_check = $(this).val();

            if (input_id.length < 5) {
                confirmAlert("아이디를 입력해 주세요.(5~12)");
                return;
            }

            if (input_nickname.length < 2) {
                confirmAlert("닉네임을 입력해 주세요.(2~12)");
                return;
            }

            if (input_pw != input_pw_check) {
                confirmAlert("비밀번호가 일치하지 않습니다. 비밀번호를 확인해 주세요.");
                return;
            }

            if (input_pw.length < 4) {
                confirmAlert("비밀번호는 4자리 이상 입력하세요.");
                return;
            }
            if (input_pw.length > 10) {
                confirmAlert("비밀번호는 10자리 아하로 입력하세요.");
                return;
            }

            var data = JSON.stringify({
                'member_id': input_id,
                'nickname': input_nickname
            });
            var encrypted_data = aes_encrypt(aes_key, aes_iv, data);

            if (encrypted_data == "" || encrypted_data == null) {
                confirmAlert('입력 정보를 다시 확인해 주세요.');
                return false;
            }

            var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
            var csrfHash = $('.txt_csrfname').val(); // CSRF hash

            $.ajax({
                type: 'POST',
                url: '/checkAccountInformation',
                dataType: 'json',
                data: {
                    'user_info': encrypted_data,
                    [csrfName]: csrfHash
                },
                success: function(data) {
                    // Update CSRF hash
                    $('.txt_csrfname').val(data.token);

                    if ((data.result == 'success') || (data.result == true)) {
                        show_view = "account";

                        if (type_check == 'script_type2') {
                            $('.join_wrap').slideUp();
                            $('.join03').fadeIn().slideDown();
                        } else {
                            $('.step02').hide();
                            $('.step03').show();
                        }

                        $('#user_name').focus();
                    } else {
                        confirmAlert(data.msg);
                    }
                },
                error: function() {
                    confirmAlert("관리자에게 문의 바랍니다. ");
                }
            });
        });

        //////////////////////////////////////////////////
        //  STEP3 : 이름, 연락처, 은행명...
        //////////////////////////////////////////////////

        // 이름, 연락처, 은행명 등 입력 : 이전 
        $('.step03 .prev_btn').on('click', function(e) {
            e.preventDefault();

            show_view = "user_info";
            var type_check = $(this).val();

            if (type_check == 'script_type2') {
                $('.join_wrap').slideUp();
                $('.join02').fadeIn().slideDown();
            } else {
                $('.step03').hide();
                $('.step02').show();
            }
        });

        // 이름, 연락처, 은행명 등 입력 : 다음 
        $('.step03 .next_btn').on('click', function(e) {
            e.preventDefault();

            var user_name = $("#user_name").val();
            var user_phone = $("#user_phone").val();
            var bank_name = $("#bank_name").val();
            var bank_account = $("#bank_account").val();
            var bank_pw = $("#bank_pw").val();
            var type_check = $(this).val();

            if (user_name.length < 1) {
                confirmAlert("이름(예금주)을 입력해 주세요.");
                return;
            }

            if (user_phone.length < 1) {
                confirmAlert("연락처를 입력해 주세요.");
                return;
            }

            if (bank_name.length < 1) {
                confirmAlert("은행명을 입력해 주세요.");
                return;
            }

            if (bank_account.length < 1) {
                confirmAlert("계좌번호를 입력해 주세요.");
                return;
            }

            if (bank_pw.length < 1) {
                confirmAlert("환전 비밀번호를 입력해 주세요.");
                return;
            }

            var check = /[ㄱ-ㅎ|ㅏ-ㅣ|가-힣]/;
            if (check.test(bank_pw)) {
                confirmAlert("환전 비밀번호는 한글을 포함할수 없습니다.");
                return;
            }

            var proposer = $("#proposer").val();
            var input_id = $("#input_id").val();
            var nickname = $("#input_nickname").val();
            var input_pw = $("#input_pw").val();

            var data = JSON.stringify({
                'proposer': proposer,
                'member_id': input_id,
                'nickname': nickname,
                'password': input_pw,
                'name': user_name,
                'contact': user_phone,
                'bank_name': bank_name,
                'account_number': bank_account,
                'refund_password': bank_pw
            });
            var encrypted_data = aes_encrypt(aes_key, aes_iv, data);
            if (encrypted_data == "" || encrypted_data == null) {
                confirmAlert('회원가입에 실패하였습니다. 관리자에게 문의 바랍니다.');
                return false;
            }
            var auth_val = CryptoJS.SHA256(input_id + ":" + input_pw).toString(CryptoJS.enc.Hex);

            var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
            var csrfHash = $('.txt_csrfname').val(); // CSRF hash

            $.ajax({
                type: 'POST',
                url: '/register',
                dataType: 'json',
                data: {
                    'user_info': encrypted_data,
                    [csrfName]: csrfHash
                },
                beforeSend: function(xhr) {
                    if (auth_val.length > 0) {
                        xhr.setRequestHeader("Authentication", auth_val);
                    }
                },
                success: function(data) {
                    // Update CSRF hash
                    $('.txt_csrfname').val(data.token);

                    if ((data.result == 'success') || (data.result == true)) {
                        show_view = "done";

                        if (type_check == 'script_type2') {
                            $('.join_wrap').slideUp();
                            $('.join04').fadeIn().slideDown();
                        } else {
                            $('.step03').hide();
                            $('.step04').show();
                        }

                        $('#name_complete').html(user_name);
                    } else {
                        confirmAlert(data.msg);
                    }
                },
                error: function() {
                    confirmAlert("관리자에게 문의 바랍니다. ");
                }
            });
        });


        //////////////////////////////////////////////////
        //  STEP4 : 가입 완료 
        //////////////////////////////////////////////////

        // 회원 가입 완료 
        $('.step04 .next_btn').on('click', function(e) {
            e.preventDefault();

            show_view = "login";
            var type_check = $(this).val();

            if (type_check == 'script_type2') {
                $('.join_wrap').slideUp();
                $('.login_wrap').fadeIn().slideDown();
            } else {
                $('.step04').hide();
                $('.login_area').show();
            }

            var user_id = $("#input_id").val();

            reset_input();
            $("#user_id").val(user_id);
            $('#user_pw').focus();
        });


        //////////////////////////////////////////////////
        //  고객센터 상담하기 
        //////////////////////////////////////////////////

        // 창 열기
        $('.live_chat_open_btn').on('click', function(e) {
            chat_open = 'true';

            e.preventDefault();
            $('.live_chat_open').slideDown();
            $('.live_chat_close').slideUp();
        });

        // 창 닫기
        $('.live_chat_close_btn').on('click', function(e) {
            chat_open = 'false';

            e.preventDefault();
            $('.live_chat_close').slideDown();
            $('.live_chat_open').slideUp();
        });

        $('#chat_form').submit(function() {

            var name = $('#chat_form').find('input[name="name"]').val();
            var phone = $('#chat_form').find('input[name="phone"]').val();
            var content = $('#chat_form').find('textarea[name="content"]').val();

            if (name.length == 0) {
                confirm2Alert('이름을 입력하세요', function() {
                    $('#chat_form').find('input[name="name"]').focus()
                })
                return false;
            }
            if (phone.length == 0) {
                confirm2Alert('번호를 입력하세요', function() {
                    $('#chat_form').find('input[name="phone"]').focus()
                })
                return false;
            }
            if (content.length == 0) {
                confirm2Alert('내용을 입력하세요', function() {
                    $('#chat_form').find('textarea[name="content"]').focus()
                })
                return false;
            }

            $.ajax({
                type: 'POST',
                url: '/nocustomer',
                dataType: "json",
                data: $('#chat_form').serialize(),
                success: function(data) {
                    if (data.result == 'success') {
                        confirmAlert('문의가 전달되었습니다.', 'reloadPage()')
                    } else {
                        confirmAlert(data.msg, 'reloadPage()')
                    }
                },
                error: function() {
                    confirmAlert('관리자에게 문의 바랍니다.', 'reloadPage()')
                }
            });

            return false;
        });

        function reloadPage() {
            window.location.reload()
        }

        function loadScript(url) {
            if (url == null || url == "") {
                return;
            }
            var script = document.createElement('script');
            script.src = url;
            document.head.appendChild(script);
        }

        // (function autoScript() {
        //     loadScript('https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js');    // include aes, sha256
        // }());

        $(function() {
            setAesData('8362cf2664b39e1d883b96e35b25aca47839ed56ba16076c8956e74564d79546', 'b859e2e6c9d4ceb49fe45d8638b16f49', '1', '0', '');
        });