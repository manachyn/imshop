<?php

namespace im\blog\models;

use im\cms\components\ModelsListPageInterface;
use im\cms\models\Page;

/**
 * Class ArticlesListPage
 * @package im\blog\models
 */
class ArticlesListPage extends Page implements ModelsListPageInterface
{
    const TYPE = 'articles_list_page';

    /**
     * @inheritdoc
     */
    public function getModelClass()
    {
        return Article::class;
    }
}
