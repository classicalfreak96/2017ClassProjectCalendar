<?php
header("Content-Type: application/json");

$validateLogin = false;

$mysqli = new mysqli('localhost', 'harrisonlu', 'Sandia.1', 'calendar');
if ($mysqli -> connect_errno) {
	printf("Connection Failed: %s\n", $mysqli -> connect_error);
	exit;
}

	if (isset($_POST['username']) && isset($_POST['password'])) {				//if username and password is set
		$username = $_POST['username'];
		$password = $_POST['password'];											//pull user and password from database
		$stmt = $mysqli->prepare("												
			select 
			password 
			from 
			users 
			where 
			user like ?");
		if (!$stmt) {
			printf("Query prep failed: %s\n", $mysqli -> error);
			exit;
		}
		$stmt -> bind_param('s', $username);
		$stmt -> execute();
		$stmt -> bind_result($passwordCompare);

		while ($stmt->fetch()){
		}

		$stmt -> close(); 

		if (password_verify($password, $passwordCompare)) {						//if password is verified
			$validateLogin = true;						
		}
	}

if($validateLogin){																//if login worked
	ini_set("session.cookie_httponly", 1);
	session_start();															//start session
	$_SESSION['username'] = $_POST['username'];									//assign passed in username to session variable 
	$_SESSION['token'] = substr(md5(rand()), 0, 10);							//generate sesion token

	echo json_encode(array(
		"success" => true
	));
	exit;
}else{
	echo json_encode(array(														//else generate error message
		"success" => false,
		"message" => "Incorrect Username or Password"
	));
	exit;
}
?>