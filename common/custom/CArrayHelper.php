<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 5/20/17
 * Time: 6:29 PM
 */

namespace common\custom;

use yii\helpers\ArrayHelper;

/**
 * Class CArrayHelper
 * @package common\custom
 */
class CArrayHelper extends ArrayHelper
{
	/**
	 * @param $array array
	 * @param $value
	 */
	public static function setValor(&$array, $value)
	{
		array_push($array, $value);
	}

	/**
	 * @param $array array
	 * @param $value
	 * @return bool
	 */
	public static function valueExists($array, $value)
	{
		return array_search($value, $array) !== false;
	}

	/**
	 * @param $array
	 * @return mixed
	 */
	public static function getFirstValue($array)
	{
		return reset($array);
	}

	/**
	 * @param $array
	 * @return mixed
	 */
	public static function getFirstKey($array)
	{
		return key($array);
	}

	/**
	 * @param $array
	 * @param $index
	 * @return mixed
	 */
	public static function getElement($array, $index)
	{
		return array_values($array)[$index];
	}

	/**
	 * @param $delimiter string
	 * @param $array array
	 * @return string
	 */
	public static function implode ($delimiter, $array) {
		return implode($delimiter, $array);
	}

    /**
     * @param $array
     * @return string
     */
    public static function toString($array)
    {
        return json_encode($array);
    }
}