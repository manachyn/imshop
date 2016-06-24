<?php

namespace im\search\widgets;

use im\search\components\SearchManager;
use Yii;
use yii\base\Widget;

/**
 * Class SearchWidget
 * @package im\search\widgets
 */
class SearchWidget extends Widget
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        /** @var SearchManager $searchManager */
        $searchManager = Yii::$app->get('searchManager');

        return $this->render('search', ['action' => $searchManager->getSearchPageUrl()]);
    }
}