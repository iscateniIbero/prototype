<?php
require_once '../../config/db.php';
require_once '../functions.php';
$investments = json_decode(patrimonyInvestments());
$saving = json_decode(saving());
$emergencyFound = json_decode(emergencyFound());
$debts = json_decode(valueDebt());
$assets = $investments->investments->total + $saving->total + $emergencyFound->total;
$data = array(
  "assets" => $assets,
  "liabilities" => $debts->totalDebit,
  "equity" => $assets - $debts->totalDebit
);
echo json_encode($data);