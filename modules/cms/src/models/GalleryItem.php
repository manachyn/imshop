<?php

namespace im\cms\models;

use im\filesystem\models\DbFile;

/**
 * Gallery item model class.
 *
 * @property int $gallery_id
 */
class GalleryItem extends DbFile
{
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
            [['gallery_id'], 'integer']
        ]);
    }
}
