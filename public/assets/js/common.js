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

const APPTYPE_0 = 0;
const APPTYPE_1 = 1;
const APPTYPE_2 = 2;
const APPTYPE_3 = 3;

const LEVEL_ADMIN = 100;
const LEVEL_COMPANY = 99;
const LEVEL_AGENCY = 98;
const LEVEL_EMPLOYEE = 97;
const LEVEL_MARKET = 96;
const LEVEL_MIN = 1;

function getMemberLevelString(nLevel) {
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