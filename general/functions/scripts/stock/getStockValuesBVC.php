https://es.tradingview.com/symbols/BVC-AAPL/ --> aqui hacer scraping para sacar valores

<?php
include_once "../../../config/db.php";
include_once "../../functions.php";
include_once "../../../../general/public/libraries/simple_html_dom/simple_html_dom.php";

$url = 'https://es.tradingview.com/symbols/BVC-AAPL/';

$html = file_get_html($url);
$content = $html->find('div[class=v-symbol-price-quote__value]');

print_r($content);