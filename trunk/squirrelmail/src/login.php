<?php

/**
 * login.php -- simple login screen
 *
 * Copyright (c) 1999-2002 The SquirrelMail Project Team
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * This a simple login screen. Some housekeeping is done to clean
 * cookies and find language.
 *
 * $Id$
 */

$rcptaddress = '';
if (isset($emailaddress)) {
    if (stristr($emailaddress, 'mailto:')) {
        $rcptaddress = substr($emailaddress, 7);
    } else {
        $rcptaddress = $emailaddress;
    }
    
    if (($pos = strpos($rcptaddress, '?')) !== false) {
        $a = substr($rcptaddress, $pos + 1);
        $rcptaddress = substr($rcptaddress, 0, $pos);
        $a = explode('=', $a, 2);
        if (isset($a[1])) {
            $name = urldecode($a[0]);
            $val = urldecode($a[1]);
            global $$name;
            $$name = $val;
        }
    }
    
    /* At this point, we have parsed a lot of the mailto stuff. */
    /*   Let's do the rest -- CC, BCC, Subject, Body            */
    /*   Note:  They can all be case insensitive                */
    foreach ($GLOBALS as $k => $v) {
        $key = strtolower($k);
        $value = urlencode($v);
        if ($key == 'cc') {
            $rcptaddress .= '&amp;send_to_cc=' . $value;
        } else if ($key == 'bcc') {
            $rcptaddress .= '&amp;send_to_bcc=' . $value;
        } else if ($key == 'subject') {
            $rcptaddress .= '&amp;subject=' . $value;
        } else if ($key == 'body') {
            $rcptaddress .= '&amp;body=' . $value;
        }
    }
    
    /* Double-encode in this fashion to get past redirect.php properly. */
    $rcptaddress = urlencode($rcptaddress);
}

require_once('../functions/strings.php');
require_once('../config/config.php');
require_once('../functions/i18n.php');
require_once('../functions/plugin.php');
require_once('../functions/constants.php');
require_once('../functions/page_header.php');
require_once('../functions/html.php');

/*
 * $squirrelmail_language is set by a cookie when the user selects
 * language and logs out
 */
set_up_language($squirrelmail_language, TRUE);

/**
 * Find out the base URI to set cookies.
 */
if (!function_exists('sqm_baseuri')){
    require_once('../functions/display_messages.php');
}
$base_uri = sqm_baseuri();
@session_destroy();

/*
 * In case the last session was not terminated properly, make sure
 * we get a new one.
 */
$cookie_params = session_get_cookie_params();
setcookie(session_name(), '', 0, $cookie_params['path'], 
          $cookie_params['domain']);
setcookie('username', '', 0, $base_uri);
setcookie('key', '', 0, $base_uri);
header('Pragma: no-cache');

do_hook('login_cookie');

/* Output the javascript onload function. */

$header = "<SCRIPT LANGUAGE=\"JavaScript\" type=\"text/javascript\">\n" .
          "<!--\n".
          "  function squirrelmail_loginpage_onload() {\n".
          "    document.forms[0].js_autodetect_results.value = '" . SMPREF_JS_ON . "';\n".
          '    document.forms[0].elements[' . (isset($loginname) ? 1 : 0) . "].focus();\n".
          "  }\n".
          "// -->\n".
          "</script>\n";
$custom_css = 'none';          
displayHtmlHeader( "$org_name - " . _("Login"), $header, FALSE );

/* Set the title of this page. */
echo '<body text="#000000" bgcolor="#FFFFFF" link="#0000CC" vlink="#0000CC" alink="#0000CC" onLoad="squirrelmail_loginpage_onload();">';

$username_form_name = 'login_username';
$password_form_name = 'secretkey';
do_hook('login_top');

$loginname_value = (isset($loginname) ? htmlspecialchars($loginname) : '');

/* Display width and height like good little people */
$width_and_height = '';
if (isset($org_logo_width) && is_int($org_logo_width) && $org_logo_width>0) {
    $width_and_height = " width=\"$org_logo_width\"";
}
if (isset($org_logo_height) && is_int($org_logo_height) && $org_logo_height>0) {
    $width_and_height .= " height=\"$org_logo_height\"";
}

$rcptaddress_input = '';
if ($rcptaddress != '') {
    $rcptaddress_input = '<input type="hidden" name="rcptemail" value="htmlspecialchars(' . $rcptaddress . ')">';
}

echo "\n" . '<form action="redirect.php" method="post">' . "\n" .
html_tag( 'table',
    html_tag( 'tr',
        html_tag( 'td',
            '<center>'.
            '<img src="' . $org_logo . '" alt="' . sprintf(_("%s Logo"), $org_name) .'"' .
            $width_and_height .'><br>' . "\n".
            ( $hide_sm_attributions ? '' :
            '<small>' . sprintf (_("SquirrelMail version %s"), $version) . '<br>' ."\n".
            '  ' . _("By the SquirrelMail Development Team") . '<br></small>' . "\n" ) .
            "<br>\n" .
            html_tag( 'table',
                html_tag( 'tr',
                    html_tag( 'td',
                        '<b>' . sprintf (_("%s Login"), $org_name) . "</b>\n",
                    'center', '#DCDCDC' )
                ) .
                html_tag( 'tr',
                    html_tag( 'td',  "\n" .
                        html_tag( 'table',
                            html_tag( 'tr',
                                html_tag( 'td',
                                    _("Name:") ,
                                'right', '', 'width="30%"' ) .
                                html_tag( 'td',
                                    '<input type="text" name="' . $username_form_name .'" value="' . $loginname_value .'">' ,
                                'left', '', 'width="*"' )
                                ) . "\n" .
                            html_tag( 'tr',
                                html_tag( 'td',
                                    _("Password:") ,
                                'right', '', 'width="30%"' ) .
                                html_tag( 'td',
                                    '<input type="password" name="' . $password_form_name . '">' . "\n" .
                                    '<input type=hidden name="js_autodetect_results" value="SMPREF_JS_OFF">' . "\n" .
                                    '<input type=hidden name="just_logged_in" value=1>' . "\n" .
                                    $rcptaddress_input . "\n" ,
                                'left', '', 'width="*"' )
                            ) ,
                        'center', '#ffffff', 'border="0" cols="2" width="100%"' ) ,
                    'left', '#FFFFFF' )
                ) . 
                html_tag( 'tr',
                    html_tag( 'td',
                        '<center><input type="submit" value="' . _("Login") . '"></center>',
                    'left' )
                ),
            '', '#ffffff', 'border="0" cols="1" width="350"' ),
        'center' )
    ) ,
'', '#ffffff', 'border="0" cellspacing="0" cellpadding="0" width="100%"' ) .
'</form>' . "\n";

do_hook('login_form');

do_hook('login_bottom');
echo "</body>\n".
     "</html>\n";
?>