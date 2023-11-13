<?php
session_start();
require_once '../../config/db.php';
$user = limpiarDatos(filter_var(strtolower($_POST['user'])));
$password = limpiarDatos($_POST['password']);
$password = hash('sha512', $_POST['password']);
$connection = connectDB();
$query =  $connection->prepare("SELECT id, name, lastname, user, validation, rol, password FROM user WHERE user = ? and password = ? LIMIT 1");
$query->bind_param('ss', $user, $password);
$query->execute();
$executionLogin = $query->get_result();
if ($executionLogin->num_rows > 0):
	sleep(1);
	$oSession = $executionLogin->fetch_object();
	$_SESSION['id'] = $oSession->id;
	$_SESSION['user'] = $user;
	$_SESSION['name'] = $oSession->name;
	$_SESSION['lastname'] = $oSession->lastname;
	$_SESSION['rol'] = $oSession->rol;
	$_SESSION['session'] = $oSession->validation;
	echo json_encode(array('error'=> false, 'session' => $oSession->validation));
else:
	sleep(1);
	echo json_encode(array('error' =>true));
endif;
$disconnectDB = disconnectDB($connection);