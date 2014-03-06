<!-- User Session Validation -->

<?php
define('PAGE_NAME', 'sm_editor');
define('SM_PATH','../');
require_once(SM_PATH . 'include/validate.php');
require_once(SM_PATH . 'functions/sm_editor-utils.php');

sqgetGlobalVar('username', $username, SQ_SESSION);

?>
<!DOCTYPE html>
<html>
	<head>
		<title>SquirrelMail Editor</title>
		
		<script src="js/editorControl.js"></script>
		<script src="https://cdn.firebase.com/v0/firebase.js"></script>
		<script src="js/codemirror/lib/codemirror.js"></script>
		<script src="js/firepad/firepad.js"></script>
		<script src="https://code.jquery.com/jquery-latest.min.js"></script>
		
		<link rel="stylesheet" href="js/firepad/firepad.css" />
		<link rel="stylesheet" href="js/codemirror/lib/codemirror.css" />
		<link rel="stylesheet" href="style/sm_editor.css" />
		
		<script type="text/javascript">
			$(document).ready(jQuery_sqmail('<?php echo addslashes($username); ?>'));
		</script>
		
	</head>
	
	<body>
        <?php echo displayPageHeader($color, 'None');?>
		<div id="uploadFile">
			<form action="" method="post" enctype="multipart/form-data">
				<label for="file">Filename:</label>
				<input type="file" name="attachment" ><br>
				<input type="submit" name="uploadButton" value="Upload">
			</form>
		</div>
		
		<div id="existingFiles" ></div> 

		<div id="addCollabDiv">
			<form id="addCollabForm" action="" method="post">
				<select name="collaborator" id="collaborator">
					<?php
						$user_list = shell_exec('awk -F: \'$3 >= 1000 && $1 != "nobody" {print $1}\' /etc/passwd'); 
						$user_arr = explode("\n", $user_list);
						foreach ($user_arr as $user_inst) {
							if(($user_inst != "")&&($user_inst != $username))
								echo "<option value=\"".$user_inst."\">".$user_inst."</option>";
						}
					?>
				</select>
				
				<input type="submit" name="submit" value="Add Collaborator">				
			</form>
		</div>
		<br/>
	
		<div id="notifications" >
			<center><h3>Notifications</h3></center>
		</div>
		
		<div id="revisionHistory">
			<center><h3>Revision History</h3></center>
			<ul>
				<li><a href=#>Rev 1</a></li>
			</ul>
		</div>

		<div id="saveDiv">
                        <input type="button" id="saveButton" name="save" value="Save Document">
                </div>
	
		<div id="smEditor"></div>
<?php

echo "<script> document.getElementById('existingFiles').innerHTML ='". addslashes(display_files($username)) . "'</script>";

if(isset($_POST['uploadButton']))
{
	//echo "POST set!!!";
	$ret_val = sm_upload($file_content,$file_name);
	//echo "Uploaded";
	
	if ($ret_val ==0 || $ret_val == 2)
	{
		$val = sm_create_file($username,$file_name);
		if($val == -1 || $val == -2)
		{
			$ret_val = $val;
		}
	}
	$file_content = utf8_encode($file_content);
	echo "<script> var fileData =".json_encode($file_content).";".
	"fp = fillFirepad(".$ret_val.",'".$val."','".$username."',fileData); </script>";
}
?>


		<br />
	</body>
</html>
