<?php

namespace im\cms\models;

use app\modules\seo\models\Meta;

/**
 * Page meta model class.
 */
class PageMeta extends Meta
{
    const ENTITY_TYPE = 'page_meta';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page_meta}}';
    }

//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getEntity()
//    {
//        return $this->hasOne(Page::className(), ['id' => 'entity_id']);
//    }
}
