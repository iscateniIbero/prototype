<?php 
include_once "../../../config/db.php";
include_once "../../functions.php";
echo balanceBinance(PUBLIC_KEY, SECRET_KEY);
function balanceBinance($public_key, $secret_key){
  $connection = connectDB();
  include_once "../../../public/libraries/binanceAPI/vendor/autoload.php";
  include_once "bookPrices.php";
  $api = new Binance\API($public_key, $secret_key);
  $ticker = $api->prices(); 
  //Update bookPrices 
  $bookPrices = bookPrices($ticker, $connection);
  $aBalances = $api->balances($ticker);
  //Staking bloqueado 
  $timestamp = time()*1000; 
  $query_string = 'product=STAKING&timestamp='.$timestamp;
  $signature = hash_hmac('sha256',$query_string,SECRET_KEY);
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://api.binance.com/sapi/v1/staking/position?".$query_string."&signature=".$signature);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-MBX-APIKEY:'.PUBLIC_KEY)); 
  $response = curl_exec($ch);
  curl_close($ch);
  $aStaking = json_decode($response);
  //Ahorros
  $query_string = 'timestamp='.$timestamp;
  $signature = hash_hmac('sha256',$query_string,SECRET_KEY);
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://api.binance.com/sapi/v1/lending/daily/token/position?".$query_string."&signature=".$signature);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-MBX-APIKEY:'.PUBLIC_KEY)); 
  $response = curl_exec($ch);
  curl_close($ch);
  $aSavings = json_decode($response);
  $criptos = [];
  $balance = [];
  foreach ($aBalances as $sSymbol => $value) {
    if ( $value['available'] > 0 ) {
      $symbol = $sSymbol;
      if (substr($symbol, 0, 2) != "LD") {
        $btcTotal = $value['btcTotal'];
        $available = $value['available'];
        $coin = [
          "available" => $available,
          "btcTotal" => $btcTotal
        ];
        $balance[$symbol] = $coin;
      }
    }
  }
  $saving = [];
  foreach ($aSavings as $skey => $savings) {
    $coin = [
      "available" => $savings->totalAmount,
      "btcTotal" => 0
    ];
    $saving[$savings->asset] = $coin;
      ;
  }
  $staking = [];
  foreach ($aStaking as $skey => $stakings) {
    if (array_key_exists($stakings->asset, $staking)) {
      $stakings->amount = $staking[$stakings->asset]['available'] + $stakings->amount;
    }
    $coin = [
      "available" => $stakings->amount,
      "btcTotal" => 0
    ];
    $staking[$stakings->asset] = $coin;
  }
  $bs = [];
  foreach ($balance as $balanceKey => $balanceValue) {
    $amount = 0;
    $bttotal = 0;
    if (array_key_exists($balanceKey, $saving)) {
      $amount = $saving[$balanceKey]['available'] + $balanceValue['available'];
      $bttotal = $saving[$balanceKey]['btcTotal'] + $balanceValue['btcTotal'];
      $coin = [
        "available" => $amount,
        "btcTotal" => $bttotal
      ];
      $bs[$balanceKey] = $coin;
      unset($balance[$balanceKey], $saving[$balanceKey]);
    }
  }
  $mergeBs = array_merge($saving, $balance, $bs);
  $sb = [];
  foreach ($mergeBs as $keyCripto => $valueCripto) {
    $amount = 0;
    $bttotal = 0;
    if (array_key_exists($keyCripto, $staking)) {
      $amount = $staking[$keyCripto]['available'] + $valueCripto['available'];
      $bttotal = $staking[$keyCripto]['btcTotal'] + $valueCripto['btcTotal'];
      $coin = [
        "available" => $amount,
        "btcTotal" => $bttotal
      ];
      $sb[$keyCripto] = $coin;
      unset($mergeBs[$keyCripto], $staking[$keyCripto]);
    }
    $criptos = array_merge($mergeBs, $sb);
  }
  // Borramos las cripto de binance
  $queryDelete = $connection->query("DELETE FROM portfoliocryptocurrencies WHERE Platform = 'Binance'");
  // Insertamos las criptomonedas
  foreach ($criptos as $nameCrypto => $infoCryto) {
    $available = $infoCryto['available'];
    $btcTotal = $infoCryto['btcTotal'];
    //Buscamos el id de la cripto
    $query = $connection->query("SELECT id, price FROM cryptocurrency 
                                                  WHERE symbol = '$nameCrypto'");
		$row = mysqli_fetch_row($query);
    $idCrypto = $row[0];
    $price = $row[1];
    $value = $available * $price;
		$date = date("Y-m-d H-i-s");
    $querySelect = $connection->query("SELECT id FROM portfoliocryptocurrencies 
                                                 WHERE idCryptoCurrency = '$idCrypto' && Platform = 'Binance'");
    if (!empty($querySelect) && mysqli_num_rows($querySelect) > 0) {
      $queryUpdate = $connection->query("UPDATE portfoliocryptocurrencies SET value = '$value', 
					                                                                    available = '$available', 
					                                                                    btcTotal = '$btcTotal', 
					                                                                    date = '$date' 
					                                                      	WHERE idCryptoCurrency = '$idCrypto' && Platform = 'Binance'");
    } else {
      $queryInsert = $connection->query("INSERT INTO portfoliocryptocurrencies (idCryptoCurrency, value, available, btcTotal, Platform, date) 
                                                VALUES ('$idCrypto', '$value', '$available', '$btcTotal', 'Binance', '$date')");  
    }                                           
  }
}
echo balanceBlockFi(PUBLIC_KEY, SECRET_KEY);
function balanceBlockFi ($public_key, $secret_key) {
	$connection = connectDB();
  //Update bookPrices 
  $api = new Binance\API($public_key, $secret_key);
  $ticker = $api->prices(); 
  $bookPrices = bookPrices($ticker, $connection);
	$cryptoCurrencyBlockFi = getCryptoCurrencyInverted("BlockFi");
	foreach ($cryptoCurrencyBlockFi as $key => $value) {
		$id = $value->id;
		$valueCrypto = $value->available;
		$price =  $value->price;
		$currentPrice = $price * $valueCrypto;
		$date = date("Y-m-d H-i-s");
		$queryUpdate =  $connection->query("UPDATE portfoliocryptocurrencies SET value = '$currentPrice', date = '$date' WHERE id = '$id'");
	}
}
echo json_encode('1');
?>