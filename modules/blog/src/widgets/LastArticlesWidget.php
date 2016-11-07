<?php

namespace im\blog\widgets;

use im\blog\models\Article;
use im\blog\Module;
use im\cms\models\widgets\Widget;

/**
 * Class LastArticlesWidget
 * @package im\blog\widgets
 * @property int $display_count
 * @property int $category_id
 * @property int $list_url
 * @property int $columns
 * @property int $template
 */
class LastArticlesWidget extends Widget
{
    const TYPE = 'last_articles';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['display_count'], 'number'],
            [['category_id'], 'integer'],
            [['list_url'], 'safe'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'display_count' => Module::t('last-articles-widget', 'Articles count to display'),
            'category_id' => Module::t('last-articles-widget', 'Articles category'),
            'list_url' => Module::t('last-articles-widget', 'All articles url'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getCMSTitle()
    {
        return Module::t('last_articles_widget', 'Last articles widget');
    }

    /**
     * @inheritdoc
     */
    public function getCMSDescription()
    {
        return Module::t('last_articles_widget', 'Widget for displaying last articles on the page.');
    }

    /**
     * @inheritdoc
     */
    public function getEditView()
    {
        return '@im/blog/backend/views/widgets/last-articles-widget/_form';
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('last_articles', [
            'widget' => $this,
            'articles' => Article::getLastArticles($this->display_count, $this->category_id)
        ]);
    }
}
