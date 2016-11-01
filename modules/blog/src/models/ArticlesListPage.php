<?php

namespace im\blog\models;

use im\blog\Module;
use im\cms\components\ModelsListPageInterface;
use im\cms\models\Page;

/**
 * Class ArticlesListPage
 * @package im\blog\models
 * @property int $category_id
 */
class ArticlesListPage extends Page implements ModelsListPageInterface
{
    const TYPE = 'articles_list_page';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['category_id'], 'integer']
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'category_id' => Module::t('articles-list-page', 'Category')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getModelClass()
    {
        return Article::class;
    }

    /**
     * @inheritdoc
     */
    public static function getViewRoute()
    {
        return 'blog/articles-list-page/view';
    }
}
