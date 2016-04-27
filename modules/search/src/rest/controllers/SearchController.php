<?php

namespace im\search\rest\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Request;

/**
 * Class SearchController
 * @package im\catalog\rest\controllers
 */
class SearchController extends Controller
{
    /**
     * @return array
     */
    public function actionIndex()
    {
        //Yii::$app->request->post()
        $type = '';
        $searchManager = $this->getSearchManager();
        $searchableType = $type ? $searchManager->getSearchableType($type) : $searchManager->getDefaultSearchableType();
        $dataProvider = $searchManager->getSearchComponent()->getSearchDataProvider($searchableType);
        //$query = $searchManager->getSearchComponent()->getQuery($searchableType);
        //$result = $query->result();

        return $dataProvider;
    }

    /**
     * @param string $text
     * @param string $type
     * @return array
     */
    public function actionSuggest($text)
    {
        $type = '';
        $searchManager = $this->getSearchManager();
        $searchableType = $type ? $searchManager->getSearchableType($type) : $searchManager->getDefaultSearchableType();
        $query = $searchManager->getSearchComponent()->getSuggestionsQuery($text, $searchableType);
        if ($query) {
            $result = $query->result();
            return $result->getSuggestions();
        } else {
            return [];
        }
    }

    /**
     * Returns search manager.
     *
     * @return \im\search\components\SearchManager
     */
    protected function getSearchManager()
    {
        return Yii::$app->get('searchManager');
    }
} 