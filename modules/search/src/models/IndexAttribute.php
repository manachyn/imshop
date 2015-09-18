<?php

namespace im\search\models;

use im\search\backend\Module;
use Yii;
use yii\db\ActiveRecord;

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

    /**
     * Saves indexable attributes from data array.
     *
     * @param array $data
     * @return bool whether the attributes were saved
     */
    public static function saveFromData($data)
    {
        list($indexableCondition, $deleteCondition) = self::getConditions($data);
        $indexableAttributes = IndexAttribute::find()->where($indexableCondition)->all();
        foreach ($data as $item) {
            if ($item['indexable']) {
                $indexable = false;
                foreach ($indexableAttributes as $indexableItem) {
                    if ($item['index_type'] === $indexableItem['index_type'] && $item['name'] === $indexableItem['name']) {
                        $indexableItem->load($item);
                        $indexable = true;
                        break;
                    }
                }
                if (!$indexable) {
                    $indexableAttributes[] = new IndexAttribute($item);
                }
            }
        }
        $saved = true;
        foreach ($indexableAttributes as $item) {
            if (!$item->save()) {
                $saved = false;
            }
        }
        if ($saved) {
            IndexAttribute::deleteAll($deleteCondition);
        }

        return $saved;
    }

    /**
     * Returns conditions from data array for searching and deleting indexable attributes.
     *
     * @param array $data
     * @return array
     */
    private static function getConditions($data)
    {
        $indexableCondition = ['or'];
        $deleteCondition = ['or'];
        foreach ($data as $item) {
            $condition = [
                'index_type' => $item['index_type'],
                'name' => $item['name'] ?: ''
            ];
            if ($item['indexable']) {
                $indexableCondition[] = $condition;
            } else {
                $deleteCondition[] = $condition;
            }
        }

        return [$indexableCondition, $deleteCondition];
    }
}
