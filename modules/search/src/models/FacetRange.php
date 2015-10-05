<?php

namespace im\search\models;

use im\search\components\query\facet\FacetValueInterface;
use im\search\components\query\RangeInterface;
use Yii;
use yii\db\ActiveRecord;

/**
 * Facet range model class.
 *
 * @property integer $id
 * @property integer $facet_id
 * @property string $lower_bound
 * @property string $upper_bound
 * @property integer $include_lower_bound
 * @property integer $include_upper_bound
 * @property string $display
 * @property integer $sort
 *
 * @property Facet $facet
 */
class FacetRange extends ActiveRecord implements RangeInterface, FacetValueInterface
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
        $this->include_lower_bound = true;
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
            [['facet_id', 'include_lower_bound', 'include_upper_bound', 'sort'], 'integer'],
            [['lower_bound', 'upper_bound', 'display'], 'string', 'max' => 255]
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
            'lower_bound' => Yii::t('app', 'From'),
            'upper_bound' => Yii::t('app', 'To'),
            'include_lower_bound' => Yii::t('app', 'Include lower bound'),
            'include_upper_bound' => Yii::t('app', 'Include upper bound'),
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
    public function getLowerBound()
    {
        return $this->lower_bound;
    }

    /**
     * @inheritdoc
     */
    public function getUpperBound()
    {
        return $this->upper_bound;
    }

    /**
     * @inheritdoc
     */
    public function isIncludeLowerBound()
    {
        return (bool) $this->include_lower_bound;
    }

    /**
     * @inheritdoc
     */
    public function isIncludeUpperBound()
    {
        return (bool) $this->include_upper_bound;
    }

    /**
     * @inheritdoc
     */
    public function getKey()
    {
        return $this->_key ?: (($this->lower_bound ?: '*') . '-' . ($this->upper_bound ?: '*'));
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
