<?php

namespace im\cms\models;

use im\cms\Module;
use im\filesystem\models\DbFile;

/**
 * Gallery item model class.
 *
 * @property int $gallery_id
 * @property string $caption
 * @property string $alt_text
 * @property integer $status
 * @property int $sort
 */
class GalleryItem extends DbFile
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const DEFAULT_STATUS = self::STATUS_ACTIVE;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gallery_items}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['gallery_id', 'sort'], 'integer'],
            ['status', 'default', 'value' => self::DEFAULT_STATUS],
            ['status', 'in', 'range' => array_keys(self::getStatusesList())],
            [['title', 'caption', 'alt_text'], 'safe'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'gallery_id' => Module::t('gallery_item', 'Gallery'),
            'caption' => Module::t('gallery_item', 'Caption'),
            'alt_text' => Module::t('gallery_item', 'Alt text'),
            'status' => Module::t('gallery_item', 'Active'),
            'sort' => Module::t('gallery_item', 'Sort')
        ]);
    }

    /**
     * @return array Statuses list
     */
    public static function getStatusesList()
    {
        return [
            self::STATUS_ACTIVE => Module::t('gallery_item', 'Active'),
            self::STATUS_INACTIVE => Module::t('gallery_item', 'Inactive')
        ];
    }
}
