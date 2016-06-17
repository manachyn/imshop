<?php

namespace im\blog\models;

use im\seo\models\Meta;

/**
 * Article meta model class.
 */
class ArticleMeta extends Meta
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_meta}}';
    }
}

