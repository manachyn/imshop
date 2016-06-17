<?php

namespace im\config;

use yii\base\BootstrapInterface;
use Yii;

/**
 * Class Bootstrap
 * @package im\config
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->setAliases();
    }

    /**
     * @return void
     */
    public function setAliases()
    {
        Yii::setAlias('@im/config', __DIR__);
    }
}

