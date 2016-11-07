<?php

namespace im\search\models;

use im\search\components\searchable\AttributeDescriptor;
use im\search\Module;
use Yii;
use yii\db\ActiveRecord;

/**
 * Index attribute model class.
 *
 * @property integer $id
 * @property string $index_type
 * @property string $name
 * @property string $index_name
 * @property string $type
 * @property bool $full_text_search
 * @property int $boost
 * @property bool $suggestions

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
            [['index_type', 'name', 'index_name', 'type'], 'string', 'max' => 100],
            [['full_text_search', 'boost', 'suggestions'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('index-attribute', 'ID'),
            'name' => Module::t('index-attribute', 'Name'),
            'index_name' => Module::t('index-attribute', 'Name in index'),
            'type' => Module::t('index-attribute', 'Type'),
            'index_type' => Module::t('index-attribute', 'Index type'),
            'label' => Module::t('index-attribute', 'Label'),
            'indexable' => Module::t('index-attribute', 'Indexable'),
            'full_text_search' => Module::t('index-attribute', 'Full text search'),
            'boost' => Module::t('index-attribute', 'Search boost'),
            'suggestions' => Module::t('index-attribute', 'Suggestions')
        ];
    }

    /**
     * Returns value types list.
     *
     * @return array
     */
    public static function getTypesList()
    {
        return [
            AttributeDescriptor::TYPE_STRING => Module::t('indexAttribute', 'String'),
            AttributeDescriptor::TYPE_INTEGER => Module::t('indexAttribute', 'Integer'),
            AttributeDescriptor::TYPE_FLOAT => Module::t('indexAttribute', 'Floating point number'),
            AttributeDescriptor::TYPE_BOOLEAN =>  Module::t('indexAttribute', 'Boolean')
        ];
    }

    /**
     * Returns attributes by entity type.
     *
     * @param string $indexType
     * @param string|array $orderBy
     * @return static[]
     */
    public static function findByIndexType($indexType, $orderBy = null)
    {
        $query = static::find()->where(['index_type' => $indexType]);
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
                $item['full_text_search'] = (int) $item['full_text_search'];
                $item['suggestions'] = (int) $item['suggestions'];
                if (isset($item['boost'])) {
                    $item['boost'] = (int) $item['boost'];
                }
                $indexable = false;
                foreach ($indexableAttributes as $indexableItem) {
                    if ($item['index_type'] === $indexableItem['index_type'] && $item['name'] === $indexableItem['name']) {
                        $indexableItem->load($item, '');
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
        $changedIndexType = [];
        foreach ($indexableAttributes as $item) {
            $dirtyAttributes = $item->getDirtyAttributes();
            $changed = $dirtyAttributes && count(array_intersect(array_keys($dirtyAttributes), ['index_name', 'type', 'full_text_search', 'suggestions'])) > 0 ? true : false;
            if (!$item->save()) {
                $saved = false;
            } elseif ($changed && !in_array($item->index_type, $changedIndexType)) {
                $changedIndexType[] = $item->index_type;
            }
        }
        if ($changedIndexType) {
            // Trigger index mapping changed event (currently reindexing is performed manually)
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
