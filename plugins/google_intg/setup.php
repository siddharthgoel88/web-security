<?php

function squirrelmail_plugin_init_google_intg() {
    global $squirrelmail_plugin_hooks;
    $squirrelmail_plugin_hooks['menuline']['google_intg'] = 'google_intg_menuline';
    
}

function google_intg_menuline(){
    displayInternalLink("plugins/google_intg/google_intg.php",_("Google"),'right');
    echo "&nbsp;&nbsp\n";
}