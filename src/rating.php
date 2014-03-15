<?php
define('PAGE_NAME', 'rating');
define('SM_PATH','../');
require_once(SM_PATH . 'include/validate.php');
require_once(SM_PATH . 'functions/sm_editor-utils.php');

echo "<html><body>";
sqgetGlobalVar('username', $username, SQ_SESSION);

if(isset($_GET['file-hash']) && (!isset($_GET['rate-value'])))
{
	echo '
	<center><h4>Please rate this document</h4>
			<form action="rating.php" method="GET" id="rateForm">
				<input type="hidden" name="file-hash" value="'.addslashes($_GET['file-hash']).'">
				<select id="rateData" name="rate-value">
					<option id="op1" value="Not Important">Not Important</option>
					<option id="op2" value="Normal">Normal</option>
					<option id="op3" value="Important">Important</option>
					<option id="op4" value="Very Important">Very Important</option>
				</select>
				<input type="submit" id="rateSubmit" value="Rate it!!" />
			</form>
	</center>';
	
} else if(isset($_GET['file-hash']) && isset($_GET['rate-value'])){
	$hash = addslashes($_GET['file-hash']);
	if(isOwner($username,$hash)) {
		if(isset($_GET['rate-value'])){
			$rate_val = addslashes($_GET['rate-value']);
			if(add_doc_rating($hash, $username, $rate_val)) {
				echo "<h3>Thank you for rating.</h3>";
				echo "<a href=# onclick='window.close();return false;'>Click here to close</a>";
			} else {
				echo "<h3>Some issues in storing your rating. Try later !!!</h3>";
			}
		}
	}
	else {
		echo "<h3>You are not authorized to rate this document!!!</h3>";
	}
} else {
	echo "<h3>Seems you landed on wrong page!!!</h3>";
}

echo "</body></html>";
?>