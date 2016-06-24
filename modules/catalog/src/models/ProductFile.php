<?php

namespace im\catalog\models;

use im\catalog\Module;
use im\filesystem\models\DbFile;

/**
 * Product file model class.
 *
 * @property integer $product_id
 * @property string $attribute
 * @property int type
 */
class ProductFile extends DbFile
{
    const TYPE_COVER = 1;

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
            [['attribute'], 'string', 'max' => 100],
            [['type'], 'safe']
        ]);
    }

    /**
     * Get types list.
     *
     * @return array
     */
    public static function getTypesList()
    {
        return [
            self::TYPE_COVER => Module::t('product-file', 'Cover')
        ];
    }
}