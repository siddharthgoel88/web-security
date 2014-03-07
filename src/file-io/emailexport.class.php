<?php 
/*  
 * To change this template, choose Tools | Templates 
 * and open the template in the editor. 
 */ 
class emailExport { 
//firstName //middleName //lastName //email1 //email2 //email3 
    var $filename = 'export'; 

    function getList($arrayList) { 
        $this->arrayList = $arrayList; 
    } 
    function exportCsv() { 
        $ql = chr(13).chr(10); 
        $arrayParams = array('First Name' => '', 'Middle Name' => '', 'Last Name' => '' ,'Title' => '','Suffix' => '','Initials' => '','Web Page' => '','Gender' => '','Birthday' => '','Anniversary' => '','Location' => '','Language' => '','Internet Free Busy' => '','Notes' => '','E-mail Address' => '','E-mail 2 Address' => '','E-mail 3 Address' => '','Primary Phone' => '','Home Phone' => '','Home Phone 2' => '','Mobile Phone' => '','Pager' => '','Home Fax' => '','Home Address' => '','Home Street' => '','Home Street 2' => '','Home Street 3' => '','Home Address PO Box' => '','Home City' => '','Home State' => '','Home Postal Code' => '','Home Country' => '','Spouse' => '','Children' => '','Manager\'s Name' => '','Assistant\'s Name' => '','Referred By' => '','Company Main Phone' => '','Business Phone' => '','Business Phone 2' => '','Business Fax' => '','Assistant\'s Phone' => '','Company' => '','Job Title' => '','Department' => '','Office Location' => '','Organizational ID Number' => '','Profession' => '','Account' => '','Business Address' => '','Business Street' => '','Business Street 2' => '','Business Street 3' => '','Business Address PO Box' => '','Business City' => '','Business State' => '','Business Postal Code' => '','Business Country' => '','Other Phone' => '','Other Fax' => '','Other Address' => '','Other Street' => '','Other Street 2' => '','Other Street 3' => '','Other Address PO Box' => '','Other City' => '','Other State' => '','Other Postal Code' => '','Other Country' => '','Callback' => '','Car Phone' => '','ISDN' => '','Radio Phone' => '','TTY/TDD Phone' => '','Telex' => '','User 1' => '','User 2' => '','User 3' => '','User 4' => '','Keywords' => '','Mileage' => '','Hobby' => '','Billing Information' => '','Directory Server' => '','Sensitivity' => '','Priority' => '','Private' => '','Categories' => '');
        $arrayParamsKeys = array_keys($arrayParams); 
        $fp = fopen($this->filename.'.csv', 'w'); 
        fwrite($fp, implode(',', $arrayParamsKeys).$ql); 
        foreach($this->arrayList as $array) { 
            $arrayParams['First Name'] = (isset($array['firstName']))?($array['firstName']):(''); 
            $arrayParams['Middle Name'] = (isset($array['middleName']))?($array['middleName']):(''); 
            $arrayParams['Last Name'] = (isset($array['lastName']))?($array['lastName']):(''); 
            $arrayParams['E-mail Address'] = (isset($array['email1']))?($array['email1']):(''); 
            $arrayParams['E-mail 2 Address'] = (isset($array['email2']))?($array['email2']):(''); 
            $arrayParams['E-mail 3 Address'] = (isset($array['email3']))?($array['email3']):(''); 
            fwrite($fp, implode(',', $arrayParams).$ql); 
        } 
        fclose($fp); 
    } 
    function exportVcard() { 
        $ql = chr(13).chr(10); 
        $arrayParams = array('BEGIN' => 'VCARD', 'VERSION' => '3.0', 'FN', 'EMAIL' => array(), 'N' => array(), 'TYPE' => 'INTERNET', 'END' => 'VCARD'); 
        $fp = fopen($this->filename.'.vcf', 'w'); 
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
    } 
} 

?>