<?php

//--------------------------------------------------------------------
// App Namespace
//--------------------------------------------------------------------
// This defines the default Namespace that is used throughout
// CodeIgniter to refer to the Application directory. Change
// this constant to change the namespace that all application
// classes should use.
//
// NOTE: changing this will require manually modifying the
// existing namespaces of App\* namespaced-classes.
//
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'MirAdm');

/*
|--------------------------------------------------------------------------
| Composer Path
|--------------------------------------------------------------------------
|
| The path that Composer's autoload file is expected to live. By default,
| the vendor folder is in the Root directory, but you can customize that here.
*/
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
|--------------------------------------------------------------------------
| Timing Constants
|--------------------------------------------------------------------------
|
| Provide simple ways to work with the myriad of PHP functions that
| require information to be in seconds.
*/
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2592000);
defined('YEAR')   || define('YEAR', 31536000);
defined('DECADE') || define('DECADE', 315360000);

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('ENV_PRODUCTION')   || define('ENV_PRODUCTION', 'production');
defined('ENV_DEVELOPMENT')  || define('ENV_DEVELOPMENT', 'development');

defined('APP_LUCKYONE')     || define('APP_LUCKYONE', 'Luckyone');
defined('APP_ONESTAR')      || define('APP_ONESTAR', 'Onestar');
defined('APP_SKY')          || define('APP_SKY', 'Sky');
defined('APP_GOLDMOON')     || define('APP_GOLDMOON', 'Goldmoon');
defined('APP_MSLOT')        || define('APP_MSLOT', 'Mslot');

defined('APPTYPE_0')        || define('APPTYPE_0', 0);      //Premier
defined('APPTYPE_1')        || define('APPTYPE_1', 1);      //Slot Combine
defined('APPTYPE_2')        || define('APPTYPE_2', 2);      //Netural Slot
defined('APPTYPE_3')        || define('APPTYPE_3', 3);      //Only Slot

defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


$base_url = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https://'.$_SERVER['HTTP_HOST'] : 'http://'.$_SERVER['HTTP_HOST']."".str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
defined('BASEURL') || define('BASEURL', $base_url);

defined('DOWNLOADDIR')         || define('DOWNLOADDIR', "download");
defined('DOWNLOADROOT')        || define('DOWNLOADROOT', ROOTPATH."public".DIRECTORY_SEPARATOR.DOWNLOADDIR.DIRECTORY_SEPARATOR);

//config site index
defined('CONF_SITENAME')       || define('CONF_SITENAME', 1);
defined('CONF_DOMAIN')         || define('CONF_DOMAIN', 2);
defined('CONF_USERPAGE')       || define('CONF_USERPAGE', 3);
defined('CONF_ADMINPAGE')      || define('CONF_ADMINPAGE', 4);
defined('CONF_NOTICE_MAIN')    || define('CONF_NOTICE_MAIN', 5);
defined('CONF_NOTICE_BANK')    || define('CONF_NOTICE_BANK', 6);
defined('CONF_CHARGEINFO')     || define('CONF_CHARGEINFO', 8);
defined('CONF_CHARGEMACRO')    || define('CONF_CHARGEMACRO', 9);
defined('CONF_MAINTAIN')       || define('CONF_MAINTAIN', 10);
defined('CONF_BETSITE')        || define('CONF_BETSITE', 11);
defined('CONF_NOTICE_URGENT')  || define('CONF_NOTICE_URGENT', 12);
defined('CONF_SOUND_1')        || define('CONF_SOUND_1', 15);
defined('CONF_SOUND_2')        || define('CONF_SOUND_2', 16);
defined('CONF_SOUND_3')        || define('CONF_SOUND_3', 17);
defined('CONF_SOUND_4')        || define('CONF_SOUND_4', 18);
defined('CONF_CASINO_EVOL')    || define('CONF_CASINO_EVOL', 21);
defined('CONF_SLOT_1')         || define('CONF_SLOT_1', 22);
defined('CONF_SLOT_2')         || define('CONF_SLOT_2', 23);

defined('LEVEL_MAX')           || define('LEVEL_MAX', 100);
defined('LEVEL_ADMIN')         || define('LEVEL_ADMIN', 100);
defined('LEVEL_COMPANY')       || define('LEVEL_COMPANY', 99);
defined('LEVEL_AGENCY')        || define('LEVEL_AGENCY', 98);
defined('LEVEL_EMPLOYEE')      || define('LEVEL_EMPLOYEE', 97);
defined('LEVEL_MIN')           || define('LEVEL_MIN', 1);

defined('GRADE_ADMIN')         || define('GRADE_ADMIN', 20);
defined('GRADE_COMPANY')       || define('GRADE_COMPANY', 19);
defined('GRADE_AGENCY')        || define('GRADE_AGENCY', 18);
defined('GRADE_EMPLOYEE')      || define('GRADE_EMPLOYEE', 17);
defined('GRADE_1')             || define('GRADE_1', 1);

//status
defined('STATE_DISABLE')      || define('STATE_DISABLE', 0);
defined('STATE_ACTIVE')       || define('STATE_ACTIVE', 1);

//permit state
defined('PERMIT_CANCEL')       || define('PERMIT_CANCEL', 0);
defined('PERMIT_OK')           || define('PERMIT_OK', 1);
defined('PERMIT_WAIT')         || define('PERMIT_WAIT', 2);

//
defined('RESULT_OK')           || define('RESULT_OK', 1);
defined('RESULT_FAIL')         || define('RESULT_FAIL', 2);
defined('RESULT_STOP')         || define('RESULT_STOP', 3);
defined('RESULT_ERROR')        || define('RESULT_ERROR', 4);
defined('RESULT_EXIST_ID')     || define('RESULT_EXIST_ID', 5);
defined('RESULT_EXIST_NAME')   || define('RESULT_EXIST_NAME', 6);
defined('RESULT_WAIT')         || define('RESULT_WAIT', 7);
defined('RESULT_EMP_ERROR')    || define('RESULT_EMP_ERROR', 8);


defined('TM_OFFSET')    	   || define('TM_OFFSET', 20);

defined('LOG_WRITE')            || define('LOG_WRITE', true);
defined('LOG_FILE')             || define('LOG_FILE', ROOTPATH."logs".DIRECTORY_SEPARATOR);


//game type
defined('GAME_POWER_BALL')     || define('GAME_POWER_BALL', 1);
defined('GAME_POWER_LADDER')   || define('GAME_POWER_LADDER', 2);
defined('GAME_KENO_LADDER')    || define('GAME_KENO_LADDER', 3);
defined('GAME_CASINO_EVOL')    || define('GAME_CASINO_EVOL', 4);
defined('GAME_BOGLE_BALL')     || define('GAME_BOGLE_BALL', 5);
defined('GAME_BOGLE_LADDER')   || define('GAME_BOGLE_LADDER', 6);
defined('GAME_SLOT_1')         || define('GAME_SLOT_1', 7);
defined('GAME_SLOT_2')         || define('GAME_SLOT_2', 8);


defined('ROUND_5MIN')    || define('ROUND_5MIN', 5);
defined('ROUND_3MIN')    || define('ROUND_3MIN', 3);
defined('ROUND_2MIN')    || define('ROUND_2MIN', 2);

//money change type
defined('MONEYCHANGE_CHARGE')    || define('MONEYCHANGE_CHARGE', 1);   
defined('MONEYCHANGE_EXCHANGE')  || define('MONEYCHANGE_EXCHANGE', 2); 
defined('POINTCHANGE_EXCHANGE')  || define('POINTCHANGE_EXCHANGE', 3); 
defined('MONEYCHANGE_BET_PB')    || define('MONEYCHANGE_BET_PB', 4);
defined('MONEYCHANGE_WIN_PB')    || define('MONEYCHANGE_WIN_PB', 6);
defined('MONEYCHANGE_BET_PS')    || define('MONEYCHANGE_BET_PS', 7);
defined('MONEYCHANGE_WIN_PS')    || define('MONEYCHANGE_WIN_PS', 9);
defined('MONEYCHANGE_BET_KS')    || define('MONEYCHANGE_BET_KS', 10);
defined('MONEYCHANGE_WIN_KS')    || define('MONEYCHANGE_WIN_KS', 12);
defined('MONEYCHANGE_BET_BB')    || define('MONEYCHANGE_BET_BB', 13);
defined('MONEYCHANGE_WIN_BB')    || define('MONEYCHANGE_WIN_BB', 15);
defined('MONEYCHANGE_BET_BS')    || define('MONEYCHANGE_BET_BS', 16);
defined('MONEYCHANGE_WIN_BS')    || define('MONEYCHANGE_WIN_BS', 18);
defined('MONEYCHANGE_TRANS_R')   || define('MONEYCHANGE_TRANS_R', 19);
defined('MONEYCHANGE_TRANS_S')   || define('MONEYCHANGE_TRANS_S', 20);
defined('MONEYCANCEL_CHARGE')    || define('MONEYCANCEL_CHARGE', 21);   
defined('MONEYCANCEL_EXCHANGE')  || define('MONEYCANCEL_EXCHANGE', 22); 

defined('TRANS_SITE_EVOL')      || define('TRANS_SITE_EVOL', 1); 
defined('TRANS_EVOL_SITE')      || define('TRANS_EVOL_SITE', 2); 
defined('TRANS_SITE_SLOT')      || define('TRANS_SITE_SLOT', 3); 
defined('TRANS_SLOT_SITE')      || define('TRANS_SLOT_SITE', 4); 
defined('TRANS_SITE_FSLOT')     || define('TRANS_SITE_FSLOT', 5); 
defined('TRANS_FSLOT_SITE')     || define('TRANS_FSLOT_SITE', 6); 