<?php

namespace im\events;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->registerDefinitions($app);
    }

    /**
     * Registers a class definitions in container.
     */
    public function registerDefinitions()
    {
        \Yii::$container->set('im\events\EventEmitterInterface', 'im\events\EventEmitter');
    }
}