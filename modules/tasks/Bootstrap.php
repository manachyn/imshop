<?php

namespace app\modules\tasks;

use yii\base\Application;
use yii\base\BootstrapInterface;
use Yii;

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
     *
     * @param Application $app the application currently running
     */
    public function registerDefinitions($app)
    {
        Yii::$container->set('app\modules\tasks\components\TasksDispatcherInterface', function () use ($app) {
            return $app->get('tasksDispatcher');
        });
    }
}