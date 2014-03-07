<?php

require_once("string_functions.php");

$helper_functions_dir = dirname(__FILE__)."/";
$main_config_file_path = $helper_functions_dir.'./../../config/config.php';

ini_set('auto_detect_line_endings',TRUE);

function read_custom_config(){
    
    $helper_functions_dir = dirname(__FILE__)."/";
    $path = $helper_functions_dir.'plugin_config.txt';
    $row = 1;
    $plugins_enabled = array();
    if (($handle = @fopen($path, "r")) !== FALSE) {
            while (($data = @fgetcsv($handle, ",")) !== FALSE) {
            $num = count($data);
            $plugins_enabled[$data[0]]=array();
            $row++;
            for ($c=1; $c < $num; $c++) {
                $plugins_enabled[$data[0]][$c-1]=$data[$c];
           
            }
         
        }
    
    fclose($handle);
    }
    var_dump($plugins_enabled);
    return $plugins_enabled;
  
}

function write_custom_config($plugins_enabled){
    global $helper_functions_dir;
    $path = $helper_functions_dir.'plugin_config.txt';

    $fp = fopen($path, 'w');
  
    $users=array_keys($plugins_enabled);
    $i=0;
    
    foreach ($plugins_enabled as $line) {
        $csv_line=array($users[$i]);
        foreach($line as $token){
            $csv_line[]=$token;
        }
        fputcsv_eol($fp, $csv_line,"\r\n");
        $i++;
    }
    fclose($fp);
}

function disable_plugin($plugin_name,&$plugin_list){
    $current_user=get_user_name($_SESSION['username']);
    if(is_plugin_enabled($plugin_name, $plugin_list)){
        if(($key = array_search($plugin_name, $plugin_list[$current_user])) !== false) {
            unset($plugin_list[$current_user][$key]);
            return true;
        }
    } 
    return false;   
}

function is_plugin_enabled($plugin_name,$plugin_list){
    $current_user=get_user_name($_SESSION['username']);
    $current_user=get_user_name($current_user);
    if(@is_array($plugin_list[$current_user])&&count($plugin_list)!=0){
    foreach($plugin_list[$current_user] as $plugin){
        if ($plugin==$plugin_name&&$plugin!=""){
            return true;
        }
    }
    }
    return false;
}

function enable_plugin($plugin_name,&$plugin_list){
    $current_user=get_user_name($_SESSION['username']);
    $current_user=get_user_name($current_user);
    if(is_plugin_enabled($plugin_name, $plugin_list)){
        return false;
    }
    else{
        $plugin_list[$current_user][]=$plugin_name;
        return true;
    }
}


function is_plugin_in_master_list($plugin_name){
    global $plugins;
    if(array_search($plugin_name,$plugins)!=false){
        return true;        
    }
    return false;
}

function add_plugin_master_list($plugin_name){
    global $plugins,$main_config_file_path;
    $last_plugin=count($plugins);
   
    if(!is_plugin_in_master_list($plugin_name)){
        $handle=@fopen($main_config_file_path,"a");
        $string = "\$plugins[".$last_plugin."] = '".$plugin_name."';\n";
        fwrite($handle, $string);
        fclose($handle);
        return true;
    }
    return false;
}

function is_plugin_directory_present($plugin_name){
    if(is_dir("../".$plugin_name)){
        return TRUE;
    }
    else return FALSE;
}

function get_file_name($string){
    $names_array=explode(".",$string);
    return $names_array[0];
}

function fputcsv_eol($fp,$array,$eol){
    fputcsv($fp, $array);
    if("\n" != $eol && 0 === fseek($fp, -1, SEEK_CUR)) {
          fwrite($fp, $eol);
    }
}

/* Move below code to Dashboard plugins specific file
function show_plugins(){
    $dir = opendir("plugin_zips");
    $plugins_list=  read_custom_config();
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

echo '
<form  method="GET" action="" name="uninstall_a_plugin" >
      <table border="1" width="100%">
';

$num_installed=0;
while (false !== ($entry = readdir($dir))) {
        if($entry!="."||$entry!=".."){
            $temp=  explode(".", $entry);
            $plugin_name= $temp[0];
             if(is_plugin_enabled($plugin_name, $plugins_list)){
                 echo '<tr><td>'.'<input type="radio"  name="uninstall_option" value="'.$plugin_name.'"></br>'
                         . '</td><td>'.$plugin_name.'</td></tr>';
                 $num_installed++;
            }
        }
    }

if($num_installed==0) echo '<tr><td colspan=3><center><font color=#FF9900><b><i>Sorry no custom plugins have been installed yet for '.$_SESSION['username'].'.Install it from below</i></b></font></center></td></tr>';
echo <<<EOT
   </tbody>
      </table>
      <input type="hidden" name="state" value="uninstall_plugin">
      
    </form>
EOT;

echo <<<EOD
    <table width="100%" cellpadding="1" border="0" bgcolor="#dcdcdc" align="center">
        <tbody>
            <tr>
                <td align="center"><b>Available Plugins</b></td>
            </tr>
        </tbody>
        </table>
EOD;

$dir = opendir("plugin_zips");
echo '
<form  method="GET" action="" name="install_a_plugin" >
      <table border="1" width="100%">
';
       
while (false !== ($entry = readdir($dir))) {
        if($entry!="."||$entry!=".."){
            $temp=  explode(".", $entry);
            $plugin_name= $temp[0];
             if(!is_plugin_enabled($plugin_name, $plugins_list)&&$plugin_name!=""){
                 echo '<tr><td>'.'<input type="radio" form="install_a_plugin" name="install_option" value="'.$plugin_name.'" ></br>'
                         . '</td><td>'.$plugin_name.'</td></tr>';
            }
        }
    }


echo <<<EOT
   </tbody>
      </table>
    <input type="hidden" name="state" value="install_plugin">
      
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

function install_from_folder($name){
    $plugin_name=  get_file_name_without_ext($name);
    $zip = new ZipArchive;
    $zip->open("plugin_zips/".$name);
    if (!file_exists("../".$plugin_name)) {
    mkdir("../".$plugin_name,0777);
    }
    $zip->extractTo('../'.$plugin_name."/");
    $zip->close();
       
    $plugin_list=read_custom_config();
    enable_plugin($plugin_name,$plugin_list);
    write_custom_config($plugin_list);
    add_plugin_master_list($plugin_name);
    }


function get_file_name_without_ext($name){
    $tokens = explode(".",$name);
    return $tokens[0];;
}

function is_iterable($var)
{
    return $var !== null 
        && (is_array($var) 
            || $var instanceof Iterator 
            || $var instanceof IteratorAggregate
            );
}

*/

?>




