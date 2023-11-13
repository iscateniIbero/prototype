<?php 
require_once '../../../general/config/db.php';
require_once '../../../general/functions/functions.php';
$incomeSource = json_decode(transactionIncomeAndExpense('Income'));
$investments = json_decode(investments());
$totalIncome = 0;
$monthlySaving = 0;
$fortnightlySavings = 0;
$monthlySaving = $incomeSource->total * 0.05;
$fortnightlySavings = $monthlySaving / 2;
?>
<div class="row">
    <div class="col-sm-4">
        <span style="font-size: 12px; color: #F0B90B; font-weight: bold;">Mensual</span>
        <p style="font-size: 16px; color: #fff;"><?php echo "$" . number_format($monthlySaving, 0, '.', '.'); ?></p>
    </div>
    <div class="col-sm-4 text-center">
        <span style="font-size: 12px; color: #F0B90B; font-weight: bold;">Quincenal</span>
        <p class="text-center" style="font-size: 16px; color: #fff;"><?php echo "$" . number_format($fortnightlySavings, 0, '.', '.'); ?></p>
    </div>
    <div class="col-sm-4 text-end">
        <span style="font-size: 12px; color: #F0B90B; font-weight: bold;">Porcentaje</span>
        <p class="text-end" style="font-size: 16px; color: #fff;">5%</p>
    </div>
    <div class="col-sm-6 text-end">
        <span style="font-size: 12px; color: #F0B90B; font-weight: bold;">Total Invertido</span>
        <p class="text-end" style="font-size: 16px; color: #fff;"><?php echo "$" . number_format($investments->investments->totalInvested, 0, '.', '.'); ?></p>
    </div>
    <div class="col-sm-6">
        <span style="font-size: 12px; color: #F0B90B; font-weight: bold;">Último balance</span>
        <p style="font-size: 16px; color: #fff;"><?php echo "$" . number_format($investments->investments->totalBalance, 0, '.', '.'); ?></p>
    </div>
</div>
