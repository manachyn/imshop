<?php

namespace im\eav\components;

use yii\base\Behavior;
use yii\db\ActiveRecord;

class EavBehavior extends Behavior
{
    /**
     * @var ActiveRecord
     */
    public $owner;

    public $attributes = [];

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
//            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
//            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
//            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
//            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave'
        ];
    }

    /**
     * @inheritdoc
     */
    public function canSetProperty($name, $checkVars = true)
    {
        if (parent::canSetProperty($name, $checkVars)) {
            return true;
        } else {
            return $this->hasAttribute($name);
        }
    }

    /**
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true)
    {
        if (parent::canSetProperty($name, $checkVars)) {
            return true;
        } else {
            return $this->hasAttribute($name);
        }
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        try { parent::__set($name, $value); }
        catch (\Exception $e) {
            $this->setAttribute($name, $value);
        }
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        try { return parent::__get($name); }
        catch (\Exception $e) {
            return $this->getAttribute($name);
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function hasAttribute($name)
    {
        return isset($this->attributes[$name]);
    }
}