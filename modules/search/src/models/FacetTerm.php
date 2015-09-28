<?php

namespace im\search\models;

use im\search\components\query\FacetValueInterface;
use Yii;
use yii\db\ActiveRecord;

/**
 * Facet range model class.
 *
 * @property integer $id
 * @property integer $facet_id
 * @property string $term
 * @property string $display
 * @property integer $sort
 *
 * @property Facet $facet
 */
class FacetTerm extends ActiveRecord implements FacetValueInterface
{
    /**
     * @var int
     */
    private $_resultsCount = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%facet_terms}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['facet_id', 'term'], 'required'],
            [['facet_id', 'sort'], 'integer'],
            [['term', 'display'], 'string', 'max' => 255]
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
            'term' => Yii::t('app', 'Term'),
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
    public function getKey()
    {
        return $this->term;
    }

    /**
     * @inheritdoc
     */
    public function setKey($key)
    {
        $this->term = $key;
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
