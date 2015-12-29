<?php

namespace im\search\controllers;

use im\base\context\ModelContextInterface;
use im\search\components\query\QueryResultInterface;
use im\search\components\search\SearchResultContextInterface;
use im\search\models\SearchPage;
use Yii;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Request;

/**
 * Class SearchPageController
 * @package im\search\controllers
 */
class SearchPageController extends Controller implements ModelContextInterface, SearchResultContextInterface
{
    /**
     * @var SearchPage
     */
    private $_model;

    /**
     * @var QueryResultInterface
     */
    private $_searchResult;

    /**
     * @var \im\search\components\search\SearchComponent
     */
    private $_searchComponent;

    /**
     * @var \im\search\components\SearchManager
     */
    private $_searchManager;

    /**
     * Displays search page.
     *
     * @param string $path
     * @param Request $request
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($path, Request $request)
    {
        $model = $this->findModel($path);
        $this->setModel($model);
        $searchComponent = $this->getSearchComponent();
        $searchableType = $this->getSearchManager()->getSearchableType('page');
        $searchQuery = $request->get('query', null);
        $dataProvider = $searchComponent->getSearchDataProvider($searchableType, $searchQuery, [], $model);
        $dataProvider->prepare();
        $this->_searchResult = $dataProvider->query->result();
        $searchResultsView = $searchableType->getSearchResultsView();
        $searchResultsView = $searchResultsView ?: 'view';

        return $this->render($searchResultsView, [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }


//    public function actionView($path, Request $request)
//    {
//        $model = $this->findModel($path);
//        /** @var \im\search\components\SearchManager $searchManager */
//        $searchManager = Yii::$app->get('searchManager');
//        $query = $request->get('query', '');
//        $searchComponent = $searchManager->getSearchComponent();
//        /** @var FacetSet $facetSet */
//        $facetSet = FacetSet::findOne(1);
//        $facets = $facetSet->facets;
//        $query = $searchComponent->getQuery('product', $query, $facets);
//        $dataProvider = new SearchDataProvider([
//            'query' => $query
//        ]);
//        $dataProvider->prepare();
//        $this->_searchResult = $dataProvider->query->result();
//
//        return $this->render('view', [
//            'model' => $model,
//            'dataProvider' => $dataProvider
//        ]);
//    }

    /**
     * @inheritdoc
     */
    public function getResult()
    {
        return $this->_searchResult;
    }

    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return $this->_model;
    }

    /**
     * @inheritdoc
     */
    public function setModel(Model $model)
    {
        $this->_model = $model;
    }

    protected function findModel($path)
    {
        if (($model = SearchPage::findByPath($path)->published()->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * Returns search manager.
     *
     * @return \im\search\components\SearchManager
     */
    protected function getSearchManager()
    {
        if (!$this->_searchManager) {
            $this->_searchManager = Yii::$app->get('searchManager');
        }

        return $this->_searchManager;
    }

    /**
     * Returns search component.
     *
     * @return \im\search\components\search\SearchComponent
     */
    protected function getSearchComponent()
    {
        if (!$this->_searchComponent) {
            $this->_searchComponent = $this->getSearchManager()->getSearchComponent();
        }

        return $this->_searchComponent;
    }
}