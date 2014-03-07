<?php

function get_user_name($email_id){
    $users_array=explode("@",$email_id);
    $user_name = $users_array[0];
    return $user_name;
    
}

?>