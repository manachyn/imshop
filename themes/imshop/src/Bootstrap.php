<?php

namespace im\imshop;

use yii\base\Application;
use yii\base\BootstrapInterface;
use Yii;

/**
 * Theme bootstrap class.
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->registerLayouts($app);
    }

    /**
     * @param Application $app
     */
    public function registerLayouts($app)
    {
        $layoutManager = $app->get('layoutManager');
        $layoutManager->registerLayout(Yii::createObject([
            'class' => 'im\cms\components\Layout',
            'id' => 'main',
            'name' => 'Main layout',
            'default' => true,
            'availableWidgetAreas' => [
                ['class' => 'im\cms\components\WidgetAreaDescriptor', 'code' => 'sidebar', 'title' => 'Sidebar'],
                ['class' => 'im\cms\components\WidgetAreaDescriptor', 'code' => 'footer', 'title' => 'Footer']
            ]
        ]));
        $layoutManager->registerLayout(Yii::createObject([
            'class' => 'im\cms\components\Layout',
            'id' => 'home',
            'name' => 'Home',
            'availableWidgetAreas' => [
                ['class' => 'im\cms\components\WidgetAreaDescriptor', 'code' => 'footer', 'title' => 'Footer']
            ]
        ]));
    }
}
