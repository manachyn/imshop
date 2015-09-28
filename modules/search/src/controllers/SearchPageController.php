<?php

namespace im\search\controllers;

use im\search\components\query\QueryResultInterface;
use im\search\components\SearchDataProvider;
use im\search\components\SearchResultContextInterface;
use im\search\models\FacetSet;
use Yii;
use yii\web\Controller;

class SearchPageController extends Controller implements SearchResultContextInterface
{
    /**
     * @var QueryResultInterface
     */
    private $_searchResult;

    public function actionIndex($path)
    {
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = Yii::$app->get('search');
        $queryParams = Yii::$app->getRequest()->getQueryParams();
        if (isset($queryParams['params'])) {
            $params = $queryParams['params'];
        } else {
            $params = $queryParams;
            unset($params['path']);
        }
        $searchComponent = $searchManager->getSearchComponent();
        $params = $searchComponent->parseQueryParams($params);
        /** @var FacetSet $facetSet */
        $facetSet = FacetSet::findOne(1);
        $facets = $facetSet->facets;
        $query = $searchComponent->getQuery('product', $params, $facets);
        $dataProvider = new SearchDataProvider([
            'query' => $query
        ]);
        $dataProvider->prepare();
        $this->_searchResult = $dataProvider->query->result();

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