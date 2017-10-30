<?php
ini_set("session.cookie_httponly", 1);
session_start();
header("Content-Type: application/json");

$eventID = $_POST['eventID'];														//declare variables

$mysqli = new mysqli('localhost', 'harrisonlu', 'Sandia.1', 'calendar');			//connect to sql
if ($mysqli -> connect_errno) {
	printf("Connection Failed: %s\n", $mysqli -> connect_error);
	exit;
}

$stmt = $mysqli->prepare("												
	delete  
	from 
	events 
	where 
	eventID like ?");
if (!$stmt) {
	printf("Query prep failed: %s\n", $mysqli -> error);
	exit;
}
$stmt -> bind_param('i', $eventID);
$stmt -> execute();

while ($stmt->fetch()){
}

$stmt -> close(); 


echo json_encode("event deleted");
?>
