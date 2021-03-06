<?php
/*
 * Copyright (c) 2003-2004 The SquirrelMail Project Team
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * This file is an addition/modification to the 
 * Framework for Object Orientated Web Development (Foowd).
 */

/** 
 * Template for user object view
 *
 * Modified by SquirrelMail Development
 * $Id: smdoc_user.object_view.tpl 8333 2004-11-05 23:54:18Z ebullient $
 * 
 * @package smdoc
 * @subpackage template
 */
unset($t['version']);
$t['body_function'] = 'user_view_body';

/** Include base template */
include_once(TEMPLATE_PATH.'index.tpl');

/**
 * Base template will call back to this function
 *
 * @param smdoc $foowd Reference to the foowd environment object.
 * @param string $className String containing invoked className.
 * @param string $method String containing called method name.
 * @param smdoc_user $user Reference to active user.
 * @param object $object Reference to object being invoked.
 * @param mixed $t Reference to array filled with template parameters.
 */
function user_view_body(&$foowd, $className, $method, &$user, &$object, &$t)
{
  $none = '<span class="subtext"><em>&lt;none specified&gt;</em></span>';
  if ( !isset($t['update']) )
    $t['update'] = FALSE;
?>

<table cellspacing="0" cellpadding="0" class="smdoc_table">
<tr class="separator">
    <th colspan="2">
        <?php echo _("Public Profile Attributes"); ?>
    </th>
</tr>
<tr>
  <th class="label"><?php echo _("Username"); ?>:</th>
  <td class="value"><?php echo $t['title']; ?></td>
</tr>
<tr>
  <th class="label"><?php echo _("Created"); ?>:</th>
  <td class="value"><span class="smalldate"><?php echo $t['created']; ?></span></td>
</tr>
<tr>
  <th class="label"><?php echo _("Last Visit"); ?>:</th>
  <td class="datevalue"><?php echo $t['lastvisit']; ?></td>
</tr>
<?php // DISPLAY IM ID's IF PRESENT
      if ( isset($t['nicks']) && is_array($t['nicks']) || !empty($t['nicks']) )
      {
?>
<tr class="separator">
    <th colspan="2">
        <?php echo _("Public Contact Information"); ?>
    </th>
</tr>
<?php   ksort($t['nicks']);
        foreach ( $t['nicks'] as $prot => $id )
        {
          switch ($prot)
          {
            case 'MSN':
            case 'Email':
              $id =  htmlentities(mungEmail($id));
              break;
            case 'IRC':
              $id = htmlentities($id);
              $id .= ' - <span class="subtext">#squirrelmail (<a href="http://freenode.net">irc.freenode.net</a>)</span>';
              break;
            case 'ICQ':
              $id = '<a href="http://wwp.icq.com/'.$id.'">'.$id.'</a>';
              break;
            case 'WWW':
              $id = htmlentities($id);
              $id = '<a href="'.$id.'">'.$id.'</a>';
              break;
            default:
              $id = htmlentities($id);
              break;
          }
?>
<tr>
  <th class="label"><?php echo htmlentities($prot); ?>:</th>
  <td class="value"><?php echo $id; ?></td>
</tr>
<?php
        } // END foreach
      } // END DISPLAY IM IDs

      // begin AUTHOR only elements
      if ( $t['update'] )
      {
?>
<tr class="separator">
    <th colspan="2">
        <?php echo _("Private Attributes"); ?>
        <span class="subtext">(<a href="<?php echo getURI(array('object' => 'privacy')); ?>">privacy</a>)</span>
    </th>
</tr>
<tr>
  <th class="label"><?php echo _("Groups"); ?>:</th>
  <td class="value">
<?php 
      echo _("Registered");
      if ( !empty($object->groups) ) {
          foreach($object->groups as $group) {
            echo ', ' . smdoc_group::getDisplayName($group);
          }
      }
?>
  </td>
</tr>

<?php   if ( !$t['show_email'] )
        { ?>
<tr>
  <th class="label"><?php echo _("Email"); ?>:</th>
  <td class="value"><?php echo isset($t['email']) ? htmlentities($t['email']) : $none; ?></td>
</tr>
<?php   } ?>
<tr class="separator">
  <th colspan="2"><?php echo _("Preferred Applications"); ?>:</th>
</tr>
<tr>
  <th class="label"><?php echo _("SMTP Server"); ?>:</th>
  <td class="value"><?php echo ($t['SMTP_server'] == 'Unknown') ? $none : $t['SMTP_server']; ?></td>
</tr>
<tr>
  <th class="label"><?php echo _("IMAP Server"); ?>:</th>
  <td class="value"><?php echo ($t['IMAP_server'] == 'Unknown') ? $none : $t['IMAP_server']; ?></td>
</tr>
<tr>
  <th class="label"><?php echo _("SquirrelMail Version"); ?>:</th>
  <td class="value"><?php echo ($t['SM_version'] == 'Unknown') ? $none : $t['SM_version']; ?></td>
</tr>
<?php } // END AUTHOR ONLY ELEMENTS  ?>
</table>

<?php
} // end display function
