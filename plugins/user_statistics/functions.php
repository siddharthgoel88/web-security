<?php


function user_statistics_left(){
    $plugin_name='user_statistics';

      //Get Email ID from the session variable
    $email_id =  htmlspecialchars($_SESSION['username']);

    //Call utility function and get user name from email ID
    $name_array = explode("@",$email_id);
    $name = $name_array[0];
    
    //Echo the user name and no of unread mails
    echo '<div><p>Hi '.$name.'!</p>';
    
    echo 'You have '.($_SESSION["numMessages"]>0?$_SESSION["numMessages"]:'no').' unread mails!</div>';

}


