<?php

namespace im\imshop;

use yii\base\BootstrapInterface;
use Yii;

/**
 * Class Bootstrap
 * @package im\imshop
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
        Yii::setAlias('@im/imshop', __DIR__);
    }
}
