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
 * @property string $name
 * @property string $type
 * @property string $entity_type
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
    public $enabled = false;

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
            'entity_type' => Module::t('indexAttribute', 'Entity Type')
        ];
    }
}
