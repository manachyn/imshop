<?php

namespace im\cms\tests\codeception\backend\unit;

/**
 * Class DbTestCase
 * @package im\cms\tests\codeception\backend\unit
 */
class DbTestCase extends \yii\codeception\DbTestCase
{
    public $appConfig = '@im/cms/tests/codeception/config/backend/unit.php';
}
