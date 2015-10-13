<?php

namespace im\cms\models;

use im\base\interfaces\ModelBehaviorInterface;
use im\cms\Module;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%pages}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $type
 * @property string $slug
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 */
class Page extends ActiveRecord
{
    const TYPE = 'page';

    const STATUS_DELETED = -1;
    const STATUS_UNPUBLISHED = 0;
    const STATUS_PUBLISHED = 1;

    const DEFAULT_STATUS = self::STATUS_UNPUBLISHED;

    /**
     * @inheritdoc
     */
    public static function instantiate($row)
    {
        return Yii::$app->get('cms')->getPageInstance($row['type']);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (!$this->type) {
            $this->type = static::TYPE;
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pages}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => TimestampBehavior::className(),
            'sluggable' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            ['status', 'default', 'value' => self::DEFAULT_STATUS],
            ['status', 'in', 'range' => array_keys(self::getStatusesList())],
            [['content'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('page', 'ID'),
            'title' => Module::t('page', 'Title'),
            'slug' => Module::t('page', 'Slug'),
            'content' => Module::t('page', 'Content'),
            'created_at' => Module::t('page', 'Created At'),
            'updated_at' => Module::t('page', 'Updated At'),
            'status' => Module::t('page', 'Status')
        ];
    }

    /**
     * @return string Readable status
     */
    public function getStatus()
    {
        $statuses = self::getStatusesList();

        return $statuses[$this->status];
    }

    /**
     * Gets page url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->slug;
    }

    /**
     * @return array Statuses list
     */
    public static function getStatusesList()
    {
        return [
            self::STATUS_UNPUBLISHED => Module::t('page', 'Unpublished'),
            self::STATUS_PUBLISHED => Module::t('page', 'Published'),
            self::STATUS_DELETED => Module::t('page', 'Deleted')
        ];
    }

    /**
     * Returns array of available facet types
     * @return array
     */
    public static function getTypesList()
    {
        return ArrayHelper::map(Yii::$app->get('cms')->getPageTypes(), 'type', 'name');
    }


    /**
     * @inheritdoc
     * @return PageQuery
     */
    public static function find()
    {
        return new PageQuery(get_called_class(), ['type' => static::TYPE]);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (!$this->type) {
            $this->type = static::TYPE;
        }

        return parent::beforeSave($insert);
    }

    /**
     * @param string $path
     * @return PageQuery
     */
    public static function findByPath($path)
    {
        return static::find()->andWhere(['slug' => $path]);
    }

    /**
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        return parent::load($data, $formName) && $this->loadBehaviors($data);
    }

    /**
     * Populates the model behaviors with the data from end user.
     * @param array $data the data array.
     * @return boolean whether the model behaviors is successfully populated with some data.
     */
    public function loadBehaviors($data)
    {
        $loaded = true;
        foreach ($this->getBehaviors() as $behavior) {
            if ($behavior instanceof ModelBehaviorInterface) {
                if (!$behavior->load($data)) {
                    $loaded = false;
                }
            }
        }

        return $loaded;
    }
}
