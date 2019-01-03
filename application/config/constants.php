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

define('USER_SESS_KEY', 'app_user_sess'); 
define('ADMIN_USER_SESS_KEY', 'app_admin_user_sess');

// define('DEFAULT_NO_IMG', 'noimagefound.jpg');
define('THEME_BUTTON', 'btn btn-primary');
define('THEME', ''); // skin-1, skin-2, skin-
define('EDIT_ICON', '<i class="fa fa-pencil-square-o" aria-hidden="true"  style="color:#A82E14;"></i>');
define('DELETE_ICON', '<i class="fa fa-trash-o" aria-hidden="true" style="color:#A82E14;"></i>');
define('ACTIVE_ICON', '<i class="fa fa-check" aria-hidden="true" style="color:#A82E14;"></i>');
define('INACTIVE_ICON', '<i class="fa fa-times" aria-hidden="true" style="color:#A82E14;"></i>');
define('VIEW_ICON', '<i class="fa fa-eye" aria-hidden="true"></i>');

define('DEFAULT_USER','backend_assets/logo/200.png');
define('MAP_USER','backend_asset/custom/images/new_map_icon.png');

/*Database table's constants*/
define('ADMIN', 'admin');
define('USERS', 'users');
define('USERS_IMAGE', 'users_image');
define('SITE_TITLE','Flying News');
define('COPYRIGHT','© '.date("Y",strtotime("-1 year")).'-'.date("Y").' All Rights Reserved By Flying News');
define('ADMIN_PROFILE_THUMB','uploads/profile/thumb/');
define('ADMIN_PROFILE','uploads/profile');
define('USER_PROFILE_THUMB','uploads/users/thumb/');
define('USER_PROFILE','uploads/users/');
define('ASSETS','backend_assets');
define('DEFAULT_IMAGE','extra/placeholder.png');
define('GROUPS','groups');
define('GROUP_MEMBERS','group_members');
define('GROUP_LIKES','group_likes');
define('GROUP_COMMENTS','group_comments');
define('NOTIFICATIONS','notifications');
define('GROUP_TAGS_MAPPING','group_tag_mapping');
define('NOTIFICATION_KEY','AAAAgCVau_0:APA91bHkbHPlqUKV1EQjJtXwSoOB8fWabr6NG7GeUPt9vuKrc4lel-4GWU7DFqanVSznLkYwhWx3bs_wMgsbgiIzEc_-6E40YoRY18GDjVH8oR8G_ETDu1qjzzxfpTAZf0qpPbHVhmPrnaiwoIlw6bhHPj5VDHdlDw');
define('TERM_CONDITION','uploads/content/term_condition/');
define('PLACEHOLDER', 'backend_assets/img/default1.jpg');



/* Image Folder Path*/
define('UPLOAD_FOLDER', 'uploads');
define('USER_IMG_PATH','uploads/profile/');
define('USER_IMG_PATH_THUB','uploads/profile/thumb/');
define('COMPANY_LOGO','uploads/logo/');
define('LOGO','logo/');
define('ADMIN_IMAGE_PATH','uploads/profile/');




//added a constat for fron assets//
define('FN_ADMIN', 'backend_assets/');
define('FN_ADMIN_IMAGES', 'backend_assets/images/');
define('FNJS', 'backend_assets/js/');




/* APIS Status*/
define('FAIL','fail');
define('SUCCESS','success');
define('OK',200);
define('SERVER_ERROR',400);