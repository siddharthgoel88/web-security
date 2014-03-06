<?php

$plugin_name="user_statistics";

global $plugin_name;

require_once 'utility.php';

$plugin_enabled=is_plugin_enabled($plugin_name, read_custom_config());

function user_statistics_left(){
    //Get Email ID from the session variable
    $email_id =  $_SESSION['username'];

    //Explode it into array to extract name and make the first letter of name capital
    $email_array = explode('@',$email_id);
    $name = $email_array[0];
    $name = ucfirst($name);
    
    //Echo the user name and no of unread mails
    echo '<div><p>Hi '.$name.'!</p>';
    echo 'You have '.$_SESSION['numMessages'].' unread mails!</div>';
    
    
}
