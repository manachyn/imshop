<?php

namespace im\cms;

use im\base\routing\ModuleRulesTrait;
use im\base\types\EntityType;
use yii\base\BootstrapInterface;
use Yii;

/**
 * Class Bootstrap
 * @package im\cms
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
        Yii::setAlias('@im/cms', __DIR__);
    }
}