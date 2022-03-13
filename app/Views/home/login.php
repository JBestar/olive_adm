<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta content="blendTrans(Duration=0.0)" http-equiv="Page-Enter">
    <meta content="blendTrans(Duration=0.0)" http-equiv="Page-Exit">

    <link rel="shortcut icon" href="/favicon.ico">

    <!-- CSS  -->
    <link rel="stylesheet" href="/assets/css/reset.css">
    <link rel="stylesheet" href="/assets/css/swiper.min.css">
    <link rel="stylesheet" href="/assets/css/lib/jquery-ui.css">
    <link rel="stylesheet" href="/assets/css/common.css">
    <link rel="stylesheet" href="/assets/css/content.css">
    <link rel="stylesheet" href="/assets/css/login.css">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/game.css">
    <link rel="stylesheet" href="/assets/css/theme.css">
    <script src="/assets/js/lib/jquery-3.3.1.min.js"></script>
    <script src="/assets/js/lib/jquery-ui.min.js"></script>
    <script src="/assets/js/crypto-js.js"></script>

    <title>Olive</title>
</head>

<!-- <body oncontextmenu="return false" ondragstart="return false" onselectstart="return false"> -->
<body>
    <!-- CSRF token -->
    <input type="hidden" class="txt_csrfname" name="rftn" value="3b45c58808a2a8b49b47865d348e3902">

    <div class="alert_wrap basic_alert" id="basic_alert">
        <div class="alert_bot">
            <p class="question_ico" id="alert_content"></p>
            <div class="btn_wrap">
                <a onclick="location.href='javascript:okAlert()';" class="btn btn_red" id="basic_ok" style="cursor: pointer">확인</a>
                <a onclick="location.href='javascript:closeAlert()';" class="btn" style="cursor: pointer">취소</a>
            </div>
        </div>
    </div>

    <div class="alert_wrap confirm_alert" id="confirm_alert">
        <div class="alert_bot">
            <p class="question_ico" id="alert_content"></p>
            <div class="btn_wrap">
                <a onclick="location.href='javascript:closeAlert()';" class="btn" id="confirm_ok" style="cursor: pointer">확인</a>
            </div>
        </div>
    </div>
    <!--//alert_wrap -->

    <div class="alert_wrap time_alert" id="time_alert">
        <div class="alert_bot">
            <p class="warning_ico"></p>
        </div>
    </div>
    <!--//time_alert -->

    <div class="alert_bg"></div>

    <div id="wrap" class="users_wrap">

        <div class="login_wrap">
            <div class="login_area">
                <div class="login_logo"><img src="/assets/img/login/login_logo.png"></div>
                <div class="login_con">
                    <div class="id_area">
                        <span class="id_ico"></span>
                        <input type="text" placeholder="ID" id="user_id" class="english_p">
                    </div>
                    <div class="password_area">
                        <span class="password_ico"></span>
                        <input type="password" placeholder="PASSWORD" id="user_pw" class="english_s">
                    </div>
                    <button type="button" class="submit_btn" value="LOGIN" id="btnLogin">로그인</button>
                    <p class="join_txt">
                        <button type="button" class="join_btn">회원가입</button>
                    </p>
                </div>
            </div>
            <!--//login_area -->

            <div class="join_area step01">
                <p class="txt">추천인 코드를 입력하세요.</p>
                <input type="text" name="proposer" id="proposer" class="english_p">
                <div class="btn_wrap">
                    <button type="button" class="prev_btn" value="BACK" title="BACK">BACK</button>
                    <button type="button" class="next_btn join01_btn" value="START" title="START" id="btnCode">START</button>
                </div>
                <button type="button" class="join_close_btn"><span class="ir_pm">닫기</span></button>
            </div>

            <div class="join_area step02">
                <ul>
                    <li>
                        <p class="tit">아이디<span class="desc" id="id_desc">※ 영문 5~12 O, 특수문자 X</span></p>
                        <input type="text" name="input_id" id="input_id" class="english" placeholder="아이디는 영문만 입력 가능합니다.">
                    </li>
                    <li>
                        <p class="tit">닉네임<span class="desc" id="nickname_desc">※ 닉네임은 한글과숫자만 가능합니다. 2~12</span></p>
                        <input type="text" name="input_nickname" id="input_nickname" class="korean" placeholder="닉네임은 한글과숫자만 입력 가능합니다.">
                    </li>
                    <li>
                        <p class="tit">비밀번호<span class="desc">※ 영어와 숫자 조합 4글자 이상</span></p>
                        <input type="text" name="input_pw" id="input_pw" class="english_s" autocomplete="off">
                    </li>
                    <li>
                        <p class="tit">비밀번호 확인</p>
                        <input type="text" name="input_pw_check" id="input_pw_check" class="english_s" autocomplete="off" style="-webkit-text-security: disc;">
                    </li>
                </ul>
                <div class="btn_wrap">
                    <button type="button" class="prev_btn" value="BACK" title="BACK">BACK</button>
                    <button type="button" class="next_btn" value="NEXT" title="NEXT" id="btn_next">NEXT</button>
                </div>
                <button type="button" class="join_close_btn"><span class="ir_pm">닫기</span></button>
            </div>

            <div class="join_area step03">
                <ul>
                    <li>
                        <p class="tit">이름<span class="desc">※ 가입자명과 예금주명이 동일하게 사용됩니다.</span></p>
                        <input type="text" name="user_name" id="user_name">
                    </li>
                    <li>
                        <p class="tit">연락처</p>
                        <input type="number" pattern="[0-9]*" inputmode="numeric" min="0" onkeydown="checkNumber(event)" style="ime-mode:disabled;" name="user_phone" id="user_phone" placeholder="-없이 숫자만 입력하세요">
                    </li>
                    <li>
                        <p class="tit">은행명</p>
                        <input type="text" name="bank_name" id="bank_name">
                    </li>
                    <li>
                        <p class="tit">계좌번호</p>
                        <input type="number" pattern="[0-9]*" inputmode="numeric" min="0" onkeydown="checkNumber(event)" style="ime-mode:disabled;" name="bank_account" id="bank_account" placeholder="-없이 숫자만 입력하세요">
                    </li>
                    <li>
                        <p class="tit">환전 비밀번호</p>
                        <input type="text" name="bank_pw" id="bank_pw" autocomplete="off" style="-webkit-text-security: disc;">
                    </li>
                </ul>
                <div class="btn_wrap">
                    <button type="button" class="prev_btn" value="BACK" title="BACK">BACK</button>
                    <button type="button" class="next_btn" value="NEXT" title="NEXT" id="btn_done">NEXT</button>
                </div>
                <button type="button" class="join_close_btn"><span class="ir_pm">닫기</span></button>
            </div>

            <div class="join_area step04">
                <p class="txt">회원가입이 완료되었습니다.</p>
                <p class="desc"><span class="name" id="name_complete">홍길동</span>님의 회원가입을 환영합니다.<br> 로그인 후 사이트를 이용하실 수 있습니다.<br> 감사합니다.
                </p>
                <button type="button" class="next_btn" value="LOGIN NOW" id="btn_login">LOGIN NOW</button>
                <button type="button" class="join_close_btn"><span class="ir_pm">닫기</span></button>
            </div>
        </div>
        <!-- login_wrap -->

    </div>

    <script src="/assets/js/login-js.js"></script>

</body>

</html>