<?php

namespace im\catalog\models;

use im\filesystem\models\DbFile;

/**
 * Category file model class.
 *
 * @property integer $category_id
 * @property string $attribute
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['category_id', 'sort'], 'integer'],
            [['attribute'], 'string', 'max' => 100]
        ]);
    }
}