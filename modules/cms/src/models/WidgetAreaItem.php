<?php

namespace im\cms\models;

use yii\db\ActiveRecord;
use Yii;

/**
 * Binding record for many-many relation between widgets and their owners
 *
 * @property integer $id
 * @property integer $widget_id
 * @property string $widget_type
 * @property integer $owner_id
 * @property string $owner_type
 * @property integer $widget_area_id
 * @property integer $sort
 */
class WidgetAreaItem extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%widget_area_widgets}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['widget_id', 'widget_type', 'owner_id', 'owner_type', 'widget_area_id', 'sort'], 'safe']
        ];
    }
} 