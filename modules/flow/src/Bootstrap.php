<?php

namespace im\flow;

use im\base\routing\ModuleRulesTrait;
use yii\base\BootstrapInterface;
use Yii;

/**
 * Class Bootstrap
 * @package im\flow
 */
class Bootstrap implements BootstrapInterface
{
    use ModuleRulesTrait;

    /**
     * Module routing.
     *
     * @return array
     */
    public function getRules()
    {
        return [
            ['pattern' => 'flow/<scenarioAlias:\w+>', 'route' => 'flow/flow/start'],
            ['pattern' => 'flow/<scenarioAlias:\w+>/<stepName:\w+>', 'route' => 'flow/flow/display'],
            ['pattern' => 'flow/<scenarioAlias:\w+>/<stepName:\w+>/forward', 'route' => 'flow/flow/forward'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->setAliases();
        $this->addRules($app);
        $this->registerDefinitions();
    }

    /**
     * Registers a class definitions in container.
     */
    public function registerDefinitions()
    {
        Yii::$container->set('im\flow\builder\FlowBuilderInterface', 'im\flow\builder\FlowBuilder');
        Yii::$container->set('im\flow\FlowContextInterface', 'im\flow\FlowContext');
        Yii::$container->set('im\flow\storage\StorageInterface', 'im\flow\storage\SessionStorage');
        Yii::$container->set('im\flow\coordinator\CoordinatorInterface', 'im\flow\coordinator\Coordinator');
        Yii::$container->setSingleton('im\flow\coordinator\Coordinator', 'im\flow\coordinator\Coordinator');
    }

    public function setAliases()
    {
        Yii::setAlias('@flow_start', '/flow/flow/start');
        Yii::setAlias('@flow_display', '/flow/flow/display');
        Yii::setAlias('@flow_forward', '/flow/flow/forward');
    }
}