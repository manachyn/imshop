<?php

namespace im\forms\components;

use Yii;
use yii\base\Component;

/**
 * Class FormsManager
 * @package im\forms\components
 * @author Ivan Manachyn <manachyn@gmail.com>
 */
class FormsManager extends Component
{
    /**
     * Registers widget.
     *
     * @param string $name
     * @param string $class
     */
    public function registerForm($name, $class)
    {
        Yii::$container->set($name, $class);
    }

    /**
     * @param string $name
     * @return FormInterface
     * @throws \yii\base\InvalidConfigException
     */
    public function getForm($name)
    {
        return Yii::$container->get($name);
    }
}