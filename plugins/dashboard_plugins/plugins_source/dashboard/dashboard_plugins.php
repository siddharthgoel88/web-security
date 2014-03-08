<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


if(!defined('SM_PATH'))
    define('SM_PATH','../../');

include_once(SM_PATH . 'include/validate.php');
include_once(SM_PATH . 'functions/strings.php');
include_once(SM_PATH . 'config/config.php');
include_once(SM_PATH . 'functions/page_header.php');
include_once(SM_PATH . 'functions/display_messages.php');
include_once(SM_PATH . 'functions/prefs.php');

//include_once('config.php');
require_once('functions.php');

displayPageHeader($color, "None");

$option = $_REQUEST['state'];
switch ($option){
    case 'file_upload':
       $name =  $_FILES['user_file']['name'];
       move_uploaded_file($_FILES['user_file']['tmp_name'], "plugin_zips/".$_FILES['user_file']['name']);
       install_from_folder($name);
       show_plugins();
       break;
    case 'install_plugin':
        $name = $_GET['install_option']."zip";
        install_from_folder($name);
         show_plugins();
        break;
    case 'uninstall_plugin':
        $name = $_GET['uninstall_option'];
        $plugins_list = read_custom_config();
        disable_plugin($name, $plugin_list);
        write_custom_config($plugins_list);
        show_plugins();
        break;
    default:
        show_plugins();
        break;
        
}
/*require_once('default_menu.php');

show_plugins();
*/
?>