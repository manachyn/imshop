<?php

namespace im\blog\models;

use im\filesystem\models\DbFile;

/**
 * Article file model class.
 */
class ArticleFile extends DbFile
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_files}}';
    }
}
