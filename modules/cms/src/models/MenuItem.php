<?php

namespace im\cms\models;

use creocoder\nestedsets\NestedSetsBehavior;
use im\base\behaviors\RelationsBehavior;
use im\base\traits\ModelBehaviorTrait;
use im\cms\Module;
use im\filesystem\components\FileInterface;
use im\filesystem\components\FilesBehavior;
use im\tree\models\Tree;
use Intervention\Image\Constraint;
use Intervention\Image\ImageManagerStatic;

/**
 * Menu item model class.
 *
 * @property integer $id
 * @property string $label
 * @property string $url
 * @property bool $status
 *
 * @method MenuItemQuery parents(integer $depth = null)
 * @method MenuItemQuery children(integer $depth = null)
 * @method MenuItemQuery leaves()
 * @method MenuItemQuery prev()
 * @method MenuItemQuery next()
 */
class MenuItem extends Tree
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
        return '{{%menu_items}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree'
            ],
            'files' => [
                'class' => FilesBehavior::className(),
                'attributes' => [
                    'uploadedIcon' => [
                        'filesystem' => 'local',
                        'path' => '/menus',
                        'fileName' => '{model.id}.{file.extension}',
                        'relation' => 'icon',
                        'deleteOnUnlink' => true,
                        'on beforeSave' => function (FileInterface $file) {
                            $image = ImageManagerStatic::make($file->getPath());
                            $image->resize(100, null, function (Constraint $constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            });
                            $image->save($file->getPath(), 100);
                        }
                    ],
                    'uploadedActiveIcon' => [
                        'filesystem' => 'local',
                        'path' => '/menus',
                        'fileName' => '{model.id}-active.{file.extension}',
                        'relation' => 'activeIcon',
                        'deleteOnUnlink' => true,
                        'on beforeSave' => function (FileInterface $file) {
                            $image = ImageManagerStatic::make($file->getPath());
                            $image->resize(100, null, function (Constraint $constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            });
                            $image->save($file->getPath(), 100);
                        }
                    ],
                    'uploadedVideo' => [
                        'filesystem' => 'local',
                        'path' => '/menus',
                        'fileName' => '{model.id}.{file.extension}',
                        'relation' => 'video',
                        'deleteOnUnlink' => true
                    ]
                ]
            ],
            'relations' => [
                'class' => RelationsBehavior::className(),
                'settings' => [
                    'icon' => ['deleteOnUnlink' => true],
                    'activeIcon' => ['deleteOnUnlink' => true],
                    'video' => ['deleteOnUnlink' => true]
                ],
                'relations' => [
                    'iconRelation' => $this->hasOne(MenuItemFile::className(), ['id' => 'icon_id']),
                    'activeIconRelation' => $this->hasOne(MenuItemFile::className(), ['id' => 'active_icon_id']),
                    'videoRelation' => $this->hasOne(MenuItemFile::className(), ['id' => 'video_id'])
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
            [['label'], 'required'],
            [['status'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('menu-item', 'ID'),
            'label' => Module::t('menu-item', 'Label'),
            'url' => Module::t('menu-item', 'URL'),
            'status' => Module::t('menu-item', 'Status'),
            'uploadedIcon' => Module::t('menu-item', 'Icon'),
            'uploadedActiveIcon' => Module::t('menu-item', 'Active icon'),
            'uploadedVideo' => Module::t('menu-item', 'Video')
        ];
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new MenuItemQuery(get_called_class());
    }

    /**
     * @return array Statuses list
     */
    public static function getStatusesList()
    {
        return [
            self::STATUS_ACTIVE => Module::t('menu-item', 'Active'),
            self::STATUS_INACTIVE => Module::t('menu-item', 'Inactive')
        ];
    }
}
