# ejecución cada 12 horas
<?php
require_once '../../config/db.php';
require_once '../functions.php';

#Costos fijos
$fixedCosts =  costs('fixedcosts');
$result = false;
foreach ($fixedCosts as $key => $value) {
    $average = 0;
    $average = json_decode(averageCosts($value->name));
    $detail = "";
    $detail = $average[0]->detail;
    $averageValue = $average[0]->value;
    $update = updateaverageCosts($detail, $averageValue);
    if ($update) {
        $result = true;
    } else {
        $result = false;
    }
}
return $result;