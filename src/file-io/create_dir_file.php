<?php
 $dir = '/var/www/temp/vcard_to_import';

 // create new directory with 777 permissions if it does not exist yet
 // owner will be the user/group the PHP script is run under
 if ( !file_exists($dir) ) {
  mkdir ($dir, 0777);
 }

 file_put_contents ($dir.'/test.txt', 'Hello File');
 ?>
