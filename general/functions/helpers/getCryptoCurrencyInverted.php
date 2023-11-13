<?php
require_once '../../config/db.php';
require_once '../functions.php';
$aData = json_decode(file_get_contents('php://input'),true);
$cryptoCurrency = getCryptoCurrencyInverted($aData['platform']);
echo json_encode($cryptoCurrency);