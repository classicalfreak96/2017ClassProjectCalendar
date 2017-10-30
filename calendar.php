<!doctype html>

<html>
<head>
	<title> Our Calendar </title>
	<!-- 	javascript sources -->
	<link rel="stylesheet" type="text/css" href="calendarCSS.css"/>
	<script src="http://code.jquery.com/jquery-1.7.1.min.js" type="text/javascript"></script>
	<script src="http://code.jquery.com/ui/1.7.1/jquery-ui.min.js"> type="text/javascript" </script>
	<script src="calendar.min.js"></script>
	<script type="text/javascript" src="ajax.js"></script>
</head>
<body onload = "updateCalendar();">

	<!-- 	forms for logging in user, hidden when user is logged in -->
	<div id = "loginForm">
		<p>
			<input type="text" id="username" placeholder="Username" />
			<input type="password" id="password" placeholder="Password" />
			<button id="login_btn" onclick = "loginAjax(event); updateCalendar();">Log In</button>
		</p>
		<p>
			<input type="text" id="username1" placeholder="Username" />
			<input type="password" id="password1" placeholder="Password" />
			<input type="password" id="retypePassword" placeholder="Retype Password" />
			<button id="login_btn" onclick = "newUser(event); updateCalendar();">Register</button>
		</p>
	</div>

	<!-- buttons for switching between months -->
	<div class = "month1" id = "month">
		<div class = "previousMonth"></div>
		<div class = "nextMonth"></div>
		<div class = "currentMonth"></div>
	</div>

	<!-- declare table, insert weekdays at top -->
	<table style = "width:100%" id = "calendarTable" class = "calendarTable">
		<thead> 
			<tr>
				<th>Sunday</th>
				<th>Monday</th>
				<th>Tuesday</th>
				<th>Wednesday</th>
				<th>Thursday</th>
				<th>Friday</th>
				<th>Saturday</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>

	<!-- dialogue box design -->
	<div id="fn" hidden>Event Title :
		<input type="text" />
	</div>

	<!-- add event -->
	<div id="divdeps" style= "display:none" title = "Add Event" class = "divdeps">
		<form>
			Event Title: <input type = "text" class = "title" name = "title" id = "eventTitle"/> <br>
			Start Time: <input type = "time" class = "start" name = "start" id = "startTime"/> <br>
			End Time: <input type = "time" class = "end" name = "end" id = "endTime"/> <br>
		</form> 
		<button type = "submit" value = "Add Event" onclick = "addEvent($('#divdeps').data('eventID'), currentMonth.month, currentMonth.year);"> Add Event </button>
	</div>

	<!-- modify event -->
	<div id = "modDel" title = "Choose" class = "modDel"></div>
	<div id="divdeps2" style= "display:none" title = "Modify Event" class = "divdeps2">
		<form>
			Event Title: <input type = "text" class = "title" name = "title" id = "eventTitle1"/> <br>
			Start Time: <input type = "time" class = "start" name = "start" id = "startTime1"/> <br>
			End Time: <input type = "time" class = "end" name = "end" id = "endTime1"/> <br>
		</form> 
		<button type = "submit" value = "Modify Event" onclick = "modifyEvent($('#modDel').data('param_2'), currentMonth.month, currentMonth.year, $('#modDel').data('param_3'));"> Modify Event </button>
	</div>
	
	<!-- buttons to traverse the months -->
	<button type="previous month" form="form1" value="prevMonth" id = "prev_month_btn">Previous Month</button>
	<button type="next month" form="form1" value="nextMonth" id = "next_month_btn">Next Month</button>

	<!-- buttons to logout or delete account -->
	<div id = "logoutForm" style = "visibility: hidden">
		<p>
			<button id="logout_btn" onclick = "logout(event); updateCalendar();" visibility = "hidden">Log Out</button>
			<button id="delete_btn" onclick = "deleteAccount(event); updateCalendar();">Delete Account</button>
		</p>
	</div>



	<script>
	var currentMonth = new Month(2017, 9); // October 2017
	convertMonth(currentMonth.month, currentMonth.year); // Change the month when the "next" button is pressed
	document.getElementById("next_month_btn").addEventListener("click", function(){
			currentMonth = currentMonth.nextMonth(); // Previous month would be currentMonth.prevMonth()
			updateCalendar(); // Whenever the month is updated, we'll need to re-render the calendar in HTML
			convertMonth(currentMonth.month, currentMonth.year); //alert("The new month is "+currentMonth.month+" "+currentMonth.year);
		}, false);
	document.getElementById("prev_month_btn").addEventListener("click", function(){
			currentMonth = currentMonth.prevMonth(); // Previous month would be currentMonth.prevMonth()
			updateCalendar(); // Whenever the month is updated, we'll need to re-render the calendar in HTML
			convertMonth(currentMonth.month, currentMonth.year); 
		}, false);

	function updateCalendar(){											//redraws calendar
		var weeks = currentMonth.getWeeks();
		var dateNum;
		var newTbody = document.createElement("tbody");	
		for(var w in weeks){
			var days = weeks[w].getDates();
			var node = document.createElement("tr");					//create each table cell
			node.setAttribute("class", "week" + w);						//each node has a differnt class to more easily identify it
			newTbody.appendChild(node);
			for(var d in days){											//draws dates relevant only to present month
				var date = days[d].getDate();
				if (w == 0){ 											//checks first week
					if (date > 24){ 									//checks for beginning of the month
						dateNum = " "; 									//current month first day
					}
					else{ 												//if it is the beginnign of the month
						dateNum = date;
					}
				}
				else if (w == weeks.length-1){ 							//checks last week
					if (date < 7){ 										//checks for repeat
						dateNum = " ";
					}
					else{
						dateNum = date;
					}
				}
				else{ 													// looks at all other weeks
					dateNum = date;
				}
				var node1 = document.createElement("td");				//create another cell
				node1.setAttribute("id", date);			
				var textNode1 = document.createTextNode(dateNum);
				if (dateNum != " "){									//if there is a date on the calendar
					node1.addEventListener("dblclick", function(){		//double click to open dialogue box to add new event
						$("#divdeps").dialog({
							autoOpen: false,
							show: false,
							resizable: true,
							position: 'center',
							stack: true,
							height: 'auto',
							width: 'auto',
							modal: false
						});
						$("#divdeps").data('eventID', this.id);
						$("#divdeps").dialog('open');
					}, false);
				node1.setAttribute("class", "day" + d);													//set unique class for each node
				node1.appendChild(textNode1);
				newTbody.getElementsByClassName("week"+ w)[0].appendChild(node1);
					loadEvents(dateNum, currentMonth.month, currentMonth.year, newTbody, w, d);			//calls load events function from ajax.js
				}
				console.log(days[d].toISOString());
			}
		}
		var oldChild = document.getElementById("calendarTable").getElementsByTagName("tbody")[0];
		oldChild.parentNode.replaceChild(newTbody, oldChild);											//replaces all nodes with new nodes
	}
	function convertMonth(month, year){						//converts integer representation of month into string representation
		var monthString;
		if (month == 0) {
			monthString = "January";
		}
		else if (month == 1){
			monthString = "February";
		}
		else if (month == 2){
			monthString = "March";
		}
		else if (month == 3){
			monthString = "April";
		}
		else if (month == 4){
			monthString = "May";
		}
		else if (month == 5){
			monthString = "June";
		}
		else if (month == 6){
			monthString = "July";
		}
		else if (month == 7){
			monthString = "August";
		}
		else if (month == 8){
			monthString = "September";
		}
		else if (month == 9){
			monthString = "October";
		}
		else if (month == 10){
			monthString = "November";
		}
		else {
			monthString = "December";
		}
		document.getElementById("month").getElementsByClassName("currentMonth")[0].textContent = monthString + " " + year;		//shows month and year on top of calendar
	}
//    function showDialog(){
//	var event = prompt("event title", "asdasdas");
//    }
</script>
</body>
</html>