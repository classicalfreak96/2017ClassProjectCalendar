<?php
ini_set("session.cookie_httponly", 1);
session_start();
header("Content-Type: application/json");

$mysqli = new mysqli('localhost', 'harrisonlu', 'Sandia.1', 'calendar');			//connect to sql
if ($mysqli -> connect_errno) {
	printf("Connection Failed: %s\n", $mysqli -> connect_error);
	exit;
}
$eventID = $_POST['eventID'];
																					//delete all events associated with user
$stmt = $mysqli->prepare("												
	delete  
	from 
	events 
	where 
	user like ?");
if (!$stmt) {
	printf("Query prep failed: %s\n", $mysqli -> error);
	exit;
}
$stmt -> bind_param('s', $_SESSION['username']);
$stmt -> execute();

while ($stmt->fetch()){
}

$stmt -> close(); 
																					//delete user from users table
$stmt = $mysqli->prepare("												
	delete  
	from 
	users 
	where 
	user like ?");
if (!$stmt) {
	printf("Query prep failed: %s\n", $mysqli -> error);
	exit;
}
$stmt -> bind_param('s', $_SESSION['username']);
$stmt -> execute();

while ($stmt->fetch()){
}

$stmt -> close(); 

$_SESSION['username'] = "";													//destroy session
session_unset();
session_destroy();
$_SESSION = NULL;
?>