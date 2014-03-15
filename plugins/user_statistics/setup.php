<?php
    
$plugin_name='user_statistics';

function squirrelmail_plugin_init_user_statistics() {
    global $squirrelmail_plugin_hooks;
    $squirrelmail_plugin_hooks['left_main_before']['user_statistics'] = 'user_stat_left';
    
}

function user_stat_left(){
    require_once('functions.php');
    user_statistics_left();
}