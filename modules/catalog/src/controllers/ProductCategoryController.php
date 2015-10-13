<?php

namespace im\catalog\controllers;

use im\catalog\models\ProductCategory;
use im\search\components\query\QueryResultInterface;
use im\search\components\search\SearchDataProvider;
use im\search\components\search\SearchResultContextInterface;
use im\search\models\FacetSet;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class ProductCategoryController extends CategoryController implements SearchResultContextInterface
{
    /**
     * @var QueryResultInterface
     */
    private $_searchResult;

    /**
     * Displays product category page.
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

    /**
     * Finds the Category model based on its path.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $path
     * @throws \yii\web\NotFoundHttpException
     * @return ProductCategory the loaded model
     */
    protected function findModel($path)
    {
        if (($model = ProductCategory::findByPath($path)->active()->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}