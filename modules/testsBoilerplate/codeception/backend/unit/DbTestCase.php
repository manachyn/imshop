<?php

namespace im\testsBoilerplate\codeception\backend\unit;

/**
 * Class DbTestCase
 * @package im\testsBoilerplate\codeception\backend\unit
 */
class DbTestCase extends \yii\codeception\DbTestCase
{
    public $appConfig = '@im/testsBoilerplate/codeception/config/backend/unit.php';
}
