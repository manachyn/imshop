<?php

namespace im\catalog\models;

use creocoder\nestedsets\NestedSetsBehavior;
use im\base\behaviors\RelationsBehavior;
use im\base\interfaces\ModelBehaviorInterface;
use im\base\traits\ModelBehaviorTrait;
use im\catalog\components\CategoryPageTrait;
use im\catalog\Module;
use im\filesystem\components\FileInterface;
use im\filesystem\components\FilesBehavior;
use im\filesystem\components\UploadBehavior;
use im\filesystem\models\DbFile;
use im\filesystem\models\EntityFile;
use im\tree\models\Tree;
use Intervention\Image\Constraint;
use Intervention\Image\ImageManagerStatic;
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
                    'uploadedImage' => [
                        'filesystem' => 'local',
                        'path' => '/categories',
                        'fileName' => '{model.slug}.{file.extension}',
                        'relation' => 'image',
                        'deleteOnUnlink' => true,
                        'extraColumns' => ['attribute' => 'image'],
                        'on beforeSave' => function (FileInterface $file) {
                            $image = ImageManagerStatic::make($file->getPath());
                            $image->resize(300, null, function (Constraint $constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            });
                            $image->save($file->getPath(), 100);
                        }
                    ]
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(CategoryFile::className(), ['category_id' => 'id'])->where(['attribute' => 'image']);
    }
}
