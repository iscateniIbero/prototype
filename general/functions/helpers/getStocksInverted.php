<?php 
require_once '../../config/db.php';
require_once '../functions.php';
$aData = json_decode(file_get_contents('php://input'),true);
$stocks = getStocksInverted($aData['platform']);
echo json_encode($stocks);