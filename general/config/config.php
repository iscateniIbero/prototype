<?php
define("PATH", "https://" . $_SERVER['HTTP_HOST'] . "/");
define("HOST", "localhost");
define("USER", "root");
define("PASSWORD", "881127gecjrNtr");
define("DB", "sgfp");

function limpiarDatos($datos){
  $datos = trim($datos);
  $datos = stripcslashes($datos);
  $datos = htmlspecialchars($datos);
  return $datos;
}