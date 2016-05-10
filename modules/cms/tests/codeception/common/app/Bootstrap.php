<?php

namespace im\cms\tests\codeception\common\app;

use yii\base\Application;
use yii\base\BootstrapInterface;
use Yii;

/**
 * Class Bootstrap
 * @package im\cms\tests\codeception\common\app
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
        /** @var \im\cms\components\LayoutManager $layoutManager */
        $layoutManager = $app->get('layoutManager');
        $layoutManager->registerLayout([
            'class' => 'im\cms\components\Layout',
            'id' => 'test-layout',
            'name' => 'Test layout',
            'default' => true,
            'availableWidgetAreas' => [
                ['class' => 'im\cms\components\WidgetAreaDescriptor', 'code' => 'test-layout-sidebar', 'title' => 'Sidebar area'],
                ['class' => 'im\cms\components\WidgetAreaDescriptor', 'code' => 'test-layout-footer', 'title' => 'Footer area']
            ]
        ]);
        $layoutManager->registerMenuLocation([
            'class' => 'im\cms\components\MenuLocationDescriptor',
            'code' => 'test-menu',
            'name' => 'Test menu'
        ]);
    }
}

