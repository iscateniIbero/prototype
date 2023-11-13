<?php 
require_once '../../../general/config/db.php';
require_once '../../../general/functions/functions.php';
$investments = json_decode(patrimonyInvestments());
$saving = json_decode(saving());
$emergencyFound = json_decode(emergencyFound());
$debts = json_decode(valueDebt());
$assets = $investments->investments->total + $saving->total + $emergencyFound->total;
$liabilities = $debts->totalDebit;
$equity = $assets - $debts->totalDebit;
$style = "neutral";
if ($equity < 0 ) {
 $stylePre = "negative";
} else {
  $stylePre = "positive";
}
?>
<div class="row">
	<div class="col-sm cardInfoText">
		<span class="positive">◉ </span><span class="headers">Activos</span><br>
		<span class="negative">◉ </span><span class="headers">Pasivos</span><br>
		<span class="neto">◉ </span><span class="headers">Patrimonio neto</span><br>
	</div>
	<div class="col-sm text-end cardInfoText">
    <span><?php echo number_format($assets, 0, '.', '.'); ?> $</span><br>
    <span>- <?php echo number_format($liabilities, 0, '.', '.'); ?> $</span></span><br>
    <span style="font-weight: bold" class="<?php echo $stylePre; ?>"><?php echo number_format($equity, 0, '.', '.'); ?> $</span><br>
  </div>
</div>