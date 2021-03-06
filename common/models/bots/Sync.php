<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 1/20/18
 * Time: 6:32 PM
 */

namespace common\models\bots;

/**
 * Class Sync
 * @package common\models\bots
 */
class Sync extends \Stackable
{
    private $syncronized;

    public function __construct()
    {
        $this->syncronized = false;
    }

    public function run()
    {
        $this->syncronized = true;
    }
}