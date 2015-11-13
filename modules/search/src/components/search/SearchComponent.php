<?php

namespace im\search\components\search;

use im\search\components\index\IndexableInterface;
use im\search\components\query\parser\QueryConverterInterface;
use im\search\components\query\parser\QueryParser;
use im\search\components\query\parser\QueryParserInterface;
use im\search\components\query\SearchQueryInterface;
use Yii;
use yii\base\Component;

class SearchComponent extends Component
{
    /**
     * Query parser is used to parse search request to query object.
     *
     * @var QueryParserInterface
     */
    public $queryParser = [
        'class' => 'im\search\components\query\parser\QueryParser',
        'higherPriorityOperator' => QueryParser::OPERATOR_OR
    ];

    /**
     * Query converter is used to convert query back to string.
     *
     * @var QueryConverterInterface
     */
    public $queryConverter = 'im\search\components\query\parser\QueryConverter';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (!$this->queryParser instanceof QueryParserInterface) {
            $this->queryParser = Yii::createObject($this->queryParser);
        }
        if (!$this->queryConverter instanceof QueryConverterInterface) {
            $this->queryConverter= Yii::createObject($this->queryConverter);
        }
    }

    public function search($type, $params)
    {
        //$query = $this->getQuery($type);
    }

    /**
     * Return search query.
     *
     * @param string $type
     * @param SearchQueryInterface|string $searchQuery
     * @param \im\search\components\query\facet\FacetInterface[] $facets
     * @return \im\search\components\query\QueryInterface
     */
    public function getQuery($type, $searchQuery = null, $facets = [])
    {
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = Yii::$app->get('searchManager');
        $searchableType = $searchManager->getSearchableType($type);
        if ($searchableType instanceof IndexableInterface) {
            $mapping = $searchableType->getIndexMapping($type);
        }
        $searchService = $searchableType->getSearchService();
        $finder = $searchService->getFinder();
        if ($searchQuery) {
            if (is_string($searchQuery)) {
                $searchQuery = $this->parseQuery($searchQuery);
            }
            $query = $finder->findByQuery($type, $searchQuery);
        } else {
            $query = $finder->find($type);
        }
        if ($facets) {
            foreach ($facets as $facet) {
                $query->addFacet($facet);
            }
        }

        return $query;
    }

    /**
     * Parses query string.
     *
     * @param string $querySting
     * @return \im\search\components\query\SearchQueryInterface
     */
    public function parseQuery($querySting)
    {
        $query = $this->queryParser->parse($querySting);

        return $query;
    }
}