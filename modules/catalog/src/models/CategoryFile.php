<?php

namespace im\catalog\models;

use im\filesystem\models\DbFile;

/**
 * Category file model class.
 */
class CategoryFile extends DbFile
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_files}}';
    }
}