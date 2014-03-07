<?php



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
        if($entry!="."||$entry!=".."||$entry!=""){
            $temp=  explode(".", $entry);
            $plugin_name= $temp[0];
             if(is_plugin_enabled($plugin_name, $plugins_list)){
                 echo '<tr><td>'.'<input type="radio"  Name="uninstall_option" value="'.$plugin_name.'">'
                         . '</td><td>'.$plugin_name.'</td></tr>';
                 $num_installed++;
            }
        }
    }

if($num_installed==0) echo '<tr><td colspan=3><center><font color=#FF9900><b><i>Sorry no custom plugins have been installed yet for '.$_SESSION['username'].'.Install it from below</i></b></font></center></td></tr>';
else echo '<tr><td><input type="submit" name="submit" value="Submit"></tr></td>';
echo <<<EOT
  
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
                 echo '<tr><td>'.'<input type="radio"  Name="install_option" value="'.$plugin_name.'" >'
                         . '</td><td>'.$plugin_name.'</td></tr>';
            }
        }
    }


echo <<<EOT
   </tbody>
      </table>
    <input type="hidden" name="state" value="install_plugin">
      <input type="submit" name="submit" value="Submit">
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

?>
