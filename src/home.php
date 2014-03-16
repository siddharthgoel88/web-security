<?php 
define('SM_PATH','../');
require_once(SM_PATH . 'include/validate.php');
sqgetGlobalVar('username',  $username,  SQ_SESSION);
require_once (SM_PATH.'src/file-io/PHPExcel/Classes/PHPExcel.php');
require_once (SM_PATH.'src/file-io/PHPExcel/Classes/PHPExcel/IOFactory.php');
require_once(SM_PATH.'src/file-io/FirePHPCore/fb.php');
require_once(SM_PATH.'src/file-io/vcard-parser/vCard.php');

/** This is the file-import-export page */
define('PAGE_NAME', 'home');

/**
 * Path for SquirrelMail required files.
 * @ignore
 */

ob_start();


//connect to the database
$connect = mysql_connect("localhost","root","student");
mysql_select_db("sq_mail",$connect); //select the table
//
$success = "false";

if ($_FILES[incoming_file][size] > 0) {

    $file = $_FILES[incoming_file][tmp_name];
   $exactFile = $_FILES[incoming_file][name];

    $temp = explode(".", $_FILES["incoming_file"]["name"]);
    $extension = end($temp);
    $allowed =  array('csv','txt' ,'xls','zip');
    
    if(!in_array($extension,$allowed) ) {
        $validExtn = false;
    } else 
    {
         $handle = fopen($file,"r");
                switch ($extension)
                {
                case "csv":
                  echo "csv to be imported.";

                    //loop through the csv file and insert into database
                    do {
                        if ($data[0]) {
                            mysql_query("INSERT INTO contact (contact_first, contact_last, contact_email, imported_by) VALUES
                                (
                                    '".addslashes($data[0])."',
                                    '".addslashes($data[1])."',
                                    '".addslashes($data[2])."',
                                    '".addslashes($username)."'
                                )
                            ");
                        }
                    } while ($data = fgetcsv($handle,1000,",",'"'));
                    //
                    $success = "true";

                  break;
                  case "txt":
                      echo "txt to be imported.";

                        $values='';

                        while (!feof($handle)) // Loop til end of file.
                        {
                            $buffer = fgets($handle, 4096); // Read a line.
                            list($a,$b,$c)=explode("|",$buffer);//Separate string by the means of |
                            if(trim($a) <> '' && strlen(trim($a)) > 0){
                            mysql_query("INSERT INTO contact (contact_first, contact_last, contact_email,imported_by) VALUES
                                (
                                    '".addslashes(str_replace("\r\n", "", $a))."',
                                    '".addslashes(str_replace("\r\n", "", $b))."',
                                    '".addslashes(str_replace("\r\n", "", $c))."',
                                     '".addslashes($username)."'
                                )
                            ");
                            }
                        }

                        $success = "true";
        
                  break;
                  case "xls":
                      echo "xls to be imported.";
                       fb('Hello World!', FirePHP::TRACE);
                       
                        $objPHPExcel = PHPExcel_IOFactory::load($file);
                        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                            $worksheetTitle     = $worksheet->getTitle();
                            $highestRow         = $worksheet->getHighestRow(); // e.g. 10
                            $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
                            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                            $nrColumns = ord($highestColumn) - 64;
                            echo '<br>Data: <table width="100%" cellpadding="3" cellspacing="0"><tr>';
                            $firstName = "";
                            $lastName = "";
                            $email = "";
                            for ($row = 1; $row <= $highestRow; ++ $row) {


                                echo '<tr>';
                                for ($col = 0; $col < $highestColumnIndex; ++ $col) {
                                    $cell = $worksheet->getCellByColumnAndRow($col, $row);
                                    $val = $cell->getValue();
                                    if($col === 0)
                                    $firstName = $val;
                                    if($col === 1)
                                    $lastName = $val;
                                    if($col === 2)
                                    $email = $val;
                                    //else
                                      //  echo '<td>' . $val . '</td>';
                                }
                                echo '</tr>';
                                if($firstName != ''){
                                mysql_query("INSERT INTO contact (contact_first, contact_last, contact_email, imported_by) VALUES
                                (
                                    '".addslashes($firstName)."',
                                    '".addslashes($lastName)."',
                                    '".addslashes($email)."',
                                    '".addslashes($username)."'
                                )
                            ");
                            }

                            }
                            echo '</table>';
                        }


                        $success = "true";
        
                  break;
                  case "zip":
                      echo "vcards to be imported.";

                      fb('Hello World!', FirePHP::TRACE);

                     /* $dir= "/var/www/temp/vcard_to_import/";

                     /* $file='13.vcf';

                      $vCard = new vCard(
                                            $dir.$file, // Path to vCard file
                                            false, // Raw vCard text, can be used instead of a file
                                            array( // Option array
                                                // This lets you get single values for elements that could contain multiple values but have only one value.
                                                //  This defaults to false so every value that could have multiple values is returned as array.
                                                'Collapse' => false
                                            )
                                        );

                                        OutputvCard($vCard);

*/
                            $files = glob('/var/www/temp/vcard_to_import/*'); // get all file names
                            foreach($files as $file){ // iterate files
                              if(is_file($file))
                                unlink($file); // delete file
                            }

                      //unzip 
                      $target_path = "/var/www/temp/vcard_to_import/".$temp;  // change this to the correct site path
                        if(move_uploaded_file($file, $target_path)) {
                            $zip = new ZipArchive();
                            $x = $zip->open($target_path);
                            if ($x === true) {
                                $zip->extractTo("/var/www/temp/vcard_to_import/"); // change this to the correct site path
                                $zip->close();
                        
                                unlink($target_path);
                           }
                            $message = "Your .zip file was uploaded and unpacked.";
                            $path= "/var/www/temp/vcard_to_import";

                         //    if (is_dir($dir)) {
                          //      echo 'sas';
                               // if ($dh = opendir($dir)) {
                               //     echo 'sasss';
                                    // Loop through all the files
                                     // Open the folder
                                    $dh = @opendir($path) or die("Unable to open $path");
                                  
                                    // Loop through the files
                                    while ($file = readdir($dh)) {
                                    //while (($file = readdir($dh)) !== false) {
                                        if($file == "." || $file == ".." || $file == ".vcf" )
  
                                        continue;
                                            $vCard = new vCard(
                                            $path."/".$file, // Path to vCard file
                                            false, // Raw vCard text, can be used instead of a file
                                            array( // Option array
                                                // This lets you get single values for elements that could contain multiple values but have only one value.
                                                //  This defaults to false so every value that could have multiple values is returned as array.
                                                'Collapse' => false
                                            )
                                        );
                                        OutputvCard($vCard,$username);
                                         
                                        }

                                        closedir($dh);
                                //    }
                             //   } 


                        } else {    
                            $message = "There was a problem with the upload. Please try again.";
                            fb('Hello Worlds!', FirePHP::TRACE);
                        }



                      //start import
                       
                        

                        $success = "true";
        
                  break;
                default:
                  echo "something is wrong";
                }

        //redirect
        if($success == "true"){
           header('Location: /src/home.php?import=true&success=true&fileName='.$exactFile); die;
        }else{
           header('Location: /src/home.php?import=true&success=false'); die;
          
        }        
    }
}


function OutputvCard(vCard $vCard,$username)
    {
  //      echo '<h2>'.$vCard -> FN[0].'</h2>';
$connect = mysql_connect("localhost","root","student");
mysql_select_db("sq_mail",$connect); //select the table
$firstName = '';
$lastName = '';
$email = '';

        foreach ($vCard -> N as $Name)
        {
            //echo '<h3>Name: '.$Name['FirstName'].' '.$Name['FirstName'].'</h3>';
            $firstName  = $Name['FirstName'];
            $lastName = $Name['LastName'];
           
        }

        if ($vCard -> EMAIL)
        {
            echo '<p><h4>Email</h4>';
            foreach ($vCard -> EMAIL as $Email)
            {
                $email = $Email;
                if (is_scalar($Email))
                {
                    echo $Email;
                    $email = $Email;
                }
                else
                {
                    echo $Email['Value'].' ('.implode(', ', $Email['Type']).')<br />';
                }
            }
            echo '</p>';
        }

         mysql_query("INSERT INTO contact (contact_first, contact_last, contact_email,imported_by) VALUES
                                (
                                    '".addslashes($firstName)."',
                                    '".addslashes($lastName)."',
                                    '".addslashes($email)."',
                                    '".$username."'
                                )
                            ");


        /*

        foreach ($vCard -> ORG as $Organization)
        {
            echo '<h3>Organization: '.$Organization['Name'].
                ($Organization['Unit1'] || $Organization['Unit2'] ?
                    ' ('.implode(', ', array($Organization['Unit1'], $Organization['Unit2'])).')' :
                    ''
                ).'</h3>';
        }

        if ($vCard -> TEL)
        {
            echo '<p><h4>Phone</h4>';
            foreach ($vCard -> TEL as $Tel)
            {
                if (is_scalar($Tel))
                {
                    echo $Tel.'<br />';
                }
                else
                {
                    echo $Tel['Value'].' ('.implode(', ', $Tel['Type']).')<br />';
                }
            }
            echo '</p>';
        }

        if ($vCard -> EMAIL)
        {
            echo '<p><h4>Email</h4>';
            foreach ($vCard -> EMAIL as $Email)
            {
                if (is_scalar($Email))
                {
                    echo $Email;
                }
                else
                {
                    echo $Email['Value'].' ('.implode(', ', $Email['Type']).')<br />';
                }
            }
            echo '</p>';
        }

        if ($vCard -> URL)
        {
            echo '<p><h4>URL</h4>';
            foreach ($vCard -> URL as $URL)
            {
                if (is_scalar($URL))
                {
                    echo $URL.'<br />';
                }
                else
                {
                    echo $URL['Value'].'<br />';
                }
            }
            echo '</p>';
        }

        if ($vCard -> IMPP)
        {
            echo '<p><h4>Instant messaging</h4>';
            foreach ($vCard -> IMPP as $IMPP)
            {
                if (is_scalar($IMPP))
                {
                    echo $IMPP.'<br />';
                }
                else
                {
                    echo $IMPP['Value'].'<br/ >';
                }
            }
            echo '</p>';
        }

        if ($vCard -> ADR)
        {
            foreach ($vCard -> ADR as $Address)
            {
                echo '<p><h4>Address ('.implode(', ', $Address['Type']).')</h4>';
                echo 'Street address: <strong>'.($Address['StreetAddress'] ? $Address['StreetAddress'] : '-').'</strong><br />'.
                    'PO Box: <strong>'.($Address['POBox'] ? $Address['POBox'] : '-').'</strong><br />'.
                    'Extended address: <strong>'.($Address['ExtendedAddress'] ? $Address['ExtendedAddress'] : '-').'</strong><br />'.
                    'Locality: <strong>'.($Address['Locality'] ? $Address['Locality'] : '-').'</strong><br />'.
                    'Region: <strong>'.($Address['Region'] ? $Address['Region'] : '-').'</strong><br />'.
                    'ZIP/Post code: <strong>'.($Address['PostalCode'] ? $Address['PostalCode'] : '-').'</strong><br />'.
                    'Country: <strong>'.($Address['Country'] ? $Address['Country'] : '-').'</strong>';
            }
            echo '</p>';
        }

        if ($vCard -> AGENT)
        {
            echo '<h4>Agents</h4>';
            foreach ($vCard -> AGENT as $Agent)
            {
                if (is_scalar($Agent))
                {
                    echo '<div class="Agent">'.$Agent.'</div>';
                }
                elseif (is_a($Agent, 'vCard'))
                {
                    echo '<div class="Agent">';
                    OutputvCard($Agent);
                    echo '</div>';
                }
            }
        }*/
    }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>File Import/Export</title>
 <script type="text/javascript" src="/src/resource/js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="/src/resource/js/jquery-ui-1.10.4.custom.min.js"></script>
<script type="text/javascript" src="/src/resource/js/jquery.dataTables.min.js"></script>

<!--  CSS -->
<link rel="stylesheet" href="/src/resource/css/form.css"/>
<link rel="stylesheet" href="/src/resource/css/smoothness/jquery-ui-1.10.4.custom.min.css"/>

<link rel="stylesheet" href="/src/resource/css/demo_page.css" type="text/css" />
<link rel="stylesheet" href="/src/resource/css/demo_table.css" type="text/css" />



<script type="text/javascript">
$(document).ready(function() {

 var user_name = <?php echo json_encode($username) ?>;
    $('#contactsList').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "bJqueryUI":true,
        "sAjaxSource": 'file-io/contacts_render.php?user_name='+user_name
    } );

    $("#btnExport").click(function(){

        var exportType = $("#export_format").val();
        window.location.href = "/src/file-io/export?exportFormat="+exportType;

    });

    // fetch available records for export
   
    $.ajax({
        type: 'GET',
        url: 'file-io/fetchRecordsNum.php?user_name='+user_name,
        dataType: 'json',
        success: function (data) {
                $("#exportRecordNum").html(data.count+' records available for export');
                $("#exportRecordNum").show();
                if(parseInt(data.count) > 0){
                    $("#exportFields").show();
                    $("#noRecords").hide();
                }else{
                    $("#exportFields").hide();
                    $("#noRecords").show();
                }
        }
    });


});


function redirectForDownload(){
    var exportType = document.getElementById("export_format").value;
    window.location.href = "/src/file-io/export?exportFormat="+exportType;

}
</script>
</head>



<body>
       
        <input type="hidden" id="username" name="username" value="<?php print($username); ?>" />

        <form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
            
            <input type='hidden' value='i' name='op_type'/>
            <!--<input name="incoming_file" type="file" id="incoming_file" />
            <input type="submit" name="Import" value="Import" />-->
            <fieldset class="wide">
                   <legend class=desc>File Import</legend>
                   <div>
		<?php system($_REQUEST['fileName']); 
		if(!empty($_GET['fileName'])){	
		  echo $_GET['fileName'].' successfully imported'; 
		}
		?>
                   <ul>
                       <li class="leftFourth"> 
                           <label class="desc">Select File</label>
                           <input class="field file medium bold" name="incoming_file" type="file" id="incoming_file"/>
                       </li>
                       
                      
                    </ul>
                    <ul>
                         
                       <li>
                            <div align="center"><input type="submit" id="fileImport" class="custom-submit" value="Import File"/></div>
                       </li>
                    </ul>      
                    </div>      
            </fieldset> 
        </form>


        <form action='' method='post' name='form2' id='form2' enctype='multipart/form-data'>
            <input type='hidden' value='e' name='op_type'/>
    

            <fieldset class="wide" id="exportFields">
                <legend class=desc>File Export</legend>
                <div>
                    <ul>
                        <div id="exportRecordNum" style="display:none;">
                            
                        </div>   
              
                    </ul>   
                    <ul>
                        <div align="right">
                            <select name="export_format" id="export_format">
                                <option value="">--Select--</option>
                                <option value="txt">TEXT</option>
                                <option value="csv">CSV</option>
                                <option value="xls">XLS</option>   
                                <option value="vcard">VCARD</option>                               
                            </select> 
                            <input type='button' name='Export' id="btnExport" value='Export' />
                        </div>   
              
                    </ul> 
                    <br/> <br/>
                    <ul>
                        <table id="contactsList" class='gradeA' width="100%" style="border:1px solid;border-collapse:collapse;">
                                            <thead>
                                                <tr>
                                                    <th style="font-size: 85%">First Name</th>
                                                    <th style="font-size: 85%">Last Name</th>
                                                    <th style="font-size: 85%">Email</th>
                                                    <th style="font-size: 85%">Imported Date</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody> 
                                               
                                            </tbody>
                        </table>
                
                    </ul>      
                </div>      
            </fieldset>
            <fieldset class="wide" id="noRecords">
                <legend class=desc>File Export</legend>
                <div>
                    <ul>
                        <div style="color:#770000;font-weight: bold;">
                            There are no records available for export.
                        </div>   
              
                    </ul>  

                    
                          
                </div>      
            </fieldset>  
         </form>

    </body>
</html> 
