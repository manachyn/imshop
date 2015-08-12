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
 * @property string $entity_type
 * @property integer $attribute_id
 * @property string $attribute_name
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
     * @var string
     */
    public $name;

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
            [['name', 'type', 'entity_type'], 'required'],
            [['name', 'type'], 'string', 'max' => 100],
            [['entity_type'], 'string', 'max' => 255]
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
            'entity_type' => Module::t('indexAttribute', 'Entity Type'),
            'label' => Module::t('indexAttribute', 'Label'),
            'indexable' => Module::t('indexAttribute', 'Indexable')
        ];
    }

    /**
     * Returns attributes by entity type.
     *
     * @param string $entityType
     * @param string|array $orderBy
     * @return static[]
     */
    public static function findByEntityType($entityType = null, $orderBy = null)
    {
        $query = static::find();
        if ($entityType) {
            $query->where(['entity_type' => $entityType]);
        }
        if ($orderBy) {
            $query->orderBy($orderBy);
        }

        return $query->all();
    }
}
