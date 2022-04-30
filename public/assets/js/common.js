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
const LEVEL_MIN = 1;