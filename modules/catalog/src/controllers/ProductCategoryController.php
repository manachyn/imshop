<?php

namespace im\catalog\controllers;

use im\catalog\models\Product;
use im\catalog\models\ProductCategoriesFacet;
use im\catalog\models\ProductCategory;
use im\search\components\index\IndexableInterface;
use im\search\components\query\facet\FacetValueInterface;
use im\search\components\query\QueryResultInterface;
use im\search\components\query\SearchQueryHelper;
use im\search\components\query\Term;
use im\search\components\search\SearchDataProvider;
use im\search\components\search\SearchResultContextInterface;
use im\search\models\FacetSet;
use Yii;
use yii\helpers\ArrayHelper;
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

        $searchableType = $searchManager->getSearchableTypeByClass(Product::className());
        if ($searchableType instanceof IndexableInterface) {
            $mapping = $searchableType->getIndexMapping();
            foreach ($mapping as $name => $attribute) {
                $nameParts = explode('.', $name);
                if (count($nameParts) == 2 && $nameParts[0] == 'categoriesRelation') {
                    $categoryQuery = new Term($attribute->name, $model->{$nameParts[1]});
                    break;
                }
            }
        } else {
            $categoryQuery = new Term('categoriesRelation.id', $model->id);
        }

        if ($query) {
            $searchQuery = SearchQueryHelper::includeQuery($searchComponent->parseQuery($query), $categoryQuery);
        } else {
            $searchQuery = $categoryQuery;
        }
        $query = $searchComponent->getQuery('product', $searchQuery, $facets);
        $dataProvider = new SearchDataProvider([
            'query' => $query
        ]);
        $dataProvider->prepare();
        $this->_searchResult = $dataProvider->query->result();
        $query->setSearchQuery(SearchQueryHelper::excludeQuery($query->getSearchQuery(), $categoryQuery));
        $facets = $this->_searchResult->getFacets();
        foreach ($facets as $facet) {
            if ($facet instanceof ProductCategoriesFacet) {
                $values = $facet->getValues();
                foreach ($values as $key => $value) {
                    if ($model->equals($value->getEntity())) {
                        unset($values[$key]);
                        continue;
                    }
                    $value->setRouteParams(['path' => $value->getEntity()->slug]);
                }
                $facet->setValues($values);
            }
        }

        return $this->render('view', [
            'model' => $model,
            'productsDataProvider' => $dataProvider
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
     * FiCategory ategory model based on its path.
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