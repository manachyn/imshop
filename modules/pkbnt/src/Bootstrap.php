<?php

namespace im\pkbnt;

use yii\base\BootstrapInterface;
use Yii;

/**
 * Class Bootstrap
 * @package im\pkbnt
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
        Yii::setAlias('@im/pkbnt', __DIR__);
    }
}
