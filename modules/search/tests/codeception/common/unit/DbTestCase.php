<?php

namespace im\search\tests\codeception\common\unit;

/**
 * @inheritdoc
 */
class DbTestCase extends \yii\codeception\DbTestCase
{
    /**
     * @inheritdoc
     */
    public $appConfig = '@tests/codeception/config/common/unit.php';
}
