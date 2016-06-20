<?php

namespace im\cms\models;

use creocoder\nestedsets\NestedSetsBehavior;
use im\base\traits\ModelBehaviorTrait;
use im\cms\components\PageInterface;
use im\cms\Module;
use im\tree\models\Tree;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%pages}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $type
 * @property string $slug
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 *
 * @method PageQuery parents(integer $depth = null)
 * @method PageQuery children(integer $depth = null)
 * @method PageQuery leaves()
 * @method PageQuery prev()
 * @method PageQuery next()
 */
class Page extends Tree implements PageInterface
{
    use ModelBehaviorTrait;

    const TYPE = 'page';

    const STATUS_DELETED = -1;
    const STATUS_UNPUBLISHED = 0;
    const STATUS_PUBLISHED = 1;

    const DEFAULT_STATUS = self::STATUS_UNPUBLISHED;

    /**
     * @var int
     */
    protected $parentId;

    /**
     * @var Page[]
     */
    protected $parents = [];

    /**
     * @var Page|null
     */
    protected $parent;

    /**
     * @inheritdoc
     */
    public static function instantiate($row)
    {
        return Yii::$app->get('cms')->getPageInstance($row['type']);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (!$this->type) {
            $this->type = static::TYPE;
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pages}}';
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
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree'
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
            ['status', 'default', 'value' => self::DEFAULT_STATUS],
            ['status', 'in', 'range' => array_keys(self::getStatusesList())],
            [['slug', 'content', 'parentId'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('page', 'ID'),
            'title' => Module::t('page', 'Title'),
            'slug' => Module::t('page', 'Slug'),
            'content' => Module::t('page', 'Content'),
            'created_at' => Module::t('page', 'Created At'),
            'updated_at' => Module::t('page', 'Updated At'),
            'status' => Module::t('page', 'Status'),
            'parent' => Module::t('page', 'Parent'),
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
        $parts = [];
        if ($parent = $this->getParent()) {
            $parts[] = trim($parent->getUrl($scheme), '/');
        }
        if ($this->slug != 'index') {
            $parts[] = $this->slug;
        }

        return Url::to(['/cms/page/view', 'path' => implode('/', $parts)], $scheme);
    }

    /**
     * @return Page[]
     */
    public function getParents()
    {
        if (!$this->parents) {
            $this->parents = $this->parents()->all();
        }

        return $this->parents;
    }

    /**
     * @return Page|null
     */
    public function getParent()
    {
        if (!$this->parent) {
            $this->parent = $this->parents(1)->one();
        }

        return $this->parent;
    }

    /**
     * @return int
     */
    public function getParentId()
    {
        if (isset($this->parentId)) {
            return $this->parentId;
        } elseif ($parent = $this->parents(1)->one()) {
            /** @var Page $parent */
            return $parent->id;
        }

        return null;
    }

    /**
     * @param int $parentId
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    /**
     * @return array Statuses list
     */
    public static function getStatusesList()
    {
        return [
            self::STATUS_UNPUBLISHED => Module::t('page', 'Unpublished'),
            self::STATUS_PUBLISHED => Module::t('page', 'Published'),
            self::STATUS_DELETED => Module::t('page', 'Deleted')
        ];
    }

    /**
     * Returns array of available facet types
     * @return array
     */
    public static function getTypesList()
    {
        return ArrayHelper::map(Yii::$app->get('cms')->getPageTypes(), 'type', 'name');
    }


    /**
     * @inheritdoc
     * @return PageQuery
     */
    public static function find()
    {
        return new PageQuery(get_called_class(), ['type' => static::TYPE != Page::TYPE ? static::TYPE : null]);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (!$this->type) {
            $this->type = static::TYPE;
        }

        return parent::beforeSave($insert);
    }

    /**
     * @param string $path
     * @return PageQuery
     */
    public static function findByPath($path)
    {
//        $parts = explode('/', $path);
//        /** @var Page $page */
//        $page = static::find()->andWhere(['slug' => array_pop($parts)])->one();
//        $parents = $page->parents()->all();
        return static::findBySlug($path);
    }

    /**
     * @inheritdoc
     */
    public static function getViewRoute()
    {
        return '';
    }

    /**
     * @param string $slug
     * @return PageQuery
     */
    public static function findBySlug($slug)
    {
        return static::find()->andWhere(['slug' => $slug]);
    }
}

