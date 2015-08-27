<?php

namespace im\search\models;

use im\search\backend\Module;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 * Index attribute model class.
 *
 * @property integer $id
 * @property string $index_type
 * @property string $name
 * @property string $type
 *
 */
class IndexAttribute extends ActiveRecord
{
    /**
     * @var string
     */
    public $label;

    /**
     * @var boolean
     */
    public $indexable = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%index_attributes}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['index_type', 'name'], 'required'],
            [['index_type', 'name', 'type'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('indexAttribute', 'ID'),
            'name' => Module::t('indexAttribute', 'Name'),
            'type' => Module::t('indexAttribute', 'Type'),
            'index_type' => Module::t('indexAttribute', 'Index type'),
            'label' => Module::t('indexAttribute', 'Label'),
            'indexable' => Module::t('indexAttribute', 'Indexable')
        ];
    }

    /**
     * Returns attributes by entity type.
     *
     * @param string $indexType
     * @param string|array $orderBy
     * @return static[]
     */
    public static function findByIndexType($indexType = null, $orderBy = null)
    {
        $query = static::find();
        if ($indexType) {
            $query->where(['index_type' => $indexType]);
        }
        if ($orderBy) {
            $query->orderBy($orderBy);
        }

        return $query->all();
    }
}
