function loginAjax(event){															//logs in user
	var username = document.getElementById("username").value;						//declare variables
	var password = document.getElementById("password").value; 
	document.getElementById("username").value = "";									//empties fields once login is pressed
	document.getElementById("password").value = "";
	document.getElementById("logoutForm").style.visibility = "visible";				//once logged in, logout form becomes visible

	var dataString = "username=" + encodeURIComponent(username) + "&password=" + encodeURIComponent(password);	//pass datastring into server 
	
	var xmlHttp = new XMLHttpRequest(); 
	xmlHttp.open("POST", "login_ajax.php", true); 
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText); 						//parse response data for success or failure, and alert
		if(jsonData.success){  
			alert("You've been Logged In!");
			document.getElementById("loginForm").style.visibility = "hidden"
		}else{
			alert("You were not logged in.  "+jsonData.message);
		}
	}, false); 
	xmlHttp.send(dataString); 														//execute
}

function newUser(event){															//puts new user into sql
	var username = document.getElementById("username1").value;						//declare varaibles 
	var password = document.getElementById("password1").value;
	var retypePassword = document.getElementById("retypePassword").value
	document.getElementById("username1").value = "";								//empties fields once button is pressed
	document.getElementById("password1").value = "";
	document.getElementById("retypePassword").value = "";
	document.getElementById("logoutForm").style.visibility = "visible";				//logout form is visible once logged in

	var dataString = "username=" + encodeURIComponent(username) + "&password=" + encodeURIComponent(password) + "&retypePassword=" + encodeURIComponent(retypePassword); //passes datastring into server
	
	var xmlHttp = new XMLHttpRequest(); 
	xmlHttp.open("POST", "newUser_ajax.php", true); 
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText); 
		if(jsonData.success){  													//parse response data for success or failure, and alert
			alert("You've been Logged In!");
			document.getElementById("loginForm").style.visibility = "hidden"
		}else{
			alert("You were not logged in.  "+jsonData.message);
		}
	}, false); 
	xmlHttp.send(dataString); 
}

function loadEvents(day, month, year, node, plusWeek, plusDay){					//loads events from sql into calendar
	month = month + 1;
	var dataString = "day=" + encodeURIComponent(day) + "&month=" + encodeURIComponent(month) + "&year=" + encodeURIComponent(year); //passes datastring into server
	var xmlHttp = new XMLHttpRequest(); 
	xmlHttp.open("POST", "loadEvents_ajax.php", true); 
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	xmlHttp.addEventListener("load", function(event){
		if (event.target.responseText != "null") {
			var jsonData = JSON.parse(event.target.responseText); 
		for (i in jsonData){												//resonse comes in form of json array. parse json array to extract data
			var node2 = document.createElement("p");						//create new node for each set of data
			var eventID = jsonData[i].eventID;
			node2.setAttribute("class", "events");
			node2.setAttribute("id", eventID);
			text = jsonData[i].event + "(" + jsonData[i].startTime.substr(11, 5) + " to " + jsonData[i].endTime.substr(11, 5) + ")";  //format display string
			var textNode2 = document.createTextNode(text);
			console.log(text);
			node2.appendChild(textNode2);
			node.getElementsByClassName("week"+ plusWeek)[0].getElementsByClassName("day"+ plusDay)[0].appendChild(node2);		//appendnode 
			document.getElementsByClassName("week"+ plusWeek)[0].getElementsByClassName("day"+ plusDay)[0].getElementsByClassName("events")[i].addEventListener("click", function(){													//when clicking the event once, dialogue box opens 
				$("#modDel").data('param_3', this.id);
				$("#modDel").data('param_2', day);
				$("#modDel").dialog({
					autoOpen: false,
					show: false,
					resizable: true,
					position: 'center',
					stack: true,
					height: 'auto',
					width: 'auto',
					modal: false,
					buttons: {
							"Modify Event": function(){						//modify or delete event? 
								$("#modDel").dialog('close');
								$("#divdeps2").dialog({
									autoOpen: false,
									show: false,
									resizable: true,
									position: 'center',
									stack: true,
									height: 'auto',
									width: 'auto',
									modal: false
								});
								$("#divdeps2").dialog('open');				//modifying event opens the modify event dialogue box
							},
							"Delete Event": function(){
								deleteEvent($("#modDel").data('param_3'));	//deleting the event calls the delete event function
							}
						}
					});
				$("#modDel").dialog('open');
			}, false);
		}
	}
}, false); 
	xmlHttp.send(dataString); 
}
function openaDialog(){									//opens dialogue, passes in several variables
	$("#modDel").dialog({
		autoOpen: false,
		show: false,
		resizable: true,
		position: 'center',
		stack: true,
		height: 'auto',
		width: 'auto',
		modal: false });
	$("#modDel").data('param_2', this.id);
	$("#modDel").dialog('open');
}

function deleteEvent(eventID) {							//delets event
	$("#modDel").dialog('close');
	var dataString = "eventID=" + encodeURIComponent(eventID);						//passes datastring into server
	var xmlHttp = new XMLHttpRequest(); 
	xmlHttp.open("POST", "deleteEvent_ajax.php", true); 
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText); 
	}, false); 
	xmlHttp.send(dataString); 							//execute
	updateCalendar();									//redraw calendar
}

function modify(){
	$("#divdeps2").dialog({								//calls dialogue box
		autoOpen: false,
		show: false,
		resizable: true,
		position: 'center',
		stack: true,
		height: 'auto',
		width: 'auto',
		modal: false
	});
	$("#divdeps2").dialog('open');
}

function modifyEvent(day, month, year, eventID){						//modifies event
	$("#divdeps2").dialog('close');
	month = month + 1;
	var eventTitle = document.getElementById("eventTitle1").value;		//delcare variables
	var startTime = document.getElementById("startTime1").value; 
	var endTime = document.getElementById("endTime1").value;
	var finalStartTime = year + "-" + month + "-" + day + " " + startTime;	//format strings to be passed into sql
	var finalEndTime = year + "-" + month + "-" + day + " " + endTime;
	var dataString = "eventID=" + encodeURIComponent(eventID) + "&startTime=" + encodeURIComponent(finalStartTime)  + "&endTime=" + encodeURIComponent(finalEndTime) + "&eventTitle=" + encodeURIComponent(eventTitle);						//datastring will be passed into server
	var xmlHttp = new XMLHttpRequest(); 
	xmlHttp.open("POST", "modifyEvent_ajax.php", true); 
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	xmlHttp.addEventListener("load", function(event){}, false); 
	xmlHttp.send(dataString); 											//execute
	updateCalendar();													//update calendar
}

function addEvent(day, month, year) {									//adds event to calendar
	$("#divdeps").dialog('close');
	month = month + 1;
	var eventTitle = document.getElementById("eventTitle").value;				//declare variables
	var startTime = document.getElementById("startTime").value; 
	var endTime = document.getElementById("endTime").value;
	var finalStartTime = year + "-" + month + "-" + day + " " + startTime;		//format strings to be passed into sql
	var finalEndTime = year + "-" + month + "-" + day + " " + endTime;
	var dataString = "startTime=" + encodeURIComponent(finalStartTime)  + "&endTime=" + encodeURIComponent(finalEndTime) + "&eventTitle=" + encodeURIComponent(eventTitle); //datastring to be passed into server
	var xmlHttp = new XMLHttpRequest(); 
	xmlHttp.open("POST", "addEvent_ajax.php", true); 
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	xmlHttp.addEventListener("load", function(event){}, false); 
	xmlHttp.send(dataString); 											//execute
	updateCalendar();													//redraw calendar
}

function logout(event){													//logs user out
	document.getElementById("loginForm").style.visibility = "visible";	//login form becomes visible once button is pressed
	document.getElementById("logoutForm").style.visibility = "hidden";	//logout form is hidden
	var xmlHttp = new XMLHttpRequest(); 
	xmlHttp.open("POST", "logout_ajax.php", true); 
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlHttp.send(); 													//execute
	alert("succesfully logged out");
}

function deleteAccount(event){											//deletes account
	document.getElementById("loginForm").style.visibility = "visible";	//login form becomes visible once button is pressed
	document.getElementById("logoutForm").style.visibility = "hidden";	//logout form is hidden
	var xmlHttp = new XMLHttpRequest(); 
	xmlHttp.open("POST", "deleteAccount_ajax.php", true); 
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	alert("succesfully deleted account");
	xmlHttp.send(); 													//execute
	
}