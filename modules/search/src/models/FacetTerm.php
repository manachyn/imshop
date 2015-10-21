<?php

namespace im\search\models;

use im\search\components\query\facet\FacetInterface;
use im\search\components\query\facet\FacetValueInterface;
use im\search\components\query\facet\FacetValueTrait;
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
 * @property Facet $facetRelation
 */
class FacetTerm extends ActiveRecord implements FacetValueInterface
{
    use FacetValueTrait;

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
    public function getLabel()
    {
        return $this->display ?: $this->getKey();
    }

//    /**
//     * @inheritdoc
//     */
//    public function createUrl($route = null, $scheme = false)
//    {
//        if (!$route) {
//            $currentParams = Yii::$app->getRequest()->getQueryParams();
//            $currentParams[0] = '/' . Yii::$app->controller->getRoute();
//            $route = ArrayHelper::merge($currentParams, []);
//        }
//        if ($searchQuery = $this->getSearchQuery()) {
//            $route['query'] = $searchComponent->queryConverter->toString($searchQuery);
//        }
//    }
}
