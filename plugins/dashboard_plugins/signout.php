<?php

/**
 * signout.php -
 *
 *  Show the signout page
 *
/** This is the signout page */
define('PAGE_NAME', 'signout');

/**
 * Path for SquirrelMail required files.
 * @ignore
 */
define('SM_PATH','../../');

require_once(SM_PATH . 'include/validate.php');
require_once(SM_PATH . 'functions/prefs.php');
require_once(SM_PATH . 'functions/plugin.php');
require_once(SM_PATH . 'functions/strings.php');
require_once(SM_PATH . 'functions/html.php');

/* Erase any lingering attachments */
sqgetGlobalVar('compose_messages',  $compose_messages,  SQ_SESSION);
if (!empty($compose_messages) && is_array($compose_messages)) {
    foreach($compose_messages as $composeMessage) {
        $composeMessage->purgeAttachments();
    }
}

if (!isset($frame_top)) {
    $frame_top = '_top';
}

/* If a user hits reload on the last page, $base_uri isn't set
 * because it was deleted with the session. */
if (! sqgetGlobalVar('base_uri', $base_uri, SQ_SESSION) ) {
    require_once(SM_PATH . 'functions/display_messages.php');
}



if ($signout_page) {
    // Status 303 header is disabled. PHP fastcgi bug. See 1.91 changelog.
    //header('Status: 303 See Other');
    header("Location: $signout_page");
    exit; /* we send no content if we're redirecting. */
}

/* internal gettext functions will fail, if language is not set */
set_up_language($squirrelmail_language, true, true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
   <meta name="robots" content="noindex,nofollow">
<?php
    if ($theme_css != '') {
?>
   <link rel="stylesheet" type="text/css" href="<?php echo $theme_css; ?>">
<?php
    }
?>
   <title><?php echo $org_title . ' - ' . _("Signout"); ?></title>
</head>
<body text="<?php echo $color[8]; ?>" bgcolor="<?php echo $color[4]; ?>"
link="<?php echo $color[7]; ?>" vlink="<?php echo $color[7]; ?>"
alink="<?php echo $color[7]; ?>">
<br /><br />
<?php
unset($_SESSION['session_expired_location']);
$plugin_message = concat_hook_function('logout_above_text');
echo
html_tag( 'table',
    html_tag( 'tr',
         html_tag( 'th', _("Sign Out"), 'center' ) ,
    '', $color[0], 'width="100%"' ) .
    $plugin_message .
    html_tag( 'tr',
	html_tag( 'td', _("You have been successfully signed out.") .
             '<br /><a href="/" target="' . $frame_top . '">' .
             _("Click here to log back in.") . '</a><br />' ,
         'center' ) ,
    '', $color[4], 'width="100%"' ) .
    html_tag( 'tr',
         html_tag( 'td', '<br />', 'center' ) ,
    '', $color[0], 'width="100%"' ) ,
'center', $color[4], 'width="50%" cols="1" cellpadding="2" cellspacing="0" border="0"' )
?>
</body>
</html>

