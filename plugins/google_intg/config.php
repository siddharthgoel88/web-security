<?php
/*******************************************************************************

    Author ......... Pranav Cavatur
    Contact ........ pranav@pranavpc.com
    Home Site ...... http://www.pranavpc.com
    Program ........ Google Integration
    Version ........ 0.1
    Purpose ........ Importing Contacts from Google & updating Google Calender

*******************************************************************************/

//Define the Globals here

//Google App Globals
  $client_id = "351750094524-8csrb8bnlumptmqqn30cimfs0o52j81d.apps.googleusercontent.com"; //your client id
  $client_secret = "WjtfoJDWS2yadYe3k0prB5ik"; //your client secret
  $developer_key = "AIzaSyDPTTtlK6oUlfGMpgphtwlgE41rrinu7rI";
  
  //Calculate redirect URI
  $server_id = getenv('HTTP_HOST');
  $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
  $redirect_uri = $protocol.$server_id."/plugins/google_intg/google_intg.php";
?>
