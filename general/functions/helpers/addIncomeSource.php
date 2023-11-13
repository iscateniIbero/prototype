<?php 
require_once '../../config/db.php';
require_once '../functions.php';
$deduction = 0;
$name  = trim($_POST['nameIncome']);
$value = trim(str_replace('.', '', $_POST['valueIncome']));
if ($_POST['deductionPercentage'] > 0) {
    $deduction = ($_POST['deductionPercentage'] * $value) / 100;
}
$netIncome = $value - $deduction;
$date = date('Y-m-d');
$data = array(
	$name,
	$value,	
	$_POST['periodicityIncome'],	
	$_POST['deductionPercentage'],
	$netIncome,
	$date
);
echo addIncomeSource($data);