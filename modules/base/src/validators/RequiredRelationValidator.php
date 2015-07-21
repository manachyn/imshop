<?php

namespace im\base\validators;

use SplObjectStorage;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\validators\RequiredValidator;
use Yii;

class RequiredRelationValidator extends RequiredValidator
{

    public $allValid = true;

    /**
     * Cache of validated models from relation to prevent recursion in validating of recursive relations
     * @var SplObjectStorage
     */
    private $_validated;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->_validated = new SplObjectStorage();
    }

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        /** @var ActiveRecord $model */
        $relation = $model->getRelation($this->getRelationName($attribute));
        $result = $this->validateRelationValue($model->$attribute, $relation->modelClass);
        if (!empty($result)) {
            $this->addError($model, $attribute, $result[0], $result[1]);
        }
    }

    /**
     * @inheritdoc
     */
    public function addError($model, $attribute, $message, $params = [])
    {
        $value = $model->$attribute;
        $params['attribute'] = $model->getAttributeLabel($attribute);
        switch (gettype($value)) {
            case 'array':
                $params['value'] = 'array()';
                break;
            case 'object':
                $params['value'] = 'object';
                break;
            default:
                $params['value'] = $value;
        }
        $model->addError($attribute, Yii::$app->getI18n()->format($message, $params, Yii::$app->language));
    }

    /**
     * Validates relation value.
     * @param mixed $value the data value to be validated.
     * @param string $modelClass
     * @return array|null the error message and the parameters to be inserted into the error message.
     */
    protected function validateRelationValue($value, $modelClass)
    {
        $result = $this->validateValue($value);
        if (empty($result)) {
            if (!is_array($value)) {
                $value = [$value];
            }
            $validCount = 0;
            foreach ($value as $item) {
                /** @var Model $item */
                if ($item instanceof $modelClass && $item->validate()) {
                    $validCount++;
                } elseif ($this->allValid) {
                    break;
                }
            }
            if ($validCount == 0 || ($this->allValid && $validCount != count($value))) {
                $result = [$this->message, []];
            }
        }
        return $result;
    }

    /**
     * @param Model $model
     * @return bool
     */
    protected function validateRelationModel($model)
    {
        if ($this->_validated->contains($model)) {
            return true;
        } else {
            $this->_validated->attach($model);
            return $model->validate();
        }
    }

    /**
     * @param string $attribute
     * @return string
     */
    protected function getRelationName($attribute)
    {
        if (strncmp($attribute, 'related ', 7) === 0) {
            $attribute = substr($attribute, 7);
        }
        return lcfirst(trim($attribute)) . 'Relation';
    }
} 