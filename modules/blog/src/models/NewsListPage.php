<?php

namespace im\blog\models;

use im\cms\components\ModelsListPageInterface;
use im\cms\models\Page;

/**
 * Class NewsListPage
 * @package im\blog\models
 */
class NewsListPage extends Page implements ModelsListPageInterface
{
    const TYPE = 'news_list_page';

    /**
     * @inheritdoc
     */
    public function getModelClass()
    {
        return News::class;
    }
}
