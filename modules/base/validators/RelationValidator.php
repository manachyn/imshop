<?php

namespace im\base\validators;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\validators\Validator;

class RelationValidator extends Validator
{
    public $allValid = true;

    public function validateAttribute($model, $attribute)
    {
        /** @var ActiveRecord $model */
        $relation = $model->getRelation($this->getRelationName($attribute));
        $result = $this->validateRelationValue($relation, $model->$attribute);
        if (!empty($result)) {
            $this->addError($model, $attribute, $result[0], $result[1]);
        }
    }

    /**
     * Validates relation value.
     * @param ActiveQuery $relation
     * @param mixed $value the data value to be validated.
     * @return array|null the error message and the parameters to be inserted into the error message.
     */
    protected function validateRelationValue($relation, $value)
    {
        $result = null;
        if (!empty($value)) {
            $modelClass = $relation->modelClass;

            if (!is_array($value)) {
                $value = [$value];
            }
            $validCount = 0;
            foreach ($value as $item) {
                /** @var ActiveRecord $item */
                if ($item instanceof $modelClass && $item->validate()) {
                    $validCount++;
                }
            }
            if ($validCount == 0 || ($this->allValid && $validCount != count($value))) {
                $result = [$this->message, []];
            }
        }

        return $result;
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