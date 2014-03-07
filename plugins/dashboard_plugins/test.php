<?php
require_once('../pranav_plugins_common/string_functions.php');
require_once('../pranav_plugins_common/utility_functions.php');

$_SESSION['username'] = "pranav@lh.com";
$plugins_enabled=read_custom_config();

//enable_plugin("user_statistics", $plugins_enabled);
var_dump($plugins_enabled);
//write_custom_config($plugins_enabled);


?>

