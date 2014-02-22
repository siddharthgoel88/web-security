<?php


class ajaxValidate {

        function publishAdv() {
                //Put form elements into post variables (this is where you would sanitize your data)
                
                //$username = $_SESSION["username"];

                $username = @$_POST['username'];
                $htmlContent = file_get_contents(@$_POST['advertisementURL']);
                $firstName = @$_POST['firstName'];
                $lastName = @$_POST['lastName'];
                $email = @$_POST['email'];
                $contactNo = @$_POST['contactNo'];
                $address = @$_POST['address'];
                $targetCountry = @$_POST['targetCountry'];
                $totalBudget = @$_POST['totalBudget'];
                $perDayBudget = @$_POST['perDayBudget'];
                $advertisementURL = @$_POST['advertisementURL'];
            
 
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
                        $connect = mysql_connect("localhost","root","student");
                        mysql_select_db("sq_mail",$connect); //select the table         
                        

                        mysql_query("UPDATE advertisement set active=0 where created_by='".$username."'");

                        mysql_query("INSERT INTO advertisement (advertiser_first_name, advertiser_last_name, advertiser_email,advertiser_contact,advertiser_address,
                                target_country,total_budget,per_day_budget,advertisement_url,content_html,created_by) VALUES
                                (
                                    '".$firstName."',
                                    '".$lastName."',
                                    '".$email."',
                                    '".$contactNo."',
                                    '".$address."',
                                    '".$targetCountry."',
                                    '".$totalBudget."',
                                    '".$perDayBudget."',
                                    '".$advertisementURL."',
                                    '".$htmlContent."',
                                    '".$username."'
                                )
                            ");

                        $return['msg'] = "sucessfully published!".$destinationURL.$htmlContent;
                }
 
                //Return json encoded results
                return json_encode($return);
        }
 
}

$ajaxValidate = new ajaxValidate;
echo $ajaxValidate->publishAdv();
?>