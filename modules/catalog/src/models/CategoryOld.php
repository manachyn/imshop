<?php

namespace im\catalog\models;

use kartik\tree\models\Tree;
use Yii;

/**
 * This is the model class for table "{{%categories}}".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Products[] $products
 */
class CategoryOld extends Tree
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%categories}}';
    }

//    /**
//     * @inheritdoc
//     */
//    public function rules()
//    {
//        return [
//            [['lft', 'rgt', 'depth', 'name'], 'required'],
//            [['lft', 'rgt', 'depth'], 'integer'],
//            [['name'], 'string', 'max' => 255]
//        ];
//    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'lft' => Yii::t('app', 'Lft'),
            'rgt' => Yii::t('app', 'Rgt'),
            'depth' => Yii::t('app', 'Depth'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('{{%product_category}}', ['category_id' => 'id']);
    }

//    /**
//     * Override isDisabled method if you need as shown in the
//     * example below. You can override similarly other methods
//     * like isActive, isMovable etc.
//     */
//    public function isDisabled()
//    {
//        if (Yii::$app->user->id !== 'admin') {
//            return true;
//        }
//        return parent::isDisabled();
//    }
}
