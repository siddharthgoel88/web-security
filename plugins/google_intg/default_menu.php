<?php

 echo <<<'EOD'
        <table width="100%" cellpadding="1" border="0" bgcolor="#dcdcdc" align="center">
        <tbody>
            <tr>
                <td align="center"><b>Google Services Integration</b></td>
            </tr>
        </tbody>
        </table>

        <table width="100%" cellspacing="0" cellpadding="0"border="0">
        <tbody>
            <tr><td height="10" bgcolor="#ffffff"></td></tr>
            <tr><td height="3"></td></tr>
            <tr><td align="center" bgcolor="#dcdcdc" >Choose an option</td></tr>
            <tr><td height="5"></td></tr>
            <tr><td align="center" width="100%">
                <a href="?state=get_contacts">Import Contacts</a>
                &nbsp&nbsp
                <a href="?state=update_calendar">Update Calendar</a>
            </td></tr>
            <tr><td height="3"></td></tr>
        </tbody>
        </table>

        <table width=90% bgcolor="#ffffff"> <tbody>
        <tr><td>
            <p align="left"><b>The options are explained below: </b></p>
   
            <p align="left"><b>1. Google Contacts Import: </b> This option allow the user 
                            to import contacts from his google account. The imported
                            contacts will be stored in the Address book of the user.
            </p>
            <p align="left">

                           <b> 2.Google Calender Event Update : </b> This option will 
                           allow the user to update an event on the calender hosted at
                           the google account owned.
            </p>                    
        </td> </tr>
        </tbody> </table>
EOD;
 
?>
