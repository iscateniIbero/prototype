<?php
require_once '../../config/db.php';
$connection = connectDB();
$query =  "SELECT JSON FROM wishes WHERE id = '1' LIMIT 1";
$execution = mysqli_query($connection,$query);
if ($execution->num_rows > 0) {
  $row = mysqli_fetch_row($execution);
  echo json_encode($row[0]);
} else {
  echo 0;
}
