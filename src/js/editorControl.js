var fileURL;
var firepadObj;

function fillFirepad(ret_val,fhash,user,data)
{
	console.log(ret_val);
	if(ret_val == -1)
	{
		alert("Database entry issue !!! Make sure you are not uploading file with same name again!!!");
	}
	else if (ret_val == -2)
	{
		alert("Some strange issue happened in DB !!!");
	}
	else if (ret_val == 1)
	{
		alert ('Oops! Seems the file you are trying to upload does not have .txt extension. Please upload plain text files with .txt extension.');
	}
	else if(ret_val == 3)
	{
		alert('Error in uploading of file. Please try again.');
	}
	else if(ret_val == 4)
	{
		//alert('Attachment Missing!!! Browse the file to be uploaded');
	}
	else if((ret_val == 0)||(ret_val == 2))
	{
		var url = "https://resplendent-fire-6199.firebaseio.com/squirrelmail/" + fhash;
		fp = initializeFirepad(url,user,data);
	}
}

function initializeFirepad(url,user,data)
{
	var firepadRef = new Firebase(url);
	var codeMirror = CodeMirror(document.getElementById('smEditor'), { lineWrapping: true });
	var firepad = Firepad.fromCodeMirror(firepadRef, codeMirror,
          { richTextShortcuts: true, richTextToolbar: true }); 
    setFirepad(firepad);
    firepad.setUserId(user);
    document.getElementById("uploadFile").style.display = "none";
    document.getElementById("existingFiles").style.display = "none";
    document.getElementById("addCollabDiv").style.display = "block";
    document.getElementById("revisionHistory").style.display = "block";
    document.getElementById("saveDiv").style.display = "block";
    firepad.on('ready', function() {
    	if(firepad.isHistoryEmpty()){
    		firepad.setText(data);
    	};
    });
    setFileURL(url);
}


function loadFirepad(url,user)
{
	var firepadRef = new Firebase(url);
	var codeMirror = CodeMirror(document.getElementById('smEditor'), { lineWrapping: true });
	var firepad = Firepad.fromCodeMirror(firepadRef, codeMirror,
          { richTextShortcuts: true, richTextToolbar: true });
    setFirepad(firepad);
    firepad.setUserId(user);
    document.getElementById("uploadFile").style.display = "none";
    document.getElementById("existingFiles").style.display = "none";
    document.getElementById("addCollabDiv").style.display = "block";
    document.getElementById("revisionHistory").style.display = "block";
    document.getElementById("saveDiv").style.display = "block";
    firepad.on('ready', function() {
    	if(firepad.isHistoryEmpty()){
    		firepad.setText('');
    	};
    });
    setFileURL(url);
  //  loadRev();
    
}

/*
function loadRev()
{
	var fpad = getFirepad();
	for every child in the 'history' ref: op = fpad.TextOperation.fromJSON(child.val().o);
	value = op.apply(value);
	console.log(value);
}
*/

function jQuery_sqmail(whoami){
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
	
		socket.emit('iam',whoami);
	
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

	});
}

function setFirepad(fpad)
{
	firepadObj = fpad;
}

function getFirepad()
{
	return firepadObj;
}

function setFileURL(url)
{
	fileURL = url;
}

function getFileURL()
{
	return fileURL;
}
