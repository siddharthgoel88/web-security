<?php

/* Database related utilities */

function smdb_connect()
{
	//Create Connection
	$con=mysqli_connect("localhost","root","student","SM");
	
	// Check Connection
	if (mysqli_connect_errno())
	{
		//echo "Failed to connect to SquirrelMail Database: " . mysqli_connect_error();
		return -1;
	}
	else
	{
		return $con;
	}
}

function update_file_metadata($con,$name,$ctime,$owner,$fhash)
{
	$name = mysqli_real_escape_string ($con,$name);
	$owner = mysqli_real_escape_string($con, $owner);
	$url = "https://resplendent-fire-6199.firebaseio.com/squirrelmail/".$fhash;
	$query = "INSERT INTO FILE (FileName,CreationTime,Owner,FileHash, URL) 
	VALUES ('".$name. "','" .$ctime. "','". $owner . "','" .$fhash."','". $url ."');";
	//echo "Query:".$query."<br>";
	$ret = mysqli_query($con,$query);

	//echo mysqli_error($con);
	if ($ret === FALSE)
	{
		return -1;
	}
}
/*
function update_file_data($con, $fhash, $data, $mtime, $modifier, $revision)
{
	$data = mysqli_real_escape_string($con,$data);
	$modifier = mysqli_real_escape_string($con,$modifier);
	$query = "INSERT INTO FILEDATA (FileHash, Data, ModifyTime, ModifiedBy, Revision) 
	VALUES ('".$fhash."','". $data . "','". $mtime . "','". $modifier ."','". $revision. "')";
	$ret = mysqli_query($con,$query);
	
	echo mysqli_error($con);
	if ($ret === FALSE)
	{
		return -2;
	}
}
*/

function update_file_collaborator($con,$fhash,$owner)
{
	$owner = mysqli_real_escape_string ($con,$owner);
	$query = "INSERT INTO COLLABORATORS (FileHash, Collaborator) VALUES".
				"('" . $fhash . "','" . $owner . "');";

	$ret = mysqli_query($con,$query);
	//echo mysqli_error($con);
	
}

function smdb_close($con)
{
	mysqli_close($con);
}

function sm_create_file($owner,$file_name)
{
	$con = smdb_connect();
	$fhash = sha1($file_name.$owner);
		
	if($con != -1)
	{
		//echo "Inserting";
		$ctime = date('Y-m-d H:i:s');
		$ret = update_file_metadata($con, $file_name, $ctime, $owner, $fhash);
		if($ret == -1)
		{
			return $ret;
		}
		//echo "Inserted";
		/*
		$mtime = date('Y-m-d H:i:s');
		$ret= update_file_data($con, $fhash, $fileContent, $mtime, $owner, "1");
		 */
		 
		 update_file_collaborator($con,$fhash,$owner);
	}
	smdb_close($con);
	return $fhash;
}

function display_files($user)
{
	//echo "Going to ask for connection!!!";
	$con = smdb_connect();
	//$user = mysqli_real_escape_string($user);
	$returnString = '';
	//echo "Asked for connection!!!";
	
	if($con!= -1)
	{
		//echo "Entered if ...";
		$query = "Select FILE.FileName AS FileName, FILE.CreationTime AS Ctime, FILE.URL AS URL FROM FILE INNER JOIN COLLABORATORS ON FILE.FileHash=COLLABORATORS.FileHash AND COLLABORATORS.Collaborator='".$user."' ;";
		$result = mysqli_query($con,$query);
		
		$returnString = $returnString . '<center><h3>Existing Files</h3></center>';
		if($result)
		{
			$returnString = $returnString . '<ul>';
			while($row = mysqli_fetch_array($result, MYSQL_ASSOC))
			{
				$returnString = $returnString . '<li> <a href="javascript:void(0)" onclick=loadFirepad(\''.$row['URL'] .'\',\''. $user .'\')>' . $row['FileName'] . ' uploaded on ' . $row['Ctime'] . '</li>';
			}
			$returnString = $returnString . '</ul>';
		}
		else 
		{
			$returnString = $returnString . '<i>Oops! No existing Files</i>';
		}
	}
	
	//echo mysqli_error($con);	
	smdb_close($con);
	return $returnString;
}

/*
 * Function to upload a file to the server.
 * 
 * Return Values:
 * 0 - Success
 * 1 - Incorrect File Extention
 * 2 - Error in saving the uploaded file
 * 3 - Error in uploading
 * 4 - Attachement Missing
 * */

function sm_upload(&$file_content,&$file_name)
{
	$file_content = ''; // Initializing
	if($_FILES['attachment']['name'])
	{
		if ($_FILES['attachment']['type'] != "text/plain")
		{	
			//echo "Wrong file type <br>";
			return 1; //Incorrect File Type
		}
		else 
		{
			//echo "Correct file type <br>";
			if(!$_FILES['attachment']['error'])
			{
				//echo "No error till now <br>";
				$file_name = addslashes($_FILES['attachment']['name']);
				//echo "File Name : ".$file_name ."<br>" ;
				
				$file_content = addslashes(file_get_contents($_FILES['attachment']['tmp_name']));
				//echo "file contents are :".$file_content;
				
				/*$target='uploads/'.$file_name;
				if(!move_uploaded_file($_FILES['attachment']['tmp_name'], $target))
				{
					return 2;
				}*/
				return 0;
			}
			else
			{
				$file_content = $_FILES['attachment']['error'];
				return 3;
			}
		}
		
	}
	else
	{
		return 4;
		//echo "File yet to uploaded <br>";
	}
}

function add_doc_rating($hash, $usr, $rate_val) {
	$con = smdb_connect();
	
	if($con!=-1){
		$hash = mysqli_real_escape_string ($con,$hash);
		$usr = mysqli_real_escape_string ($con,$usr);
		$rate_val = mysqli_real_escape_string ($con,$rate_val);
		
		if(isOwner($usr, $hash)){
			$query = "UPDATE FILE SET Rating='".$rate_val."' WHERE FileHash='".$hash."'";
			mysqli_query($con,$query);
			
			smdb_close($con);
			return 1;
		} else {
			smdb_close($con);
			return 0;
		}	

	} else {
		smdb_close($con);
		return 0;
	}
}

function isOwner($usr,$hash) {
	$con = smdb_connect();
	
	if($con!=-1){
		$hash = mysqli_real_escape_string ($con,$hash);
		$usr = mysqli_real_escape_string ($con,$usr);
		$query = "SELECT * FROM COLLABORATORS WHERE FileHash='".$hash."' AND Collaborator='".$usr."'";
		$result = mysqli_query($con,$query);
		
		if(mysqli_num_rows($result) >= 1){
			smdb_close($con);
			return 1;
		} else {
			smdb_close($con);
			return 0;
		}
	} else {
		smdb_close($con);
		return 0;
	}
}

?>