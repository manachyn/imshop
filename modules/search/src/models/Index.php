<?php

namespace im\search\models;

use im\search\components\index\IndexInterface;
use im\search\components\service\SearchServiceInterface;
use im\search\Module;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Index model class.
 *
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property string $service
 * @property integer $status
 */
class Index extends ActiveRecord implements IndexInterface
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    const DEFAULT_STATUS = self::STATUS_ENABLED;

    /**
     * @var SearchServiceInterface search service instance
     */
    private $_searchService;

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
            [['name', 'type', 'service'], 'required'],
            [['name', 'type'], 'unique'],
            [['status'], 'integer'],
            [['status'], 'default', 'value' => self::DEFAULT_STATUS],
            [['name', 'service'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 255]
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
            'type' => Module::t('index', 'Type'),
            'service' => Module::t('index', 'Service'),
            'status' => Module::t('index', 'Status'),
        ];
    }

    /**
     * Returns array of available searchable types.
     *
     * @return array
     */
    public static function getTypesList()
    {
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = Yii::$app->get('searchManager');

        return $searchManager->getSearchableTypeNames();
    }

    /**
     * Returns array of available search services.
     *
     * @return array
     */
    public static function getServicesList()
    {
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = Yii::$app->get('searchManager');

        return array_combine(array_keys($searchManager->searchServices), ArrayHelper::getColumn($searchManager->searchServices, 'name'));
    }

    /**
     * Returns statuses list.
     *
     * @return array
     */
    public static function getStatusesList()
    {
        return [
            self::STATUS_ENABLED => Module::t('index', 'Enabled'),
            self::STATUS_DISABLED => Module::t('index', 'Disabled')
        ];
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @inheritdoc
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @inheritdoc
     */
    public function getSearchService()
    {
        if (!$this->_searchService) {
            /** @var \im\search\components\SearchManager $searchManager */
            $searchManager = Yii::$app->get('searchManager');
            $this->_searchService = $searchManager->getSearchService($this->service);
        }

        return $this->_searchService;
    }

    /**
     * @inheritdoc
     */
    public function setSearchService($service)
    {
        $this->_searchService = $service;
    }
}
