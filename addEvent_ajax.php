<?php
ini_set("session.cookie_httponly", 1);
session_start();
header("Content-Type: application/json");

$startTime = $_POST['startTime'];															//declare variables
$endTime = $_POST['endTime'];
$eventTitle = $_POST['eventTitle'];

$mysqli = new mysqli('localhost', 'harrisonlu', 'Sandia.1', 'calendar');					//connect to sql
if ($mysqli -> connect_errno) {
	printf("Connection Failed: %s\n", $mysqli -> connect_error);
	exit;
}

$stmt = $mysqli->prepare("												
	insert  
	into 
	events 
	(user, startTime, endTime, event)
	values
	(?, ?, ?, ?)");
if (!$stmt) {
	printf("Query prep failed: %s\n", $mysqli -> error);
	exit;
}
$stmt -> bind_param('ssss', $_SESSION['username'], $startTime, $endTime, $eventTitle);	//bind parameters
$stmt -> execute();

while ($stmt->fetch()){
}

$stmt -> close(); 

?>
