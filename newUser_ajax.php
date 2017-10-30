<?php
header("Content-Type: application/json");

$validateLogin = false;

	$mysqli = new mysqli('localhost', 'harrisonlu', 'Sandia.1', 'calendar');							//connect to sql
	if ($mysqli -> connect_errno) {
		printf("Connection Failed: %s\n", $mysqli -> connect_error);
		exit;
	}

	if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['retypePassword'])) {	//if fields are filled out
		$username = $_POST['username'];
		$password = $_POST['password'];
		$retypePassword = $_POST['retypePassword'];														//check to make sure passwords match
		$passwordHash = password_hash($password, PASSWORD_BCRYPT);										//hash the given password
		$testvar = strcmp((string) $password, (string) $retypePassword);								//pull any information from users from existing database that matches the given username
		$errorMsg = "";

		$stmt = $mysqli->prepare("																		
			select 	
			user 
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
		$stmt -> bind_result($usernameCompare);														

		while ($stmt->fetch()){
		}

		$stmt -> close(); 

		if ($username == '' || $password == '' || $retypePassword = '') {								//if some field is empty
			$errorMsg = "Please fill all fields";
		}
		else if ($testvar != 0){																		//if passwords do not match
			$errorMsg = "Passwords do not match";
		}
		else if ($username == $usernameCompare){														//if username already exists
			$errorMsg = " user is already in system";
		}
		else {																							//otherwise import data into users sql database
			$stmt = $mysqli -> prepare ("insert into users (user, password) values (?, ?)");
			if (!$stmt) {
				printf("Query prep failed: %s\n", $mysqli -> error);
				exit;
			}
			$stmt -> bind_param('ss', $username, $passwordHash);
			$stmt -> execute();
			$stmt -> close();
			$validateLogin = true;
		}
	}

if($validateLogin){														//if login validated
	ini_set("session.cookie_httponly", 1);
	session_start();													//start session
	$_SESSION['username'] = $username;									//assign passed in username to session variable
	$_SESSION['token'] = substr(md5(rand()), 0, 10);					//generate token

	echo json_encode(array(
		"success" => true
	));
	exit;
}else{
	echo json_encode(array(									//return error message if error
		"success" => false,
		"message" => $errorMsg
	));
	exit;
}
?>