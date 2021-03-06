<?php
/*
 * Copyright (c) 2003-2004 The SquirrelMail Project Team
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 * 
 * This file is an addition to the
 * Framework for Object Orientated Web Development (Foowd).
 */

/**
 * Alternate entry point allowing content to be searched.
 *
 * $Id: sqmsearch.php 9183 2005-04-03 21:08:59Z ebullient $
 * 
 * @package smdoc
 * @subpackage extern
 */

/** 
 * Initial configuration, start session
 * @see config.default.php
 */
require('smdoc_init.php');
  
/* 
 * Initialize smdoc/FOOWD environment
 */
$smdoc_parameters['debug']['debug_enabled'] = TRUE;
$foowd = new smdoc($smdoc_parameters);

$foowd->template->assign('title', _("Site Search"));
$foowd->template->assign('method', '');
$foowd->template->assign('body_template', 'smdoc_external.search.tpl');

$foowd->template->display();

/*
 * destroy Foowd - triggers cleanup of database object and 
 * display of debug information.
 */
$foowd->__destruct();

