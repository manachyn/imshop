<?php

namespace im\users\traits;

/**
 * Class ModuleTrait
 * Implements `getModule` method, to receive current module instance.
 * @property \im\users\Module $module
 * @package im\users\models
 */
trait ModuleTrait
{
    /**
     * @var \im\users\Module|null Module instance
     */
    private $_module;

    /**
     * @return \im\users\Module Module instance
     */
    public function getModule()
    {
        if ($this->_module === null) {
            $this->_module = \Yii::$app->getModule('users');
        }

        return $this->_module;
    }
}