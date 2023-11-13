<?php 
session_start();
require 'general/config/config.php';
if (isset($_SESSION['user'])) {
	$title = "Tique Wallet | Actividad 4";
	require 'general/config/db.php';
	require 'general/functions/functions.php';
	require 'general/views/layout/header.html';
	require 'views/indexView.html';
	require 'general/views/layout/footer.html';
} else {
	$title = "Sistema de Gestión Financiera Personal";
	require 'general/views/layout/headerLogin.html';
	require 'general/views/loginView.html';
	require 'general/views/layout/footerLogin.html';
}?>