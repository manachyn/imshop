<?php

namespace im\seo\models;

use im\base\interfaces\TypeableEntityInterface;
use im\seo\components\MetaInterface;
use im\seo\Module;
use Yii;
use yii\db\ActiveRecord;

/**
 * Social meta model class.
 *
 * @property integer $id
 * @property integer $meta_id
 * @property integer $meta_type
 * @property integer $social_type
 *
 * @property Meta $meta
 */
abstract class SocialMeta extends ActiveRecord implements MetaInterface, TypeableEntityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%social_meta}}';
    }

    /**
     * @inheritdoc
     */
    public static function instantiate($row)
    {
        try {
            $class = Yii::$app->get('seo')->getSocialMetaClass($row['social_type']);
            return new $class;
        } catch (\Exception $e) {
            return new static;
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['meta_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('socialMeta', 'ID'),
            'meta_id' => Module::t('socialMeta', 'Meta ID'),
            'social_type' => Module::t('socialMeta', 'Social Type')
        ];
    }

    abstract public function getSocialName();

//    /**
//     * @inheritdoc
//     */
//    public static function find()
//    {
//        return new SocialMetaQuery(get_called_class(), ['socialType' => self::getEntityType()]);
//    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        $this->social_type = $this->getEntityType();
        return parent::beforeSave($insert);
    }

//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getMeta()
//    {
//        if ($this->meta_type) {
//            $class = Yii::$app->seo->getMetaClass($this->meta_type);
//            return $this->hasOne($class, ['id' => 'meta_id']);
//        }
//        else {
//            return null;
//        }
//    }
}
