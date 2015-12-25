<?php

namespace im\seo\models;

use im\base\behaviors\RelationsBehavior;
use im\base\helpers\TemplateHelper;
use im\seo\components\MetaInterface;
use im\seo\Module;
use Yii;
use yii\db\ActiveRecord;
use yii\web\View;

/**
 * Page meta model class.
 *
 * @property integer $id
 * @property integer $entity_id
 * @property string $entity_type
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_robots
 * @property string $custom_meta
 *
 * @property ActiveRecord $entity
 * @property SocialMeta[] $socialMeta
 */
class Meta extends ActiveRecord implements MetaInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%seo_meta}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'relations' => RelationsBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity_id'], 'integer'],
            [['custom_meta'], 'string'],
            [['meta_title'], 'string', 'max' => 70],
            [['meta_description'], 'string', 'max' => 160],
            [['meta_robots', 'metaRobotsDirectives'], 'safe'],
            [['socialMeta'], 'im\base\validators\RelationValidator']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('meta', 'ID'),
            'entity_id' => Module::t('meta', 'Entity ID'),
            'meta_title' => Module::t('meta', 'Meta Title'),
            'meta_description' => Module::t('meta', 'Meta Description'),
            'meta_robots' => Module::t('meta', 'Meta Robots'),
            'metaRobotsDirectives' => Module::t('meta', 'Meta Robots'),
            'custom_meta' => Module::t('meta', 'Custom Meta Tags')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntity()
    {
        if ($this->entity_type) {
            $class = Yii::$app->get('core')->getEntityClass($this->entity_type);
            return $this->hasOne($class, ['id' => 'entity_id']);
        }
        else {
            return null;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocialMetaRelation()
    {
        return $this->hasMany(SocialMeta::className(), ['meta_id' => 'id'])->where(['meta_type' => Yii::$app->get('typesRegister')->getEntityType($this)]);
    }

    /**
     * @return SocialMeta[]
     */
    public function getEnabledSocialMeta()
    {
        $socialMeta = [];
        foreach (Yii::$app->get('seo')->getSocialMetaTypes(Yii::$app->get('typesRegister')->getEntityType($this)) as $socialMetaType) {
            $socialMeta[] = Yii::createObject(Yii::$app->get('seo')->getSocialMetaClass($socialMetaType));
        }

        return $socialMeta;
    }

    /**
     * @return array
     */
    public function getMetaRobotsDirectives()
    {
        return is_string($this->meta_robots) ? explode(',', $this->meta_robots) : $this->meta_robots;
    }

    /**
     * @param array|string $metaRobotsDirectives
     */
    public function setMetaRobotsDirectives($metaRobotsDirectives)
    {
        $this->meta_robots = is_array($metaRobotsDirectives) ? implode(',', $metaRobotsDirectives) : $metaRobotsDirectives;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (is_array($this->meta_robots)) {
            $this->meta_robots = implode(',', $this->meta_robots);
        }
        return parent::beforeSave($insert);
    }

    /**
     * @return array
     */
    public static function getMetaRobotsDirectivesList()
    {
        return [
            'noindex' => 'Noindex',
            'nofollow' => 'Nofollow',
            'noarchive' => 'Noarchive'
        ];
    }

    /**
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        return parent::load($data, $formName) && $this->loadSocialMeta($data);
    }

    /**
     * @inheritdoc
     */
    public function applyTo(View $view)
    {
        $view->params['metaTitle'] = $this->meta_title ? TemplateHelper::evaluateTemplate($this->meta_title, ['model' => $this->entity]) : $view->title;
        if ($this->meta_description)
            $view->registerMetaTag(['name' => 'description', 'content' => TemplateHelper::evaluateTemplate($this->meta_description, ['model' => $this->entity])], 'description');
        if ($this->meta_robots)
            $view->registerMetaTag(['name' => 'robots', 'content' => $this->meta_robots], 'robots');
        if ($this->custom_meta) {
            $view->params['customMeta'] = isset($view->params['customMeta'])
                ? $view->params['customMeta'] .= "\n" . $this->custom_meta : "\n" . $this->custom_meta;
        }
//        if ($this->openGraph !== null)
//            $this->openGraph->applyTo($view);

    }

    protected function loadSocialMeta($data)
    {
        $loaded = true;
        $socialMeta = $this->socialMeta ? $this->socialMeta : $this->getEnabledSocialMeta();
        foreach ($socialMeta as $meta) {
            if (!$meta->load($data)) {
                $loaded = false;
            }
            $meta->meta_type = Yii::$app->get('typesRegister')->getEntityType($this);
        }
        $this->socialMeta = $socialMeta;

        return $loaded;
    }

    /**
     * Performs the social meta data validation.
     * @param array $attributeNames list of attribute names that should be validated.
     * @param boolean $clearErrors whether to clear errors before performing validation
     * @return boolean whether the validation is successful without any error.
     */
    protected function validateSocialMeta($attributeNames = null, $clearErrors = true)
    {
        $valid = true;
        foreach ($this->socialMeta as $meta) {
            if (!$meta->validate($attributeNames, $clearErrors)) {
                $valid = false;
            }
        }

        return $valid;
    }
}
