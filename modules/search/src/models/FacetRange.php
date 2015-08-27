<?php

namespace im\search\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Facet range model class.
 *
 * @property integer $id
 * @property integer $facet_id
 * @property string $from
 * @property string $to
 * @property integer $from_include
 * @property integer $to_include
 * @property string $display
 * @property integer $sort
 *
 * @property Facet $facet
 */
class FacetRange extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%facet_ranges}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['facet_id', 'from'], 'required'],
            [['facet_id', 'from_include', 'to_include'], 'integer'],
            [['from', 'to', 'display'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'facet_id' => Yii::t('app', 'Facet ID'),
            'from' => Yii::t('app', 'From'),
            'to' => Yii::t('app', 'To'),
            'from_include' => Yii::t('app', 'Include from value'),
            'to_include' => Yii::t('app', 'Include to value'),
            'display' => Yii::t('app', 'Display')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFacet()
    {
        return $this->hasOne(Facet::className(), ['id' => 'facet_id']);
    }
}
