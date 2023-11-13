<?php 
require_once '../../config/db.php';
require_once '../functions.php';
$aData = json_decode(file_get_contents('php://input'),true);
$stocks = getDetailStocksInverted($aData['asset']);
echo json_encode($stocks);