<?php
require 'PHPExcel/Classes/PHPExcel.php';
require_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';
require_once('FirePHPCore/fb.php');
include_once('vcard2/class.vCard.inc.php');

// Function for basic field validation (present and neither empty nor only white space
function IsNullOrEmptyString($question){
    return (!isset($question) || trim($question)==='');
}


if (!empty($_GET[exportFormat]) && $_GET[exportFormat] == 'csv') { 

// if requested type is CSV..
//connect to the database
$connect = mysql_connect("localhost","root","student");
mysql_select_db("sq_mail",$connect); //select the table

echo mysql_error();

// Fetch Record from Database

$output = "";
$table = "contact"; // Enter Your Table Name 
$sql = mysql_query("select contact_first,contact_last,contact_email from $table");
$columns_total = mysql_num_fields($sql);

// Get The Field Name

/*for ($i = 0; $i < $columns_total; $i++) {
$heading = mysql_field_name($sql, $i);
$output .= '"'.$heading.'",';
}
$output .="\n";*/

// Get Records from the table

while ($row = mysql_fetch_array($sql)) {
for ($i = 0; $i < $columns_total; $i++) {
$output .='"'.$row["$i"].'",';
}
$output .="\n";
}

// Download the file

$filename = "export.csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $output;
exit;
}

// if txt..
if (!empty($_GET[exportFormat]) && $_GET[exportFormat] == 'txt') { 
    $connect = mysql_connect("localhost","root","student");
    mysql_select_db("sq_mail",$connect); //select the table

    echo mysql_error();
    $current_time = time();
    $fh = fopen('/var/www/temp'.'/file_'.$current_time.'.txt', 'w+');

        $result = mysql_query("SELECT contact_first,contact_last,contact_email FROM contact");
        while ($row = mysql_fetch_row($result)) {
            $last = end($row);
            foreach ($row as $item) {
                fwrite($fh, $item);
                if ($item != $last)
                    fwrite($fh, "|");
                    //echo $fh;
            }
            fwrite($fh, "\r\n");
        }
        fclose($fh);

    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename('/var/www/temp'.'/file_'.$current_time.'.txt'));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize('/var/www/temp'.'/file_'.$current_time.'.txt'));
    readfile('/var/www/temp'.'/file_'.$current_time.'.txt');
    exit;
    
}


if (!empty($_GET[exportFormat]) && $_GET[exportFormat] == 'xls') { 
    $connect = mysql_connect("localhost","root","student");
    mysql_select_db("sq_mail",$connect); //select the table

    echo mysql_error();
    
     $result = mysql_query("SELECT contact_first,contact_last,contact_email FROM contact");

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $rowCount = 1;
    while($row = mysql_fetch_array($result)){
        if($row['contact_first'] != ''){
        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['contact_first']);
        $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['contact_last']);
        $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['contact_email']);
        }
        $rowCount++;

    }
    $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
    $objWriter->save('/var/www/temp'.'/xls-file.xls');

    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename('/var/www/temp'.'/xls-file.xls'));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize('/var/www/temp'.'/xls-file.xls'));
    readfile('/var/www/temp'.'/xls-file.xls');
    exit;
    
}


if (!empty($_GET[exportFormat]) && $_GET[exportFormat] == 'vcard') { 
    $connect = mysql_connect("localhost","root","student");
    mysql_select_db("sq_mail",$connect); //select the table

    echo mysql_error();
    
    $result = mysql_query("SELECT contact_first,contact_last,contact_email FROM contact");


    //generate vcards and store them in temp folder...
    $files = glob('/var/www/temp/vcf/*'); // get all file names
                            foreach($files as $file){ // iterate files
                              if(is_file($file))
                                unlink($file); // delete file
        }

    $i=1;
    while($row = mysql_fetch_array($result)){
        
        $vCard = (object) new vCard('','');

        $vCard->setFirstName($row['contact_first']);
        $vCard->setMiddleName($row['contact_last']);
        $vCard->setLastName($row['contact_email']);

        $vCard->writeCardFile($i);
        $i++;
        
        $rowCount++;
    }

     //code to generate VCARD ZIP.

     $zipArchive = new ZipArchive();
     $archive_file_name='/var/www/temp/contacts_'.time().'.zip';
    //create the file and throw the error if unsuccessful
    if ($zipArchive->open($archive_file_name, ZIPARCHIVE::CREATE )!==TRUE) {
        exit("cannot open <$archive_file_name>\n");
    }


   $file_names = "";
    $file_path = "/var/www/temp/vcf/";
    $dir= "/var/www/temp/vcf/";



     if (is_dir($dir)) {
        if ($dh = opendir($dir)) {

            //Add the directory
           // $zipArchive->addEmptyDir('contacts');
            
            // Loop through all the files
            while (($file = readdir($dh)) !== false) {
                    if(!IsNullOrEmptyString($file))
                    $zipArchive->addFile($dir . $file,$file);
                    
                }
            }
        } 


    $zipArchive->close(); 
    //then send the headers to foce download the zip file */
   // if(file_exists($archive_file_name))
   // {
        header("Content-type: application/zip"); 
        header("Pragma: no-cache"); 
        header("Expires: 0"); 
        header('Content-Disposition: attachment; filename="'.$archive_file_name.'"');
        readfile($archive_file_name);
        unlink($archive_file_name);
        exit;
 // }
    
    
}



?>

