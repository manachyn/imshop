<?php

namespace im\search\components\search;

use im\search\components\query\parser\QueryParser;
use im\search\components\query\parser\QueryParserInterface;
use Yii;
use yii\base\Component;

class SearchComponent extends Component
{
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
        $query = $this->getQuery($type);
    }

    /**
     * Return search query.
     *
     * @param string $type
     * @param \im\search\components\SearchParam[] $params
     * @param \im\search\components\query\FacetInterface[] $facets
     * @return \im\search\components\query\QueryInterface
     */
    public function getQuery($type, $params = [], $facets = [])
    {
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = Yii::$app->get('search');
        $index = $searchManager->getIndexManager()->getIndexByType($type);
        $finder = $index->getSearchService()->getFinder();
        $query = $finder->find($index->getName(), $type);
        //$query->where(ArrayHelper::map($params, 'name', 'value'));
        $query->query = ['filtered' => ['filter' => ['term' => ['status' => 1]]]];
        if ($facets) {
            foreach ($facets as $facet) {
                $query->addFacet($facet);
            }
        }

        return $query;
    }

    /**
     * @param string $querySting
     * @return SearchParam[]
     */
    public function parseQuery($querySting)
    {

//        $query = 'title:one OR two AND three name:test';
//        Analyzer::setDefault(new CaseInsensitive());
//        $query = \ZendSearch\Lucene\Search\QueryParser::parse($query);
        $querySting = 'title=one OR two&date=[10 to 20]&test>100';
        $query = $this->queryParser->parse($querySting);
        $a = 1;
    }
}