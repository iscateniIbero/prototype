<?php
require_once '../../config/db.php';
require_once '../functions.php';
$value = trim(str_replace('.', '', $_POST['value']));
switch ($_POST['typeTransaction']) {
    case 'Income': {
        $data = array(
            $_POST['detail'],
            $value,
            $_POST['date'],
            $_POST['category'],
            'Income'
        );	
        echo recordIncome($data);
        break;
    }
    case 'Egress': {
        if ($_POST['type'] == 'Fijo') {
            $data = array(
				$_POST['detail'],
				$value,
				$_POST['date'],
				$_POST['category'],
				$_POST['type'],
				$_POST['frequency'],
				$_POST['paymentMethod'],
				'Expense'
			);	
			echo recordEgress($data);
            break; 
        }
        if ($_POST['type'] == 'Variable') {
            if ($_POST['paymentMethod'] == 'Efectivo') {
                $data = array(
                    $_POST['detail'],
                    $value,
                    $_POST['date'],
                    $_POST['category'],
                    $_POST['type'],
                    $_POST['frequency'],
                    $_POST['paymentMethod'],
                    'Expense'
                );	
                echo recordEgress($data);
                break; 
            }
            if ($_POST['paymentMethod'] == 'Credito') {
                $data = array(
					$_POST['detail'],
					$value,
					$_POST['date'],
					$_POST['category'],
					$_POST['type'],
					$_POST['frequency'],
					$_POST['paymentMethod'],
					$_POST['targetCredit'],
					$_POST['fees'],
					'Expense'
				);
                echo recordEgress($data);
                break;
            }
        }
        if ($_POST['type'] == 'Eventual') {
            $data = array(
                $_POST['detail'],
                $value,
                $_POST['date'],
                $_POST['category'],
                $_POST['type'],
                $_POST['paymentMethod'],
                'Expense'
            );
            echo recordEgress($data);
            break;
        }
    }
    case 'Payment': {
        $data = array(
            $_POST['detailPay'],
            $value = trim(str_replace('.', '', $_POST['valuePay'])),
            $_POST['date'],
            $_POST['category'],
            'Expense'
        );	
        echo recordPayment($data);
        break;
    }
    case 'Withdrawal': {
        $data = array(
            $_POST['detailWithdrawal'],
            $value = trim(str_replace('.', '', $_POST['valueWithdrawal'])),
            $_POST['date'],
            $_POST['typeTransaction']
        );	
        echo recordWithdrawal($data);
        break;
    }
}