<?php
ini_set("session.cookie_httponly", 1);
session_start();
header("Content-Type: application/json");

if (isset($_SESSION['username'])) {

	$mysqli = new mysqli('localhost', 'harrisonlu', 'Sandia.1', 'calendar');
	if ($mysqli -> connect_errno) {
		printf("Connection Failed: %s\n", $mysqli -> connect_error);
		exit;
	}
$user = $_SESSION['username'];					//declare variables to be passed in to sql search
$day = $_POST['day'];
$month = $_POST['month'];
$year = $_POST['year'];
$eventsArray = array();							//returned values will be stored in this array

$stmt = $mysqli->prepare("												
	select 
	eventID, startTime, endTime, event 
	from 
	events 
	where 
	user like ? and month(startTime) like ? and day(startTime) like ? and year(startTime) like ?");
if (!$stmt) {
	printf("Query prep failed: %s\n", $mysqli -> error);
	exit;
}
$stmt -> bind_param('siii', $user, $month, $day, $year);
$stmt -> execute();
$stmt -> bind_result($eventID, $startTime, $endTime, $event);

while ($stmt->fetch()){														//place each set of results into array and push array into second array
	$eventArray = array("eventID" => htmlspecialchars($eventID), "startTime" => htmlspecialchars($startTime), "endTime" => htmlspecialchars($endTime), "event" => htmlspecialchars($event));                                  
	array_push($eventsArray, $eventArray);
}

$stmt -> close(); 


echo json_encode($eventsArray);					//encode results array into json format				
}
else {
	echo json_encode(array("nil"));
}
?>
