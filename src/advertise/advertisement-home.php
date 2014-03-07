<?php 
/** This is the file-import-export page */
define('PAGE_NAME', 'advertisement-home');

define('SM_PATH','../../');

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
    <script  type="text/javascript" src="/src/advertise/js/nicEdit.js"></script>
    <script type="text/javascript" src="/src/advertise/js/jquery-ui-1.10.4.custom.min.js"></script>

    <!--  CSS -->
    <link rel="stylesheet" href="/src/advertise/css/form.css"/>
    <link rel="stylesheet" href="/src/advertise/css/smoothness/jquery-ui-1.10.4.custom.min.css"/>

     <script>
     //function hello(){
     //                               alert("helllo");
      //                            }
       $(function() {        
            $( "#confirm-dialog" ).dialog({
              autoOpen: false,
              height: 200,
              width: 550,
              modal: true,
              
              buttons: {
                "Yes I'm sure": function() {
                 // $("#sys-msg-running").html("<img src='/src/advertise/images/ajax-loader.gif'/><br/> Saving and Routing. Please wait..");
                  //$("#sys-msg").html("");
                  $("#confirm-dialog").html("<div align='center'><img src='/src/advertise/images/ajax-loader.gif'/><br/> Details are being saved. Please wait..</div>");
                  $(".ui-dialog-titlebar-close").hide();
                  $(":button:contains('Yes I'm sure')").attr("disabled","disabled").addClass("ui-state-disabled");
                  $(":button:contains('Cancel')").attr("disabled","disabled").addClass("ui-state-disabled");
                  $.ajax({
                        type: 'POST',
                        url: 'publishAdv.php',
                        data: $(this).serialize(),
                        dataType: 'json',
                        success: function (data) {
                                console.log(data);
                                $("#msg").html("Advertisement has been published successfully.");
                                $("#msg").show();
                        }
                  });

                  $(this).dialog("close");
                },
                "Cancel Save": function() {
                  $(this).dialog("close");
                  return false;
                }
              }
            });
         });
    </script>

    <script type="text/javascript">
    $(document).ready(function() {

       

        $("#form2").hide();

        $("#nextStep").click(function(){

            $("#form1").hide();
            $("#form2").show();


        });

         $('#form1').submit(function(event) {
                event.preventDefault();
               nicEditors.findEditor('advHTML').saveContent();
              // ('#publishAdv').attr('disabled','disabled');
               $( "#confirm-dialog" ).dialog( "open" );
               //$("#msg").hide();
                
                //('#publishAdv').removeAttr('disabled');
                //$( "#confirm-dialog" ).dialog( "close" );
                //$("#msg").html("Advertisement has been published successfully.");
                //$("#msg").show();
        });
  


    });

    </script>

    <script type="text/javascript">
        

        bkLib.onDomLoaded(function() { 
       
        new nicEditor({iconsPath : '/src/advertise/images/nicEditorIcons.gif'}).panelInstance('advHTML');
     
      });
  </script>
    </head>

    <body>
        
        <form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
            
             <fieldset class="wide">
                   <legend class=desc>Personal Details</legend>
                   <div>
                   <ul>
                       <li class="leftFourth"> 
                           <label class="desc">First Name</label>
                           <input class="field text medium bold" type="text" name="firstName"/>
                       </li>
                       <li class="middleFourth"> 
                           <label class="desc">Last Name</label>
                           <input class="field text medium bold" type="text" name="lastName"/>
                       </li>
                       <li class="middleFourth">
                           <label class="desc">Email</label>
                           <input class="field text medium bold" type="text" name="email"/>
                       </li>
                       <li class="middleFourth">
                           <label class="desc">Contact No.</label>
                           <input class="field text medium bold" type="text" name="contactNo"/>
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
                           <input class="field text medium bold" type="text" name="targetCountry"/>
                       </li>
                       <li class="middleFourth"> 
                           <label class="desc">Total Budget</label>
                           <input class="field text medium bold" type="text" name="totalBudget"/>
                       </li>
                       <li class="middleFourth">
                           <label class="desc">Per Day Budget</label>
                           <input class="field text medium bold" type="text" name="perDayBudget"/>
                       </li>
                        <li class="rightFourth">
                           <label class="desc">Destination URL</label>
                           <input class="field text medium bold" type="text" name="destinationURL"/>
                       </li>
                      
                    </ul>
                     
                    </div>      
            </fieldset>
            <ul>
                         
                       <li>
                            <div align="center"><input type="button" id="nextStep" class="custom-submit" value="Next Step"/></div>
                       </li>
                    </ul>   
     
       <!-- </form>
        <form action="" method="post" name="form2" id="form2"> -->
             <input type="hidden" name="username" value="<?php print($username); ?>" />
             <fieldset class="wide">
                   <legend class=desc>Preview/Review Advertisement</legend>
                   <div>
                   
                        <ul>
                      <li>
                            <textarea  style="height: 200px; width: 900px;" cols="1000" rows="6" class="field textarea small" name="advHTML" id="advHTML" >
                                <!-- Begin TrafficWave.net Banner Code -->
                          <html>
                            <head>
                               
                            </head> 
                          <body>
                            <input type="button" value="submit" onclick="javascript:hello();"/>
                          </body>
                          </html>

                            </textarea>


              
                        </li>
                
                    </ul>    
                       <ul>
                         
                       <li> <div id="msg" style="display: none;color: green;font-weight: bold;" align="center"></div>
                            <div align="center"><input type="submit" id="publishAdv" class="custom-submit" value="Publish"/></div>
                              <input type="button" value="submit" onclick="javascript:hello();"/>
                       </li>
                    </ul> 
                    </div>      
            </fieldset>  
             
     
        </form>

        <div id="confirm-dialog" title="Progress">
              <div> You will not be able to make changes to it after publishing. Are you sure you want to publish the advertisement?</div>
        </div>  

    </body>
</html> 
