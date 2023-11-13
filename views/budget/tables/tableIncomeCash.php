<?php
require_once '../../../general/config/db.php';
require_once '../../../general/functions/functions.php';
$transactionIncome = json_decode(transactionIncomeAndExpense('Income'));
?>
<?php foreach ($transactionIncome->transaction as $key) {?>
<div class="row row-cols-3" style="font-size: 13px;">
    <div class="col" style="font-weight: bold;">
     <?php echo $key->name; ?>
    </div>
    <div class="col" style="text-align: right;">
      <span class="positive">$<?php echo number_format($key->value, 0, '.', '.');?></span>
    </div>
    <div class="col" style="text-align: right;">
      <?php echo fechaCastellano($key->date);?>
    </div>
</div><hr>
<?php } 
function fechaCastellano ($fecha) {
  $fecha = substr($fecha, 0, 10);
  $numeroDia = date('d', strtotime($fecha));
  $dia = date('l', strtotime($fecha));
  $mes = date('F', strtotime($fecha));
  $anio = date('Y', strtotime($fecha));
  $dias_ES = array("Lun.", "Mar.", "Mié.", "Jue.", "Vie.", "Sáb.", "Dom.");
  $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
  $nombredia = str_replace($dias_EN, $dias_ES, $dia);
$meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Dic.");
  $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
  $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
  return $nombredia." ".$numeroDia." de ".$nombreMes;
}
?>