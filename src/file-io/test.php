<?php 


$emails = array(); 
$emails[] = array('firstName' => 'Igor', 'middleName' => 'Herson', 'lastName' => 'Aquino', 'email1' => 'igorhaf@gmail.com', 'email2' => 'ihaf@ig.com.br', 'email3' => 'ihaf@hotmail.com');
//$emails[] = array('firstName' => 'Jenner', 'middleName' => 'Portela', 'lastName' => 'Chagas', 'email1' => 'gtr_portela@hotmail.com', 'email2' => 'jenner.gtr@gmail.com'); 

$ql = chr(13).chr(10); 
        $arrayParams = array('BEGIN' => 'VCARD', 'VERSION' => '3.0', 'FN', 'EMAIL' => array(), 'N' => array(), 'TYPE' => 'INTERNET', 'END' => 'VCARD'); 
         $fh = fopen('/var/www/temp'.'/vc.vcf', 'w+'); 
        foreach($this->arrayList as $array) { 
            $arrayParams['N'][2] = (isset($array['lastName']))?($array['lastName']):(''); 
            $arrayParams['N'][0] = (isset($array['firstName']))?($array['firstName']):(''); 
            $arrayParams['N'][1] = (isset($array['middleName']))?($array['middleName']):(''); 
            $arrayParams['FN'] = trim($arrayParams['N'][0].' '.$arrayParams['N'][1].' '.$arrayParams['N'][2]); 
            $arrayParams['EMAIL'][0] = (isset($array['email1']))?($array['email1']):(''); 
            $arrayParams['EMAIL'][1] = (isset($array['email2']))?($array['email2']):(''); 
            $arrayParams['EMAIL'][2] = (isset($array['email3']))?($array['email3']):(''); 
            fwrite($fp, 'BEGIN:'.$arrayParams['BEGIN'].$ql); 
            fwrite($fp, 'VERSION:'.$arrayParams['VERSION'].$ql); 
            fwrite($fp, 'FN:'.$arrayParams['FN'].$ql); 
            fwrite($fp, 'N:'.$arrayParams['N'][0].';'.$arrayParams['N'][1].';'.$arrayParams['N'][2].';;'.$ql); 
            fwrite($fp, 'EMAIL;TYPE='.$arrayParams['TYPE'].':'.$arrayParams['EMAIL'][0].$ql); 
            if($arrayParams['EMAIL'][1] != '') { 
                fwrite($fp, 'EMAIL;TYPE='.$arrayParams['TYPE'].';TYPE=HOME:'.$arrayParams['EMAIL'][1].$ql); 
            } 
            if($arrayParams['EMAIL'][2] != '') { 
                fwrite($fp, 'EMAIL;TYPE='.$arrayParams['TYPE'].';TYPE=HOME:'.$arrayParams['EMAIL'][2].$ql); 
            } 
            fwrite($fp, 'END:'.$arrayParams['END'].$ql); 
        } 
        fclose($fp); 
?> 
<p align="right"><span><a href="export.vcf">Exportar para vCard</a></span> - <span><a href="export.csv">Exportar para CVS</a></span></p>