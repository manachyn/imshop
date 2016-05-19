<?php

namespace im\config\tests\codeception\common\unit;

use im\config\models\Config as BaseConfig;

/**
 * Class TestConfig
 * @package im\config\tests\codeception\common\unit
 */
class TestConfig extends BaseConfig
{
    /**
     * @var string
     */
    public $option1 = 1;

    /**
     * @var string
     */
    public $option2;

    /**
     * @inheritdoc
     */
    public function getKey()
    {
        return 'test-config';
    }

    /**
     * @inheritdoc
     */
    public function getUserSpecificOptions()
    {
        return ['option2'];
    }
}
