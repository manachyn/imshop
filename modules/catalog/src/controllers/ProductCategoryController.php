<?php

namespace im\catalog\controllers;

use im\catalog\models\CategoriesFacetValue;
use im\catalog\models\ProductCategory;
use im\search\components\query\QueryResultInterface;
use im\search\components\search\SearchResultContextInterface;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class ProductCategoryController
 * @package im\catalog\controllers
 */
class ProductCategoryController extends CategoryController implements SearchResultContextInterface
{
    /**
     * @var QueryResultInterface
     */
    private $_searchResult;

    /**
     * @var \im\catalog\components\search\CategorySearchComponent
     */
    private $_categorySearchComponent;


    /**
     * Displays product category page.
     *
     * @param string $path
     * @param string $query
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($path, $query = '')
    {
        $model = $this->findModel($path);
        $this->setModel($model);
        $searchComponent = $this->getCategorySearchComponent();
        $searchableType = 'product';
        $params['categoriesFacetValueRouteParams'] = function (CategoriesFacetValue $value) {
            return ['path' => $value->getEntity()->slug];
        };
        $dataProvider = $searchComponent->getSearchDataProvider(
            $searchableType,
            $query,
            $searchComponent->getFacets($model),
            $model,
            $params
        );
        $dataProvider->prepare();
        $this->_searchResult = $dataProvider->query->result();

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
     * Find category model based on its path.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $path
     * @throws NotFoundHttpException
     * @return ProductCategory
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
