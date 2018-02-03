<?php

use app\librerias\jaggedsoft\API;

$api = new API("XnBAtdgSaILAnT7vRLyVH5KI1rj7NFs0dwMZf3SqJWLL4IGijx9vyXQbdMkVV9Ur","UVsNzI5UiYhwcUkb4c9RNOrV2K1s3CbF5zHo9IrebSMAXMs3WemyGVkTZXHf0ZN2");
$ticker = $api->prices();
//print_r($ticker); // List prices of all symbols
//echo "Price of BNB: {$ticker['BNBBTC']} BTC.".PHP_EOL;
$balances = $api->balances($ticker);
print_r($balances);
