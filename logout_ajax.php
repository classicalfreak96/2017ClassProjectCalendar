<?php
header("Content-Type: application/json");
ini_set("session.cookie_httponly", 1);
session_start();
$_SESSION['username'] = "";							//clear username
session_unset();									//unset session variables
session_destroy();									//destroy session variables
$_SESSION = NULL;

?>