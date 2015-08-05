<?php

namespace im\catalog\models;

use creocoder\nestedsets\NestedSetsBehavior;
use im\base\behaviors\RelationsBehavior;
use im\base\interfaces\ModelBehaviorInterface;
use im\base\traits\ModelBehaviorTrait;
use im\catalog\components\CategoryPageTrait;
use im\catalog\Module;
use im\filesystem\components\FilesBehavior;
use im\filesystem\components\UploadBehavior;
use im\filesystem\models\DbFile;
use im\filesystem\models\EntityFile;
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
    use ModelBehaviorTrait;

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
            ],
            'files' => [
                'class' => FilesBehavior::className(),
                'attributes' => [
                    'image' => [
                        'filesystem' => 'local',
                        'path' => '/categories',
                        'fileName' => '{model.slug}.{file.extension}',
                    ],
                    'images' => [
                        'filesystem' => 'local',
                        'path' => '/categories',
                        'fileName' => '{model.slug}-{file.index}.{file.extension}',
                        'multiple' => true
                    ]
                ],
                'relations' => [
                    'image' => function () {
                        return $this->hasOne(DbFile::className(), ['id' => 'image_id']);
                    },
                    'entityFiles' => function () {
                        return $this->hasMany(EntityFile::className(), ['entity_id' => 'id'])/*->where(['entity_type' => 'product'])*/;
                    },
                    'images' => function () {
                        return $this->hasMany(DbFile::className(), ['id' => 'file_id'])->via('entityFiles');
                    },
                    'imagesRelation' => function () {
                        return $this->hasMany(DbFile::className(), ['id' => 'file_id'])->via('entityFiles');
                    }
                ]
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
//            [['uploadedImage'], 'file', 'skipOnEmpty' => false],
//            [['uploadedImages'], 'file', 'skipOnEmpty' => false, 'maxFiles' => 4],
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
}
