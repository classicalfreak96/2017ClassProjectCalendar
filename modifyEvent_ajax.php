<?php
ini_set("session.cookie_httponly", 1);
session_start();
header("Content-Type: application/json");

$startTime = $_POST['startTime'];												//declare variables
$endTime = $_POST['endTime'];
$eventTitle = $_POST['eventTitle'];
$eventId = $_POST['eventID'];

$mysqli = new mysqli('localhost', 'harrisonlu', 'Sandia.1', 'calendar');		//connect to sql
if ($mysqli -> connect_errno) {
	printf("Connection Failed: %s\n", $mysqli -> connect_error);
	exit;
}

$stmt = $mysqli->prepare("												
	update events
	set startTime = ?,
	endTime = ?,
	event = ?
	where
	eventID like ?");
if (!$stmt) {
	printf("Query prep failed: %s\n", $mysqli -> error);
	exit;
}
$stmt -> bind_param('sssi', $startTime, $endTime, $eventTitle, $eventId);			//post changes into sql
$stmt -> execute();

while ($stmt->fetch()){
}

$stmt -> close(); 

?>

