<?php

namespace im\cms\components\layout;

use im\cms\models\Widget;
use im\cms\models\WidgetArea;
use im\cms\models\WidgetOwner;
use yii\base\Behavior;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class LayoutBehavior extends Behavior
{
    /**
     * @var ActiveRecord
     */
    public $owner;

    /** @var bool marker for this behavior */
    private $useLayout = true;

    /**
     * @var string
     */
    public $layoutAttribute = 'layout_id';

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave'
        ];
    }

    /**
     * @inheritdoc
     */
    public function attach($owner)
    {
        parent::attach($owner);
        $this->initConfig();
    }

    /**
     * Init behavior config
     * @throws InvalidConfigException
     */
    public function initConfig()
    {
        if (!$this->owner->hasAttribute($this->layoutAttribute))
            throw new InvalidConfigException("Class " . get_class($this->owner) . " must have {$this->layoutAttribute} attribute");
    }

    /**
     * Handles beforeSave event of the owner.
     */
    public function beforeSave()
    {
        $this->saveLayout(Yii::$app->request->post());
    }

    /**
     * Handles afterSave event of the owner.
     */
    public function afterSave()
    {
        $this->saveWidgetAreas(Yii::$app->request->post());
    }

    /**
     * Returns owner's layout
     * @param bool $withWidgetAreas whether to return layout with related widget areas
     * @param bool $withDefaultWidgets whether to load default widgets if layout has no own widgets
     * @param string $layoutId
     * @return Layout|null
     */
    public function getLayout($withWidgetAreas = true, $withDefaultWidgets = false, $layoutId = null)
    {
        $pks = $this->owner->primaryKey();
        $layoutId = isset($layoutId) ? $layoutId : $this->owner->{$this->layoutAttribute};
        $layout = Yii::$app->layoutManager->getLayout($layoutId, $withWidgetAreas, $withDefaultWidgets, $this->owner->$pks[0]);
        return $layout;
    }

    /**
     * Returns owner's default layout
     * @return Layout|null
     */
    public function getDefaultLayout()
    {
        return Yii::$app->layoutManager->getDefaultLayout();
    }


    /**
     * Returns array of available layouts for the owner (except default)
     * @return array
     */
    public function getAvailableLayoutsList()
    {
        $layouts = ArrayHelper::map(Yii::$app->layoutManager->getLayouts(), 'id', 'name');
        $defaultLayout = Yii::$app->layoutManager->getDefaultLayout();
        unset($layouts[$defaultLayout->id]);
        return $layouts;
    }

//    /**
//     * Returns related widget areas
//     * @return ActiveQuery
//     */
//    public function getWidgetAreas()
//    {
//        return $this->owner->hasMany(WidgetArea::className(), ['owner_id' => 'id']);
//    }

//    /**
//     * Returns related widgets
//     * @return Widget[]
//     */
//    public function getWidgets()
//    {
//        $pks = $this->owner->primaryKey();
//        return Yii::$app->layoutManager->getWidgets(['owner_id' => $this->owner->$pks[0]]);
//    }

    /**
     * Sets owner's layout attribute
     * @param array $data
     */
    protected function saveLayout($data)
    {
        $scope = $this->owner->formName();
        if (isset($data[$scope]))
            $data = $data[$scope];
        if (isset($data[$this->layoutAttribute]))
            $this->owner->{$this->layoutAttribute} = $data[$this->layoutAttribute];
    }

    /**
     * Loads and saves widget areas from array of data
     * @param array $data
     */
    protected function saveWidgetAreas($data)
    {
        $layout = $this->getLayout();
        if ($layout !== null) {
            // Load and save widget areas
            $widgetAreas = $layout->widgetAreas;
            $first = reset($widgetAreas);
            $formName = $first->formName();
            if (isset($data[$formName])) {
                $pks = $this->owner->primaryKey();
                $addData = ['layout_id' => $layout->id, 'owner_id' => $this->owner->$pks[0]];
                $data[$formName] = array_map(function($array) use ($addData) {
                    return array_merge($array, $addData);
                }, $data[$formName]);
                if (Model::loadMultiple($widgetAreas, $data) && Model::validateMultiple($widgetAreas)) {
                    foreach ($widgetAreas as $area)
                        $area->save(false);
                }
            }
            //$this->saveWidgets($widgetAreas, $data);
            // Get only areas with custom set of widgets
            $customWidgetAreas = array_filter($widgetAreas, function(WidgetArea $widgetArea) {
                return $widgetArea->display == WidgetArea::DISPLAY_CUSTOM;
            });
            // Link widgets to areas
            $this->linkWidgets($customWidgetAreas, $data);
        }
    }

    /**
     * Loads widgets from array of data and links to the owner
     * @param WidgetArea[] $widgetAreas
     * @param array $data
     */
    protected function linkWidgets($widgetAreas, $data)
    {
        if (isset($data['Widgets']) && !empty($data['Widgets'])) {
            $data = $this->normalizeWidgetsData($widgetAreas, $data['Widgets']);
            $pks = $this->owner->primaryKey();
            $condition = ['owner_id' => $this->owner->$pks[0], 'widget_area_id' => ArrayHelper::map($widgetAreas, 'id', 'id')];
            // Get binding records
            /** @var WidgetOwner[] $widgetOwners */
            $widgetOwners = WidgetOwner::find()->where($condition)->all();
            // Delete binding records if widgets set was changed
            if ($widgetOwners && $this->isWidgetsChanged($widgetOwners, $data)) {
                WidgetOwner::deleteAll($condition);
                $widgetOwners = [];
            }
            $sort = [];
            // Update/create binding records
            foreach ($data as $widgetOwnerData) {
                if (!empty($widgetOwnerData['widget_id']) && !empty($widgetOwnerData['widget_type'])
                    && !empty($widgetOwnerData['owner_id']) && !empty($widgetOwnerData['widget_area_id'])) {
                        $widgetOwner = $this->findWidgetOwnerByData($widgetOwners, $widgetOwnerData);
                        if ($widgetOwner === null) {
                            $widgetOwner = new WidgetOwner();
                            $widgetOwner->load($widgetOwnerData, '');
                        }
                        $sort[$widgetOwnerData['widget_area_id']] = isset($sort[$widgetOwnerData['widget_area_id']])
                            ? $sort[$widgetOwnerData['widget_area_id']] + 1 : 1;
                        $widgetOwner->sort = $sort[$widgetOwnerData['widget_area_id']];
                        $widgetOwner->save();
                }
            }
        }
    }

    /**
     * @param WidgetArea[] $widgetAreas
     * @param array $data
     * @return array
     */
    protected function normalizeWidgetsData($widgetAreas, $data)
    {
        $pks = $this->owner->primaryKey();
        foreach ($data as $key => $value) {
            $data[$key]['owner_id'] = $this->owner->$pks[0];
            if (empty($value['widget_area_id']) && !empty($value['widget_area_code'])) {
                unset($data[$key]['widget_area_code']);
                $widgetArea = ArrayHelper::getValue($widgetAreas, $value['widget_area_code']);
                if ($widgetArea !== null)
                    $data[$key]['widget_area_id'] = $widgetArea->id;
            }
        }
        return $data;
    }

    /**
     * @param WidgetOwner[] $widgetOwners
     * @param array $data
     * @return bool
     */
    protected function isWidgetsChanged($widgetOwners, $data)
    {
        if (count($widgetOwners) != count($data))
            return true;

        foreach ($widgetOwners as $widgetOwner) {
            $exists = false;
            foreach ($data as $widgetOwnerData) {
                if (!empty($widgetOwnerData['widget_id']) && !empty($widgetOwnerData['widget_type'])
                    && !empty($widgetOwnerData['owner_id']) && !empty($widgetOwnerData['widget_area_id'])) {
                    if ($this->isWidgetOwnerEquals($widgetOwner, $widgetOwnerData)) {
                        $exists = true;
                        break;
                    }
                }
            }
            if (!$exists)
                return true;
        }
        return false;
    }

    /**
     * @param WidgetOwner $widgetOwner
     * @param WidgetOwner|array $record
     * @return bool
     */
    protected function isWidgetOwnerEquals(WidgetOwner $widgetOwner, $record)
    {
        if (is_array($record)) $record = (object) $record;
        return $widgetOwner->widget_id == $record->widget_id && $widgetOwner->widget_type == $record->widget_type
        && $widgetOwner->widget_area_id == $record->widget_area_id && $widgetOwner->owner_id == $record->owner_id;
    }

    /**
     * @param WidgetOwner[] $widgetOwners
     * @param array $data
     * @return WidgetOwner|null
     */
    protected function findWidgetOwnerByData($widgetOwners, $data)
    {
        foreach ($widgetOwners as $widgetOwner)
            if ($this->isWidgetOwnerEquals($widgetOwner, $data))
                return $widgetOwner;

        return null;
    }

    /**
     * @param WidgetArea[] $widgetAreas
     * @param WidgetOwner[] $widgetOwners
     * @param array $data
     */
    protected function unlinkNonExistentWidgets($widgetAreas, $widgetOwners, $data)
    {
        $toDelete = [];
        foreach ($widgetOwners as $widgetOwner) {
            $exists = false;
            foreach ($data as $widgetOwnerData) {
                if (!empty($widgetOwnerData['widget_id']) && !empty($widgetOwnerData['widget_type'])
                    && !empty($widgetOwnerData['widget_area_code'])) {
                    $widgetArea = ArrayHelper::getValue($widgetAreas, $widgetOwnerData['widget_area_code']);
                    if ($widgetOwner->widget_id == $widgetOwnerData['widget_id'] && $widgetOwner->widget_type == $widgetOwnerData['widget_type'] && $widgetOwner->widget_area_id == $widgetArea->id) {
                        $exists = true;
                        break;
                    }
                }
            }
            if (!$exists)
                $toDelete[] = $widgetOwner->id;
        }
        if ($toDelete)
            WidgetOwner::deleteAll($toDelete);
    }

    /**
     * @deprecated not used because there is no need to save widgets
     * Loads and saves widgets from array of data
     * @param array $data
     */
    protected function saveWidgets($data)
    {
        $widgets = $this->loadWidgets($data);
        foreach ($widgets as $widget) {
            if (!$widget->save())
                $widget->delete();
        }
    }

    /**
     * @deprecated not used because there is no need to save widgets
     * Loads widgets from array of data
     * @param array $data
     * @param string|null $formName
     * @return Widget[]
     */
    protected function loadWidgets($data, $formName = null)
    {
        $widgets = [];
        if (!empty($data)) {
            if ($formName === null) {
                $reflector = new \ReflectionClass(Widget::className());
                $formName = $reflector->getShortName();
            }
            if ($formName != '') {
                if (isset($data[$formName]))
                    $data = $data[$formName];
                else
                    return $widgets;
            }

            /** @var Widget[] $availableWidgets */
            $availableWidgets = Yii::$app->layoutManager->getAvailableWidgets();
            $availableWidgets = ArrayHelper::index($availableWidgets, function(Widget $widget) {
                return $widget->formName();
            });
            $widgets = [];
            foreach ($data as $formName => $widgetsByFormName) {
                if (isset($availableWidgets[$formName])) {
                    foreach ($widgetsByFormName as $id => $widgetData) {
                        /** @var Widget $widgetInstance */
                        $widgetInstance = $availableWidgets[$formName];
                        $widget = $widgetInstance->findOne($id);
                        if ($widget !== null && $widget->load($widgetData, ''))
                            $widgets[] = $widget;
                    }
                }
            }
        }
        return $widgets;
    }
} 