<?php

namespace im\users\tests\codeception\backend\unit;

/**
 * Class DbTestCase
 * @package im\users\tests\codeception\backend\unit
 */
class DbTestCase extends \yii\codeception\DbTestCase
{
    public $appConfig = '@im/testsBoilerplate/codeception/config/backend/unit.php';
}
