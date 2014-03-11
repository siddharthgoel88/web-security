<?php

if(!defined('SM_PATH'))
    define('SM_PATH','../');

include_once(SM_PATH . 'include/validate.php');

//echo $_SESSION['dashboard_url_redirect'];
//echo urldecode($_SESSION['dashboard_url_redirect']);
if(isset($_SESSION['dashboard_url_redirect'])){
	//header("Location:".$_SESSION['dashboard_url_redirect']);
	echo"<script>
		window.location = \"".urldecode($_SESSION['dashboard_url_redirect'])."\";
	     </script>";
}
?>

