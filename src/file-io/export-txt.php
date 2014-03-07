
<?php
    $connect = mysql_connect("localhost","root","student");
    mysql_select_db("sq_mail",$connect); //select the table

    echo mysql_error();
    $fh = fopen('/var/www/temp'.'/file.txt', 'w+');

        $result = mysql_query("SELECT * FROM contact");
        while ($row = mysql_fetch_array($result)) {
            $last = end($row);
            foreach ($row as $item) {
                fwrite($fh, $item);
                if ($item != $last)
                    fwrite($fh, "\t");
                //echo $item;
            }
            fwrite($fh, "\n");
        }
        fclose($fh);

    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename('/var/www/temp'.'/file.txt'));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize('/var/www/temp'.'/file.txt'));
    readfile('/var/www/temp'.'/file.txt');
    exit;

?>

