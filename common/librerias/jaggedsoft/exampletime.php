<?php

namespace app\librerias\jaggedsoft;

use app\librerias\jaggedsoft\API;
use Exception;

$fecha = date_create();
echo date_format($fecha, 'U = Y-m-d H:i:s') . "\n";

try {

	$api = new API("XnBAtdgSaILAnT7vRLyVH5KI1rj7NFs0dwMZf3SqJWLL4IGijx9vyXQbdMkVV9Ur","UVsNzI5UiYhwcUkb4c9RNOrV2K1s3CbF5zHo9IrebSMAXMs3WemyGVkTZXHf0ZN2");

	$response = $this->request("v1/time", []);

	echo '<pre>';
	print_r($response);
	echo '</pre>';


	$fecha = date_create();
	date_timestamp_set($fecha, 1516476542);
	echo date_format($fecha, 'U = Y-m-d H:i:s') . "\n";
} catch (Exception $exception) {
	echo $exception->getMessage();
}
?>