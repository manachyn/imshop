<?php

namespace im\catalog\models;

use im\filesystem\models\DbFile;

/**
 * Product file model class.
 *
 * @property integer $product_id
 * @property string $attribute
 */
class ProductFile extends DbFile
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_files}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['product_id', 'sort'], 'integer'],
            [['attribute'], 'string', 'max' => 100]
        ]);
    }
}