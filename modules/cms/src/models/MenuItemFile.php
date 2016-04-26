<?php

namespace im\cms\models;

use im\filesystem\models\DbFile;

/**
 * Menu item file model class.
 *
 * @property integer $menu_item_id
 * @property string $attribute
 */
class MenuItemFile extends DbFile
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu_item_files}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['menu_item_id'], 'integer'],
            [['attribute'], 'string', 'max' => 100]
        ]);
    }
}