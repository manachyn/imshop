<?php

namespace im\catalog\backend;

/**
 * Catalog backend module.
 *
 * @package im\catalog\backend
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'im\catalog\backend\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->modules = [
            'rest' => [
                'class' => 'im\catalog\rest\Module'
            ]
        ];
    }
}
