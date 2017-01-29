<?php

namespace im\pkbnt;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;

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
            'name' => 'Two columns, right sidebar',
            'default' => true,
            'availableWidgetAreas' => [
                ['class' => 'im\cms\components\WidgetAreaDescriptor', 'code' => 'top', 'title' => 'Top block'],
                ['class' => 'im\cms\components\WidgetAreaDescriptor', 'code' => 'topSidebar', 'title' => 'Top sidebar'],
                ['class' => 'im\cms\components\WidgetAreaDescriptor', 'code' => 'beforeContent', 'title' => 'Before content'],
                ['class' => 'im\cms\components\WidgetAreaDescriptor', 'code' => 'afterContent', 'title' => 'After content'],
                ['class' => 'im\cms\components\WidgetAreaDescriptor', 'code' => 'sidebar', 'title' => 'Sidebar']
            ]
        ]);
        $layoutManager->registerMenuLocation(['class' => 'im\cms\components\MenuLocationDescriptor', 'code' => 'top', 'name' => 'Top menu']);
        $layoutManager->registerMenuLocation(['class' => 'im\cms\components\MenuLocationDescriptor', 'code' => 'bottom', 'name' => 'Bottom menu']);
        $layoutManager->registerMenuLocation(['class' => 'im\cms\components\MenuLocationDescriptor', 'code' => 'bottomRight', 'name' => 'Bottom right menu']);
    }

    /**
     * @return void
     */
    public function setAliases()
    {
        Yii::setAlias('@im/pkbnt', __DIR__);
    }
}
