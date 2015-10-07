<?php

namespace im\search\components\search;

use im\search\components\query\Boolean;
use im\search\components\query\BooleanQueryInterface;
use im\search\components\query\facet\FacetInterface;
use im\search\components\query\facet\FacetValueInterface;
use im\search\components\query\FieldQueryInterface;
use im\search\components\query\parser\QueryParser;
use im\search\components\query\parser\QueryParserInterface;
use im\search\components\query\Range;
use im\search\components\query\RangeInterface;
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
     * @var QueryParserInterface
     */
    public $queryParser = [
        'class' => 'im\search\components\query\parser\QueryParser',
        'higherPriorityOperator' => QueryParser::OPERATOR_OR
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (!$this->queryParser instanceof QueryParserInterface) {
            $this->queryParser = Yii::createObject($this->queryParser);
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
        //$querySting = 'title=one OR two&date=[10 to 20]&test>100';
        $query = $this->queryParser->parse($querySting);
        $this->_serchQuery = $query;
        return $query;
    }

    /**
     * Creates facet value url in the context of current search query.
     *
     * @param FacetValueInterface $facetValue
     * @param FacetInterface $facet
     * @param SearchQueryInterface $query
     * @return string
     */
    public function createFacetValueUrl(FacetValueInterface $facetValue, FacetInterface $facet, SearchQueryInterface $query = null)
    {
        $query = $this->_serchQuery;
        $url = '';
        if ($facetValue instanceof FacetTerm) {
            $facetValueQuery = new Term($facet->getName(), $facetValue->getKey());
            if ($query) {
                $multivalue = $facet->isMultivalue();
                $sign = $facet->getOperator() === Facet::OPERATOR_AND ? true : null;
                $this->combineSearchQueries($query, $facetValueQuery, $multivalue, $sign);
            }

            $url = Url::to(['/search/search-page/index', 'path' => 'search-results', 'query' => $this->queryParser->toString($query)]);
            $a = 1;
        }
//        if ($facetValue instanceof RangeInterface) {
//            $query = new Range(
//                $facet->getField(),
//                $facetValue->getLowerBound(),
//                $facetValue->getUpperBound(),
//                $facetValue->isIncludeLowerBound(),
//                $facetValue->isIncludeUpperBound()
//            );
//            $url = Url::to(['/search/search-page/index', 'path' => 'search-results', 'query' => $this->queryParser->toString($query)]);
//            $a = 1;
//        }

        return $url;
    }

    protected function combineSearchQueries(SearchQueryInterface $query1, FieldQueryInterface $query2, $multivalue = false, $sign = true)
    {
        if ($query1 instanceof BooleanQueryInterface) {
            $signs = $query1->getSigns();
            $toAdd = true;
            foreach ($query1->getSubQueries() as $key => $subQuery) {
                if ($subQuery instanceof Boolean) {
                    $toAdd = false;
                    break;
                } elseif ($query2->equals($subQuery) !== 0 || $signs[$key] !== $sign) {
                    $toAdd = false;
                    break;
                } else {
                    $a = 1;
                }
            }
            if ($toAdd) {
                $query1->addSubQuery($query2, $sign);
                return true;
            } else {
                return false;
            }
        }
        return $query1;
    }
}