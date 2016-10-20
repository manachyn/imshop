<?php

namespace im\pkbnt;

use Yii;
use yii\console\Application;

/**
 * Class Module
 * @package im\pkbnt
 * @author Ivan Manachyn <manachyn@gmail.com>
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'im\pkbnt\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (Yii::$app instanceof Application) {
            $this->controllerNamespace = 'im\pkbnt\commands';
        }
    }
}
