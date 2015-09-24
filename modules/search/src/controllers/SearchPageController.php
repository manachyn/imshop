<?php

namespace im\search\controllers;

use im\search\models\FacetSet;
use Yii;
use yii\web\Controller;

class SearchPageController extends Controller
{
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
        $query = $searchComponent->getQuery('product', $params, $facets)->limit(100);
        $result = $query->result();
        $facets = $result->getFacets();

        return $this->render('index');
    }
}