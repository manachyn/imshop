<?php

namespace im\users\tests\codeception\common\fixtures;

use yii\test\ActiveFixture;

/**
 * Class ProfileFixture
 * @package tests\codeception\fixtures
 */
class ProfileFixture extends ActiveFixture
{
    /**
     * @inheritdoc
     */
    public $modelClass = 'im\users\models\Profile';
}
