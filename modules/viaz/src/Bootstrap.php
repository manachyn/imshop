<?php

namespace im\viaz;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;

/**
 * Class Bootstrap
 * @package im\viaz
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
            'name' => 'Two columns',
            'default' => true
        ]);
        $layoutManager->registerLayout([
            'class' => 'im\cms\components\Layout',
            'id' => 'home',
            'name' => 'Home'
        ]);
    }

    /**
     * @return void
     */
    public function setAliases()
    {
        Yii::setAlias('@im/viaz', __DIR__);
    }
}
