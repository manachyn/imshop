<?php

namespace im\search\components\search;

use im\search\components\query\Boolean;
use im\search\components\query\BooleanQueryInterface;
use im\search\components\query\facet\FacetInterface;
use im\search\components\query\facet\FacetValueInterface;
use im\search\components\query\FieldQueryInterface;
use im\search\components\query\parser\QueryConverterInterface;
use im\search\components\query\parser\QueryParser;
use im\search\components\query\parser\QueryParserInterface;
use im\search\components\query\SearchQueryHelper;
use im\search\components\query\SearchQueryInterface;
use im\search\components\query\Term;
use im\search\models\Facet;
use im\search\models\FacetTerm;
use Yii;
use yii\base\Component;
use yii\helpers\Url;

class SearchComponent extends Component
{
    /**
     * @var SearchQueryInterface
     */
    private $_serchQuery;

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
     * @param string $querySting
     * @param \im\search\components\query\facet\FacetInterface[] $facets
     * @return \im\search\components\query\QueryInterface
     */
    public function getQuery($type, $querySting, $facets = [])
    {
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = Yii::$app->get('search');
        $index = $searchManager->getIndexManager()->getIndexByType($type);
        $finder = $index->getSearchService()->getFinder();
        $query = $querySting ? $finder->findByQuery($this->parseQuery($querySting), $index->getName(), $type)
            : $finder->find($index->getName(), $type);
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