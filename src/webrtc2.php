<?php


/**
 * testrtc2.php
 *
 * Manage personal address book.
 *
 * @copyright 1999-2010 The SquirrelMail Project Team
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id: addressbook.php 13893 2010-01-25 02:47:41Z pdontthink $
 * @package squirrelmail
 * @subpackage addressbook
 */

/** This is the addressbook page */
define('PAGE_NAME', 'webrtc2');

/**
 * Path for SquirrelMail required files.
 * @ignore
 */
define('SM_PATH','../');

/** SquirrelMail required files. */
require_once(SM_PATH . 'include/validate.php');
require_once(SM_PATH . 'functions/global.php');
require_once(SM_PATH . 'functions/display_messages.php');
require_once(SM_PATH . 'functions/addressbook.php');
require_once(SM_PATH . 'functions/strings.php');
require_once(SM_PATH . 'functions/html.php');
require_once(SM_PATH . 'functions/forms.php');

?>
<!DOCTYPE HTML>

<html>
<head>
  <link rel="stylesheet" href="style.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
  <script type="text/javascript" src="js/peer.js"></script>
  <script>

    // Compatibility shim
    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;

    // PeerJS object
    var peer = new Peer({ key: 'cky3yqb23xxenrk9', debug: 3, config: {'iceServers': [
      { url: 'stun:stun.l.google.com:19302' } // Pass in optional STUN and TURN server for maximum network compatibility
    ]}});

    peer.on('open', function(){
      $('#my-id').text(peer.id);
	window.peerid = peer.id;
	//alert("Peer ID: " + window.peerid + " " + peer.id);
    });
    
	// Receiving a call
    peer.on('call', function(call){
      // Answer the call automatically (instead of prompting user) for demo purposes
      call.answer(window.localStream);
      step3(call);
    });
    peer.on('error', function(err){
      alert(err.message);
      // Return to step 2 if error occurs
      step2();
    });

    // Click handlers setup
    $(function(){
      $('#make-call').click(function(){
        // Initiate a call!
        var call = peer.call($('#callto-id').val(), window.localStream);

        step3(call);
      });

      $('#end-call').click(function(){
        window.existingCall.close();
        step2();
      });

      // Retry if getUserMedia fails
      $('#step1-retry').click(function(){
        $('#step1-error').hide();
        step1();
      });

      // Get things started
      step1();
    });

    function step1 () {
      // Get audio/video stream
      navigator.getUserMedia({audio: true, video: true}, function(stream){
        // Set your video displays
        $('#my-video').prop('src', URL.createObjectURL(stream));

        window.localStream = stream;
        step2();
      }, function(){ $('#step1-error').show(); });
    }

    function step2 () {
      $('#step1, #step3').hide();
      $('#step2').show();
    }

    function step3 (call) {
      // Hang up on an existing call if present
      if (window.existingCall) {
        window.existingCall.close();
      }

      // Wait for stream on the call, then set peer video display
      call.on('stream', function(stream){
        $('#their-video').prop('src', URL.createObjectURL(stream));
      });

      // UI stuff
      window.existingCall = call;
      $('#their-id').text(call.peer);
      call.on('close', step2);
      $('#step1, #step2').hide();
      $('#step3').show();
    }

  </script>

</head>

<body>
<?php echo displayPageHeader($color,'None');?>
<b>Who would you like to chat with?</b></br>

<select id="userinvt">
<?php
	$user_list = shell_exec('awk -F: \'$3 >= 1000 && $1 != "nobody" {print $1}\' /etc/passwd'); 
	$user_arr = explode("\n", $user_list);
	foreach ($user_arr as $user_inst) {
		if(($user_inst != "")&&($user_inst != $username))
		echo "<option value=\"".$user_inst."\">".$user_inst."</option>";
	}
?>
</select>
<input type="button" id="invite" value="Invite"/>
<br/></br/>
<a href="/src/webrtc2.php">Refresh RTC</a>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
</script>
<script>
$(document).ready(function(){
$("#invite").click(function(){
  var a = document.getElementById('userinvt');

   $.post("send_invite.php",
   {
               'userpeerid': window.peerid,
               'userinvtid': a.options[a.selectedIndex].value
    },
   function(data,status){
	$("#invite").attr("disabled",true);
   });  
});
}); 

</script>

  <div class="pure-g">

      <!-- Video area -->
      <div class="pure-u-2-3" id="video-container">
        <video id="their-video" autoplay></video>
        <video id="my-video" muted="true" autoplay></video>
      </div>

      <!-- Steps -->
      <div class="pure-u-1-3">

        <!-- Get local audio/video stream -->
        <div id="step1">
          <p>Please click `allow` on the top of the screen so we can access your webcam and microphone for calls.</p>
          <div id="step1-error">
            <p>Failed to access the webcam and microphone. Click allow when asked for permission by the browser.</p>
            <a href="#" class="pure-button pure-button-error" id="step1-retry">Try again</a>
          </div>
        </div>

        <!-- Make calls to others -->
        <div id="step2">
          <p>Your id: <span id="my-id">...</span></p>
          <p>Share this id with others so they can call you.</p>
          <h3>Make a call</h3>
          <div class="pure-form">
            <input type="text" placeholder="Call user id..." id="callto-id">
            <a href="#" class="pure-button pure-button-success" id="make-call">Call</a>
          </div>
        </div>
        <!-- Call in progress -->
        <div id="step3">
          <p>Currently in call with <span id="their-id">...</span></p>
          <p><a href="#" class="pure-button pure-button-error" id="end-call">End call</a></p>
        </div>
      </div>
  </div>


</body>
</html>

