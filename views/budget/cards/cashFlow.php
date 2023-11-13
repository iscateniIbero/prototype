<?php 
require_once '../../../general/config/db.php';
require_once '../../../general/functions/functions.php';
$cashAndExpense = cashAndExpense();
$monthsAct = date('m');
$monthsPre = date('m', strtotime('-1 month'));
$months = array (1=>'Ene',2=>'Feb',3=>'Mar',4=>'Abr',5=>'May',6=>'Jun',7=>'Jul',8=>'Ago',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dic');
$cashFlowPre = $cashAndExpense['incomeLastMonth'] - $cashAndExpense['expenseLastMonth']; 
$cashFlow = $cashAndExpense['incomeMonth'] - $cashAndExpense['expenseMonth']; 
$style = "neutral";
if ($cashFlowPre < 0 ) {
 $stylePre = "negative";
} else {
  $stylePre = "positive";
}
if ($cashFlow < 0) {
  $style = "negative";
} else  {
  $style = "positive";
}
?>
<div class="row">
	<div class="col-sm cardInfoText">
		<strong>Tipo</strong><br>
		<span class="positive">◉ </span><span class="headers">Entrada de efectivo</span><br>
		<span class="negative">◉ </span><span class="headers">Salida de efectivo</span><br>
		<span class="headers">Flujo de caja</span><br>
	</div>
	<div class="col-sm text-end cardInfoText">
    <strong><?php echo $months[(int)$monthsPre];?></strong><br>
    <span><?php echo number_format($cashAndExpense['incomeLastMonth'], 0, '.', '.'); ?> $</span><br>
    <span>- <?php echo number_format($cashAndExpense['expenseLastMonth'], 0, '.', '.'); ?> $</span></span><br>
    <span style="font-weight: bold" class="<?php echo $stylePre; ?>"><?php echo number_format($cashFlowPre, 0, '.', '.'); ?> $</span><br>
  </div>
  <div class="col-sm text-end cardInfoText">
    <strong><?php echo $months[(int)$monthsAct];?></strong><br>
    <span><?php echo number_format($cashAndExpense['incomeMonth'], 0, '.', '.'); ?> $</span><br>
    <span>- <?php echo number_format($cashAndExpense['expenseMonth'], 0, '.', '.'); ?> $</span></span><br>
    <span style="font-weight: bold" class="<?php echo $style; ?>"><?php echo number_format($cashFlow, 0, '.', '.'); ?> $</span><br>
  </div>
</div>