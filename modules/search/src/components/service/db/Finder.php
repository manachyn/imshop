<?php

namespace im\search\components\service\db;

use im\search\components\finder\BaseFinder;
use im\search\components\query\SearchQueryInterface;
use Yii;

class Finder extends BaseFinder
{
    /**
     * @inheritdoc
     */
    public function find($type)
    {
        return Yii::createObject([
            'class' => Query::className(),
            'searchableType' => $this->getSearchableType($type)
        ], [$this->getModelClass($type)]);
    }

    /**
     * @inheritdoc
     */
    public function findByQuery($type, SearchQueryInterface $query)
    {
        return Yii::createObject([
            'class' => Query::className(),
            'searchableType' => $this->getSearchableType($type),
            'searchQuery' => $query
        ], [$this->getModelClass($type)]);
    }

    /**
     * Returns model class by searchable type.
     *
     * @param string $type
     * @return string
     */
    protected function getModelClass($type)
    {
        /** @var SearchableType $type */
        $type = $this->getSearchableType($type);

        return $type->modelClass;
    }
}