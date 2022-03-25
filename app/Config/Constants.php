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

defined('LEVEL_ADMIN')         || define('LEVEL_ADMIN', 10);
defined('LEVEL_COMPANY')       || define('LEVEL_COMPANY', 9);
defined('LEVEL_AGENCY')        || define('LEVEL_AGENCY', 8);
defined('LEVEL_EMPLOYEE')      || define('LEVEL_EMPLOYEE', 7);
defined('LEVEL_USER')          || define('LEVEL_USER', 1);

//permit state
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


defined('CONF_SITENAME')       || define('CONF_SITENAME', 1);
defined('CONF_MAINTAIN')       || define('CONF_MAINTAIN', 10);

//game type
defined('GAME_POWER_BALL')     || define('GAME_POWER_BALL', 1);
defined('GAME_POWER_LADDER')   || define('GAME_POWER_LADDER', 2);
defined('GAME_KENO_LADDER')    || define('GAME_KENO_LADDER', 3);
defined('GAME_CASINO')         || define('GAME_CASINO', 4);
defined('GAME_BOGLE_BALL')     || define('GAME_BOGLE_BALL', 5);
defined('GAME_BOGLE_LADDER')   || define('GAME_BOGLE_LADDER', 6);
defined('GAME_SLOT')           || define('GAME_SLOT', 7);

defined('ROUND_5MIN')    || define('ROUND_5MIN', 5);
defined('ROUND_3MIN')    || define('ROUND_3MIN', 3);
defined('ROUND_2MIN')    || define('ROUND_2MIN', 2);