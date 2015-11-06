<?php

namespace im\search\models;

use im\search\components\query\facet\FacetInterface;
use im\search\components\query\facet\FacetValueInterface;
use im\search\components\query\facet\FacetValueTrait;
use im\search\Module;
use Yii;
use yii\db\ActiveRecord;

/**
 * Facet range model class.
 *
 * @property integer $id
 * @property string $type
 * @property integer $facet_id
 * @property string $display
 * @property integer $sort
 *
 * @property Facet $facetRelation
 */
class FacetValue extends ActiveRecord implements FacetValueInterface
{
    use FacetValueTrait;

    const TYPE = 'facet_term';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->type = static::TYPE;
    }

    /**
     * @inheritdoc
     */
    public static function instantiate($row)
    {
        return Yii::$app->get('searchManager')->getFacetValueInstance($row['type']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%facet_values}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'facet_id'], 'required'],
            [['facet_id', 'sort'], 'integer'],
            [['display'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('facet-value', 'ID'),
            'facet_id' => Module::t('facet-value', 'Facet ID'),
            'display' => Module::t('facet-value', 'Display'),
            'sort' => Module::t('facet-value', 'Sort')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFacetRelation()
    {
        return $this->hasOne(Facet::className(), ['id' => 'facet_id']);
    }

    /**
     * @inheritdoc
     */
    public function getFacet()
    {
        return $this->facetRelation;
    }

    /**
     * @inheritdoc
     */
    public function setFacet(FacetInterface $facet)
    {
        /** @var Facet $facet */
        $this->facet_id = $facet->id;
        $this->populateRelation('facetRelation', $facet);
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return $this->display ?: $this->getKey();
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (!$this->type) {
            $this->type = static::TYPE;
        }

        return parent::beforeSave($insert);
    }
}
