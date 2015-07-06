<?php

namespace im\cms\models;

use app\modules\base\behaviors\RelationsBehavior;
use app\modules\base\interfaces\ModelBehaviorInterface;
use im\cms\components\layout\LayoutBehavior;
use im\cms\Module;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

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
 *
 * @property PageMeta $pageMeta
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
        try {
            $class = Yii::$app->cms->getPageClass($row['type']);
            return Yii::createObject($class);
        } catch (\Exception $e) {
            return Yii::createObject(static::className());
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
            TimestampBehavior::className(),
            'sluggableBehavior' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true
            ],
            LayoutBehavior::className(),
            //RelationsBehavior::className()
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
     * @return \yii\db\ActiveQuery
     */
    public function getPageMeta()
    {
        return $this->hasOne(PageMeta::className(), ['page_id' => 'id']);
    }

    /**
     * @return string Readable status
     */
    public function getStatus()
    {
        $statuses = self::getStatusesList();

        return $statuses[$this->status];
    }

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
     * @inheritdoc
     * @return PageQuery
     */
    public static function find()
    {
        return new PageQuery(get_called_class(), ['type' => self::TYPE]);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        $this->type = self::TYPE;
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
