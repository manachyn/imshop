<?php

namespace im\search\models;

use im\search\components\query\RangeInterface;
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
class FacetRange extends ActiveRecord implements RangeInterface
{
    /**
     * @var string
     */
    private $_key;

    /**
     * @var int
     */
    private $_resultsCount = 0;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->from_include = true;
    }

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
            [['facet_id'], 'required'],
            [['facet_id', 'from_include', 'to_include', 'sort'], 'integer'],
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
            'display' => Yii::t('app', 'Display'),
            'sort' => Yii::t('app', 'Sort')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFacet()
    {
        return $this->hasOne(Facet::className(), ['id' => 'facet_id']);
    }

    /**
     * @inheritdoc
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @inheritdoc
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @inheritdoc
     */
    public function getKey()
    {
        return $this->_key ?: (($this->from ?: '*') . '-' . ($this->to ?: '*'));
    }

    /**
     * @inheritdoc
     */
    public function setKey($key)
    {
        $this->_key = $key;
    }

    /**
     * @inheritdoc
     */
    public function getResultsCount()
    {
        return $this->_resultsCount;
    }

    /**
     * @inheritdoc
     */
    public function setResultsCount($count)
    {
        $this->_resultsCount = $count;
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return $this->display ?: $this->getKey();
    }
}
