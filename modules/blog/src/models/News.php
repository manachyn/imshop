<?php

namespace im\blog\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%news}}".
 */
class News extends Article
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%news}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'files' => [
                'attributes' => [
                    'uploadedImage' => [
                        'path' => '/news'
                    ],
                    'uploadedVideo' => [
                        'path' => '/news'
                    ]
                ]
            ]
        ]);
    }

    /**
     * Returns url.
     *
     * @param boolean|string $scheme the URI scheme to use in the generated URL
     * @return string
     */
    public function getUrl($scheme = false)
    {
//        /** @var \im\cms\components\PageFinder $finder */
//        $finder = Yii::$app->get('pageFinder');
//        $newsPage = $finder->findModel(['type' => NewsListPage::TYPE, 'status' => NewsListPage::STATUS_PUBLISHED]);
//
//        return $newsPage ? Url::to(['/cms/page/view', 'path' => $newsPage->getUrl() . '/' . $this->slug], $scheme) : '';

        return Url::to(['/cms/page/view', 'path' => $this->slug], $scheme);
    }

    /**
     * Get last news.
     *
     * @param int $count
     * @param int $category
     * @return News[]
     */
    public static function getLastNews($count = 10, $category = null)
    {
        $query = static::find()->published()->orderBy(['created_at' => SORT_DESC])->limit($count);
        if ($category) {
            $query->innerJoin(
                '{{%news_categories_pivot}}',
                '{{%news}}.id = {{%news_categories_pivot}}.news_id AND {{%news_categories_pivot}}.category_id = :category_id', ['category_id' => $category]);
        }

        return $query->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesRelation()
    {
        return $this->hasMany(NewsCategory::className(), ['id' => 'category_id'])
            ->viaTable('{{%news_categories_pivot}}', ['news_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public static function getViewRoute()
    {
        return 'blog/news/view';
    }
}
