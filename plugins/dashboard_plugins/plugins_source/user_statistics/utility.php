<?php
function read_custom_config(){
    $path = '../plugins/dashboard_plugins/plugin_config.txt';
    $row = 1;
    $plugins_enabled = array();
    if (($handle = fopen($path, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, ",")) !== FALSE) {
        $num = count($data);
        $plugins_enabled[$data[0]]=array();
        $row++;
        for ($c=1; $c < $num; $c++) {
            $plugins_enabled[$data[0]][$c-1]=$data[$c];
           
        }
         
    }
    
    fclose($handle);
    return $plugins_enabled;
    }
    else return null;
}

function is_plugin_enabled($plugin_name,$plugin_list){
    $current_user=$_SESSION['username'];
    if(is_array($plugin_list)&&count($plugin_list)!=0){
    foreach($plugin_list[$current_user] as $plugin){
        if ($plugin==$plugin_name){
            return true;
        }
    }
    }
    return false;
}
