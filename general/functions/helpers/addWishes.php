<?php
session_start();
require_once '../../config/db.php';
$aData = file_get_contents('php://input');
$connection = connectDB();
$aData =  $connection->real_escape_string($aData);
$query =  "SELECT id FROM wishes WHERE id = '1' LIMIT 1";
$execution = mysqli_query($connection,$query);
if ($execution->num_rows > 0) {
  $queryUpdate = "UPDATE wishes SET JSON = '$aData' WHERE id = '1'";
  $executionUpdate = mysqli_query($connection,$queryUpdate);
  $executionUpdate == "1" ? $return = true : $return = false;
  echo $return;
} else {
  $queryInsert = "INSERT INTO wishes (id, JSON) VALUES ('1', '$aData')";
  $executionInsert = mysqli_query($connection,$queryInsert);
  $execution == "1" ? $return = true : $return = false;
  echo $return;
}
?>