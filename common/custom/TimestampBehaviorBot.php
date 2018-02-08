<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 6/7/17
 * Time: 12:23 PM
 */

namespace common\custom;


use yii\behaviors\TimestampBehavior;

class TimestampBehaviorBot extends TimestampBehavior
{
	public $createdAtAttribute = 'bot_last_update';
	public $updatedAtAttribute = 'bot_last_update';
}