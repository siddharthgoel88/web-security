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

	        <link rel="stylesheet" href="js/codemirror/lib/codemirror.css" />
		<script src="js/codemirror/lib/codemirror.js"></script>

		<link rel="stylesheet" href="js/firepad/firepad.css" />
		<script src="js/firepad/firepad.js"></script>
		
		<script src="https://code.jquery.com/jquery-latest.min.js"></script>
	<!--	<script src="../squirrelmail-node/socket.io/socket.io.js"></script> -->
		
		<style>
			#addCollabDiv {
				display: none;
			}
			.firepad {
      			position: absolute; 
      			height: 500px; 
      			width: 800px;
      			border-style:solid;
				border-width:1px;
    		}
    		#notifications {
    			border-style:solid;
				border-width:1px;
				position:absolute; 
				left:820px; 
				top:60px;
				width: 350px;
				height: 300px;
				overflow: scroll
    		}
    		#addCollabDiv {
    			border-style:solid;
				border-width:1px;
				width: 200px;
    		}
    		#uploadFile {
    			border-style:solid;
				border-width:1px;
				width: 320px;
    		}
		#saveDiv {
			border-style:solid;
			border-width:1px;
			width: 120px;
			position: absolute;
			top: 78px;
			left: 620px;
			display: none;
		}
    		#revisionHistory {
    			border-style:solid;
				border-width:1px;
				position:absolute; 
				left:820px; 
				top:370px;
				width: 350px;
				height: 253px;
				overflow: scroll;
				display: none;
    		}
    		#existingFiles {
    			border-style:solid;
				border-width:1px;
    			position:absolute; 
    			left:400px; 
    			top:60px;
    			height: 550px;
				overflow: scroll;
    			width: 400px;
    		}
		</style>
		
		<script type="text/javascript">
			jQuery(function ($){
				var hostIP = window.location.host;
                                var host = "https://" + hostIP + ":3000/";
                                var sockURL = host + "socket.io/socket.io.js";
				console.log(sockURL);
                                console.log(hostIP);
                                $.getScript(sockURL, function(){
		                        var socket = io.connect(host);
					var $collabForm = $('#addCollabForm');
				
					$collabForm.submit(function(e){
						e.preventDefault();
						var user = $('#collaborator').val() ; //TODO: Sanitize this !!!!
						var link = getFileURL();
						var data = {
							collaborator : user,
							url : link
						};
						socket.emit('collaborator', data);
					});
				
					socket.emit('iam','<?php echo addslashes($username); ?>');
				
					socket.on('notification', function(data){
						$('#noNotification').fadeOut();
						$('#notifications').append(data).hide().fadeIn();
					});
				
					$("#notifications").on("click",".rqst",function(e){
						var rid = $(this).data("rid");
						var stat = $(this).data("status");
						console.log(rid);
						console.log(stat);
						var response = {
							requestID: rid,
							status: stat
						};
						console.log(response.status);
						socket.emit('shared-doc-response', response);
						$('#'+rid).fadeOut();
						return false;
					});

					$('#saveButton').click(function(){
						var fpad = getFirepad();
						var data = fpad.getText();
						var user = '<?php echo $username; ?>';
						var curURL = getFileURL();
						var revData = {
							fileData: data,
							savedBy: user,
							url: curURL
						};
						socket.emit('revision-data', revData);
					});
				
				});
			});
			
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

if($_GET['file'] && $_GET['ext'])
{
	$flnm = $_GET['file'];
	$ext = $_GET['ext'];
	echo $flnm.$ext;
}

if(isset($_POST['uploadButton']))
{
	//echo "POST set!!!";
	$ret_val = sm_upload($file_content,$file_name);
	//echo "Uploaded";
	
	if ($ret_val ==0 || $ret_val == 2)
	{
		$val = sm_create_file($file_content,$username,$file_name);
		if($val == -1 || $val == -2)
		{
			$ret_val = $val;
		}
	}
	
	echo "<script> var data =".json_encode($file_content).";".
	"fp = fillFirepad(".$ret_val.",'".$val."','".$username."',data); </script>";
}

?>


		<br />
	</body>
</html>
