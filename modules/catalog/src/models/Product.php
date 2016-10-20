<?php

namespace im\catalog\models;

use im\base\behaviors\RelationsBehavior;
use im\base\traits\ModelBehaviorTrait;
use im\catalog\components\ProductEavTrait;
use im\catalog\components\ProductInterface;
use im\catalog\components\ProductTypeInterface;
use im\catalog\components\VariableProductTrait;
use im\catalog\Module;
use im\filesystem\components\FileInterface;
use im\filesystem\components\FilesBehavior;
use im\filesystem\components\FilesystemComponent;
use im\filesystem\components\StorageConfig;
use Intervention\Image\Constraint;
use Intervention\Image\ImageManagerStatic;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * Product model class.
 *
 * @property string $sku
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string $short_description
 * @property float $price
 * @property int $status
 * @property int $availability
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ProductType $relatedProductType
 * @property ProductCategory[] $categories
 */
class Product extends ActiveRecord implements ProductInterface
{
    use ProductEavTrait, VariableProductTrait, ModelBehaviorTrait;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const DEFAULT_STATUS = self::STATUS_ACTIVE;

    /**
     * @var ProductFile
     */
    protected $cover;

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
        return '{{%products}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'sluggable' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true,
                'immutable' => true
            ],
            'timestamp' => TimestampBehavior::className(),
            'files' => [
                'class' => FilesBehavior::className(),
                'attributes' => [
                    'uploadedImages' => [
                        'filesystem' => 'local',
                        'path' => '/products/{model.id}',
                        'fileName' => '{file.filename}.{file.extension}',
                        'multiple' => true,
                        'relation' => 'images',
//                        'on beforeSave' => function (FileInterface $file) {
//                            $image = ImageManagerStatic::make($file->getPath());
//                            $image->resize(1000, null, function (Constraint $constraint) {
//                                $constraint->aspectRatio();
//                                $constraint->upsize();
//                            });
//                            $image->save($file->getPath(), 100);
//                        },
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
                    'relatedEAttributes' => ['deleteOnUnlink' => true],
                    'images' => ['deleteOnUnlink' => true, 'extraColumns' => ['attribute' => 'images']]
                ],
                'relations' => [
                    'imagesRelation' => $this->hasMany(ProductFile::className(), ['product_id' => 'id'])->where(['attribute' => 'images'])
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            ['price', 'default', 'value' => 0],
            [['sku', 'slug', 'short_description', 'description', 'quantity', 'price', 'status', 'brand_id', 'type_id', 'eAttributes', 'categories'], 'safe'],
            [['eAttributes'], 'im\base\validators\RelationValidator'],
            //[['uploadedImages'], 'file', 'skipOnEmpty' => false, 'maxFiles' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('product', 'ID'),
            'sku' => Module::t('product', 'SKU'),
            'title' => Module::t('product', 'Title'),
            'slug' => Module::t('product', 'URL'),
            'short_description' => Module::t('product', 'Short description'),
            'description' => Module::t('product', 'Description'),
            'quantity' => Module::t('product', 'Quantity'),
            'price' => Module::t('product', 'Price'),
            'status' => Module::t('product', 'Status'),
            'brand_id' => Module::t('product', 'Brand'),
            'type_id' => Module::t('product', 'Type'),
            'available_on' => Module::t('product', 'Available On'),
            'created_at' => Module::t('product', 'Created At'),
            'updated_at' => Module::t('product', 'Updated At'),
            'categories' => Module::t('product', 'Categories'),
        ];
    }

    /**
     * @return array Statuses list
     */
    public static function getStatusesList()
    {
        return [
            self::STATUS_ACTIVE => Module::t('product', 'Active'),
            self::STATUS_INACTIVE => Module::t('product', 'Inactive')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductTypeRelation()
    {
        return $this->hasOne(ProductType::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrandRelation()
    {
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * {@inheritdoc}
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * {@inheritdoc}
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function setShortDescription($description)
    {
        $this->short_description = $description;
    }

    /**
     * {@inheritdoc}
     */
    public function getShortDescription()
    {
        return $this->short_description;
    }

    /**
     * {@inheritdoc}
     */
    public function isAvailable()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductType()
    {
        return $this->relatedProductType;
    }

    /**
     * {@inheritdoc}
     */
    public function setProductType(ProductTypeInterface $type = null)
    {
        $this->relatedProductType = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesRelation()
    {
        return $this->hasMany(ProductCategory::className(), ['id' => 'category_id'])
            ->viaTable('{{%products_categories}}', ['product_id' => 'id']);
    }

    /**
     * Returns product categories.
     *
     * @return ProductCategory[]
     */
    public function getCategories()
    {
        return $this->getCategoriesRelation()->all();
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->created_at = $createdAt->getTimestamp();
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updated_at = $updatedAt->getTimestamp();
    }

    /**
     * @return ProductAttributeValue[]
     */
    public function getAvailableEAttributes()
    {
        $values = [];
        $type = $this->getProductType();
        $attributes = $type !== null ? $type->getEAttributes() : [];
        if ($attributes) {
            foreach ($attributes as $attribute) {
                $value = static::getAttributeValueInstance();
                $value->setEAttribute($attribute);
                $values[$attribute->getName()] = $value;
            }
        }
        return $values;
    }

    /**
     * Returns url.
     *
     * @param boolean|string $scheme the URI scheme to use in the generated URL
     * @return string
     */
    public function getUrl($scheme = false)
    {
        return Url::to(['/catalog/product/view', 'path' => $this->slug], $scheme);
    }

    /**
     * @return ProductFile
     */
    public function getCover()
    {
        if (!$this->cover) {
            $cover = $this->imagesRelation()->where(['type' => ProductFile::TYPE_COVER])->one();
            $this->cover = $cover ?: $this->imagesRelation()->orderBy('sort')->one();
        }

        return $this->cover;
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new ProductQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        if (!$this->getSku()) {
            $this->setSku($this->getId());
        }
        parent::afterFind();
    }

    /**
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        return parent::load($data, $formName) && $this->loadBehaviors($data) && $this->loadEAttributes($data);
    }

    /**
     * Finds product by path.
     *
     * @param string $path
     * @return ProductQuery
     */
    public static function findByPath($path)
    {
        return static::find()->andWhere(['slug' => $path]);
    }
}
