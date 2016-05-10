<?php

namespace im\cms\backend;

use im\base\traits\ModuleTranslateTrait;
use Yii;

class Module extends \yii\base\Module
{
    use ModuleTranslateTrait;

    /**
     * @var string module messages category.
     */
    public static $messagesCategory = 'cms';

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
        $this->modules = [
            'rest' => [
                'class' => 'im\cms\rest\Module'
            ]
        ];
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
