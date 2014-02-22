<?php
    $handle = fopen(sys_get_temp_dir().'/file.txt', "w+");
    fwrite($handle, "text1.....");
    fclose($handle);

$str = "test";

    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename(sys_get_temp_dir().'/file.txt'));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize(sys_get_temp_dir().'/file.txt'));
    readfile('file.txt');
    //echo $str;
    exit;
?>