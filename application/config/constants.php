<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

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
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

//application codes

define('MISSING_PARAMETER','100');
define('USER_ALREADY','101');
define('SUCCESS_CODE','200');
define('RECORDS_PER_PAGE',5);
define('WEB_RECORDS_PER_PAGE',20);
define('TIPS_RECORDS_PER_PAGE',5);
define('COMPTITORS_PER_PAGE',20);
define('FAILURE_CODE','400');
define('REGISTER_VIA_NORMAL',1);
define('REGISTER_VIA_FACEBOOK',2);
define('REGISTER_VIA_GOOGLE',3);
define('REGISTER_VIA_TWITTER',4);
define('REGISTER_VIA_ADMIN',5);
define('DEVICE_TYPE_WEB','web');
define('DEVICE_TYPE_ANDROID','android');
define('DEVICE_TYPE_I_PHONE','iphone');
define('INVALID_FORMAT','102');
define('CURRENT_DATE',date('Y-m-d H:i:s'));
define('DISPLAY1',5);
define('DISPLAY2',10);
define('DISPLAY3',15);
define('NUM_LINKS',2);
define('TIPS_ACTIVE',1);
define('TIPS_INACTIVE',2);
define('ACTIVE',1);
define('INACTIVE',2);
define('CONSUMER_KEY', 'FkJiGKhZjikUWb5VmkMdasZqF'); // YOUR CONSUMER KEY
define('CONSUMER_SECRET', 'C4lnjxj4nHKNF07u7qm9M02rx8JYEhLoXk0xNzWZCX5tap7TIV'); //YOUR CONSUMER SECRET KEY 
define('OAUTH_CALLBACK', 'http://betcomparedev.applaurels.com/web');  // Redirect URL 
define('ACCESS_TOKEN','880035182266335232-goN2QLCDRvy2aMQKJ813EgjKDS1YeK9');
define('TOKEN_SECRET','j1b4OI7LN3DsTowQf91n280UY5IrRKlRWQyXbId9h58Iu');
define('SET_PRIORITY1',1);
define('SET_PRIORITY2',2);
define('SET_PRIORITY3',3);
define('SET_PRIORITY4',4);
define('SET_PRIORITY5',5);
define('POSITION1',1);
define('POSITION2',2);
define('POSITION3',3);
define('POSITION4',4);
define('POSITION5',5);
