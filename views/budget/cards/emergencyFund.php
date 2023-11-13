<?php 
require_once '../../../general/config/db.php';
require_once '../../../general/functions/functions.php';
session_start(); 
$emergencyFound = json_decode(emergencyFound());
$goal = "$" . number_format($_SESSION["totalExpenses"] * 12, 0, '.', '.'); 
$mediumTerm = "$" . number_format($_SESSION["totalExpenses"] * 6, 0, '.', '.'); 
$shortTerm = "$" . number_format($_SESSION["totalExpenses"] * 3, 0, '.', '.'); 
?>
<div class="row">
    <div class="col-sm-4">
        <span style="font-size: 12px; color: #F0B90B; font-weight: bold;">Meta 12 meses</span>
        <p style="font-size: 16px; color: #fff;"><?= $goal ?></p>
    </div>
    <div class="col-sm-4 text-center">
        <span style="font-size: 12px; color: #F0B90B; font-weight: bold;">Medio plazo 6</span>
        <p class="text-center" style="font-size: 16px; color: #fff;"><?= $mediumTerm ?></p>
    </div>
    <div class="col-sm-4 text-end">
        <span style="font-size: 12px; color: #F0B90B; font-weight: bold;">Corto plazo 3</span>
        <p class="text-end" style="font-size: 16px; color: #fff;"><?= $shortTerm ?></p>
    </div>
    <div class="col-sm-12 text-center">
        <span style="font-size: 12px; color: #F0B90B; font-weight: bold;">Último balance</span>
        <p class="text-center positive" style="font-size: 16px;"><?php echo "$" . number_format($emergencyFound->total, 0, '.', '.'); ?></p>
    </div>
</div>