<?php 
require_once '../../../general/config/db.php';
require_once '../../../general/functions/functions.php';
session_start(); 
$fixedCost = costs('fixedcosts');
$totalVariable = variableCostTotal();
$variableCost = costs('variablecosts');
$formatterES = new NumberFormatter("es", NumberFormatter::SPELLOUT);
?>
<h5 class="text-left"><i class="iconTitle fa-solid fa-money-from-bracket"></i>Gastos fijos</h5>
<div class="table-responsive-sm">
    <table class="table text-end budgetTable" style="width: 100%;">
		<thead>
			<tr>
				<th scope="col" style="text-align: left;">#</th>
				<th scope="col" style="text-align: left;">Nombre</th>
				<th scope="col" style="text-align: right;">Pago</th>
				<th scope="col" style="text-align: right;">Próximo</th>
				<th scope="col" style="text-align: right;">Frecuencia</th>
				<th scope="col" style="text-align: right;">Estado</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		$totalExpenses = 0;
		$payableValue = 0;
		$payableValue = 0;
		$currentDate = strtotime(date('Y-m-d'));
		foreach ($fixedCost as $key => $value) {
			if ($value->periodicity == 60) {
				$payableValue = $value->value / 2;
			} else {
				$payableValue = $value->value;
			}
			if ($value->periodicity == 15) {
				$payableValue = $value->value * 2;
			}
			if ($value->periodicity == 7) {
				$payableValue = $value->value * 4;
			}
			$totalExpenses = $totalExpenses + $payableValue;
			$lastDate = "";
			$nextDate = "";
			$lastDate = $value->date;
			if ($value->periodicity == 90) {
				$nextDate = date("Y-m-d",strtotime($lastDate ."+ 3 month"));
			}
			if ($value->periodicity == 60) {
				$nextDate = date("Y-m-d",strtotime($lastDate ."+ 2 month"));
			}
			if ($value->periodicity == 30) {
				$nextDate = date("Y-m-d",strtotime($lastDate ."+ 1 month"));
			}
			if ($value->periodicity == 15) {
				$nextDate = date("Y-m-d",strtotime($lastDate ."+ 15 days"));
			}
			if ($value->periodicity == 7) {
				$nextDate = date("Y-m-d",strtotime($lastDate ."+ 7 days"));
			}
			$mediumDate = date("Y-m-d",strtotime($nextDate ."- 5 days"));
			$minimumDate = strtotime($mediumDate);
			$expirationDate = strtotime($nextDate);
			if ($currentDate < $minimumDate) {
				$styleDate = "positive";
                $iconStatus = "fa-solid fa-check";
                $styleStatus = "positiveStatus";
			}
			if ($currentDate >= $minimumDate && $currentDate < $expirationDate) {
				$styleDate = "next";
                $iconStatus = "fa-solid fa-spinner fa-spin-pulse";
                $styleStatus = "nextStatus";
			}
			if ($currentDate >=  $expirationDate) {
				$styleDate = "negative";
                $iconStatus = "fa-solid fa-exclamation fa-beat-fade";
                $styleStatus = "negativeStatus";
			}
		?>
			<tr>	
				<th scope="row" style="text-align: left;"><?= $key + 1; ?></th>
				<td style="text-align: left; font-weight: bold;"><?= $value->name; ?></td>
				<td class="negative"><?= "$" . number_format($value->value, 0, '.', '.'); ?></td>
				<td style="font-weight: bold;" class="<?= $styleDate; ?>"><?= $nextDate;?></td>
				<td><?= ucfirst($formatterES->format($value->periodicity) . " días"); ?></td>
                <td><div class="<?= $styleStatus; ?>" style="text-align: right;"><i class="<?= $iconStatus; ?>"></i></div></td>
			</tr>
		<?php }	?>
		</tbody>
		<div class="row row-cols-4 text-left">
			<div class="col-sm-auto">
				<span style="font-family: inherit; font-weight: 500;">Total gastos fijos mes</span>
				<h4 class="negative"><?= "$" . number_format($totalExpenses, 0, '.', '.'); ?></h4>
			</div>
			<div class="col-sm-auto">
				<span style="font-family: inherit; font-weight: 500;">Balance gastos quincena</span>
				<h4 class="negative"><?= "$" . number_format($totalExpenses / 2, 0, '.', '.'); ?></h4>
			</div>
			<div class="col-sm-auto">
				<?php 
				unset($_SESSION["totalExpenses"]);
				$_SESSION["totalExpenses"] = $totalVariable[0]->totalVariable + $totalExpenses; 
				?>
				<span style="font-family: inherit; font-weight: 500;">Total gastos mes</span>
				<h4 class="negative"><?= "$" . number_format($totalVariable[0]->totalVariable + $totalExpenses, 0, '.', '.'); ?></h4>
			</div>
			<div class="col-sm-auto">
				<span style="font-family: inherit; font-weight: 500;">Total gastos quincenal</span>
				<h4 class="negative"><?= "$" . number_format(($totalVariable[0]->totalVariable + $totalExpenses) / 2, 0, '.', '.'); ?></h4>
			</div>
		</div>
	</table>
</div>
<h5 class="text-left"><i class="iconTitle fa-solid fa-money-simple-from-bracket"></i>Gastos variables</h5>
<div class="table-responsive-sm">
    <table class="table text-end budgetTable" style="width: 100%;">
		<thead>
			<tr>
				<th scope="col" style="text-align: left;">#</th>
				<th scope="col" style="text-align: left;">Nombre</th>
				<th scope="col" style="text-align: right;">Pago</th>
				<th scope="col" style="text-align: right;">Próximo</th>
				<th scope="col" style="text-align: right;">Frecuencia</th>
				<th scope="col" style="text-align: right;">Eliminar</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		$totalExpenses = 0;
		$payableValue = 0;
		$payableValue = 0;
		$currentDate = strtotime(date('Y-m-d'));
		foreach ($variableCost as $key => $value) {
			if ($value->periodicity == 60) {
				$payableValue = $value->value / 2;
			} else {
				$payableValue = $value->value;
			}
			if ($value->periodicity == 15) {
				$payableValue = $value->value * 2;
			}
			$totalExpenses = $totalExpenses + $payableValue;
			$lastDate = "";
			$nextDate = "";
			$lastDate = $value->date;
			if ($value->periodicity == 60) {
				$nextDate = date("Y-m-d",strtotime($lastDate ."+ 2 month"));
			}
			if ($value->periodicity == 30) {
				$nextDate = date("Y-m-d",strtotime($lastDate ."+ 1 month"));
			}
			if ($value->periodicity == 15) {
				$nextDate = date("Y-m-d",strtotime($lastDate ."+ 15 days"));
			}
			$mediumDate = date("Y-m-d",strtotime($nextDate ."- 10 days"));
			$minimumDate = strtotime($mediumDate);
			$expirationDate = strtotime($nextDate);
			if ($currentDate < $minimumDate) {
				$styleDate = "positive";
			}
			if ($currentDate >= $minimumDate && $currentDate < $expirationDate) {
				$styleDate = "next";
			}
			if ($currentDate >=  $expirationDate) {
				$styleDate = "negative";
			}
		?>
			<tr>	
				<th scope="row" style="text-align: left;"><?php echo $key + 1; ?></th>
				<td style="text-align: left; font-weight: bold;"><?php echo $value->name; ?></td>
				<td class="negative"><?php echo "$" . number_format($value->value, 0, '.', '.'); ?></td>
				<td style="font-weight: bold;" class="<?php echo $styleDate; ?>"><?php echo $nextDate;?></td>
				<td><?php echo ucfirst($formatterES->format($value->periodicity) . " días"); ?></td>
				<td><span style="color: red; cursor:pointer;" onclick="cancelVariableCost('<?php echo $value->id?>')"><span class="fas fa-trash-alt"></span></span></td>
			</tr>
		<?php }	?>
		</tbody>
		<div class="row row-cols-4 text-left">
			<div class="col-sm-auto">
				<span style="font-family: inherit; font-weight: 500;">Total gastos mes</span>
				<h4 class="negative"><?php echo "$" . number_format($totalExpenses, 0, '.', '.'); ?></h4>
			</div>
			<div class="col-sm-auto">
				<span style="font-family: inherit; font-weight: 500;">Balance gastos quincena</span>
				<h4 class="negative"><?php echo "$" . number_format($totalExpenses / 2, 0, '.', '.'); ?></h4>
			</div>
		</div>
	</table>
</div>