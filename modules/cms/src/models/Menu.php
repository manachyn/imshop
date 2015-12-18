<?php

namespace im\cms\models;

use im\catalog\Module;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Brand model class.
 *
 * @property integer $id
 * @property string $name
 * @property string $location
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property MenuItem[] $items
 */
class Menu extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menus}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['location'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('menu', 'ID'),
            'name' => Module::t('menu', 'Name'),
            'location' => Module::t('menu', 'Location'),
            'created_at' => Module::t('brand', 'Created At'),
            'updated_at' => Module::t('brand', 'Updated At')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(MenuItem::className(), ['menu_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function getLocationsList()
    {
        /** @var \im\cms\components\LayoutManager $layoutManager */
        $layoutManager = Yii::$app->get('layoutManager');

        return ArrayHelper::map($layoutManager->getMenuLocations(), 'code', 'name');
    }
}
