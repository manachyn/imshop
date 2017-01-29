<?php

namespace im\cms\models;

use im\base\behaviors\RelationsBehavior;
use im\base\traits\ModelBehaviorTrait;
use im\cms\Module;
use im\filesystem\components\FilesBehavior;
use im\filesystem\components\FilesystemComponent;
use im\filesystem\components\StorageConfig;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%galleries}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property GalleryItem[] $items
 */
class Gallery extends ActiveRecord
{
    use ModelBehaviorTrait;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const DEFAULT_STATUS = self::STATUS_ACTIVE;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%galleries}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => TimestampBehavior::className(),
            'files' => [
                'class' => FilesBehavior::className(),
                'attributes' => [
                    'uploadedItems' => [
                        'filesystem' => 'local',
                        'path' => '/galleries/{model.id}',
                        'multiple' => true,
                        'relation' => 'items',
                        'on afterDeleteAll' => function(StorageConfig $config, FilesystemComponent $filesystemComponent) {
                            $path = $config->resolvePath($this);
                            $filesystemComponent->deleteDirectory($path, $config->filesystem);
                        }
                    ]
                ]
            ],
            'relations' => [
                'class' => RelationsBehavior::className(),
                'settings' => [
                    'items' => ['deleteOnUnlink' => true]
                ],
                'relations' => [
                    'itemsRelation' => $this->hasMany(GalleryItem::className(), ['gallery_id' => 'id'])
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
            ['status', 'default', 'value' => self::DEFAULT_STATUS],
            ['status', 'in', 'range' => array_keys(self::getStatusesList())]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('gallery', 'ID'),
            'name' => Module::t('gallery', 'Name'),
            'created_at' => Module::t('gallery', 'Created At'),
            'updated_at' => Module::t('gallery', 'Updated At'),
            'status' => Module::t('gallery', 'Status')
        ];
    }

    /**
     * @return array Statuses list
     */
    public static function getStatusesList()
    {
        return [
            self::STATUS_ACTIVE => Module::t('gallery', 'Active'),
            self::STATUS_INACTIVE => Module::t('gallery', 'Inactive')
        ];
    }
}
