<?php

namespace im\search\backend;

use im\base\routing\ModuleRulesTrait;
use im\base\types\EntityType;
use yii\base\BootstrapInterface;
use Yii;

/**
 * Class Bootstrap
 * @package im\search\backend
 */
class Bootstrap extends \im\search\Bootstrap implements BootstrapInterface
{
    /**
     * Event handlers.
     *
     * @var array
     */
    public $eventHandlers = [
        'im\search\components\EventsHandler'
    ];

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        parent::bootstrap($app);
        $this->registerEventHandlers();
    }

    /**
     * Registers event handlers.
     */
    public function registerEventHandlers()
    {
        foreach ($this->eventHandlers as $key => $handler) {
            $this->eventHandlers[$key] = Yii::createObject($handler);
        }
    }
}