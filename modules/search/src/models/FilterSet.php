<?php

namespace im\search\models;

use im\search\backend\Module;
use yii\db\ActiveRecord;

/**
 * Filters set model class.
 *
 * @property integer $id
 * @property string $name
 *
 * @property Filter[] $filters
 */
class FilterSet extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%filter_sets}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('filterSet', 'ID'),
            'name' => Module::t('filterSet', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFilters()
    {
        return $this->hasMany(Filter::className(), ['id' => 'filter_id'])
            ->viaTable('filter_set_filters', ['set_id' => 'id']);
    }
}
