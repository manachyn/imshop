<?php

namespace im\search\components\service\db;

use im\search\components\finder\BaseFinder;
use im\search\components\query\QueryInterface;
use im\search\components\query\SearchQueryInterface;
use im\search\components\query\Suggest;
use Yii;

/**
 * Class Finder
 * @package im\search\components\service\db
 */
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

    /**
     * Finds text suggestions.
     *
     * @param Suggest $suggest
     * @param string $type
     * @param SearchQueryInterface|null $query
     * @return QueryInterface
     */
    public function findSuggestions(Suggest $suggest, $type, SearchQueryInterface $query = null)
    {
        $params = [
            'class' => SuggestionsQuery::className(),
            'searchableType' => $this->getSearchableType($type),
            'suggestQuery' => $suggest
        ];
        if ($query) {
            $params['searchQuery'] = $query;
        }
        $query = Yii::createObject($params, [$this->getModelClass($type)]);

        return $query;
    }
}
