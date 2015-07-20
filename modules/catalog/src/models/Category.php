<?php

namespace im\catalog\models;

use creocoder\nestedsets\NestedSetsBehavior;
use im\base\interfaces\ModelBehaviorInterface;
use im\catalog\components\CategoryPageTrait;
use im\catalog\Module;
use im\tree\models\Tree;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * Category model class.
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $description
 */
class Category extends Tree
{
    use CategoryPageTrait;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const DEFAULT_STATUS = self::STATUS_ACTIVE;

    /**
     * @inheritdoc
     */
    public static function instantiate($row)
    {
        return \Yii::createObject(static::className());
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%categories}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'timestamp' => TimestampBehavior::className(),
            'sluggable' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name'
            ],
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'slug', 'description'], 'string', 'max' => 255],
            [['status'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('category', 'ID'),
            'name' => Module::t('category', 'Name'),
            'slug' => Module::t('category', 'URL'),
            'description' => Module::t('category', 'Description'),
            'status' => Module::t('product', 'Status'),
            'created_at' => Module::t('category', 'Created At'),
            'updated_at' => Module::t('category', 'Updated At'),
        ];
    }

    /**
     * @return array Statuses list
     */
    public static function getStatusesList()
    {
        return [
            self::STATUS_ACTIVE => Module::t('category', 'Active'),
            self::STATUS_INACTIVE => Module::t('category', 'Inactive')
        ];
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
