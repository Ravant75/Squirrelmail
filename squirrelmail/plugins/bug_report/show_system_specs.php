<?php
/**
 * This script shows system specification details.
 *
 * @copyright 1999-2013 The SquirrelMail Project Team
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id: show_system_specs.php 14387 2013-07-26 17:31:02Z jervfors $
 * @package plugins
 * @subpackage bug_report
 */


// This is the bug_report show system specs page
//
define('PAGE_NAME', 'bug_report_show_system_specs');


// Include the SquirrelMail initialization file.
//
require('../../include/init.php');


// load plugin functions
//
require_once(SM_PATH . 'plugins/bug_report/functions.php');


// error out when bug_report plugin is disabled
// or is called by the wrong user
//
if (! is_plugin_enabled('bug_report') || ! bug_report_check_user()) {
    error_box(_("Plugin is disabled."));
    $oTemplate->display('footer.tpl');
    exit();
}


// get system specs
//
require_once(SM_PATH . 'plugins/bug_report/system_specs.php');
list($body, $warnings, $corrections) = get_system_specs();

global $oTemplate;
$oTemplate->assign('body', $body);
$oTemplate->display('plugins/bug_report/system_specs.tpl');
$oTemplate->display('footer.tpl');


