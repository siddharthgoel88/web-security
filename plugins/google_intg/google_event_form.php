<?php
echo <<<EOD
<table width="100%" cellpadding="1" border="0" bgcolor="#dcdcdc" align="center">
        <tbody>
            <tr>
                <td align="center"><b>Enter the event details</b></td>
            </tr>
        </tbody>
        </table>
        
        
        <form name ="googleEventCreator" action = "" method = "get" onSubmit="return check_everything(this)">
        <table style="margin: 0 auto;margin-top:-70px">
        <col width=300>
        <col width=50>
        <tr>
	<td>Enter event name* : </td><td><input type = "text"  name="ename" class="text_form"></td>
	</tr>
        <br>
	<tr>
	<td>Enter event start date* (YYYY-MM-DD) : </td><td><input  name="start_date"></td>
	</tr>
        <br>
        <tr>
	<td>Enter start time* (MM:HH): </td><td><input  name="start_time"></td>
	</tr>
        <br>
        <tr>
	<td>Enter event end date* (YYYY-MM-DD) : </td><td><input name="end_date"></td>
	</tr>
        <br>
	<tr>
	<td>Enter end time* (MM:HH): </td><td><input name="end_time" ></td>
	</tr>
        <br>
	<tr>
	<td>Enter summary :</td><td> <textarea name="summary"></textarea></td>
	</tr>
        
        <input type = "hidden" name="state" value="got_event_details">
        <tr><td colspan=2><input type = "submit" value="Submit" style="margin-left:200px"></td></tr>
        <tr><td colspan=2><p><b><i><font color="#A38200">Note : Fields marked with * are compulsary</font></i></b></td></tr> 
        </table>
        </form>
EOD;
?>
