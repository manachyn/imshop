<?php

namespace im\search\components\service\db;

use im\search\components\query\Boolean;
use im\search\components\query\facet\FacetInterface;
use im\search\components\query\Match;
use im\search\components\query\MultiMatch;
use im\search\components\query\QueryInterface;
use im\search\components\query\QueryResultInterface;
use im\search\components\query\Range;
use im\search\components\query\SearchQueryInterface;
use im\search\components\query\Suggest;
use im\search\components\query\Term;
use im\search\components\searchable\AttributeDescriptor;
use im\search\components\searchable\SearchableInterface;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class Query
 * @package im\search\components\service\db
 */
class Query extends ActiveQuery implements QueryInterface
{
    /**
     * @var SearchableInterface
     */
    public $searchableType;

    /**
     * @var FacetInterface[]
     */
    private $_facets;

    /**
     * @var QueryResultInterface
     */
    private $_result;

    /**
     * @var SearchQueryInterface
     */
    private $_searchQuery;

    /**
     * @var AttributeDescriptor[]
     */
    private $_searchableAttributes;

    /**
     * @var array
     */
    private $_joined = [];

    /**
     * @inheritdoc
     */
    public function result($db = null)
    {
        if (!$this->_result) {
            $this->_result = new QueryResult($this, $this->all());
        }

        return $this->_result;
    }

    /**
     * @inheritdoc
     */
    public function setFacets($facets)
    {
        $this->_facets = [];
        foreach ($facets as $facet) {
            $this->addFacet($facet);
        }
    }

    /**
     * @inheritdoc
     */
    public function addFacet(FacetInterface $facet)
    {
        $this->_facets[$facet->getName()] = $facet;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getFacets()
    {
        return $this->_facets;
    }

    /**
     * @inheritdoc
     */
    public function getSearchQuery()
    {
        return $this->_searchQuery;
    }

    /**
     * @inheritdoc
     */
    public function setSearchQuery(SearchQueryInterface $searchQuery)
    {
        $this->_searchQuery = $searchQuery;
        $this->where($this->getCondition($searchQuery));
    }

    /**
     * @inheritdoc
     */
    public function getOrderBy()
    {
        // TODO: Implement getOrderBy() method.
    }

    /**
     * @inheritdoc
     */
    public function getSuggestQuery()
    {
        // TODO: Implement getSuggestQuery() method.
    }

    /**
     * @inheritdoc
     */
    public function setSuggestQuery(Suggest $suggestQuery)
    {
        // TODO: Implement
    }

    /**
     * Returns condition by search query.
     *
     * @param SearchQueryInterface $query
     * @return array
     */
    protected function getCondition(SearchQueryInterface $query)
    {
        $condition = [];
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->modelClass;
        $table = $modelClass::tableName();
        if ($query instanceof Boolean) {
            $operator = reset($query->getSigns()) === null ? 'or' : 'and';
            $condition = [$operator];
            foreach ($query->getSubQueries() as $subQuery) {
                $condition[] = $this->getCondition($subQuery);
            }
        } elseif ($query instanceof Term) {
            $field = $table . '.' . $query->getField();
            $childName = '';
            if (($pos = strrpos($query->getField(), '.')) !== false) {
                $childName = substr($query->getField(), $pos + 1);
                $name = substr($query->getField(), 0, $pos);
            } else {
                $name = $query->getField();
            }
            $attribute = $this->getSearchableAttribute($name);
            if ($attribute->value && ($attribute->value instanceof \Closure)) {
                $attributeValue = call_user_func_array($attribute->value, [new $this->modelClass]);
                if ($attributeValue instanceof ActiveQuery) {
                    $modelClass = $attributeValue->modelClass;
                    $relationTable = $modelClass::tableName();
                    $joinName = isset($attribute->params['relationName']) ? $attribute->params['relationName'] : $attribute->name;
                    if (!isset($this->_joined[$joinName])) {
                        $this->joinWith($joinName, false);
                        $this->_joined[$joinName] = true;
                    }
                    $field = $relationTable . '.' . ($childName ?: $name);
                }
            }
            $value = $query->getTerm();
            $condition = is_array($value) ? ['in', $field, $value] : [$field => $value];
        } elseif ($query instanceof Range) {
            if (($from = $query->getLowerBound()) !== null) {
                $condition[] = [$query->isIncludeLowerBound() ? '>=' : '>', $table . '.' . $query->getField(), $from];
            }
            if (($to = $query->getUpperBound()) !== null) {
                $condition[] = [$query->isIncludeLowerBound() ? '<=' : '<', $table . '.' . $query->getField(), $to];
            }
            if ($condition) {
                $condition = count($condition) == 1 ? $condition[0] : array_merge(['and'], $condition);
            }
        } elseif ($query instanceof Match) {
            $condition = ['like', $query->getField(), $query->getTerm()->getTerm()];
        } elseif ($query instanceof MultiMatch) {
            $condition = ['or'];
            $term = $query->getTerm()->getTerm();
            foreach (array_values($query->getFields()) as $field) {
                $condition[] = ['like', $field, $term];
            }
        }

        return $condition;
    }

    /**
     * Returns searchable attribute by name.
     *
     * @param string $name
     * @return AttributeDescriptor|null
     */
    protected function getSearchableAttribute($name)
    {
        if ($this->_searchableAttributes === null) {
            $this->_searchableAttributes = $this->searchableType->getSearchableAttributes();
        }
        foreach ($this->_searchableAttributes as $attribute) {
            if ($attribute->name === $name) {
                return $attribute;
            }
        }

        return null;
    }
}