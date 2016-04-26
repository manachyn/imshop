<?php

namespace im\cms\models;

use im\seo\models\Meta;

/**
 * Page meta model class.
 */
class PageMeta extends Meta
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page_meta}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntity()
    {
        return $this->hasOne(Page::className(), ['id' => 'entity_id']);
    }
}
