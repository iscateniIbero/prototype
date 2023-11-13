<?php
date_default_timezone_set('America/Bogota');
class user {
	public $id;
	public $user;
	public $name;
	public $lastname;
	public $surname;
	public $email;
	public $rol;
	public $registration_date;
}
class apps {
	public $title;
	public $url;
	public $fontAwesome;
	public $spanishTitle;
}
class functions{
	public function passwordChange($data) {
		$user = $data[0];
		$password = limpiarDatos($data[1]);
		$password = hash('sha512', $password);
		$connection = connectDB();
		$query = "UPDATE user SET password = '$password',
															session = '0' 
													WHERE  user = '$user'";
		$execution = mysqli_query($connection,$query);
		if ($execution == "1") {
			session_start();
			$_SESSION['session'] = "0";
			return 1;
		} else {
			return 2;
		}    
	}
}
function apps($id){
	$connection = connectDB();
	$apps = [];
	try {
		$query = $connection->query("SELECT title, url, fontAwesome, spanishTitle FROM app WHERE id_user = '$id'");
		if ($query) {
			while($row = mysqli_fetch_row($query)){
				$app = new apps();
				$app->title = $row[0];
				$app->url = $row[1];
				$app->fontAwesome = $row[2];
				$app->spanishTitle = $row[3];
				array_push($apps, $app);
			}
			return $apps;
		}
	} catch (PDOException $e) {
		return [];
	}
}
class incomeSources {
	public $id;
	public $name;
	public $income;
	public $periodicity;
	public $deduction;
	public $netIncome;
	public $date;
	public $status;
}
class fixedCosts {
	public $id;
	public $name;
	public $value;
	public $periodicity;
	public $date;
	public $status;
	public $totalVariable;
}
class budgetcategories {
	public $id;
	public $title;
	public $type;
}
class creditCard {
	public $id;
	public $name;
	public $utilizationFee;
	public $interest;
}
class costPay {
	public $name;
}
class portfolioCryptoCurrencies {
	public $id;
	public $name;
	public $value;
	public $available;
	public $btcTotal;
	public $icon;
	public $price;
	public $priceUSD;
	public $symbol;
	public $interestPaid;
}
class transactionhistory {
	public $id;
	public $concept;
	public $value;
	public $type;
	public $platform;
	public $date;
}
class debitValue {
	public $id;
	public $detail;
	public $value;
}
class portfolioStocks {
	public $id;
	public $name;
	public $acronym;
	public $quantity;
	public $purchaseValue;
	public $currentValue;
	public $totalValue;
	public $platform;
	public $date;
}
function incomeSources() {
	$connection = connectDB();
	$incomeSources = [];
	try {
		$query = $connection->query("SELECT * FROM sourcesofincome WHERE status = '0'");
		while ($row = mysqli_fetch_row($query)) {
			$income = new incomeSources();
			$income->id = $row[0];
			$income->name = $row[1];
			$income->income = $row[2];
			$income->periodicity = $row[3];
			$income->deduction = $row[4];
			$income->netIncome = $row[5];
			$income->date = $row[6];
			$income->status = $row[7];
			array_push($incomeSources, $income);
		}
		return $incomeSources;	
	} catch (Exception $e) {
		return [];	
	}
}
function addIncomeSource($data) {
	$connection = connectDB();
	$name =  $connection->real_escape_string($data[0]);
	$income =  $connection->real_escape_string($data[1]);
	$periodicity =  $connection->real_escape_string($data[2]);
	$deduction =  $connection->real_escape_string($data[3]);
	$netIncome =  $connection->real_escape_string($data[4]);
	$date =  $connection->real_escape_string($data[5]);
	try {
		$queryInsert = $connection->query("INSERT INTO sourcesofincome (name, income, periodicity, deduction, netIncome, date, status) VALUES ('$name', '$income', '$periodicity', '$deduction', '$netIncome', '$date','0')");
		return $queryInsert;
	} catch (Exception $e) {
		return [];		
	}	
}
function cancelIncomeSource($id) {
	$connection = connectDB();
	try {
		$queryUpdate = $connection->query("UPDATE sourcesofincome SET status = '1' WHERE id = '$id'");
		return $queryUpdate;
	} catch (Exception $e) {
		return [];	
	}
}
function cancelVariableCost($id) {
	$connection = connectDB();
	try {
		$queryUpdate = $connection->query("UPDATE variablecosts SET status = '1' WHERE id = '$id'");
		return $queryUpdate;
	} catch (Exception $e) {
		return [];	
	}
}
function costs($table) {
	$connection = connectDB();
	$fixedCosts = [];
	try {
		$query = $connection->query("SELECT * FROM $table WHERE status = '0' ORDER BY date ASC");
		while ($row = mysqli_fetch_row($query)) {
			$cost = new fixedCosts();
			$cost->id = $row[0];
			$cost->name = $row[1];
			$cost->value = $row[2];
			$cost->periodicity = $row[3];
			$cost->date = $row[4];
			$cost->status = $row[5];
			array_push($fixedCosts, $cost);
		}
		return $fixedCosts;	
	} catch (Exception $e) {
		return [];	
	}
}
function variableCostTotal() {
	$connection = connectDB();
	$fixedCosts = [];
	try {
		$querySelect = $connection->query("SELECT SUM(value) FROM variablecosts WHERE status = '0'");
		while ($row = mysqli_fetch_row($querySelect)) {
		$cost = new fixedCosts();
		$cost->totalVariable = $row[0];
		array_push($fixedCosts, $cost);
		}
		return $fixedCosts;	
	} catch (Exception $e) {
		return [];		
	}
}
function cashAndExpense() {
	$connection = connectDB();
	$date = date('Y-m-d');
	$firstDayMonth = date('Y-m-01');
	$dayMonth = new DateTime($date);
	$dayMonth = $dayMonth->format('Y-m-t');

	$firstDayLastMonth = date("Y-m-01",strtotime($date."- 1 month"));
	$lastMonth = date("Y-m-t",strtotime($date."- 1 month"));
	$lastMonth = new DateTime($lastMonth);
	$lastMonth = $lastMonth->format('Y-m-t');

	//-2 month
	$firstDayTwoMonth = date("Y-m-01",strtotime($date."- 2 month"));
	$twoMonth = date("Y-m-t",strtotime($date."- 2 month"));
	$twoMonth = new DateTime($twoMonth);
	$twoMonth = $twoMonth->format('Y-m-t');
	//-3 month
	$firstDayThreeMonth = date("Y-m-01",strtotime($date."- 3 month"));
	$threeMonth = date("Y-m-t",strtotime($date."- 3 month"));
	$threeMonth = new DateTime($threeMonth);
	$threeMonth = $threeMonth->format('Y-m-t');
	//-4 month
	$firstDayFourMonth = date("Y-m-01",strtotime($date."- 4 month"));
	$fourMonth = date("Y-m-t",strtotime($date."- 4 month"));
	$fourMonth = new DateTime($fourMonth);
	$fourMonth = $fourMonth->format('Y-m-t');
	//-5 month
	$firstDayFiveMonth = date("Y-m-01",strtotime($date."- 4 month"));
	$fiveMonth = date("Y-m-t",strtotime($date."- 4 month"));
	$fiveMonth = new DateTime($fiveMonth);
	$fiveMonth = $fiveMonth->format('Y-m-t');

	$cashAndExpense = [];
	try {
		//Income
		$queryFiveMonth = $connection->query("SELECT SUM(value) FROM incomeandexpense WHERE (date between '$firstDayFiveMonth' AND '$fiveMonth') AND typeTransaction = 'Income'");
		if ($queryFiveMonth->num_rows > 0) {
			$row = mysqli_fetch_row($queryFiveMonth);
			$cashAndExpense['incomeFiveMonth'] = $row[0];
		} else {
			$cashAndExpense['incomeFiveMonth'] = 0;
		}
		$queryFourMonth = $connection->query("SELECT SUM(value) FROM incomeandexpense WHERE (date between '$firstDayFourMonth' AND '$fourMonth') AND typeTransaction = 'Income'");
		if ($queryFourMonth->num_rows > 0) {
			$row = mysqli_fetch_row($queryFourMonth);
			$cashAndExpense['incomeFourMonth'] = $row[0];
		} else {
			$cashAndExpense['incomeFourMonth'] = 0;
		}
		$queryThreeMonth = $connection->query("SELECT SUM(value) FROM incomeandexpense WHERE (date between '$firstDayThreeMonth' AND '$threeMonth') AND typeTransaction = 'Income'");
		if ($queryThreeMonth->num_rows > 0) {
			$row = mysqli_fetch_row($queryThreeMonth);
			$cashAndExpense['incomeThreeMonth'] = $row[0];
		} else {
			$cashAndExpense['incomeThreeMonth'] = 0;
		}
		$queryTwoMonth = $connection->query("SELECT SUM(value) FROM incomeandexpense WHERE (date between '$firstDayTwoMonth' AND '$twoMonth') AND typeTransaction = 'Income'");
		if ($queryTwoMonth->num_rows > 0) {
			$row = mysqli_fetch_row($queryTwoMonth);
			$cashAndExpense['incomeTwoMonth'] = $row[0];
		} else {
			$cashAndExpense['incomeTwoMonth'] = 0;
		}
		$queryLastMonth = $connection->query("SELECT SUM(value) FROM incomeandexpense WHERE (date between '$firstDayLastMonth' AND '$lastMonth') AND typeTransaction = 'Income'");
		if ($queryLastMonth->num_rows > 0) {
			$row = mysqli_fetch_row($queryLastMonth);
			$cashAndExpense['incomeLastMonth'] = $row[0];
		} else {
			$cashAndExpense['incomeLastMonth'] = 0;
		}
		$queryMonth = $connection->query("SELECT SUM(value) FROM incomeandexpense WHERE (date between '$firstDayMonth' AND '$dayMonth') AND typeTransaction = 'Income'");
		if ($queryMonth->num_rows > '0') {
			$row = mysqli_fetch_row($queryMonth);
			$cashAndExpense['incomeMonth'] = $row[0];
		} else {
			$cashAndExpense['incomeMonth'] = 0;
		}
		//Expense
		$queryFiveMonth = $connection->query("SELECT SUM(value) FROM incomeandexpense WHERE (date between '$firstDayFiveMonth' AND '$fiveMonth') AND typeTransaction = 'Expense'");
		if ($queryFiveMonth->num_rows > 0) {
			$row = mysqli_fetch_row($queryFiveMonth);
			$cashAndExpense['expenseFiveMonth'] = $row[0];
		} else {
			$cashAndExpense['expenseFiveMonth'] = 0;
		}
		$queryFourMonth = $connection->query("SELECT SUM(value) FROM incomeandexpense WHERE (date between '$firstDayFourMonth' AND '$fourMonth') AND typeTransaction = 'Expense'");
		if ($queryFourMonth->num_rows > 0) {
			$row = mysqli_fetch_row($queryFourMonth);
			$cashAndExpense['expenseFourMonth'] = $row[0];
		} else {
			$cashAndExpense['expenseFourMonth'] = 0;
		}
		$queryThreeMonth = $connection->query("SELECT SUM(value) FROM incomeandexpense WHERE (date between '$firstDayThreeMonth' AND '$threeMonth') AND typeTransaction = 'Expense'");
		if ($queryThreeMonth->num_rows > 0) {
			$row = mysqli_fetch_row($queryThreeMonth);
			$cashAndExpense['expenseThreeMonth'] = $row[0];
		} else {
			$cashAndExpense['expenseThreeMonth'] = 0;
		}
		$queryTwoMonth = $connection->query("SELECT SUM(value) FROM incomeandexpense WHERE (date between '$firstDayTwoMonth' AND '$twoMonth') AND typeTransaction = 'Expense'");
		if ($queryTwoMonth->num_rows > 0) {
			$row = mysqli_fetch_row($queryTwoMonth);
			$cashAndExpense['expenseTwoMonth'] = $row[0];
		} else {
			$cashAndExpense['expenseTwoMonth'] = 0;
		}
		$queryLastMonth = $connection->query("SELECT SUM(value) FROM incomeandexpense WHERE (date between '$firstDayLastMonth' AND '$lastMonth') AND typeTransaction = 'Expense'");
		if ($queryLastMonth->num_rows > 0) {
			$row = mysqli_fetch_row($queryLastMonth);
			$cashAndExpense['expenseLastMonth'] = $row[0];
		} else {
			$cashAndExpense['expenseLastMonth'] = 0;
		}
		$queryMonth = $connection->query("SELECT SUM(value) FROM incomeandexpense WHERE (date between '$firstDayMonth' AND '$dayMonth') AND typeTransaction = 'Expense'");
		if ($queryMonth->num_rows > '0') {
			$row = mysqli_fetch_row($queryMonth);
			$cashAndExpense['expenseMonth'] = $row[0];
		} else {
			$cashAndExpense['expenseMonth'] = 0;
		}
		return $cashAndExpense;
	} catch (Exception $e) {
		return [];		
	}
}
function transactionIncomeAndExpense($type) {
	$connection = connectDB();
	$date = date('Y-m-d');
	$firstDayMonth = date('Y-m-01');
	$dayMonth = new DateTime($date);
	$dayMonth = $dayMonth->format('Y-m-t');
	$values = [];
	$values['total'] = [];
	$values['transaction'] = [];
	$valueIncome = 0;
	try {
		$querySelect = $connection->query("SELECT detail, value, date FROM incomeandexpense WHERE (date between '$firstDayMonth' AND '$dayMonth') AND typeTransaction = '$type' ORDER BY date DESC");
		while ($row = mysqli_fetch_row($querySelect)) {
			$value = new fixedCosts();
			$value->name = $row[0];
			$value->value = $row[1];
			$value->date = $row[2];
			array_push($values['transaction'], $value);
			$valueIncome = $valueIncome + $row[1];
		}
		$values['total'] = $valueIncome;
		return json_encode($values);
	} catch (Exception $e) {
		return [];		
	}
}
function budgetCategoriesSelect($type) {
	$connection = connectDB();
	try {
		$query = $connection->query("SELECT * FROM budgetcategories WHERE type = '$type'");
		$cadena="<select class='form-control-sm form-control' id='category' name='category'>";
		$cadena=$cadena."<option selected disabled value=''>Seleccione la categoría</option>";
		while ($row = mysqli_fetch_row($query)) {
			$cadena=$cadena.'<option value='.$row[1].'>'.$row[1].'</option>';
		}
		$cadena=$cadena."</select>";
		return $cadena;	
	} catch (Exception $e) {
		return [];
	}
}
function creditCard() {
	$connection = connectDB();
	$creditCard = [];
	try {
		$query = $connection->query("SELECT * FROM creditCard");
		while ($row = mysqli_fetch_row($query)) {
			$card = new creditCard();
			$card->id = $row[0];
			$card->name = $row[1];
			$card->utilizationFee = $row[2];
			$card->interest = $row[3];
			array_push($creditCard, $card);
		}
		return $creditCard;	
	} catch (Exception $e) {
		return [];
	}	
}
function recordIncome($data) {
	$connection = connectDB();
	try {
		$queryInsert = $connection->query("INSERT INTO incomeandexpense (detail, value, date, category, typeTransaction) VALUES ('$data[0]', '$data[1]', '$data[2]','$data[3]', '$data[4]')");
		return 1;
	} catch (Exception $e) {
		return [];		
	}
}
function recordEgress($data) {
	$connection = connectDB();
	try {
		if ($data[3] == 'Ahorro') {
			$querySaving = $connection->query("INSERT INTO saving (value, type, date) VALUES ('$data[1]', 'Deposit', '$data[2]')");
			$queryInsert = $connection->query("INSERT INTO incomeandexpense (detail, value, date, category, type, formOfPayment, typeTransaction) VALUES ('$data[0]', '$data[1]', '$data[2]','$data[3]', 'Fijo', '$data[6]', 'Expense')");
			return 2;
		}
		if ($data[3] == 'Inversión') {
			$queryInsert = $connection->query("INSERT INTO incomeandexpense (detail, value, date, category, type, formOfPayment, typeTransaction) VALUES ('$data[0]', '$data[1]', '$data[2]','$data[3]', 'Fijo', '$data[6]', 'Expense')");
			return 2;
		}
		if ($data[3] == 'Fondo') {
			$queryEmergencyFund = $connection->query("INSERT INTO emergencyfund (value, type, date) VALUES ('$data[1]', 'Deposit', '$data[2]')");
			$queryInsert = $connection->query("INSERT INTO incomeandexpense (detail, value, date, category, type, formOfPayment, typeTransaction) VALUES ('$data[0]', '$data[1]', '$data[2]','Fondo de emergencia', 'Fijo', '$data[6]', 'Expense')");
			return 2;
		}
		if ($data[4] == 'Fijo') {
			$InsertFixedCost = $connection->query("INSERT INTO fixedcosts (name, value, periodicity, date, status) VALUES ('$data[0]', '$data[1]', '$data[5]', '$data[2]', '0')");
		}
		if ($data[4] == 'Variable' && $data[6] == 'Efectivo') {
			$InsertVariableCost = $connection->query("INSERT INTO variablecosts (name, value, periodicity, date, status) VALUES ('$data[0]', '$data[1]', '$data[5]', '$data[2]', '0')");
			$queryInsert = $connection->query("INSERT INTO incomeandexpense (detail, value, date, category, type, frequency, formOfPayment, typeTransaction) VALUES ('$data[0]', '$data[1]', '$data[2]','$data[3]', '$data[4]', '$data[5]', '$data[6]', '$data[7]')");
			if ($data[5] == '7') {
				$nextPay = date("Y-m-d",strtotime($data[2] . "+ 7 days"));	
			}
			if ($data[5] == '15') {
				$nextPay = date("Y-m-d",strtotime($data[2] . "+ 15 days"));	
			}
			if ($data[5] == '30') {
				$nextPay = date("Y-m-d",strtotime($data[2] . "+ 1 month"));	
			}
			if ($data[5] == '60') {
				$nextPay = date("Y-m-d",strtotime($data[2] . "+ 2 month"));	
			}
			if ($data[5] == '90') {
				$nextPay = date("Y-m-d",strtotime($data[2] . "+ 3 month"));	
			}
			if ($data[5] == '180') {
				$nextPay = date("Y-m-d",strtotime($data[2] . "+ 6 month"));	
			}
			if ($data[5] == '360') {
				$nextPay = date("Y-m-d",strtotime($data[2] . "+ 12 month"));	
			}
			$queryInsertDebt = $connection->query("INSERT INTO debt (detail, value, date, nextPayDate, frequency) 
														  VALUES ('$data[0]', '$data[1]', '$data[2]', '$nextPay','$data[5]')");
		}
		if ($data[4] == 'Variable' && $data[6] == 'Credito') {
			if ($data[5] == '15') {
				$nextPay = date("Y-m-d",strtotime($data[2] . "+ 15 days"));	
			}
			if ($data[5] == '30') {
				$nextPay = date("Y-m-d",strtotime($data[2] . "+ 1 month"));	
			}
			if ($data[5] == '60') {
				$nextPay = date("Y-m-d",strtotime($data[2] . "+ 2 month"));	
			}
			if ($data[5] == '90') {
				$nextPay = date("Y-m-d",strtotime($data[2] . "+ 3 month"));	
			}
			if ($data[5] == '180') {
				$nextPay = date("Y-m-d",strtotime($data[2] . "+ 6 month"));	
			}
			if ($data[5] == '360') {
				$nextPay = date("Y-m-d",strtotime($data[2] . "+ 12 month"));	
			}
			if ($data[7] != "Otro") {
				$querySelect = $connection->query("SELECT paymentDay FROM creditcard WHERE name = '$data[7]'");
				$row = mysqli_fetch_row($querySelect);
				$day = $row[0];
				$nextPay = "";
				if ($day == '1') {
					$date = date('Y-m-01');
					$nextPay = date("Y-m-01",strtotime($date."+ 1 month"));	
				}
				if ($day == '16') {
					$date = date('Y-m-16');
					$nextPay = date("Y-m-16",strtotime($date."+ 1 month"));	
				}
			} 
			$quotaValue = ceil(($data[1] / $data[8]) * 1.132);
			$queryInsertDebt = $connection->query("INSERT INTO debt (detail, value, date, nextPayDate, frequency, card, installments, paidInstallments) 
													  VALUES ('$data[0]', '$data[1]', '$data[2]', '$nextPay','$data[5]','$data[7]', '$data[8]', '0')");
			$InsertVariableCost = $connection->query("INSERT INTO variablecosts (name, value, periodicity, date, status) 
			            								 VALUES ('$data[0]', '$quotaValue', '$data[5]', '$data[2]', '0')");
		}
		if ($data[4] == 'Eventual') {
			$queryInsert = $connection->query("INSERT INTO incomeandexpense (detail, value, date, category, type, formOfPayment, typeTransaction) 
																					VALUES ('$data[0]', '$data[1]', '$data[2]','$data[3]', '$data[4]', '$data[5]', '$data[6]')");
		}
		return 2;
	} catch (Exception $e) {
		return [];		
	}
}
function recordPayment($data) {
	$connection = connectDB();
	$name =  $connection->real_escape_string($data[0]);
	try {
		// FixedCost search
		$queryFixed = $connection->query("SELECT name, periodicity, date FROM fixedcosts WHERE status = 0 AND name = '$name'");
		if ($queryFixed->num_rows > 0) {
			while ($row = mysqli_fetch_row($queryFixed)) {
				$date = $row[2];
				if ($row[1] == '7') {
					$datePay = date("Y-m-d",strtotime($date."+ 7 days"));
				}
				if ($row[1] == '15') {
					$datePay = date("Y-m-d",strtotime($date."+ 15 days"));
				}
				if ($row[1] == '30') {
					$datePay = date("Y-m-d",strtotime($date."+ 1 month"));
				}
				if ($row[1] == '60') {
					$datePay = date("Y-m-d",strtotime($date."+ 2 month"));
				}
				if ($row[1] == '90') {
					$datePay = date("Y-m-d",strtotime($date."+ 3 month"));
				}
				$queryUpdate = $connection->query("UPDATE fixedcosts SET date = '$datePay' WHERE name = '$name'");
				$queryInsert = $connection->query("INSERT INTO incomeandexpense (detail, value, date, category, type, typeTransaction) 
																						VALUES ('$data[0]', '$data[1]', '$data[2]','$data[3]', 'Fijo', '$data[4]')");
			}
		}
		// debit search
		$paidIsntallments = 0;
		$queryDebit= $connection->query("SELECT * FROM debt WHERE detail = '$name'");
		if ($queryDebit->num_rows > 0) {
			while ($row = mysqli_fetch_row($queryDebit)) {
				$paidIsntallments = $row[8] + 1;
				//Borrar por que ya se pago
				if ( $paidIsntallments >= $row[7] ) {
				 $queryDelete = $connection->query("DELETE FROM debt WHERE detail = '$name'");
				 $queryUpdate = $connection->query("UPDATE variablecosts SET status = '1' WHERE name = '$name' AND status = '0'");
				} else if ( $row[7] > $row[8] ) {
					//Actualizar fecha y cuota
					$date = $row[4];
					if ($row[5] == '7') {
						$nextPayDay = date("Y-m-d",strtotime($date."+ 7 days"));
					}
					if ($row[5] == '15') {
						$nextPayDay = date("Y-m-d",strtotime($date."+ 15 days"));
					}
					if ($row[5] == '30') {
						$nextPayDay = date("Y-m-d",strtotime($date."+ 1 month"));
					}
					if ($row[5] == '60') {
						$nextPayDay = date("Y-m-d",strtotime($date."+ 2 month"));
					}
					if ($row[5] == '90') {
						$nextPayDay = date("Y-m-d",strtotime($date."+ 3 month"));
					}
					$queryUpdate = $connection->query("UPDATE debt SET nextPayDate = '$nextPayDay', paidInstallments = '$paidIsntallments' WHERE detail = '$name'");
				}
			}
		}
		// variableCosts search
		$queryVariable= $connection->query("SELECT name, periodicity, date FROM variablecosts WHERE name = '$name' AND status = '0'");
		if ($queryVariable->num_rows > 0) {
			while ($row = mysqli_fetch_row($queryVariable)) {
				$date = $row[2];
				if ($row[1] == '7') {
					$nextDay = date("Y-m-d",strtotime($date."+ 7 days"));
				}
				if ($row[1] == '15') {
					$nextDay = date("Y-m-d",strtotime($date."+ 15 days"));
				}
				if ($row[1] == '30') {
					$nextDay = date("Y-m-d",strtotime($date."+ 1 month"));
				}
				if ($row[1] == '60') {
					$nextDay = date("Y-m-d",strtotime($date."+ 2 month"));
				}
				if ($row[1] == '90') {
					$nextDay = date("Y-m-d",strtotime($date."+ 3 month"));
				}
				$queryUpdate = $connection->query("UPDATE variablecosts SET date = '$nextDay' WHERE name = '$name'");
			}
			$queryInsert = $connection->query("INSERT INTO incomeandexpense (detail, value, date, category, typeTransaction, type) 
																						VALUES ('$name', '$data[1]', '$data[2]','$data[3]', '$data[4]', 'Variable')");
		}
		return 2;
	} catch (Exception $e) {
		return [];		
	}
}
function recordWithdrawal($data) {
	$connection = connectDB();
	try {
		if ( $data[0] == 'Ahorro' ) {
			$queryInsert = $connection->query("INSERT INTO saving (value, type, date) VALUES ('$data[1]', '$data[3]', '$data[2]')");
		}
		if ( $data[0] == 'Fondo' ) {
			$queryInsert = $connection->query("INSERT INTO emergencyfund (value, type, date) VALUES ('$data[1]', '$data[3]', '$data[2]')");
		}
		return 2;
	} catch (Exception $e) {
		return [];		
	}
}
function saving() {
	$connection = connectDB();
	$savingsMovements = [];
	$saving = [];
	$saving['total'] = 0;
	$saving['TotalDeposit'] = 0;
	$saving['TotalWithdrawal'] = 0;
	$deposit = 0;
	$withdrawal = 0;
	$balance = 0;
	$saving['Saving'] = [];
	try {
		$queryDeposit =  $connection->query("SELECT MONTH(date) as month, YEAR(date) as year, type, SUM(value) as total FROM saving GROUP BY MONTH(date), YEAR(date), type ORDER BY year, month");
		for ($savingsMovements['Saving'] = array (); $row = $queryDeposit->fetch_assoc(); $savingsMovements['Saving'][] = $row);
		foreach ($savingsMovements['Saving'] as $valueDeposit) {
			if ( $valueDeposit['type'] == 'Deposit' ) {
				$deposit += $valueDeposit['total'];
				$dateDeposit = $valueDeposit['month'] . $valueDeposit['year']; 
				$totalWithdrawal = 0;
				foreach ($savingsMovements['Saving'] as $valueWithdrawal) {
					if ( $valueWithdrawal['type'] == 'Withdrawal' ) {
						$dateWithdrawal = $valueWithdrawal['month'] . $valueWithdrawal['year']; 
						if ( $dateDeposit == $dateWithdrawal ) {
							$totalWithdrawal = $valueWithdrawal['total'];
						}
					}
				}
				$balance += $valueDeposit['total'] -$totalWithdrawal;
				$saves = [
					"month" => $valueDeposit['month'],
					"year" => $valueDeposit['year'],
					"totalDeposited" => $valueDeposit['total'],
					"totalWithdrawal" => $totalWithdrawal,
					"Balance" => $balance
				];
				array_push($saving['Saving'], $saves);
			} else {
				$withdrawal += $valueDeposit['total'];
			}
		}
		$saving['TotalWithdrawal'] = $withdrawal;
		$saving['TotalDeposit'] = $deposit ;
		$saving['total'] = $deposit - $withdrawal;
		return json_encode($saving);
	} catch (Exception $e) {
		return [];	
	}
}
function investments() {
	$connection = connectDB();
	$investments = [];
	$totalCrypto = 0;
	$totalStock = 0;
	$totalFund = 0;
	$totalBalanceCrypto = 0;
	$totalBalanceStock = 0;
	$totalBalanceFund = 0;
	try {
		$queryInvestmentsMonth =  $connection->query("SELECT MONTH(date) as month, YEAR(date) as year, platform, concept, SUM(value) as total FROM transactionhistoryinvestment GROUP BY MONTH(date), YEAR(date), platform, concept ORDER BY year, month");
		for ($investments['investments']['date'] = array (); $row = $queryInvestmentsMonth->fetch_assoc(); $investments['investments']['date'][] = $row);
		$queryInvestments =  $connection->query("SELECT platform, concept, SUM(value) as total FROM transactionhistoryinvestment GROUP BY platform, concept");
		for ($investments['investments']['platform'] = array (); $row = $queryInvestments->fetch_assoc(); $investments['investments']['platform'][] = $row);
		foreach ($investments['investments']['platform'] as $valueInvestments) {
			if ($valueInvestments['concept'] == "CryptoMonedas") {
				$totalCrypto += $valueInvestments['total'];
			}
			if ($valueInvestments['concept'] == "Acciones") {
				$totalStock += $valueInvestments['total'];
			}
			if ($valueInvestments['concept'] == "Fondos") {
				$totalFund += $valueInvestments['total'];
			}
		}
		$investments['investments']['type'] = [
			"Cryptomonedas", "Acciones", "Fondos"
		];
		//crypto
		$queryCrypto =  $connection->query("SELECT SUM(value) as total FROM portfoliocryptocurrencies");
		$row = $queryCrypto->fetch_assoc();
		$totalBalanceCrypto = $row['total'];
		//stock
		$queryCrypto =  $connection->query("SELECT SUM(totalValue) as total FROM portfoliostock");
		$row = $queryCrypto->fetch_assoc();
		$totalBalanceStock = $row['total'];
		//Funds
		$queryCrypto =  $connection->query("SELECT SUM(totalValue) as total FROM portfoliofunds");
		$row = $queryCrypto->fetch_assoc();
		$totalBalanceFund = $row['total'];
		//Totales
		$investments['investments']['totalCrypto'] = $totalCrypto;
		$investments['investments']['totalStock'] = $totalStock;
		$investments['investments']['totalFund'] = $totalFund;
		$investments['investments']['totalInvested'] = $totalCrypto + $totalStock + $totalFund;
		$investments['investments']['totalBalanceCrypto'] = $totalBalanceCrypto;
		$investments['investments']['totalBalanceStock'] = $totalBalanceStock;
		$investments['investments']['totalBalanceFund'] = $totalBalanceFund;
		$investments['investments']['totalBalance'] = $totalBalanceCrypto + $totalBalanceStock + $totalBalanceFund;
		return json_encode($investments);
	} catch (Exception $e) {
		return [];
	}
}
function averageCosts($detail) {
	$connection = connectDB();
	$average = [];
	try {
		$queryAverage = $connection->query("SELECT detail, AVG(value) as value FROM incomeandexpense WHERE detail = '$detail' AND typeTransaction = 'Expense' GROUP BY detail");
		for ($average = array (); $row = $queryAverage->fetch_assoc(); $average[] = $row);
		return json_encode($average);
	} catch (Exception $e) {
		return [];
	}
}
function updateaverageCosts($detail, $value) {
	$connection = connectDB();
	$average = [];
	try {
		$queryUpdate = $connection->query("UPDATE fixedcosts SET value = '$value' WHERE name = '$detail'");
		return $queryUpdate;
	} catch (Exception $e) {
		return [];
	}
}
function conceptOfPayment() {
	$connection = connectDB();
	$payment = [];
	try {
		//fixedcosts
		$queryFixedCosts = $connection->query("SELECT name FROM fixedcosts");
		while ($row = mysqli_fetch_row($queryFixedCosts)) {
			$cost = new costPay();
			$cost->name = $row[0];
			array_push($payment, $cost);
		}
		//variablecosts
		$queryVariableCosts = $connection->query("SELECT name FROM variablecosts");
		while ($row = mysqli_fetch_row($queryVariableCosts)) {
			$cost = new costPay();
			$cost->name = $row[0];
			array_push($payment, $cost);
		}
		return $payment;	
	} catch (Exception $e) {
		return [];
	}	
}
function emergencyFound() {
	$connection = connectDB();
	$foundMovements = [];
	$fund = [];
	$fund['total'] = 0;
	$fund['TotalDeposit'] = 0;
	$fund['TotalWithdrawal'] = 0;
	$deposit = 0;
	$withdrawal = 0;
	$balance = 0;
	$fund['fund'] = [];
	try {
		$queryDeposit =  $connection->query("SELECT MONTH(date) as month, YEAR(date) as year, type, SUM(value) as total FROM emergencyfund GROUP BY MONTH(date), YEAR(date), type ORDER BY year, month");
		for ($foundMovements['fund'] = array (); $row = $queryDeposit->fetch_assoc(); $foundMovements['fund'][] = $row);
		foreach ($foundMovements['fund'] as $valueDeposit) {
			if ( $valueDeposit['type'] == 'Deposit' ) {
				$deposit += $valueDeposit['total'];
				$dateDeposit = $valueDeposit['month'] . $valueDeposit['year']; 
				$totalWithdrawal = 0;
				foreach ($foundMovements['fund'] as $valueWithdrawal) {
					if ( $valueWithdrawal['type'] == 'Withdrawal' ) {
						$dateWithdrawal = $valueWithdrawal['month'] . $valueWithdrawal['year']; 
						if ( $dateDeposit == $dateWithdrawal ) {
							$totalWithdrawal = $valueWithdrawal['total'];
						}
					}
				}
				$balance += $valueDeposit['total'] -$totalWithdrawal;
				$saves = [
					"month" => $valueDeposit['month'],
					"year" => $valueDeposit['year'],
					"totalDeposited" => $valueDeposit['total'],
					"totalWithdrawal" => $totalWithdrawal,
					"Balance" => $balance
				];
				array_push($fund['fund'], $saves);
			} else {
				$withdrawal += $valueDeposit['total'];
			}
		}
		$fund['TotalWithdrawal'] = $withdrawal;
		$fund['TotalDeposit'] = $deposit ;
		$fund['total'] = $deposit - $withdrawal;
		return json_encode($fund);
	} catch (Exception $e) {
		return [];	
	}
	
}
function getcurrencyExchange($data){
	$connection = connectDB();
	try {
		$query = $connection->query("SELECT base, value FROM currencyexchange WHERE base = '$data'");
		$info = mysqli_fetch_row($query);
		$data = array(
			'base'  => $info[0],
			'value'  => $info[1]
		);
		return $data;
	} catch (PDOException $e) {
		return [];
	}
}
function getAllCryptoCurrencyInverted(){
	$connection = connectDB();
	$cryptocurrency = [];
	try {
		$query = $connection->query("SELECT P.id, C.name, P.value, P.available, P.btcTotal, C.icon, C.price, C.symbol 
																	FROM  portfoliocryptocurrencies P INNER JOIN cryptocurrency C ON P.idCryptoCurrency = C.id");
		if (!empty($query) && mysqli_num_rows($query)>0) {
			while ($row = mysqli_fetch_row($query)) {
				$crypto = new portfolioCryptoCurrencies();	
				$crypto->id = $row[0];	
				$crypto->name = $row[1];	
				$crypto->value = $row[2];	
				$crypto->available = $row[3];	
				$crypto->btcTotal = $row[4];
				$crypto->icon = $row[5];
				$crypto->price = $row[6];
				$crypto->symbol = $row[7];
				array_push($cryptocurrency, $crypto);
			}
			return $cryptocurrency;
		} else {
			return 0;
		}
	} catch (Exception $e) {
		return [];
	}
}
function getCryptoCurrencyInverted($platform){
	$connection = connectDB();
	$cryptocurrency = [];
	try {
		$query = $connection->query("SELECT P.id, C.name, P.value, P.available, P.btcTotal, C.icon, C.price, C.symbol, C.priceUSD, P.interestPaid FROM  portfoliocryptocurrencies P INNER JOIN cryptocurrency C ON P.idCryptoCurrency = C.id WHERE Platform = '$platform'");
		if (!empty($query) && mysqli_num_rows($query)>0) {
			while ($row = mysqli_fetch_row($query)) {
				$crypto = new portfolioCryptoCurrencies();	
				$crypto->id = $row[0];	
				$crypto->name = $row[1];	
				$crypto->value = $row[2];	
				$crypto->available = $row[3];	
				$crypto->btcTotal = $row[4];
				$crypto->icon = $row[5];
				$crypto->price = $row[6];
				$crypto->symbol = $row[7];
				$crypto->priceUSD = $row[8];
				$crypto->interestPaid = $row[9];
				array_push($cryptocurrency, $crypto);
			}
			return $cryptocurrency;
		} else {
			return 0;
		}
	} catch (PDOException $e) {
		return [];
	}
}
function getAllStocksInverted(){
	$connection = connectDB();
	$stocks = [];
	try {
		$query = $connection->query("SELECT DISTINCT name, acronym, currentValue, SUM(quantity) as stocks, SUM(totalValue) as total FROM portfoliostock GROUP BY name, acronym, currentValue");
		if (!empty($query) && mysqli_num_rows($query)>0) {
			while ($row = mysqli_fetch_row($query)) {
				$stock = new portfolioStocks();
				$stock->name = $row[0];	
				$stock->acronym = $row[1];	
				$stock->quantity = $row[3];	
				$stock->currentValue = $row[2];	
				$stock->totalValue = $row[4];
				array_push($stocks, $stock);
			}
			return $stocks;
		} else {
			return 0;
		}
	} catch (PDOException $e) {
		return [];
	}
}
function getStocksInverted($platform){
	$connection = connectDB();
	$stocks = [];
	try {
		$query = $connection->query("SELECT DISTINCT name, acronym, currentValue, SUM(quantity) as stocks, SUM(totalValue) as total FROM portfoliostock WHERE platform = '$platform' GROUP BY name, acronym, currentValue");
		if (!empty($query) && mysqli_num_rows($query)>0) {
			while ($row = mysqli_fetch_row($query)) {
				$stock = new portfolioStocks();
				$stock->name = $row[0];	
				$stock->acronym = $row[1];	
				$stock->quantity = $row[3];	
				$stock->currentValue = $row[2];	
				$stock->totalValue = $row[4];
				array_push($stocks, $stock);
			}
			return $stocks;
		} else {
			return 0;
		}
	} catch (PDOException $e) {
		return [];
	}
}
function getDetailStocksInverted($asset){
	$connection = connectDB();
	$stocks = [];
	$aStock = [];
	try {
		$query = $connection->query("SELECT id, name, acronym, quantity, purchaseValue, currentValue, totalValue, date FROM portfoliostock WHERE acronym = '$asset'");
		$querySum = $connection->query("SELECT SUM(quantity) as stocks, SUM(totalValue) as total FROM portfoliostock WHERE acronym = '$asset'");
		$rowTotal = $querySum->fetch_assoc(); 
		$stocks['stocks'] = $rowTotal['stocks'];
		$stocks['total'] = $rowTotal['total'];
		if (!empty($query) && mysqli_num_rows($query)>0) {
			while ($row = mysqli_fetch_row($query)) {
				$stock = new portfolioStocks();
				$stock->id = $row[0];	
				$stock->name = $row[1];	
				$stock->acronym = $row[2];	
				$stock->quantity = $row[3];	
				$stock->purchaseValue = $row[4];
				$stock->currentValue = $row[5];
				$stock->totalValue = $row[6];
				$stock->date = $row[7];
				array_push($aStock, $stock);
			}
			$stocks['asset'] = $aStock;
			return $stocks;
		} else {
			return 0;
		}
	} catch (PDOException $e) {
		return [];
	}
}
function getTransactionHistoryInvestment($limit){
	$connection = connectDB();
	$transactions = [];
	try {
		if ($limit > 0) {
			$query = $connection->query("SELECT * FROM transactionhistoryinvestment ORDER BY id DESC LIMIT $limit");
		} else {
			$query = $connection->query("SELECT * FROM transactionhistoryinvestment ORDER BY id DESC");
		}
		if (!empty($query) && mysqli_num_rows($query)>0) {
			while ($row = mysqli_fetch_array($query)) {
				$transaction = new transactionhistory();
				$transaction->id = $row[0];
				$transaction->concept = $row[1];
				$transaction->value = $row[2];
				$transaction->type = $row[3];
				$transaction->platform = $row[4];
				$transaction->date = $row[5];
				array_push($transactions, $transaction);
			}
			return json_encode($transactions);
		} else {
			return 0;
		}
		
	} catch (Exception $e) {
		return [];
	}
}
function patrimonyInvestments() {
	$connection = connectDB();
	$investment = [];
	try {
		$queryInvestmentsMonth =  $connection->query("SELECT MONTH(date) as month, YEAR(date) as year, type, SUM(value) as total FROM investments GROUP BY MONTH(date), YEAR(date), type ORDER BY year, month");
		for ($investments['investments']['date'] = array (); $row = $queryInvestmentsMonth->fetch_assoc(); $investments['investments']['date'][] = $row);
		//crypto
		$queryCrypto =  $connection->query("SELECT SUM(value) as total FROM portfoliocryptocurrencies");
		$row = $queryCrypto->fetch_assoc();
		$totalBalanceCrypto = $row['total'];
		//stock
		$queryCrypto =  $connection->query("SELECT SUM(totalValue) as total FROM portfoliostock");
		$row = $queryCrypto->fetch_assoc();
		$totalBalanceStock = $row['total'];
		//Totales
		$investments['investments']['totalCrypto'] = $totalBalanceCrypto;
		$investments['investments']['totalStock'] = $totalBalanceStock;
		$investments['investments']['total'] = $totalBalanceCrypto + $totalBalanceStock;
		return json_encode($investments);
	} catch (Exception $e) {
		return [];
	}
}
function liabilities() {
	$connection = connectDB();
	try {
		$query =  $connection->query("SELECT SUM(value) as total FROM debt");
		$row = $query->fetch_assoc();
		$totalLiabilities = $row['total'];
		return $totalLiabilities;
	} catch (Exception $e) {
		return [];
	}
}
function valueDebt() {
	$connection = connectDB();
	$debits = [];
	$debits['totalDebit'] = 0;
	$missingQuota = 0;
	try {
		$query =  $connection->query("SELECT id, detail, value, installments, paidInstallments FROM debt");
		if (!empty($query) && mysqli_num_rows($query)>0) {
			while ($row = mysqli_fetch_array($query)) {
				$quotaValue = $row[2] / $row[3];
				$missingQuota = $quotaValue * ($row[3] - $row[4]);
				$debit = new debitValue();
				$debit->id = $row[0];
				$debit->detail = $row[1];
				$debit->value = $missingQuota;
				array_push($debits, $debit);
				$debits['totalDebit'] += $missingQuota;
			}
		}
		return json_encode($debits);
	} catch (Exception $e) {
		return [];
	}
}