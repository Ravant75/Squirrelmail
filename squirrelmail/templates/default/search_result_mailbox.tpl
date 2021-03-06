<?php
/**
 * search_result_mailbox.tpl
 *
 * Search results are separated by mailbox.  This template is displayed above
 * the results for each mailbox.  Usually, this simply lists the name of the
 * mailbox, but you could do other things here.
 * 
 * The following variables are available in this template:
 *      $mailbox_name - Sanitized name of the mailbox.
 *
 * @copyright 1999-2013 The SquirrelMail Project Team
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id: search_result_mailbox.tpl 14387 2013-07-26 17:31:02Z jervfors $
 * @package squirrelmail
 * @subpackage templates
 */

/** add required includes **/

/** extract template variables **/
extract($t);

/** Begin template **/
?>
<div class="search">
<h1><?php echo _("Folder"); ?>: <?php echo $mailbox_name; ?></h1>
</div>