<?php

namespace im\catalog\models;

use im\filesystem\models\DbFile;

class ProductFile extends DbFile
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_files}}';
    }
}