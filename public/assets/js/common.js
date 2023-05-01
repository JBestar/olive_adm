var logged = getCookie("logged");
if (logged != "yes") {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/logout",
        success: function(jResult) {
            location.reload();
        },
        error: function(request, status, error) {}
    });
}

$(document).ready(function() {
    $(".english_s").keyup(function(event) {
        if (!(event.keyCode >= 37 && event.keyCode <= 40)) {
            var inputVal = $(this).val();
            $(this).val(inputVal.replace(/[^a-z0-9~!@#$%^&*_:;,.=+-]/gi, ''));
        }
    });
});


const LEVEL_ADMIN = 100;
const LEVEL_COMPANY = 99;
const LEVEL_AGENCY = 98;
const LEVEL_EMPLOYEE = 97;
const LEVEL_MARKET = 96;
const LEVEL_MIN = 1;

function getMemberLevelString(nLevel, bNeed=false) {
    if(mLevelType !== undefined && mLevelType == 1){
        if (nLevel >= LEVEL_ADMIN)
            return "관리자";
        else if (nLevel == LEVEL_COMPANY)
            return "본사";
        else if (nLevel == LEVEL_AGENCY)
            return "부본";
        else if (nLevel == LEVEL_EMPLOYEE)
            return "총판";
        else if (nLevel == LEVEL_MARKET)
            return "매장";
        else 
            return (LEVEL_MARKET-nLevel)+"레벨";
    } else if(mLevelType !== undefined && mLevelType == 2){
        if (nLevel >= LEVEL_ADMIN)
             return "관리자";
        else
            return (LEVEL_ADMIN-nLevel)+"레벨";
    }  else if(mLevelType !== undefined && mLevelType == 3){
        if(bNeed){
            if (nLevel >= LEVEL_ADMIN)
                return "관리자";
            else if (nLevel == LEVEL_COMPANY)
                return "부본";
            else if (nLevel == LEVEL_AGENCY)
                return "총판";
            else if (nLevel == LEVEL_EMPLOYEE)
                return "매장";
        } else {
            if (nLevel >= LEVEL_ADMIN)
                return "관리자";
            else return null;
        }
        
    } else {
        if (nLevel >= LEVEL_ADMIN)
            return "관리자";
        else if (nLevel == LEVEL_COMPANY)
            return "부본";
        else if (nLevel == LEVEL_AGENCY)
            return "총판";
        else if (nLevel == LEVEL_EMPLOYEE)
            return "매장";
    }
    
    return null;
}


function setCookie(name, value, expiredays) {

    if (expiredays) {
        var date = new Date();
        date.setTime(date.getTime() + (expiredays * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
    } else {
        var expires = "; expires=0";
    }

    document.cookie = name + "=" + value + expires + "; path=/";

}


function getCookie(name) {

    var cName = name + "=";
    var x = 0;
    while (x <= document.cookie.length) {
        var y = (x + cName.length);
        if (document.cookie.substring(x, y) == cName) {
            if ((endOfCookie = document.cookie.indexOf(";", y)) == -1)
                endOfCookie = document.cookie.length;
            return unescape(document.cookie.substring(y, endOfCookie));
        }

        x = document.cookie.indexOf(" ", x) + 1;
        if (x == 0)
            break;
    }
    return "";
}

function setNavBarElement() {
    var activeGame = $(".sub-navbar").attr("value");
    // console.log("activeGame:" + activeGame);
    if (typeof activeGame !== typeof undefined && activeGame !== false) {
        var navbarlist = $(".sub-navbar-a");
        // console.log("activeGame:" + activeGame + "navbar length:" + navbarlist.length);
        for (var i = 0; i < navbarlist.length; i++) {
            // console.log("activeGame:" + activeGame + "navbarText:" + $(navbarlist[i]).text());
            if (activeGame == $(navbarlist[i]).text()) {
                $(navbarlist[i]).attr("class", "sub-navbar-a active");
            }
        }
    }
}

function showAlert(txt){
    Swal.fire({
        // position: 'top',
        title: txt,
        confirmButtonText: '닫기',
    });
}

function showConfirm(txt, callFunc = null){
    
    Swal.fire({
        title: txt,
        showCancelButton: true,
        confirmButtonText: '확인',
        cancelButtonText: '취소',
    }).then((result) => {
        if (result.value == true) {
            if(callFunc != null){
                callFunc();
            }
        } 
    })
}


function goResultPage(){
    if(mGameId == 1){
        window.location.replace( FURL +'/result/pbresult');
    } else if(mGameId == 2){
        window.location.replace( FURL +'/result/psresult');
    } else if(mGameId == 5){
        window.location.replace( FURL +'/result/bbresult');
    } else if(mGameId == 6){
        window.location.replace( FURL +'/result/bsresult');
    } else if(mGameId == 9){
        window.location.replace( FURL +'/result/e5result');
    } else if(mGameId == 10){
        window.location.replace( FURL +'/result/e3result');
    } else if(mGameId == 11){
        window.location.replace( FURL +'/result/c5result');
    } else if(mGameId == 12){
        window.location.replace( FURL +'/result/c3result');
    }
}


function getEvolSide(side) {
    let strSide = '';
    switch(side){
        case "Player": strSide = "<span class='pb-home-odd-span'>플</span>"; break;
        case "Banker": strSide = "<span class = 'pb-home-even-span'>뱅</span>"; break;
        case "Tie": strSide = "<span class = 'pb-home-mid-span'>타이</span>"; break;
        case "Betting.Player": strSide = "<div class='pb-home-odd-span'>플</div>"; break;
        case "Betting.Banker": strSide = "<span class = 'pb-home-even-span'>뱅</span>"; break;
        case "Betting.Tie": strSide = "<span class = 'pb-home-mid-span'>타이</span>"; break;
        default:break;
    }
    return strSide;
}