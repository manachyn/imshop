<?php

namespace im\cms\backend;

use Yii;

class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'im\cms\backend\controllers';

    /**
     * Module event handlers.
     *
     * @var array
     */
    public $eventHandlers = [
        'im\cms\components\CmsBackendEventsHandler'
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
