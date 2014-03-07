<?php


class ajaxPreviewAdv {

        function previewAd() {
                //Put form elements into post variables (this is where you would sanitize your data)
                
                //$username = $_SESSION["username"];
        		
                $url = @$_GET['url'];
 
                //Establish values that will be returned via ajax
                $return = array();
                $return['msg'] = '';
                $return['error'] = false;
 
                //Begin form validation functionality
               // if (!isset($field1) || empty($field1)){
                //        $return['error'] = true;
                //        $return['msg'] .= '<li>Error: Field1 is empty.</li>';
                //}
 
                //Begin form success functionality
                if ($return['error'] === false){
                        //connect to the database
                        $html = file_get_contents($url);

                        $return['msg'] = "success";
                        $return['html_contents'] = $html;
                }
 						
                //Return json encoded results
                return json_encode($return);
        }
 
}
 // sqgetGlobalVar('username',  $username,      SQ_SESSION);
$ajaxPreviewAdv = new ajaxPreviewAdv;
echo $ajaxPreviewAdv->previewAd();
?>