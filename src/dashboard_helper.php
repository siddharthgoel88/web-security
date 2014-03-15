<?php

if(!defined('SM_PATH'))
  define('SM_PATH','../');

include_once(SM_PATH . 'include/validate.php');

session_start();
$_SESSION['dashboard_url_redirect'] = $_REQUEST['redirect_url'];
header("Location:webmail.php?right_frame=dashboard_loader.php");

?>
