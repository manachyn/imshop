<?php

namespace im\users\tests\codeception\common\fixtures;

use yii\test\ActiveFixture;

/**
 * Class UserFixture
 * @package im\users\tests\codeception\common\fixtures
 */
class UserFixture extends ActiveFixture
{
    /**
     * @inheritdoc
     */
    public $modelClass = 'im\users\models\User';
}
