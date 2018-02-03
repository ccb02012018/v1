<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 1/20/18
 * Time: 9:36 PM
 */

namespace bots\log;

class Logger {
	public static function writeException (\Exception $e) {
		$fecha = date_create();
		/*$log = new Log();
		$log->log_message = date_format($fecha, 'Y-m-d H:i:s') . ' ' . $e->getMessage();
		$log->save();*/
	}
	public static function writeLog ($message) {
		$fecha = date_create();
		/*$log = new Log();
		$log->log_message = date_format($fecha, 'Y-m-d H:i:s') . ' ' . $message;
		$log->save();*/
	}
}