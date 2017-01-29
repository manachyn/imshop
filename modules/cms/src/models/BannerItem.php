<?php

namespace im\cms\models;

use im\cms\Module;
use im\filesystem\models\DbFile;

/**
 * Banner item model class.
 *
 * @property int $banner_id
 * @property string $caption
 * @property string $link
 * @property integer $status
 * @property int $sort
 */
class BannerItem extends DbFile
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const DEFAULT_STATUS = self::STATUS_ACTIVE;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%banner_items}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['banner_id', 'sort'], 'integer'],
            ['status', 'default', 'value' => self::DEFAULT_STATUS],
            ['status', 'in', 'range' => array_keys(self::getStatusesList())],
            [['caption', 'link'], 'safe'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'banner_id' => Module::t('banner_item', 'Banner'),
            'caption' => Module::t('banner_item', 'Caption'),
            'link' => Module::t('banner_item', 'Link'),
            'status' => Module::t('banner_item', 'Status'),
            'sort' => Module::t('banner_item', 'Sort')
        ]);
    }

    /**
     * @return array Statuses list
     */
    public static function getStatusesList()
    {
        return [
            self::STATUS_ACTIVE => Module::t('banner_item', 'Active'),
            self::STATUS_INACTIVE => Module::t('banner_item', 'Inactive')
        ];
    }
}
