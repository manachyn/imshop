<?php

namespace im\catalog\controllers;

use im\catalog\models\CategoriesFacet;
use im\catalog\models\CategoriesFacetValue;
use im\catalog\models\Product;
use im\catalog\models\ProductCategoriesFacet;
use im\catalog\models\ProductCategoriesFacetValue;
use im\catalog\models\ProductCategory;
use im\search\components\index\IndexableInterface;
use im\search\components\query\FieldQueryInterface;
use im\search\components\query\QueryResultInterface;
use im\search\components\query\SearchQueryHelper;
use im\search\components\query\Term;
use im\search\components\search\SearchDataProvider;
use im\search\components\search\SearchResultContextInterface;
use im\search\components\SearchManager;
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
     * @var SearchManager
     */
    private $_searchManager;

    /**
     * @var \im\catalog\components\search\CategorySearchComponent
     */
    private $_categorySearchComponent;

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
        $categorySearchComponent = $this->getCategorySearchComponent();
        $searchableType = 'product';
        $searchQuery = $request->get('query', null);
        $params['categoriesFacetValueRouteParams'] = function (CategoriesFacetValue $value) {
            return ['path' => $value->getEntity()->slug];
        };
        $dataProvider = $categorySearchComponent->getSearchDataProvider($searchableType, $model, $searchQuery, $params);
        $dataProvider->prepare();
        $this->_searchResult = $dataProvider->query->result();

        return $this->render('view', [
            'model' => $model,
            'productsDataProvider' => $dataProvider
        ]);
    }

//    /**
//     * Displays product category page.
//     *
//     * @param string $path
//     * @param Request $request
//     * @return string
//     * @throws NotFoundHttpException
//     */
//    public function actionView($path, Request $request)
//    {
//        $model = $this->findModel($path);
//        $this->setModel($model);
//        $searchManager = $this->getSearchManager();
//        $searchComponent = $searchManager->getSearchComponent();
//        $queryParam = $request->get('query', '');
//
//        // Get model facets
//        /** @var FacetSet $facetSet */
//        $facetSet = FacetSet::findOne(1);
//        $facets = $facetSet->facets;
//
//        //text:"test1 test2"~10^100 OR test3
//        //$searchQuery = \ZendSearch\Lucene\Search\QueryParser::parse($queryParam);
//        //http://imshop.loc/laptops/status=1%20or%20title=Test
//
//        $searchQuery = null;
//        // Parse search query
//        if ($queryParam) {
//            $searchQuery = $searchComponent->parseQuery($queryParam);
//        }
//
//        // Add category query to search query
//        list($categoryQuery, $categoryParentsQuery) = $this->getSearchQueries($model);
//        if ($categoryQuery) {
//            $categoryQuery = $searchQuery ? SearchQueryHelper::includeQuery(clone $searchQuery, $categoryQuery) : $categoryQuery;
//            if ($categoryParentsQuery) {
//                $categoryParentsQuery = $searchQuery ? SearchQueryHelper::includeQuery(clone $searchQuery, $categoryParentsQuery) : $categoryParentsQuery;
//            }
//        }
//        // Add filter to facets
//        if (array_filter($facets, function ($facet) { return $facet instanceof CategoriesFacet; })) {
//            foreach ($facets as $facet) {
//                if ($facet instanceof CategoriesFacet) {
//                    $facet->setFilter($categoryParentsQuery ?: $searchQuery);
//                } else {
//                    $facet->setFilter($categoryQuery);
//                }
//            }
//        }
//
//        // Create query for data provider
//        $query = $searchComponent->getQuery('product', $categoryQuery ?: $searchQuery, $facets);
//        $dataProvider = new SearchDataProvider([
//            'query' => $query
//        ]);
//        $dataProvider->prepare();
//        $this->_searchResult = $dataProvider->query->result();
//        $query->setSearchQuery($searchQuery);
//
//        // Set context and route params for categories facets
//        $facets = $this->_searchResult->getFacets();
//        foreach ($facets as $facet) {
//            $facet->setContext($model);
//            if ($facet instanceof ProductCategoriesFacet) {
//                $facet->setValueRouteParams(function (ProductCategoriesFacetValue $value) {
//                    return ['path' => $value->getEntity()->slug];
//                });
//            }
//        }
//
//        return $this->render('view', [
//            'model' => $model,
//            'productsDataProvider' => $dataProvider
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

    /**
     * @param ProductCategory $model
     * @return FieldQueryInterface[]
     */
    protected function getSearchQueries(ProductCategory $model)
    {
        $categoryQuery = null;
        $categoryParentsQuery = null;
        $searchableType = $this->getSearchManager()->getSearchableTypeByClass(Product::className());
        if ($searchableType instanceof IndexableInterface) {
            $mapping = $searchableType->getIndexMapping();
            foreach ($mapping as $name => $attribute) {
                if ($name == 'all_categories') {
                    $categoryParentsQuery = new Term($attribute->name, $model->id);
                } else {
                    $nameParts = explode('.', $name);
                    if (count($nameParts) == 2 && $nameParts[0] == 'categories') {
                        $categoryQuery = new Term($attribute->name, $model->{$nameParts[1]});
                    }
                }
            }
        } else {
            $categoryQuery = new Term('categoriesRelation.id', $model->id);
        }

        return [$categoryQuery, $categoryParentsQuery];
    }

    /**
     * @return SearchManager
     */
    protected function getSearchManager()
    {
        if (!$this->_searchManager) {
            $this->_searchManager = Yii::$app->get('searchManager');
        }

        return $this->_searchManager;
    }

    /**
     * @return \im\catalog\components\search\CategorySearchComponent
     */
    protected function getCategorySearchComponent()
    {
        if (!$this->_categorySearchComponent) {
            $this->_categorySearchComponent = Yii::$app->get('categorySearch');
        }

        return $this->_categorySearchComponent;
    }

}