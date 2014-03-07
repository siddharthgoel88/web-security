<?php

/**
 * send_invite.php
 *
 * Manage personal address book.
 *
 * @copyright 1999-2010 The SquirrelMail Project Team
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id: addressbook.php 13893 2010-01-25 02:47:41Z pdontthink $
 * @package squirrelmail
 * @subpackage addressbook
 */

/** This is the addressbook page */
define('PAGE_NAME', 'send_invite');

/**
 * Path for SquirrelMail required files.
 * @ignore
 */
define('SM_PATH','../');

/** SquirrelMail required files. */
require_once(SM_PATH . 'include/validate.php');
require_once(SM_PATH . 'functions/global.php');
require_once(SM_PATH . 'functions/display_messages.php');
require_once(SM_PATH . 'functions/addressbook.php');
require_once(SM_PATH . 'functions/strings.php');
require_once(SM_PATH . 'functions/html.php');
require_once(SM_PATH . 'functions/forms.php');

$userinvite = $_POST['userinvtid'];
$peerid = $_POST['userpeerid'];
/*if ($userinvite) {
	echo "</br>Name Entered: ".$userinvite;
	echo "</br>Current User: ".$username;
}*/

/* Send email : Begin */

    $to = $userinvite.'@localhost'; // this is your Email address
    $from = $username.'@localhost'; // this is the sender's Email address
    $subject = "Invite: Conference with ".$username;
    $subject2 = "Hi ".$userinvite;
    $message = "Hi ".$userinvite.",\n\n".$username . " has invited you for a Video Conference. Please call '".$username."' on this ID - ".$peerid."\n\nYou must do a captcha validation first by clicking on WebRTC link on left hand side panel.";
    $message2 = "Please call ".$username." by using this ID - ".$peerid;
    $headers = "From:" . $from;
    $headers2 = "From:" . $to;
    mail($to,$subject,$message,$headers);
    mail($from,$subject2,$message2,$headers2);

/* Send email : End */

?>
