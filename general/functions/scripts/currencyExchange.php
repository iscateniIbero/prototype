<?php
// run every 3 hours
echo $exchangeRates = exchangeRates();
function exchangeRates(){
  require_once '../../config/db.php';
  $connection = connectDB();
  // set API Endpoint and API key
  $endpoint = 'live';
	$access_key = ACCESS_KEY;
  $url = 'http://api.currencylayer.com/'.$endpoint.'?access_key='.$access_key;
  $aCURLSettings = array(CURLOPT_RETURNTRANSFER => true);
  $rCURL =  curl_init($url);
  curl_setopt_array($rCURL, $aCURLSettings);
  $sCURLResult = curl_exec($rCURL);
	$aCURLinfo = curl_getinfo($rCURL);
	curl_close($rCURL);
  if ( $aCURLinfo['http_code'] === 200 ) {
    $oCURLResult = json_decode($sCURLResult);
    $exchange = $oCURLResult->quotes;
    $queryTruncate = $connection->query("TRUNCATE TABLE currencyexchange");
    foreach ($exchange as $key => $value) {
      $sValue = $value;
      $title = str_replace("USD", "", $key);
      $queryInsert = $connection->query("INSERT INTO currencyexchange (base, value) VALUES ('$title', '$sValue')");
    }
    return 1;
  }
}
