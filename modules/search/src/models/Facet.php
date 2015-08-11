<?php

namespace im\search\models;

use im\search\backend\Module;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 * Facet model class.
 *
 * @property integer $id
 * @property string $name
 * @property string $type
 */
class Facet extends ActiveRecord
{
    const TYPE_TERMS = 'terms';
    const TYPE_RANGE = 'range';
    const TYPE_INTERVAL = 'interval';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%facets}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('facet', 'ID'),
            'name' => Module::t('facet', 'Name')
        ];
    }

    /**
     * Returns array of available facet types
     * @return array
     */
    public static function getTypesList()
    {
        return [
            self::TYPE_TERMS => Module::t('facet', 'Terms'),
            self::TYPE_RANGE => Module::t('facet', 'Range'),
            self::TYPE_INTERVAL => Module::t('facet', 'Interval')
        ];
    }

    /**
     * Returns array of searchable attributes
     * @return array
     */
    public static function getSearchableAttributes()
    {
        /** @var \im\search\components\Search $search */
        $search = Yii::$app->get('search');
        $attributes = [];
        foreach ($search->getSearchableAttributes() as $type => $typeAttributes) {
            $attributes[$type] = ArrayHelper::map($typeAttributes, 'name', 'label');
        }

        return $attributes;
    }

    public static function getSearchableAttributesGroups()
    {
        /** @var \im\search\components\Search $search */
        $search = Yii::$app->get('search');
        $groups = $search->getSearchableEntityTypes();
        foreach ($groups as $key => $label) {
            $groups[$key] = ['label' => $label];
        }

        return $groups;
    }
}
