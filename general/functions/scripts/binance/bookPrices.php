<?php
function bookPrices($prices, $connection){
	$connection = connectDB();
	$currencyExchange = getcurrencyExchange("COP");
	$valueUSDT = $currencyExchange['value'];
	$listCurrency = getAllCryptoCurrencyInverted();
	$symbol = "";
	$date = date("Y-m-d H-i-s");
	foreach ($prices as $key => $value) {
		foreach ($listCurrency as $currency) {
			$symbol = $currency->symbol;
			$par = $symbol . "USDT";
			$valueExchange = $value * $valueUSDT;		
			if ($key == $par) {
				$queryUpdate = $connection->query("UPDATE cryptocurrency SET price = '$valueExchange', date = '$date', priceUSD = '$value' WHERE symbol = '$symbol'");
			}	
		}
	}
	$queryUpdate = $connection->query("UPDATE cryptocurrency SET price = '$valueUSDT', date = '$date', priceUSD = '1' WHERE symbol = 'USDT'");
	return 1;
}