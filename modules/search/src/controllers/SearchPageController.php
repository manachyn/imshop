<?php

namespace im\search\controllers;

use im\base\context\ModelContextInterface;
use im\catalog\models\CategoriesFacetValue;
use im\search\components\query\QueryResultInterface;
use im\search\components\search\SearchResultContextInterface;
use im\search\components\searchable\SearchableInterface;
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
     * @var \im\search\components\SearchManager
     */
    private $_searchManager;

    /**
     * Displays search page.
     *
     * @param string $path
     * @param string $type
     * @param string $query
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($path, $type = null, $query = '')
    {
        $model = $this->findModel($path);
        $this->setModel($model);
        $searchManager = $this->getSearchManager();
        $searchableType = $type ? $searchManager->getSearchableType($type) : $searchManager->getDefaultSearchableType();
        $searchComponent = $this->getSearchComponent($searchableType);
        $params = [];
        if ($searchableType->getType() == 'product') {
            $params['categoriesFacetValueRouteParams'] = function (CategoriesFacetValue $value) {
                return ['path' => $value->getEntity()->slug];
            };
        }
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
            'dataProvider' => $dataProvider,
            'searchableType' => $searchableType
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


    /**
     * @param string $path
     * @return SearchPage
     * @throws NotFoundHttpException
     */
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
     * @param SearchableInterface $searchableType
     * @return \im\search\components\search\SearchComponent
     */
    protected function getSearchComponent(SearchableInterface $searchableType = null)
    {
        return $searchableType && $searchableType->getType() == 'product' ? Yii::$app->get('categorySearch')
            : $this->getSearchManager()->getSearchComponent();
    }
}