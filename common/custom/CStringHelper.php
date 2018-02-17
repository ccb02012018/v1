<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 5/20/17
 * Time: 6:30 PM
 */

namespace common\custom;

use yii\helpers\StringHelper;

class CStringHelper extends StringHelper
{
	/**
	 * @param $string
	 * @return string
	 */
	public static function subStringLessLast($string)
	{
		return substr($string, 0, -1);
	}

	/**
	 * @param $string
	 * @param $less
	 * @return string
	 */
	public static function subStringLess($string, $less)
	{
		return substr($string, 0, -$less);
	}

	/**
     * @param $string string
	 * @param $pattern string
     * @param $replacement string
	 * @return mixed
	 */
	public static function replace($string, $pattern, $replacement)
	{
		return str_replace($pattern, $replacement, $string);
	}
}