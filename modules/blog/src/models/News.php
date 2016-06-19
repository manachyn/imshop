<?php

namespace im\blog\models;

use im\blog\Module;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%news}}".
 * @property string $announce
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
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['announce'], 'string', 'max' => 255]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'announce' => Module::t('news', 'Announce')
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
        return Url::to(['/blog/news/view', 'path' => $this->slug], $scheme);
    }

    /**
     * Get last news.
     *
     * @param int $count
     * @return News[]
     */
    public static function getLastNews($count = 10)
    {
        return static::find()->published()->orderBy(['created_at' => SORT_DESC])->limit($count)->all();
    }
}
