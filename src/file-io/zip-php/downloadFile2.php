<?php

$file_names = array('2.vcf');
$archive_file_name=time().'.zip';
$file_path='/var/www/temp/vcf/';



	$zip = new ZipArchive();
	//create the file and throw the error if unsuccessful
	if ($zip->open($archive_file_name, ZIPARCHIVE::CREATE )!==TRUE) {
    	exit("cannot open <$archive_file_name>\n");
	}
	//add each files of $file_name array to archive
	foreach($file_names as $files)
	{
  		$zip->addFile($file_path.$files,$files);
		//echo $file_path.$files,$files."<br />";
	}
	$zip->close();
	//then send the headers to foce download the zip file
	header("Content-type: application/zip"); 
	header("Content-Disposition: attachment; filename=$archive_file_name"); 
	header("Pragma: no-cache"); 
	header("Expires: 0"); 
	readfile("$archive_file_name");
	exit;


?>