<?php

namespace im\blog\backend;

use im\base\traits\ModuleTranslateTrait;
use Yii;

/**
 * Class Module
 * @package im\blog\backend
 */
class Module extends \yii\base\Module
{
    use ModuleTranslateTrait;

    /**
     * @var string module messages category.
     */
    public static $messagesCategory = 'blog';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'im\blog\backend\controllers';

    /**
     * Module event handlers.
     *
     * @var array
     */
    public $eventHandlers = [
        'im\blog\components\BackendEventsHandler'
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
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
