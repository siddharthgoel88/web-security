<?php

$main_config_file_path = "../../config/config.php";

//Returns the plugins installed. Always use this function
function installed_plugins(){
    
    global $plugins;
    return $plugins;
    
}

//Restart squirrel mail after this for plugin to work
function install_plugin_global($plugin_name){
    global $plugins,$main_config_file_path;
    $last_plugin=count(installed_plugins());
   
    if(!is_plugin_global($plugin_name)){
        $handle=@fopen($main_config_file_path,"a");
        $string = "<?php \$plugins[] = '".$plugin_name."';?>";
        fwrite($handle, $string);
        fclose($handle);
        return true;
    }
    return false;
}

//Returns bool if plugin is in global list or not
function is_plugin_global($plugin_name){
    global $plugins;
    if(count( $plugins)==0) return false;
    if(array_search($plugin_name,$plugins)!=false){
        return true;        
    }
    return false;
    
}

//This function removes the plugin from config.php file
function remove_plugin_global($plugin_name){
   
    global $plugins,$main_config_file_path;
      
    $file = file_get_contents($main_config_file_path);
    $lines = explode("\n", $file);
    $exclude = array();
    foreach ($lines as $line) {
        if (strpos($line, $plugin_name) !== FALSE) {
              continue;
        }
        $exclude[] = $line;
    }
    $to_write =  implode("\n", $exclude);

     $config_file_write = fopen($main_config_file_path,"w");
     fwrite($config_file_write,$to_write);
     
    
    
}

function show_plugins(){
    
    $plugins_list = installed_plugins();
  
    
    //Just show the heading "Installed Plugins"
    echo <<<EOD
    <br><br>
    <table width="100%" cellpadding="1" border="0" bgcolor="#dcdcdc" align="center">
        <tbody>
            <tr>
                <td align="center"><b>Installed Plugins</b></td>
            </tr>
        </tbody>
        </table>
EOD;
    
    //Show installed plugins
    echo '<form  method="GET" action="" name="uninstall_a_plugin" >
      <table border="1" width="100%">';
    if(!count($plugins_list)==0){
        foreach($plugins_list as $plugin){
            echo '<tr><td>'.
               '<input type="radio"  Name="uninstall_option" value="'.$plugin.'">'
                . '</td><td>'.$plugin.'</td></tr>';
        }
    }
    
    echo '</table><input type="hidden" name="state" value="uninstall_plugin"><input type="submit" name="submit" value="Uninstall"></form>';
    
    //Show available plugin zips
    $dir = opendir("plugins_source");
echo '
<form  method="GET" action="" name="install_a_plugin" >
      <table border="1" width="100%">
';
       
while (false !== ($entry = readdir($dir))) {
        if($entry!="."||$entry!=".."){
            $temp=  explode(".", $entry);
            $plugin_name= $temp[0];
             if(!is_plugin_global($plugin_name)&&$plugin_name!=""){
                 echo '<tr><td>'.'<input type="radio"  Name="install_option" value="'.$plugin_name.'" >'
                         . '</td><td>'.$plugin_name.'</td></tr>';
            }
        }
    }


echo <<<EOT
   </tbody>
      </table>
    <input type="hidden" name="state" value="install_plugin">
      <input type="submit" name="Submit" value="Install">
      </div>
    </form>
EOT;

echo <<<EOT
<div style="text-align: center;border-width:1px;border-style:dotted;border-color:blue">
<h4> You can also install a plugin via the following file upload box</h4>
<form action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="state" value="file_upload">
<label for="file">Filename:</label>
<input type="file" name="user_file" id="file"><br>
<input type="submit" name="submit" value="Submit">

</div>
EOT;

    
}

function recurse_copy($src,$dst){ 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
} 

function install_from_folder($folder_name){
    
    $src = "plugins_source".$folder_name;
    $dst = "../".$folder_name;
    
    recurse_copy($src,$dst);
    
    install_plugin_global($folder_name);
     
}

function is_dir_present($name){
   $path = "../".$name;
   return is_dir($path);
}


function zip_file_install(){
if($_FILES["user_file"]["name"]) {
	$filename = htmlspecialchars($_FILES["user_file"]["name"]);
	$source = htmlentities($_FILES["user_file"]["tmp_name"]);
	$type = $_FILES["user_file"]["type"];
	$name = explode(".", $filename);
	$accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
	foreach($accepted_types as $mime_type) {
		if($mime_type == $type) {
			$okay = true;
			break;
		} 
	}
	
	$continue = strtolower($name[1]) == 'zip' ? true : false;
	if(!$continue) {
		echo '<script>alert("Please only upload zip files")</script>';
	}
        else{
            $target_path = "./temp/".$name[0];  // change this to the correct site path
            if(move_uploaded_file($source, $target_path)) {
		$zip = new ZipArchive();
		$x = $zip->open("./temp/".$name[0]);
		if ($x === true) {
			$zip->extractTo("../".$name[0]); // change this to the correct site path
			$zip->close();
	
			unlink($target_path);
		}
            }
            install_plugin_global($name[0]);
            return true;
        }
 }
    return false;
    
}
