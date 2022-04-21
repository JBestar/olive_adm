var logged = getCookie("logged");
if (logged != "yes") {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/api/logout",
        success: function(jResult) {
            location.reload();
        },
        error: function(request, status, error) {}
    });
}

const APP_LUCKYONE = "Luckyone";
const APP_ONESTAR = "Onestar";
const APP_SKY = "Sky";


const LEVEL_ADMIN = 100;
const LEVEL_COMPANY = 99;
const LEVEL_AGENCY = 98;
const LEVEL_EMPLOYEE = 97;
const LEVEL_MIN = 1;