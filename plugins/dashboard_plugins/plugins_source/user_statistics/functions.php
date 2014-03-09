<?php

/*$user_stats_path = dirname(__FILE__);


require_once ($user_stats_path.'./../pranav_plugins_common/utility_functions.php');
require_once ($user_stats_path.'./../pranav_plugins_common/string_functions.php');
*/

function user_statistics_left(){
    $plugin_name='user_statistics';

      //Get Email ID from the session variable
    $email_id =  $_SESSION['username'];

    //Call utility function and get user name from email ID
    $name_array = explode("@",$email_id);
    $name = $name_array[0];
    
    //Echo the user name and no of unread mails
    echo '<div><p>Hi '.$name.'!</p>';
    
    echo 'You have '.$_SESSION["numMessages"].' unread mails!</div>';

}


