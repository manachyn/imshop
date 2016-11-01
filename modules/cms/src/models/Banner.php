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
 * This is the model class for table "{{%banners}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property BannerItem[] $items
 */
class Banner extends ActiveRecord
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
        return '{{%banners}}';
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
                        'path' => '/banners/{model.id}',
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
                    'itemsRelation' => $this->hasMany(BannerItem::className(), ['banner_id' => 'id'])
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
            'id' => Module::t('banner', 'ID'),
            'name' => Module::t('banner', 'Name'),
            'created_at' => Module::t('banner', 'Created At'),
            'updated_at' => Module::t('banner', 'Updated At'),
            'status' => Module::t('banner', 'Status')
        ];
    }

    /**
     * @return array Statuses list
     */
    public static function getStatusesList()
    {
        return [
            self::STATUS_ACTIVE => Module::t('banner', 'Active'),
            self::STATUS_INACTIVE => Module::t('banner', 'Inactive')
        ];
    }
}
