<?php

namespace im\webpack;

use Yii;
use yii\console\Application;

/**
 * Webpack module.
 *
 * @package im\webpack
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'im\webpack\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (Yii::$app instanceof Application) {
            $this->controllerNamespace = 'im\webpack\commands';
        }
    }
}
