<?php

namespace im\search\components\index\provider;

use yii\db\ActiveRecord;

class ActiveRecordIndexProvider implements IndexProviderInterface
{
    /**
     * @var string
     */
    private $_modelClass;

    /**
     * @inheritdoc
     */
    public function countObjects($offset = 0)
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->getModelClass();

        return $modelClass::find()->count() - $offset;
    }

    /**
     * @inheritdoc
     */
    public function getObjects($limit = null, $offset = 0)
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->getModelClass();
        $query = $modelClass::find()->offset($offset);
        if ($limit) {
            $query->limit($limit);
        }

        return $query->all();
    }

    /**
     * @inheritdoc
     */
    public function getModelClass()
    {
        return $this->_modelClass;
    }

    /**
     * @inheritdoc
     */
    public function setModelClass($modelClass)
    {
        $this->_modelClass = $modelClass;
    }
}