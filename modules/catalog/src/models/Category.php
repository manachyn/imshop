<?php

namespace im\catalog\models;

use creocoder\nestedsets\NestedSetsBehavior;
use im\base\behaviors\RelationsBehavior;
use im\base\traits\ModelBehaviorTrait;
use im\catalog\components\CategoryPageTrait;
use im\catalog\Module;
use im\filesystem\components\FileInterface;
use im\filesystem\components\FilesBehavior;
use im\tree\models\Tree;
use Intervention\Image\Constraint;
use Intervention\Image\ImageManagerStatic;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Url;

/**
 * Category model class.
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string $content
 * @property bool $status
 * @property bool $image
 *
 * @method CategoryQuery parents(integer $depth = null)
 * @method CategoryQuery children(integer $depth = null)
 * @method CategoryQuery leaves()
 * @method CategoryQuery prev()
 * @method CategoryQuery next()
 */
class Category extends Tree
{
    use ModelBehaviorTrait;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const DEFAULT_STATUS = self::STATUS_ACTIVE;

    /**
     * @inheritdoc
     */
    public static function instantiate($row)
    {
        return Yii::createObject(static::className());
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
                'attribute' => 'name',
                'immutable' => true
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
                        'on beforeSave' => function (FileInterface $file) {
                            $image = ImageManagerStatic::make($file->getPath());
                            $image->resize(400, null, function (Constraint $constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            });
                            $image->save($file->getPath(), 100);
                        }
                    ]
                ]
            ],
            'relations' => [
                'class' => RelationsBehavior::className(),
                'settings' => [
                    'image' => ['deleteOnUnlink' => true]
                ],
                'relations' => [
                    'imageRelation' => $this->hasOne(CategoryFile::className(), ['id' => 'image_id'])
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
            [['name', 'slug'], 'string', 'max' => 255],
            [['description', 'status', 'content'], 'safe']
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
            'content' => Module::t('category', 'Content'),
            'status' => Module::t('product', 'Status'),
            'created_at' => Module::t('category', 'Created At'),
            'updated_at' => Module::t('category', 'Updated At'),
        ];
    }

    /**
     * Returns url.
     *
     * @param boolean|string $scheme the URI scheme to use in the generated URL
     * @return string
     */
    public function getUrl($scheme = false)
    {
        return Url::to(['/catalog/category/view', 'path' => $this->slug], $scheme);
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new CategoryQuery(get_called_class());
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
     * Finds category by path.
     * @param string $path
     * @return CategoryQuery
     */
    public static function findByPath($path)
    {
        return static::find()->andWhere(['slug' => $path]);
    }
}
