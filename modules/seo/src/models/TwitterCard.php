<?php

namespace im\seo\models;

use im\seo\Module;
use Yii;
use yii\web\View;

/**
 * Twitter card model class.
 *
 * @property string $card
 * @property string $site
 * @property string $title
 * @property string $description
 * @property string $creator
 * @property string $image
 */
class TwitterCard extends SocialMeta
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['card', 'site', 'title', 'creator', 'image'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 255]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'card' => Module::t('twitterCard', 'Card'),
            'site' => Module::t('twitterCard', 'Site'),
            'title' => Module::t('twitterCard', 'Title'),
            'description' => Module::t('twitterCard', 'Description'),
            'creator' => Module::t('twitterCard', 'Creator'),
            'image' => Module::t('twitterCard', 'Image'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getSocialName()
    {
        return Module::t('twitterCard', 'Twitter Cards');
    }

    /**
     * @inheritdoc
     */
    public function applyTo(View $view)
    {
        $attributes = $this->getAttributes(null, ['id', 'meta_id', 'meta_type', 'social_type']);
        foreach ($attributes as $name => $value) {
            if ($value) {
                $view->registerMetaTag(['property' => 'twitter:' . $name, 'content' => $value]);
            }
        }
    }
}
