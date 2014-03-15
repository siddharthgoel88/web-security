/**
 * @author Siddharth Goel
 */

var fs = require('fs');

var options = {
	key : fs.readFileSync('/etc/apache2/ssl/squirrelmail.key'),
	cert : fs.readFileSync('/etc/apache2/ssl/squirrelmail.crt'),
	ca : fs.readFileSync('/etc/apache2/ssl/squirrelmail.crt')
};

var app = require('express')(), server = require('https').createServer(options, app), io = require('socket.io').listen(server), mysql = require('mysql'), crypto = require('crypto'), validator = require('validator'), onlineUser = {}, revLookup = {};

var connection = mysql.createConnection({
	host : 'localhost',
	user : 'root',
	password : 'student'
});

connection.connect(function(err) {
	if (err)
		throw err;
	else
		console.log("Connected to Database!!!");
});

server.listen(3000);

io.sockets.on('connection', function(socket) {

	var user = '';

	socket.on('iam', function(data) {
		user = validator.toString(validator.escape(data));
		if (!onlineUser[user]) {
			onlineUser[user] = [];
		}
		onlineUser[user].push(socket);
		revLookup[socket] = user;
		//console.log("Connection established to %s", user);
		//console.log("Socket is %s", socket);
		sendNotifications(user);
		//onlineUser[socket] = user;
		//socket.join(user);
	});
	//var notif = getNotifications(user);
	//socket.emit('notification',notif);

	socket.on('shared-doc-response', function(data) {
		if (check_soc(socket))
			return;
			
		if((!validator.isAlphanumeric(data.requestID)) || (!validator.isNumeric(data.status)))
			return;
			
		var rid = validator.escape(data.requestID);
		var status = data.status;

		//console.log("shared-doc-response call-back");
		//console.log("Status =%s!!", status);
		//console.log("rid =%s!!", rid);

		connection.query('USE SM', function(err) {
			if (err) {
				console.log("Could not use SM @ shared-doc-response");
			}

			if (status == '0') {
				var param = [rid];
				connection.query('SELECT RequestHash from REQUESTS where RequestHash = ? AND Status=\'0\'', param, function(err, rows, field) {
					if (err) {
						console.log("Could not query RequestHash @ shared-doc-response");
					}
					if (rows.length == 1) {
						var param = [rows[0].RequestHash];
						connection.query('DELETE from REQUESTS where RequestHash = ?', param, function(err) {
							if (err) {
								console.log("Issues in deleting the RequestHash @ shared-doc-response");
							}
						});
					}
				});
			} else if (status == '1') {
				var param = [rid];
				connection.query('SELECT Receiver, FileHash, RequestHash from REQUESTS where ' + 'RequestHash = ? AND Status = \'0\'', param, function(err, rows, field) {
					if (err) {
						console.log("Issues in quering the RequestHash @ shared-doc-response");
					}
					if (rows.length == 1) {
						connection.query('START TRANSACTION ', function(err) {
							if (err) {
								console.log("Error in transaction start !!!");
							}
							var param = [rows[0].RequestHash];
							connection.query('DELETE FROM REQUESTS WHERE RequestHash = ? ', param, function(err) {
								if (err) {
									console.log("Error in deletion !!!");
								}
								var param = [rows[0].FileHash, rows[0].Receiver];
								connection.query('INSERT INTO COLLABORATORS (FileHash, Collaborator) VALUES (?,?)', param, function(err) {
									if (err) {
										console.log("Error in insertion !!!");
									}
									connection.query('COMMIT', function(err) {
										if (err) {
											console.log("Could not commit !!!");
										}
									});
								});
							});
							//+ 'INSERT INTO COLLABORATORS (FileHash, Collaborator) VALUES (?,?); COMMIT;'
						});
					}

				});
			}
		});
	});

	socket.on('collaborator', function(data) {
		if(check_soc(socket))
			return;

		var collaborator = data.collaborator;
		var url = data.url;

		collaborator = validator.toString(validator.escape(collaborator.trim()));
		if(!validator.isURL(url))
			return;

		//console.log("Collab = %s", collaborator);
		//console.log("URL = %s", url);

		if ((url != '') && (user.localeCompare(collaborator) != 0)) {
			var fhash = url.substring(58);
			var result = '';
			var rhash = fhash + collaborator;
			var shasum = crypto.createHash('sha1');

			//TODO: check if collaborator exist !!!

			shasum.update(rhash);
			rhash = shasum.digest('hex');
			//console.log("Request Hash:%s!!!", rhash);

			connection.query('USE SM', function(err) {
				if (err)
					throw err;
				var param = [rhash];
				connection.query('SELECT * FROM REQUESTS WHERE RequestHash = ?', param, function(err, rows, fields) {
					if (err)
						throw err;
					if (rows.length == 0) {
						var param = [user, collaborator, fhash, 0, rhash];
						connection.query('INSERT INTO REQUESTS (Sender, Receiver,FileHash,Status,RequestHash) ' + 'VALUES (?,?,?,?,?)', param, function(err) {
							if (err) {
								console.log('Some strange issue');
								console.log("Param =%s,%s,%s,%s!!", user, collaborator, fhash, rhash);
								throw err;
							}
							var param = [fhash];
							connection.query('SELECT FileName FROM FILE WHERE FileHash = ? ', param, function(err, rows, field) {
								if (err)
									throw err;
								result = '<div id="' + rhash + '"><ul><li>' + user + ' added you a collaborator for ' + rows[0].FileName + ' <br/><a href="javascript:void(0)" class="rqst" data-rid="' + rhash + '" data-status="1" >Accept</a> <a href="javascript:void(0)" class="rqst" ' + ' data-rid="' + rhash + '" data-status ="0" >' + ' Ignore</a></li></ul> </div>';

								emitNotif(collaborator, result);
							});
						});
					}
				});
			});
		}

	});

/*
	socket.on('doc-rating', function(data) {
		if(check_soc(socket))
			return;

		if (!validator.isURL(data.url)) {
			console.log("Rating URL is not of correct form \n");
			return;
		}
		var url = data.url;
		var fhash = url.substring(58);
		var rating = validator.toString(validator.escape(data.rating));
		var personRating = revLookup[socket];

		connection.query('USE SM', function(err) {
			if (err) {
				console.log("Issues in using SM database");
				return;
			}

			var param = [fhash, personRating];
			connection.query('SELECT * FROM COLLABORATORS WHERE FileHash=? and Collaborator=?', param, function(err, rows, fields) {
				if (err) {
					console.log("Error in querying Collaborators table \n");
					return;
				}
				if (rows.length == 0) {
					console.log("This user is not allowed to rate this document");
					return;
				}
				var param = [rating, url];
				connection.query('UPDATE FILE SET Rating=? WHERE URL=?', param, function(err) {
					if (err) {
						console.log("Error in updating the Rating of File \n");
						return;
					}
				});
			});
		});
	});*/

	socket.on('load-rating', function(data) {
		if(check_soc(socket))
			return;
			
		if (!validator.isURL(data)) {
			console.log("The load rating URL is not valid \n");
			return;
		}
		var response = '';

		connection.query('USE SM', function(err) {
			if (err) {
				console.log("Some error in connect to SM database \n");
				return;
			}

			var param = [data];
			connection.query('SELECT Rating FROM FILE WHERE URL=?', param, function(err, rows, fields) {
				if (err) {
					console.log("Error in querying FILE table \n");
					return;
				}

				if (rows.length == 1) {
					if (rows[0].Rating == null) {
						response = '<br/><br /><center> <a href="rating.php?file-hash='+ data.substring(58) +'" target="_blank">Click here</a> to rate this document.</center>';
					} else {
						response = '<br/><br/><center>This document has been marked as <i><b>' + rows[0].Rating + '</b></i>.</center>';
					}
					socket.emit('respective-rating', response);
				}
			});
		});

	});

	socket.on('revision-data', function(data) {

	});

	/*
	socket.on('disconnect', function(){
	 var disconnected_user = revLookup[socket];
	 console.log("----------- This is socket del ------------------------");
	 console.log(socket);
	 console.log("******************revlookup**************");
	 console.log(revLookup);
	 console.log("%s to be deleted \n",disconnected_user);
	 console.log(onlineUser[disconnected_user]);
	 var i = (onlineUser[disconnected_user]).indexOf(socket);
	 console.log("Deleting socket for arrays");
	 console.log(i);
	 //console.log((onlineUser[disconnected_user])[i]);
	 (onlineUser[disconnected_user]).splice(i,1);
	 delete revLookup[socket];
	 console.log("he he");
	 console.log(onlineUser[disconnected_user]);
	 });
	 */

});

function check_soc(sock) {
	if (revLookup[sock] === undefined)
		return -1;
	else
		return 0;
}

function sendNotifications(user) {
	var result = '<ul>';
	connection.query('USE SM', function(err) {
		if (err)
			throw err;
		var param = [user];
		connection.query('SELECT FILE.FileName AS FileName, REQUESTS.Sender AS Sender, REQUESTS.RequestHash AS ' + 'RequestHash FROM FILE INNER JOIN REQUESTS WHERE FILE.FileHash=REQUESTS.FileHash AND ' + 'REQUESTS.Receiver=? AND REQUESTS.Status=0', param, function(err, rows, fields) {
			if (err)
				throw err;

			if (rows.length > 0) {
				for (var i in rows) {
					var row = rows[i];
					result += '<div id="' + row.RequestHash + '"><li>' + connection.escape(row.Sender) + ' added you a collaborator for ' + connection.escape(row.FileName) + '<br/> <a href="javascript:void(0)" class="rqst" data-rid="' + row.RequestHash + '" data-status="1">Accept</a> <a href="javascript:void(0)" class="rqst" ' + 'data-rid="' + row.RequestHash + '" data-status="0"> Ignore</a> </li></div>';
				}
				result += '</ul>';
			} else {
				result = '<div id="noNotification"><i> No Notifications !!! </i></div>';
			}
			//console.log("Result: %s, User=%s", result, user);
			emitNotif(user, result);
		});
	});
}

function emitNotif(user, data) {
	var sock = [];
	sock = onlineUser[user];
	//console.log("Entered emitNotif.User=%s", user);
	//console.log(sock);
	for (var i in sock) {
		//console.log("Socket number %d = %s", i + 1, sock[i]);
		sock[i].emit('notification', data);
	}
}
