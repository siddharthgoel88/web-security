<?php


if(!defined('SM_PATH'))
    define('SM_PATH','../../');

include_once(SM_PATH . 'include/validate.php');
include_once(SM_PATH . 'functions/strings.php');
include_once(SM_PATH . 'config/config.php');
include_once(SM_PATH . 'functions/page_header.php');
include_once(SM_PATH . 'functions/display_messages.php');
include_once(SM_PATH . 'functions/prefs.php');

include_once('config.php');
include_once('functions.php');

require_once 'src/Google_Client.php';
require_once 'src/contrib/Google_CalendarService.php';

//Script for form validation when accepting user input for events
echo '<script src="date_time_validator.js"></script>';
 
displayPageHeader($color, "None");

if(isset($_GET['error'])){
    if($_GET['error']=="access_denied"){
        header( "refresh:3;url=/src/webmail.php" );
        echo '<font color="red">Sorry you denied benign access by our app! :( Go back to old Squirrel Mail in 3 seconds!</font>';
    }
}

$option="default";

if(isset($_GET['state'])){
    $option=$_GET['state'];
 }

echo'<noscript><table><tr><td><font color="red">JavaScript is off. Please enable it or else this plugin cannot work. (We cannot IFrame Google OAuth window)</font></td></tr></table></noscript>';

if(isset($option)){
    switch($option){
        case "get_contacts":
            getGoogleCode("contacts");
            break;
        case "got_code_contacts":
            getGoogleContacts();
            break;
        case "update_calendar":
            require_once('google_event_form.php');
            break;
        case "got_event_details":
             echo "hi";
             $_SESSION['ename']=$_GET['ename'];
             $_SESSION['start_time']=$_GET['start_time'];
             $_SESSION['start_date']=$_GET['start_date'];
             $_SESSION['end_time']=$_GET['end_time'];
             $_SESSION['end_date']=$_GET['end_date'];
             $_SESSION['summary']=$_GET['summary'];
             updateGoogleCalendar();
             break;
        case "got_code_calendar":
            updateGoogleCalendar();
            break;
        default:
             //Display the main menu
             /* Print the main menu & descriptions in the below echo */
             require_once('default_menu.php');
    }
}





?>
