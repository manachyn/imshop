<?php

namespace im\ecommerce;

use im\ecommerce\flow\scenario\CheckoutScenario;
use yii\base\BootstrapInterface;

/**
 * Class Bootstrap
 * @package im\ecommerce
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->registerScenarios($app);
    }

    /**
     * Registers scenarios.
     *
     * @param \yii\base\Application $app
     */
    public function registerScenarios($app)
    {
        /** @var \im\flow\coordinator\CoordinatorInterface $flowCoordinator */
        $flowCoordinator = $app->get('flowCoordinator');
        $flowCoordinator->registerScenario('checkout', new CheckoutScenario());
    }
}