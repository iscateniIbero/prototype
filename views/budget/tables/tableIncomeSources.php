<?php 
require_once '../../../general/config/db.php';
require_once '../../../general/functions/functions.php';
$incomeSource = incomeSources();
$formatterES = new NumberFormatter("es", NumberFormatter::SPELLOUT); ?>
<h5 class="text-start"><i class="iconTitle fa-solid fa-hands-holding-dollar"></i>Fuentes de ingresos</h5>
<?php if (!empty($incomeSource)) { ?>
<div class="table-responsive-sm">
	<table class="table text-end budgetTable" style="width: 100%;">
		<thead>
			<tr>
				<th scope="col" style="text-align: left;">#</th>
				<th scope="col" style="text-align: left;">Nombre</th>
				<th scope="col" style="text-align: right;">Ingreso</th>
				<th scope="col" style="text-align: right;">Ingreso neto</th>
				<th scope="col" style="text-align: right;">Deducción</th>
				<th scope="col" style="text-align: right;">Pagos</th>
				<th scope="col" style="text-align: right;">Finalizar</th>
			</tr>
		</thead>
		<tbody>
		<?php 
			$totalIncome = 0;
			$totalBiweeklyIncome = 0;
			$totalDeduction = 0;
			$deduction = 0;
			foreach ($incomeSource as $key => $value) {
				$totalIncome = $totalIncome + $value->netIncome;
				if ($value->periodicity <= '15') {
					$totalBiweeklyIncome = $totalBiweeklyIncome + $value->netIncome / 2;
				}
				$deduction = ($value->deduction * $value->income)/100;
				$totalDeduction = $totalDeduction + $deduction;
			?>
				<tr>
					<th scope="row" style="text-align: left;"><?php echo $key + 1; ?></th>
					<td style="text-align: left; font-weight: bold;"><?php echo $value->name; ?></td>
					<td><?php echo "$" . number_format($value->income, 0, '.', '.'); ?></td>
					<td class="positive" style="font-weight: bold;"><?php echo "$" . number_format($value->netIncome, 0, '.', '.'); ?></td>
					<td class="negative"><?php echo "$" . number_format($deduction, 0, '.', '.'); ?></td>
					<td><?php echo ucfirst($formatterES->format($value->periodicity) . " días"); ?></td>
					<td><span style="color: red; cursor:pointer;" onclick="cancelIncome('<?php echo $value->id?>')"><span class="fas fa-minus-circle"></span></span></td>

				</tr>
		<?php } ?>
		</tbody>
		<div class="row row-cols-4 text-start">
			<div class="col-sm-auto">
				<span style="font-family: inherit; font-weight: 500;">Total Ingresos</span>
				<h4 class="positive"><?php echo "$" . number_format($totalIncome, 0, '.', '.'); ?></h4>
			</div>
			<div class="col-sm-auto">
				<span style="font-family: inherit; font-weight: 500;">Total deducciones</span>
				<h4 class="negative"><?php echo "$" . number_format($totalDeduction, 0, '.', '.'); ?></h4>
			</div>
			<div class="col-sm-auto">
				<span style="font-family: inherit; font-weight: 500;">Ingresos quincenal</span>
				<h4 class="positive"><?php echo "$" . number_format($totalBiweeklyIncome, 0, '.', '.'); ?></h4>
			</div>
			<div class="col"></div>
			<div class="col-sm text-end">
				<button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addIncome">+</button>
			</div>
		</div>	
	</table>
</div>
<?php } else { ?>
<div class="alert" role="alert">
	<h4 class="alert-heading">Hola!</h4>
	<p class="text-center">Actualmente no tiene ninguna fuente de ingresos, puede agregar su primera fuente de ingresos dando clic en el siguiente botón</p>
	<hr>
	<button type="button" class= "btn btn-warning" data-bs-toggle="modal" data-bs-target="#addIncome"><i class="fa-solid fa-hand-holding-dollar fa-bounce"></i> Agregar mi primera fuente de ingreso</button>
</div>
<?php } ?>
