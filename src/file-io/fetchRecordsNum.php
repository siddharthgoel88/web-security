<?php


class ajaxFetchRecords {

        function fetchRecordsNum() {
                //Put form elements into post variables (this is where you would sanitize your data)
                
                //$username = $_SESSION["username"];

                $username = @$_GET['user_name'];
 
                //Establish values that will be returned via ajax
                $return = array();
                $return['msg'] = '';
                //$return['count'] = 0;
                $return['error'] = false;
 
                //Begin form validation functionality
               // if (!isset($field1) || empty($field1)){
                //        $return['error'] = true;
                //        $return['msg'] .= '<li>Error: Field1 is empty.</li>';
                //}
 
                //Begin form success functionality
                if ($return['error'] === false){
                        $db = new mysqli("localhost","root","student","sq_mail");
                        $stmt = $db->prepare("select * from contact WHERE imported_by=?");
                        $stmt->bind_param('s', $username);
                        $stmt->execute();

                        $stmt->store_result();
                        $stmt->fetch();
                        $numberofrows = $stmt->num_rows;
                        /*.....other code...*/

                        //echo '# rows: '.$numberofrows;

                     
                        $stmt->close();
                     
                        $return['count'] = $numberofrows;
                }
 
                //Return json encoded results
                return json_encode($return);
        }
 
}
 // sqgetGlobalVar('username',  $username,      SQ_SESSION);
$ajaxFetchRecords = new ajaxFetchRecords;
echo $ajaxFetchRecords->fetchRecordsNum();
?>