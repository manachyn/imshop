<?php

namespace im\adminlte;

use yii\base\BootstrapInterface;
use Yii;

/**
 * Class Bootstrap
 * @package im\adminlte
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
        Yii::setAlias('@im/adminlte', __DIR__);
    }
}
