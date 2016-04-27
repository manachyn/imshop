<?php

namespace im\search\components\index\provider;

use yii\db\ActiveRecord;

/**
 * Provides index data using ORM.
 *
 * @package im\search\components\index\provider
 */
class ActiveRecordIndexProvider implements IndexProviderInterface
{
    /**
     * @var string
     */
    private $_objectClass;

    /**
     * @inheritdoc
     */
    public function countObjects($offset = 0)
    {
        /** @var ActiveRecord $class */
        $class = $this->getObjectClass();

        return $class::find()->count() - $offset;
    }

    /**
     * @inheritdoc
     */
    public function getObjects($limit = null, $offset = 0)
    {
        /** @var ActiveRecord $class */
        $class = $this->getObjectClass();
        $query = $class::find()->offset($offset);
        if ($limit) {
            $query->limit($limit);
        }

        return $query->all();
    }

    /**
     * @inheritdoc
     */
    public function getObjectClass()
    {
        return $this->_objectClass;
    }

    /**
     * @inheritdoc
     */
    public function setObjectClass($objectClass)
    {
        $this->_objectClass = $objectClass;
    }
}