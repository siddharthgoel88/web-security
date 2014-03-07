<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


if(!defined('SM_PATH'))
    define('SM_PATH','../../');

//include_once(SM_PATH . 'include/validate.php');
//include_once(SM_PATH . 'functions/strings.php');
//include_once(SM_PATH . 'config/config.php');
//include_once(SM_PATH . 'functions/page_header.php');
//include_once(SM_PATH . 'functions/display_messages.php');
//include_once(SM_PATH . 'functions/prefs.php');




//displayPageHeader($color, "None");

require_once('../pranav_plugins_common/string_functions.php');
require_once('../pranav_plugins_common/utility_functions.php');

require_once('functions.php');

$_SESSION['username']='pranav@lh.com';

$option = "default";

if(isset($_REQUEST['state'])){
    $option = $_REQUEST['state'];
    var_dump($_REQUEST);
}


switch ($option){
    case 'file_upload':
       $enabled_plugins = read_custom_config();
       $name =  $_FILES['user_file']['name'];
       $file_name = get_file_name($name);
       if(is_plugin_directory_present($file_name)){
           if(is_plugin_enabled($file_name,$enabled_plugins))
           {
               //Do nothing as it is installed and enabled
           }
           else{
               enable_plugin($file_name,$enabled_plugins);
               write_custom_config($enabled_plugins);
           }
       }
       else{
            move_uploaded_file($_FILES['user_file']['tmp_name'], "plugin_zips/".$_FILES['user_file']['name']);
            install_from_folder($name);
       }
       show_plugins();
       break;
    case 'install_plugin':
        $name = $_GET['install_option']."zip";
        $file_name = $_GET['install_option'];
        if(is_plugin_directory_present($file_name)){
           if(is_plugin_enabled($file_name,$enabled_plugins))
           {
               //Do nothing as it is installed and enabled
           }
           else{
               if(!is_plugin_in_master_list($file_name)){
                   add_plugin_master_list($file_name);
               }
               enable_plugin($file_name,$enabled_plugins);
               write_custom_config($enabled_plugins);
           }
       }
       else{
            install_from_folder($name);
       }
            show_plugins();
        break;
    case 'uninstall_plugin':
        $name = $_GET['uninstall_option'];
        echo $name;
        $plugins_list = read_custom_config();
        disable_plugin($name, $plugin_list);
        write_custom_config($plugins_list);
        show_plugins();
        break;
    default:
        show_plugins();
        //var_dump($plugins_list);
        break;
        
}
/*require_once('default_menu.php');

show_plugins();
*/
?>