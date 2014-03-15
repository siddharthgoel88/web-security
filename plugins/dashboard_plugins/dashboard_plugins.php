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




displayPageHeader($color, "None");


require_once('new_functions.php');


$option = "default";

if(isset($_REQUEST['state'])){
    $option = htmlspecialchars($_REQUEST['state']);
    
}

switch ($option){
    case 'file_upload':
       if(zip_file_install()){
            unset($_SESSION);
            unset($plugins);
            do_hook('logout');
            echo '<script> window.top.location = "/";</script>';
        }
       show_plugins();
       break;
    case 'install_plugin':
        $name = htmlspecialchars($_GET['install_option']);
        if($name==="dashboard_plugins"||$name==="user_statistics")
        {
        	//Do nothing as this is managed by this plugin only  only add hooks
        	if(!is_plugin_global($name))
        		install_plugin_global($name);
        }
        else{
        	if(!is_plugin_global($name)){           
            	install_plugin_global($name);
            	if(!is_dir_present($name)){
                	install_from_folder($name);
            	}
        	}
        }
        sqsession_destroy();
	    unset($SESSION);
            echo '<script> window.top.location = "signout.php";</script>';     
        break;
    case 'uninstall_plugin':
        $name = htmlspecialchars($_GET['uninstall_option']);
        remove_plugin_global($name);
        echo '<script> window.top.location = "signout.php";</script>';
        break;
    default:
        show_plugins();
         break;
        
}

?>
