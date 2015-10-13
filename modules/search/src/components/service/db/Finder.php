<?php

namespace im\search\components\service\db;

use im\search\components\finder\BaseFinder;
use im\search\components\query\SearchQueryInterface;
use Yii;
use yii\db\ActiveRecord;

class Finder extends BaseFinder
{
    /**
     * @inheritdoc
     */
    public function find($type)
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->getModelClass($type);
    }

    /**
     * @inheritdoc
     */
    public function findByQuery($type, SearchQueryInterface $query)
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->getModelClass($type);

        return [];
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