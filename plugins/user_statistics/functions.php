<?php

$plugin_name="user_statistics";

global $plugin_name;

require_once 'utility.php';

$plugin_enabled=is_plugin_enabled($plugin_name, read_custom_config());

function user_statistics_left(){
    
        echo '<div><p>Hi '.$_SESSION['username'].'!</p>';
	echo 'You have '.rand(1,5).' unread mails!';
    
    
}
