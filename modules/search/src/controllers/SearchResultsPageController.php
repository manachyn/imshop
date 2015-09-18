<?php

namespace im\search\controllers;

use Yii;
use yii\web\Controller;

class SearchResultsPageController extends Controller
{
    public function actionIndex()
    {
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = Yii::$app->get('search');
        $searchComponent = $searchManager->getSearchComponent();
        //$searchComponent->search('product')
        return $this->render('index');
    }
}