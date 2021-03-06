<?php

namespace im\search\components\transformer;

use im\search\components\index\Document;
use yii\db\ActiveRecord;

class DocumentToActiveRecordTransformer implements DocumentToObjectTransformerInterface
{
    private $_objectClass;

    /**
     * @inheritdoc
     */
    public function transform($documents)
    {
        $ids = array_map(function (Document $document) { return $document->getId(); }, $documents);
        $objects = $this->findByIds($ids);
        $keys = array_flip($ids);
        usort($objects, function (ActiveRecord $a, ActiveRecord $b) use ($keys) {
            return $keys[$a->getPrimaryKey()] > $keys[$b->getPrimaryKey()];
        });

        return $objects;
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

    /**
     * @inheritdoc
     */
    public function getIdentifierField()
    {

    }

    /**
     * @param array $ids
     * @return ActiveRecord[]
     */
    protected function findByIds(array $ids)
    {
        /** @var ActiveRecord $objectClass */
        $objectClass = $this->getObjectClass();

        return $objectClass::findAll($ids);
    }
}