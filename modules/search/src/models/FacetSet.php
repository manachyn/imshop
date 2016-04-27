<?php

namespace im\search\models;

use im\base\behaviors\RelationsBehavior;
use im\search\Module;
use yii\db\ActiveRecord;

/**
 * Facet set model class.
 *
 * @property integer $id
 * @property string $name
 *
 * @property Facet[] $facets
 */
class FacetSet extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%facet_sets}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'relations' => RelationsBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['facets'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('facetSet', 'ID'),
            'name' => Module::t('facetSet', 'Name')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFacetsRelation()
    {
        return $this->hasMany(Facet::className(), ['id' => 'facet_id'])
            ->viaTable('{{%facet_set_facets}}', ['set_id' => 'id']);
    }
}
