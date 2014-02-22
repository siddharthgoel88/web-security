<?php

/**
 * testrtc.php
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
define('PAGE_NAME', 'testrtc');

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

define( API_PUBLIC_KEY, '6LfaxO4SAAAAADUoWmX91BRCeBnoJF60ol8bXk77' );
define( API_PRIVATE_KEY, '6LfaxO4SAAAAACb4RExl6j6ecN7UpTudQ8pRkySJ'  );
require_once('recaptchalib2.php');
$validated = false;
$captchacheck = 0;
if( $_POST['recaptcha_response_field']) { 
	$response = recaptcha_check_answer( API_PRIVATE_KEY,
					$_SERVER['REMOTE_ADDR'],
					$_POST['recaptcha_challenge_field'],
					$_POST['recaptcha_response_field']);
	if( $response->is_valid ) {	
		$validated = true;
		$captchacheck = 2;
	}
	else {
		$captchacheck = 1;
	}
	}
echo displayPageHeader($color,'None');
?>
<form method="post">
       <table>
               <tr>
		<td align="right" valign="baseline"> <span style="color:blue;font-size:20px;text-decoration:underline">Enter Captcha:</span></td></tr>
               <tr>
                   <td align="right" valign="baseline">&nbsp;</td>
                   <td align="left" valign="baseline"><?php echo recaptcha_get_html(API_PUBLIC_KEY,NULL,true); ?></td>
               </tr>
               <tr>
                   <td align="left" valign="baseline"><input type="submit" value="Validate" /></td>
               </tr>
     </table>
   </form>
<?php

if ($captchacheck == 1)
{
	echo '<span style="color:blue;font-size:18px">Status: <span style="color:red;font-size:18px">Captcha Invalid</span>';
}
else if ($captchacheck == 2){
	echo '<span style="color:blue;font-size:18px">All worked</span>';
	header("Location: /src/webrtc2.php");
	exit;
}
else
{
	echo '<span style="color:blue;font-size:18px">Status: Field is blank</span>';

}
?>
