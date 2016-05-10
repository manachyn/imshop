<?php

namespace im\tree;

use Yii;
use yii\base\BootstrapInterface;

/**
 * Class Bootstrap
 * @package im\tree
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
        Yii::setAlias('@im/tree', __DIR__);
    }
}
