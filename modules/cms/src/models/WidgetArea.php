<?php

namespace im\cms\models;

use im\base\behaviors\RelationsBehavior;
use im\cms\Module;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Widget area model class.
 *
 * @property integer $id
 * @property string $code
 * @property string $template_id
 * @property integer $owner_id
 * @property string $owner_type
 * @property integer $display
 *
 * @property Widget[] $widgets
 */
class WidgetArea extends ActiveRecord
{
    const DISPLAY_NONE = 0;
    const DISPLAY_INHERIT = 1;
    const DISPLAY_CUSTOM = 2;
    const DISPLAY_ALWAYS = 3;

    const DEFAULT_DISPLAY = self::DISPLAY_INHERIT;

    /**
     * @var string area title
     */
    public $title;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%widget_areas}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => TimestampBehavior::className(),
            'relations' => [
                'class' => RelationsBehavior::className(),
                'settings' => [
                    'widgets' => ['deleteOnUnlink' => true]
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'template_id', 'display'], 'required'],
            ['display', 'default', 'value' => self::DISPLAY_INHERIT],
            ['display', 'in', 'range' => [self::DISPLAY_NONE, self::DISPLAY_INHERIT, self::DISPLAY_CUSTOM, self::DISPLAY_ALWAYS]],
            [['owner_type'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('page', 'ID'),
            'code' => Module::t('page', 'Code'),
            'template_id' => Module::t('page', 'Template'),
            'owner_id' => Module::t('page', 'Page'),
            'display' => Module::t('page', 'Display')
        ];
    }

    /**
     * @return array Display options
     */
    public static function getDisplayOptions()
    {
        return [
            self::DISPLAY_INHERIT => Module::t('page', 'Inherit'),
            self::DISPLAY_CUSTOM => Module::t('page', 'Custom'),
            self::DISPLAY_NONE => Module::t('page', 'Don\'t display')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWidgetAreaItems()
    {
        return $this->hasMany(WidgetAreaItem::className(), ['widget_area_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWidgetsRelation()
    {
        return $this->hasMany(Widget::className(), ['id' => 'widget_id'])->via('widgetAreaItems');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWidgets()
    {
        /** @var \yii\db\ActiveQuery $query */
        $query = Widget::find()
            ->select([
                '{{%widgets}}.*',
                '{{%widget_area_widgets}}.sort AS sort'
            ])
            ->innerJoin('{{%widget_area_widgets}}', '{{%widgets}}.id = {{%widget_area_widgets}}.widget_id AND {{%widget_area_widgets}}.widget_area_id = :id', ['id' => $this->id])
            ->orderBy(['{{%widget_area_widgets}}.sort' => SORT_ASC]);

        $query->multiple = true;

        return $query;
    }
}
