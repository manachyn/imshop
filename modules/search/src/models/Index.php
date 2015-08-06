<?php

namespace im\search\models;

use im\search\backend\Module;
use im\search\components\SearchableItem;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 * Index model class.
 *
 * @property integer $id
 * @property string $name
 * @property string $entity_type
 * @property string $server
 * @property integer $status
 */
class Index extends ActiveRecord
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    const DEFAULT_STATUS = self::STATUS_ENABLED;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%indexes}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'entity_type', 'server'], 'required'],
            [['status'], 'integer'],
            [['status'], 'default', 'value' => self::DEFAULT_STATUS],
            [['name', 'server'], 'string', 'max' => 100],
            [['entity_type'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('index', 'ID'),
            'name' => Module::t('index', 'Name'),
            'entity_type' => Module::t('index', 'Entity Type'),
            'server' => Module::t('index', 'Server'),
            'status' => Module::t('index', 'Status'),
        ];
    }

    /**
     * Returns array of available search servers
     * @return array
     */
    public static function getEntityTypesList()
    {
        return ArrayHelper::map(Yii::$app->get('search')->searchableItems, 'entityType', function (SearchableItem $item) {
            return Inflector::camel2words($item->entityType);
        });
    }

    /**
     * Returns array of available search servers
     * @return array
     */
    public static function getServersList()
    {
        $servers = Yii::$app->get('search')->servers;

        return array_combine(array_keys($servers), ArrayHelper::getColumn($servers, 'name'));
    }

    /**
     * Returns statuses list
     * @return array
     */
    public static function getStatusesList()
    {
        return [
            self::STATUS_ENABLED => Module::t('index', 'Enabled'),
            self::STATUS_DISABLED => Module::t('index', 'Disabled')
        ];
    }
}
