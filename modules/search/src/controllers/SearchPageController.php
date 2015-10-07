<?php

namespace im\search\controllers;

use im\search\components\query\QueryResultInterface;
use im\search\components\search\SearchDataProvider;
use im\search\components\search\SearchResultContextInterface;
use im\search\models\FacetSet;
use Yii;
use yii\web\Controller;
use yii\web\Request;

class SearchPageController extends Controller implements SearchResultContextInterface
{
    /**
     * @var QueryResultInterface
     */
    private $_searchResult;


    public function actionIndex($path, Request $request)
    {
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = Yii::$app->get('search');
        $query = $request->get('query', '');
        $searchComponent = $searchManager->getSearchComponent();
        /** @var FacetSet $facetSet */
        $facetSet = FacetSet::findOne(1);
        $facets = $facetSet->facets;
        $query = $searchComponent->getQuery('product', $query, $facets);
        $dataProvider = new SearchDataProvider([
            'query' => $query
        ]);
        $dataProvider->prepare();
        $this->_searchResult = $dataProvider->query->result();
        $facets = $this->_searchResult->getFacets();
        foreach ($facets as $facet) {
            if ($values = $facet->getValues()) {
                foreach ($values as $value) {
                    $url = $searchComponent->createFacetValueUrl($value, $facet);
                }
            }
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getResult()
    {
        return $this->_searchResult;
    }
}