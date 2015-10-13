<?php

namespace im\search\controllers;

use im\search\components\query\QueryResultInterface;
use im\search\components\search\SearchDataProvider;
use im\search\components\search\SearchResultContextInterface;
use im\search\models\FacetSet;
use im\search\models\SearchPage;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class SearchPageController extends Controller implements SearchResultContextInterface
{
    /**
     * @var QueryResultInterface
     */
    private $_searchResult;


    public function actionView($path, Request $request)
    {
        $model = $this->findModel($path);
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = Yii::$app->get('searchManager');
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

        return $this->render('view', [
            'model' => $model,
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


    protected function findModel($path)
    {
        if (($model = SearchPage::findByPath($path)->published()->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}