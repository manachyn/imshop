<?php

namespace im\blog\models;

use im\base\behaviors\RelationsBehavior;
use im\base\traits\ModelBehaviorTrait;
use im\blog\Module;
use im\cms\components\PageInterface;
use im\filesystem\components\FileInterface;
use im\filesystem\components\FilesBehavior;
use Intervention\Image\Constraint;
use Intervention\Image\ImageManagerStatic;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%articles}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $announce
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property ArticleFile $image
 * @property ArticleFile $video
 * @property ArticleCategory[] $categories
 */
class Article extends ActiveRecord implements PageInterface, \Serializable
{
    use ModelBehaviorTrait;

    const STATUS_DELETED = -1;
    const STATUS_UNPUBLISHED = 0;
    const STATUS_PUBLISHED = 1;

    const DEFAULT_STATUS = self::STATUS_UNPUBLISHED;

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
        return '{{%articles}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => TimestampBehavior::className(),
            'sluggable' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true,
                'immutable' => true
            ],
            'files' => [
                'class' => FilesBehavior::className(),
                'attributes' => [
                    'uploadedImage' => [
                        'filesystem' => 'local',
                        'path' => '/articles',
                        'fileName' => '{file.filename}.{file.extension}',
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
                    ],
                    'uploadedVideo' => [
                        'filesystem' => 'local',
                        'path' => '/articles',
                        'fileName' => '{model.slug}.{file.extension}',
                        'relation' => 'video',
                        'deleteOnUnlink' => true
                    ]
                ]
            ],
            'relations' => [
                'class' => RelationsBehavior::className(),
                'settings' => [
                    'image' => ['deleteOnUnlink' => true],
                    'video' => ['deleteOnUnlink' => true]
                ],
                'relations' => [
                    'imageRelation' => $this->hasOne(ArticleFile::className(), ['id' => 'image_id']),
                    'videoRelation' => $this->hasOne(ArticleFile::className(), ['id' => 'video_id'])
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
            [['title'], 'required'],
            ['status', 'default', 'value' => self::DEFAULT_STATUS],
            ['status', 'in', 'range' => array_keys(self::getStatusesList())],
            [['announce'], 'string', 'max' => 255],
            [['slug', 'content'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('article', 'ID'),
            'title' => Module::t('article', 'Title'),
            'slug' => Module::t('article', 'Slug'),
            'announce' => Module::t('article', 'Announce'),
            'content' => Module::t('article', 'Content'),
            'created_at' => Module::t('article', 'Created At'),
            'updated_at' => Module::t('article', 'Updated At'),
            'status' => Module::t('article', 'Status')
        ];
    }

    /**
     * @return string Readable status
     */
    public function getStatus()
    {
        $statuses = self::getStatusesList();

        return $statuses[$this->status];
    }

    /**
     * Returns url.
     *
     * @param boolean|string $scheme the URI scheme to use in the generated URL
     * @return string
     */
    public function getUrl($scheme = false)
    {
        /** @var \im\cms\components\PageFinder $finder */
        $finder = Yii::$app->get('pageFinder');
        $articlesPage = $finder->findModel(['type' => ArticlesListPage::TYPE, 'status' => ArticlesListPage::STATUS_PUBLISHED]);

        return $articlesPage ? Url::to(['/cms/page/view', 'path' => $articlesPage->getUrl() . '/' . $this->slug], $scheme) : '';
    }

    /**
     * @return array Statuses list
     */
    public static function getStatusesList()
    {
        return [
            self::STATUS_UNPUBLISHED => Module::t('article', 'Unpublished'),
            self::STATUS_PUBLISHED => Module::t('article', 'Published'),
            self::STATUS_DELETED => Module::t('article', 'Deleted')
        ];
    }


    /**
     * @inheritdoc
     * @return ArticleQuery
     */
    public static function find()
    {
        return new ArticleQuery(get_called_class());
    }

    /**
     * @param string $path
     * @return ArticleQuery
     */
    public static function findByPath($path)
    {
        return static::findBySlug($path);
    }

    /**
     * @param string $slug
     * @return ArticleQuery
     */
    public static function findBySlug($slug)
    {
        return static::find()->andWhere(['slug' => $slug])->published();
    }

    /**
     * @inheritdoc
     */
    public static function getViewRoute()
    {
        return 'blog/article/view';
    }

    /**
     * Get last articles.
     *
     * @param int $count
     * @return Article[]
     */
    public static function getLastArticles($count = 10)
    {
        return static::find()->published()->orderBy(['created_at' => SORT_DESC])->limit($count)->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesRelation()
    {
        return $this->hasMany(ArticleCategory::className(), ['id' => 'category_id'])
            ->viaTable('{{%articles_categories}}', ['article_id' => 'id']);
    }

    /**
     * @return ArticleCategory[]
     */
    public function getCategories()
    {
        return $this->getCategoriesRelation()->all();
    }

    /**
     * @inheritdoc
     */
    public function serialize()
    {
        return serialize([
            'attributes' => $this->getAttributes(),
            'related' => $this->getRelatedRecords()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->setAttributes($data['attributes']);
        foreach ($data['related'] as $name => $value) {
            $this->populateRelation($name, $value);
        }
    }
}
