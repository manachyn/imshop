<?php

namespace im\seo\models;

use im\seo\Module;
use Yii;
use yii\web\View;

/**
 * Open graph model class.
 *
 * @property string $title
 * @property string $type
 * @property string $url
 * @property string $image
 * @property string $description
 * @property string $site_name
 * @property string $video
 */
class OpenGraph extends SocialMeta
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['title', 'site_name'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 50],
            [['url', 'image', 'description', 'video'], 'string', 'max' => 255]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'title' => Module::t('openGraph', 'Title'),
            'type' => Module::t('openGraph', 'Type'),
            'url' => Module::t('openGraph', 'Url'),
            'image' => Module::t('openGraph', 'Image'),
            'description' => Module::t('openGraph', 'Description'),
            'site_name' => Module::t('openGraph', 'Site Name'),
            'video' => Module::t('openGraph', 'Video'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getSocialName()
    {
        return Module::t('openGraph', 'Open Graph');
    }

    /**
     * @inheritdoc
     */
    public function applyTo(View $view)
    {
        $attributes = $this->getAttributes(null, ['id', 'meta_id', 'meta_type', 'social_type']);
        foreach ($attributes as $name => $value) {
            $view->registerMetaTag(['property' => 'og:' . $name, 'content' => $value]);
        }
    }
}
