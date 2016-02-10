<?php

namespace app\modules\queue;

use app\modules\queue\components\dispatcher\Dispatcher;
use app\modules\queue\components\QueueManager;
use yii\base\Application;
use yii\base\BootstrapInterface;
use Yii;
use yii\di\Instance;

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
//        Yii::$container->set('app\modules\queue\components\interfaces\QueueResolverInterface', function () use ($app) {
//            return $app->queueManager;
//        });
//
//        Yii::$container->set('app\modules\queue\components\dispatcher\Dispatcher', [
//            'queueResolver' => Yii::$container->get('app\modules\queue\components\interfaces\QueueResolverInterface')
//        ]);

        Yii::$container->set('app\modules\queue\components\dispatcher\Dispatcher', function () use ($app) {
            /** @var QueueManager $queueManager */
            $queueManager = $app->get('queueManager');
            return new Dispatcher($queueManager);
        });

//        Yii::$container->set('app\modules\queue\components\interfaces\FailedJobProviderInterface', 'app\modules\queue\components\DatabaseFailedJobProvider');
//
//        Yii::$container->set('app\modules\queue\components\worker\Worker', [
//            'failedJobProvider' => Yii::$container->get('app\modules\queue\components\interfaces\FailedJobProviderInterface'
//        ]);

        Yii::$container->set('app\modules\queue\components\worker\Worker', [], [
            Instance::of('app\modules\queue\components\DatabaseFailedJobProvider')
        ]);

        Yii::$container->set('app\modules\queue\components\interfaces\EntityResolverInterface', 'app\modules\queue\components\QueueEntityResolver');
    }
}