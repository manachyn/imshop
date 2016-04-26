<?php

namespace im\seo;

use im\base\types\EntityType;
use im\seo\models\Meta;
use Yii;
use yii\base\BootstrapInterface;

/**
 * Class Bootstrap
 * @package im\seo
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
        Yii::setAlias('@im/seo', __DIR__);
    }
}