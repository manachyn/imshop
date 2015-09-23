<?php

namespace im\search;

use im\base\types\EntityType;
use yii\base\BootstrapInterface;
use Yii;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->registerPageTypes();
    }

    /**
     * Registers page types.
     */
    public function registerPageTypes()
    {
        /** @var \im\cms\components\Cms $cms */
        $cms = Yii::$app->get('cms');
        $cms->registerPageType(new EntityType('search_page', 'im\search\models\SearchPage'));
    }
}