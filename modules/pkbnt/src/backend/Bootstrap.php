<?php

namespace im\pkbnt\backend;

use yii\base\Application;
use yii\base\BootstrapInterface;
use Yii;

/**
 * Theme bootstrap class.
 */
class Bootstrap extends \im\pkbnt\Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        parent::bootstrap($app);
        $this->registerLayouts($app);
    }

    /**
     * @param Application $app
     */
    public function registerLayouts($app)
    {
        /** @var \im\cms\components\LayoutManager $layoutManager */
        $layoutManager = $app->get('layoutManager');
        $layoutManager->registerLayout([
            'class' => 'im\cms\components\Layout',
            'id' => 'main',
            'name' => 'Main layout',
            'default' => true,
            'availableWidgetAreas' => [
                ['class' => 'im\cms\components\WidgetAreaDescriptor', 'code' => 'sidebar', 'title' => 'Sidebar'],
                ['class' => 'im\cms\components\WidgetAreaDescriptor', 'code' => 'footer', 'title' => 'Footer']
            ]
        ]);
        $layoutManager->registerLayout([
            'class' => 'im\cms\components\Layout',
            'id' => 'home',
            'name' => 'Home',
            'availableWidgetAreas' => [
                ['class' => 'im\cms\components\WidgetAreaDescriptor', 'code' => 'footer', 'title' => 'Footer']
            ]
        ]);
        $layoutManager->registerMenuLocation(['class' => 'im\cms\components\MenuLocationDescriptor', 'code' => 'top', 'name' => 'Top menu']);
    }
}
