<?php

namespace im\blog\widgets;

use im\blog\models\News;
use im\blog\Module;
use im\cms\models\widgets\Widget;

/**
 * Class LastNewsWidget
 * @package im\blog\models\widgets
 * @property int $display_count
 */
class LastNewsWidget extends Widget
{
    const TYPE = 'last_news';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['display_count'], 'number']
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'news_count' => Module::t('last_news_widget', 'News count to display')
        ];
    }

    /**
     * @inheritdoc
     */
    public function getCMSTitle()
    {
        return Module::t('last_news_widget', 'Last news widget');
    }

    /**
     * @inheritdoc
     */
    public function getCMSDescription()
    {
        return Module::t('last_news_widget', 'Widget for displaying last news on the page.');
    }

    /**
     * @inheritdoc
     */
    public function getEditView()
    {
        return '@im/blog/backend/views/widgets/last-news-widget/_form';
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('last_news', [
            'widget' => $this,
            'news' => News::getLastNews($this->display_count)
        ]);
    }
}
