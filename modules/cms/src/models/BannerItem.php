<?php

namespace im\cms\models;

use im\filesystem\models\DbFile;

/**
 * Banner item model class.
 *
 * @property int $banner_id
 * @property string $caption
 * @property string $link
 */
class BannerItem extends DbFile
{
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
            [['caption', 'link'], 'safe'],
        ]);
    }
}
