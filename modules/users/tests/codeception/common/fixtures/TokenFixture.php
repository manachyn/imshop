<?php

namespace im\users\tests\codeception\common\fixtures;

use yii\test\ActiveFixture;

/**
 * Class TokenFixture
 * @package im\users\tests\codeception\common\fixtures
 */
class TokenFixture extends ActiveFixture
{
    /**
     * @inheritdoc
     */
    public $modelClass = 'im\users\models\Token';
}
