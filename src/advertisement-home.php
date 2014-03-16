<?php 
/** This is the advertisement home page */
define('SM_PATH','../');
define('PAGE_NAME', 'advertisement-home');

/* SquirrelMail required files. */
require_once(SM_PATH . 'include/validate.php');
sqgetGlobalVar('username',  $username,      SQ_SESSION);

ob_start();


//connect to the database
$connect = mysql_connect("localhost","root","student");
mysql_select_db("sq_mail",$connect); //select the table
//
$success = "false";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Advertisement Home</title>
    <script type="text/javascript" src="/src/advertise/js/jquery-1.10.2.js"></script>
   <!-- <script  type="text/javascript" src="/src/advertise/js/nicEdit.js"></script>-->
   <script type="text/javascript" src="/src/advertise/js/jquery.validate.js"></script> 
    <script type="text/javascript" src="/src/advertise/js/jquery-ui-1.10.4.custom.min.js"></script>

    <!--  CSS -->
    <link rel="stylesheet" href="/src/advertise/css/form.css"/>
    <link rel="stylesheet" href="/src/advertise/css/smoothness/jquery-ui-1.10.4.custom.min.css"/>

	<style>
		div.clickjack { /* iframe from facebook.com */
    	opacity: 0.0;
	display:block;
	
		}
	</style>

     <script src='/src/js/adv.js'>
     //function hello(){
     //                               alert("helllo");
      //                            }
       
    </script>

    <script type="text/javascript" src='/src/js/adv2.js'>
   

    </script>

   <!-- <script type="text/javascript">
        

        bkLib.onDomLoaded(function() { 
       
        new nicEditor({iconsPath : '/src/advertise/images/nicEditorIcons.gif'}).panelInstance('advHTML');
     
      });
  </script> -->
    </head>

    <body>
        <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=336610189686504";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

        <form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
            <div>
             <div id='step1'>

              <fieldset class="wide">
                   <legend class=desc>Personal Details</legend>
                   <div>
                   <ul>
                       <li class="leftFourth"> 
                           <label class="desc">First Name</label>
                           <input class="field text medium bold" type="text" id="firstName" name="firstName"/>
                       </li>
                       <li class="middleFourth"> 
                           <label class="desc">Last Name</label>
                           <input class="field text medium bold" type="text" id="lastName" name="lastName"/>
                       </li>
                       <li class="middleFourth">
                           <label class="desc">Email</label>
                           <input class="field text medium bold" type="text" id="email" name="email"/>
                       </li>
                       <li class="middleFourth">
                           <label class="desc">Contact No.</label>
                           <input class="field text medium bold" type="text" id="contactNo" name="contactNo"/>
                       </li>
                      
                    </ul>
                    <ul>
                         <li class="leftHalf"><label class="desc">Address</label>
                        <div>
                            <textarea cols="50" rows="3" class="field textarea small" name="address" id="address" ></textarea>
                        </div>
                        </li>
                
                    </ul>    
                    </div>      
            </fieldset>  
          
         
             <fieldset class="wide">
                   <legend class=desc>Advertisement Campaign Details</legend>
                   <div>
                   <ul>
                       <li class="leftFourth"> 
                           <label class="desc">Target Country</label>
                           <input class="field text medium bold" type="text" id="targetCountry" name="targetCountry"/>
                       </li>
                       <li class="middleFourth"> 
                           <label class="desc">Total Budget</label>
                           <input class="field text medium bold" type="text" id="totalBudget" name="totalBudget"/>
                       </li>
                       <li class="middleFourth">
                           <label class="desc">Per Day Budget</label>
                           <input class="field text medium bold" type="text" id="perDayBudget" name="perDayBudget"/>
                       </li>
                        
                      
                    </ul>
                     
                    </div>      
            </fieldset>
           </div> 
                      
                
            <div id='step2'>
            <fieldset class="wide">
             <input type="hidden" name="username" value="<?php print($username); ?>" />
                   <legend class=desc>Preview/Review Advertisement</legend>
                   <div>
                   
                        <ul>
                      <li class='leftHalf'>
                            <label class="desc">Advertisement URL</label>
                           <input class="field text medium bold" type="text" name="advertisementURL" id="adURL" value="https://www.cs5331.com/src/advertise/sample.html"/>
                           <label for="adURL" id="urlErrorMsg" generated="true" class="error" style="font-size:11px;">This field is required.</label>
                      </li>
                      <li class="middleFourth">
                          <label class="desc">
                      <input type="button" class="custom-submit" value="preview" style="margin-top:9%;" id="fetchURLHTML">
                      <img src='/src/advertise/images/ajax-loader-small.gif' style="display: none;"/>
                      </label>
                    </li>
                
                    </ul>    
                    
                     
                        <ul id="previewFrame" style="display: none;">
                        <li class='leftHalf'>
                            <label class="desc">This is how the advertisement is going to look like:</label>
                            <iframe id='preview-iframe' src="about:blank"></iframe>
                        </li>
                        </ul>  
                   
                   
                    </div>      
            </fieldset>  
             </div>
		</div>

            <div id='button-div'>
                  <input type="button" value="Back" class="custom-submit" id="previousStep" url-pointer='webmail.php'/>
<div title='' fb-iframe-plugin-query="action=like&amp;app_id=336610189686504&amp;href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FSquirrelmail_assignment2%2F1449074828661923%3Ffref%3Dts&amp;layout=button&amp;locale=en_GB&amp;sdk=joey&amp;share=false&amp;show_faces=true" fb-xfbml-state="rendered" title="" class="fb-like clickjack fb_iframe_widget" data-href="https://www.facebook.com/pages/Squirrelmail_assignment2/1449074828661923?fref=ts" data-layout="button" data-action="like" data-show-faces="true" data-share="false"><span style="width: 49px; vertical-align: bottom;"><iframe width="1000px" scrolling="no" height="1000px" frameborder="0" class="" src="https://www.facebook.com/plugins/like.php?action=like&amp;app_id=336610189686504&amp;channel=https%3A%2F%2Fs-static.ak.facebook.com%2Fconnect%2Fxd_arbiter%2F63KoCqPoniC.js%3Fversion%3D40%23cb%3Df5924a0e680e4%26domain%3Dcs5331.com%26origin%3Dhttps%253A%252F%252Fcs5331.com%252Ff2530dc0decc564%26relation%3Dparent.parent&amp;href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FSquirrelmail_assignment2%2F1449074828661923%3Ffref%3Dts&amp;layout=button&amp;locale=en_GB&amp;sdk=joey&amp;share=false&amp;show_faces=false" style="border: medium none; height: 20px; visibility: visible; width: 49px;"  allowtransparency="true" name="f3942048347f3ae"></iframe></span></div>
         
                 <input type="button" style="margin-left:0px;cursor:pointer;" id="nextStep" class="custom-submit" value="Click here if you would like to proceed" title='Like'>
<p class='alignright' style="display:none;" id="publishAd">
                  <input type="button" id="publishAdBtn" class="custom-submit" value="Publish"/>
             </p>
	
               </div>
        </form>
        <div id="confirm-dialog" title="Confirmation">
              <div style="font-size:11px;"> You will not be able to make changes to it after publishing. Ad campaigns running currently would be turned off. Are you sure you want to publish the advertisement?</div>
        </div>  

    </body>
</html> 
