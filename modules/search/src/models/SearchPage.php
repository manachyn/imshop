<?php

namespace im\search\models;

use im\cms\models\Page;
use im\search\components\SearchBehavior;

/**
 * Class SearchPage
 * @package im\search\models
 */
class SearchPage extends Page
{
    const TYPE = 'search_page';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'search' => SearchBehavior::className()
        ];
    }
}