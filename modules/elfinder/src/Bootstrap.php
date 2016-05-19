<?php

namespace im\elfinder;

use yii\base\BootstrapInterface;
use Yii;

/**
 * Class Bootstrap
 * @package im\elfinder
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
        Yii::setAlias('@im/elfinder', __DIR__);
    }
}

