<?php 
require_once 'config.php';
function connectDB() {
	$connection = new mysqli(HOST, USER, PASSWORD, DB);
	if ($connection->connect_errno) {
		return "Error al conectar";
		exit();
	}else {
		mysqli_set_charset($connection, 'utf8mb4');
		return $connection;
	}
}
function disconnectDB($connection) {
	return $connection -> close();
}