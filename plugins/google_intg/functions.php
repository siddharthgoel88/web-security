<?php

function getGoogleCode($type = null) {
    require_once 'config.php';
    global $client_id;
    global $client_secret;
    global $developer_key;
    
    global $redirect_uri;

    $client = new Google_Client();
    $client->setApplicationName("CS5331 - Google Intg");
    $client->setScopes(array('https://www.google.com/m8/feeds/'));
   

    $client->setClientId($client_id);
    $client->setClientSecret($client_secret);
    $client->setRedirectUri($redirect_uri);
    $client->setDeveloperKey($developer_key);
    $client->setState("got_code_contacts");
    
    switch ($type) {
        case 'contacts':
            //default is contacts so we use it as is
            break;
        case 'calendar':
            //change variables to calendar specific
            $client->setScopes(array('https://www.googleapis.com/auth/calendar'));
            $client->setState("got_code_calendar");
            break;
    }

   

    echo "<script>window.top.location=\"" . $client->createAuthUrl() . "\"</script>";
}

function updateGoogleCalendar(){
    
    require_once 'config.php';
    global $client_id;
    global $client_secret;
    global $developer_key;
    global $redirect_uri;
    
    $client = new Google_Client();
    $client->setApplicationName("CS5331 - Google Intg");
    $client->setClientId($client_id);
    $client->setClientSecret($client_secret);
    $client->setRedirectUri($redirect_uri); 
    $client->setDeveloperKey($developer_key);
    $client->setScopes(array("https://www.googleapis.com/auth/calendar"));
    $client->setState("got_code_calendar");
        
    $cal = new Google_CalendarService($client);
        

    if (isset($_GET['code'])) {
        $client->authenticate(htmlentities($_GET['code']));
        $_SESSION['token'] = $client->getAccessToken();
    }
    else{
        echo "<script>window.top.location=\"" . $client->createAuthUrl() . "\"</script>";
    }

    if (isset($_SESSION['token'])) {
        $client->setAccessToken(htmlentities($_SESSION['token']));
        $event = new Google_Event();
        $event->setSummary(htmlentities($_SESSION['ename']));
        $start = new Google_EventDateTime();
        $start_date_time=$_SESSION['start_date']."T".$_SESSION['start_time'].":00+08:00";
        $start->setDateTime($start_date_time);
        $event->setStart($start);
        $end = new Google_EventDateTime();
        $end_date_time=$_SESSION['end_date']."T".$_SESSION['end_time'].":00+08:00";
        $end->setDateTime($end_date_time);
        $event->setEnd($end);
        $createdEvent = $cal->events->insert('primary', $event);
       
    }

}

function getGoogleContacts() {

    require_once 'config.php';
    global $client_id;
    global $client_secret;
    global $developer_key;
    global $redirect_uri;


    $client = new Google_Client();
    $client->setApplicationName("CS5331 - Google Intg");
    $client->setScopes(array(
        'https://www.google.com/m8/feeds/'
    ));



    $client->setClientId($client_id);
    $client->setClientSecret($client_secret);
    $client->setRedirectUri($redirect_uri);
    $client->setDeveloperKey($developer_key);

    if (isset($_GET['code'])) {
        $client->authenticate();
        $_SESSION['access_token'] = $client->getAccessToken();
    }

    if (isset($_SESSION['access_token'])) {
        $client->setAccessToken($_SESSION['access_token']);
        $token = json_decode($_SESSION['access_token']);
        $auth_pass = $token->access_token;


        $req = new Google_HttpRequest("https://www.google.com/m8/feeds/contacts/default/full?updated-min=2007-03-16T00:00:00
");
        $req->setRequestHeaders(array('GData-Version' => '3.0', 'content-type' => 'application/atom+xml; charset=UTF-8; type=feed'));

        $val = $client->getIo()->authenticatedRequest($req);
        unset($_SESSION['access_token']);

        // The contacts api only returns XML responses.
        $response = $val->getResponseBody();

        $doc = new DOMDocument;
        $doc->recover = true;
        $doc->loadXML($response);

        $xpath = new DOMXPath($doc);
        $xpath->registerNamespace('gd', 'http://schemas.google.com/g/2005');

        $emails = $xpath->query('//gd:email');
       
        $to_write_array;
        $counter = 0;
        header("refresh:5;url=localhost");
        echo"<p>The following contacts have been imported : </p>";
        echo "<p>You can check the contacts in Address Book. Redirecting in 5 seconds</p>";
        echo"<table border=1><body><tr><th>Nick Name</th><th>First Name</th><th>Last Name</th><th>Email</th></tr>";
        foreach ($emails as $email) {
            $this_array= array();
            
            echo "<tr><td>";
            $name = $email->parentNode->getElementsByTagName('title')->item(0)->textContent;
            $name_tokens = explode(" ",$name);
            $this_array[0]= $name_tokens[0];
            $this_array[1]= $name_tokens[0];
           
            if(count($name_tokens)>1){
                $this_array[2]=  $name_tokens[1];
            }
            else{
                
                $this_array[2]=  "";
            }
            if(count($name_tokens)==0){
                $this_array[0]=$email->getAttribute('address');
            }
             $this_array[3]= $email->getAttribute('address');
             $this_array[4]=  "";
             echo"<tr><td>".$this_array[0]."</td><td>".$this_array[1]."</td><td>".$this_array[2]."</td><td>".$this_array[3]."</td></tr>";
             $to_write_array[$counter]=$this_array;
             $counter++;
        }
        echo "</tbody></table>";
        
        global $data_dir;
        $abook_file = $data_dir.$_SESSION['username'].".abook";

        

        $fp = fopen($abook_file,"a");

        foreach($to_write_array as $fields){
              fputcsv($fp, $fields,"|");
        }
        
        fclose($fp);
       
    }
}

?>
