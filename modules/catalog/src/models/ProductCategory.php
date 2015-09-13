<?php

namespace im\catalog\models;

use creocoder\nestedsets\NestedSetsBehavior;
use im\base\behaviors\RelationsBehavior;
use im\filesystem\components\FileInterface;
use im\filesystem\components\FilesBehavior;
use Intervention\Image\Constraint;
use Intervention\Image\ImageManagerStatic;
use yii\behaviors\SluggableBehavior;

/**
 * Product category model class.
 *
 * @property Product[] $products
 */
class ProductCategory extends Category
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_categories}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return array_merge(parent::behaviors(), [
            'sluggable' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'ensureUnique' => true
            ],
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                //'treeAttribute' => false
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
                                $image->resize(300, null, function (Constraint $constraint) {
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
                    'imageRelation' => $this->hasOne(ProductCategoryFile::className(), ['id' => 'image_id'])
                ]
            ]
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('{{%products_categories}}', ['category_id' => 'id']);
    }

//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getImage()
//    {
//        return $this->hasOne(ProductCategoryFile::className(), ['category_id' => 'id'])->where(['attribute' => 'image']);
//    }
}
