<?php

namespace im\catalog\models;

use im\catalog\Module;
use yii\db\ActiveRecord;

/**
 * Manufacturer model class.
 *
 * @property int $id
 * @property string $name
 * @property Product[] $products
 * @package im\catalog\models
 * @author Ivan Manachyn <manachyn@gmail.com>
 */
class Manufacturer extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%manufacturers}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('product/manufacturer', 'ID'),
            'name' => Module::t('product/manufacturer', 'Name')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['manufacturers_id' => 'id']);
    }
}