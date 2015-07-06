<?php

namespace im\cms\components\layout;

use im\cms\models\Widget;
use im\cms\models\WidgetArea;
use im\cms\models\WidgetOwner;
use yii\base\Component;
use Yii;
use yii\base\InvalidParamException;
use yii\base\Model;
use yii\caching\Cache;
use yii\caching\TagDependency;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class LayoutManager extends Component {

    public $widgetBaseClass = 'im\cms\models\Widget';

    /** @var array|Layout[] */
    private $_layouts;

    /** @var array */
    private $_widgetClasses = [];

    /** @var array */
    private $_ownerClasses = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        foreach ($this->_layouts as $key => $layout) {
            if (!$layout instanceof Layout) {
                $this->_layouts[$key] = Yii::createObject($layout);
            }
        }
    }

    public function registerWidgetClass($class) {
        if (!is_subclass_of($class, $this->widgetBaseClass))
            throw new InvalidParamException("Class $class must extend $this->widgetBaseClass");
        $this->_widgetClasses[] = $class;
    }

    public function registerOwnerClass($class, $type) {
        $ownersBaseClass = ActiveRecord::className();
        if (!is_subclass_of($class, $ownersBaseClass))
            throw new InvalidParamException("Class $class must extend $ownersBaseClass");
        $this->_ownerClasses[$type] = $class;
    }

    /**
     * @param ActiveRecord $owner
     * @throws InvalidParamException
     * @return string type
     */
    public function getOwnerType($owner)
    {
        $ownerType = null;
        $ownerClass = $owner::className();
        foreach ($this->_ownerClasses as $type => $class) {
            if ($class == $ownerClass) {
                $ownerType = $type;
                break;
            }
        }
        if ($ownerType === null)
            throw new InvalidParamException("Class '$ownerClass' is not registered as owner");

        return $ownerType;
    }

    /**
     * @param $ownerType
     * @throws \yii\base\InvalidParamException
     * @internal param \yii\db\ActiveRecord $owner
     * @return string class
     */
    public function getOwnerClass($ownerType)
    {
        $ownerClass = null;
        foreach ($this->_ownerClasses as $type => $class) {
            if ($type == $ownerType) {
                $ownerClass = $class;
                break;
            }
        }
        if ($ownerClass === null)
            throw new InvalidParamException("Type '$ownerType' is not registered as owner");

        return $ownerClass;
    }

    /**
     * @param Layout[] $layouts
     */
    public function setLayouts($layouts)
    {
        $this->_layouts = $layouts;
    }

    /**
     * @return Layout[] array of all layouts
     */
    public function getLayouts()
    {
        return $this->_layouts;
    }

    /**
     * @param string $id
     * @param bool $withWidgetAreas
     * @param bool $withDefaultWidgets
     * @param ActiveRecord $owner
     * @return Layout|null
     */
    public function getLayout($id = '')
    {
        $layout = null;
        if ($id) {
            foreach ($this->_layouts as $item) {
                if ($item->id == $id) {
                    $layout = $item;
                    break;
                }
            }
        } else {
            $layout = $this->getDefaultLayout();
        }

//        if ($layout !== null && $withWidgetAreas) {
//            $condition = ['layout_id' => $layout->id];
//            if ($owner !== null) {
//                $pks = $owner->primaryKey();
//                $condition['owner_id'] = $owner->$pks[0];
//                $condition['owner_type'] = $this->getOwnerType($owner);
//            }
//            else
//                $condition['owner_id'] = 0;
//            $widgetAreas = $this->getWidgetAreas()->where($condition)->all();
//            $this->setLayoutWidgetAreas($layout, $widgetAreas, $owner);
//            // If layout's widget areas don't have widgets, set their widgets from another areas with same code
//            if ($withDefaultWidgets)
//                $this->setDefaultWidgets($layout->widgetAreas, $owner);
//        }

        return $layout;
    }

    /**
     * @return Layout
     */
    public function getDefaultLayout()
    {
        $defaultLayout = $this->_layouts[0];
        foreach ($this->_layouts as $layout) {
            if ($layout->default)
                $defaultLayout = $layout;
        }
        return $defaultLayout;
    }

    /**
     * @return Widget[]
     */
    public function getAvailableWidgets() {
        $widgets = [];
        foreach ($this->_widgetClasses as $class) {
            $widgets[] = Yii::createObject($class);
        }
        return $widgets;
    }

    /**
     * @param string $type
     * @return Widget|null
     */
    public function getWidgetInstance($type) {
        foreach ($this->_widgetClasses as $class) {
            if ($class::getType() === $type) {
                return $widget = Yii::createObject($class);
            }
        }
        return null;
    }

    /**
     * @param mixed $condition
     * @param string|array orderBy the columns (and the directions) to be ordered by
     * @return Widget[] an array of page widgets, or an empty array.
     */
    public function getWidgets($condition = '', $orderBy = '') {
        $widgets = [];
        $widgetOwners = WidgetOwner::find()->where($condition)->orderBy($orderBy)->asArray()->all();
        $widgetClasses = $this->indexWidgetClassesByType();
        $widgetsByType = ArrayHelper::map($widgetOwners, 'widget_id', 'widget_id', 'widget_type');
        $allWidgets = [];
        foreach ($widgetsByType as $type => $ids) {
            if (isset($widgetClasses[$type])) {
                /** @var Widget $class */
                $class = $widgetClasses[$type];
                $allWidgets = array_merge($allWidgets, $class::find()->where(['id' => $ids])->indexBy(function(Widget $widget) {
                    return $widget->getType() . $widget->id;
                })->all());
            }
        }
        foreach ($widgetOwners as $widgetOwner) {
            if (isset($allWidgets[$widgetOwner['widget_type'] . $widgetOwner['widget_id']]))
                $widgets[] = $allWidgets[$widgetOwner['widget_type'] . $widgetOwner['widget_id']];
        }
        return $widgets;
    }

    /**
     * @return array
     */
    public function indexWidgetClassesByType() {
        $result = [];
        foreach ($this->_widgetClasses as $class) {
            $result[$class::getType()] = $class;
        }
        return $result;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWidgetAreas()
    {
        return WidgetArea::find();
    }

    /**
     * Loads and saves widget areas from array of data
     * @param string $layoutId
     * @param array $data
     * @param ActiveRecord $owner
     */
    public function saveWidgetAreas($layoutId, $data, $owner = null)
    {
        $layout = $this->getLayout($layoutId, true, false, $owner);
        if ($layout !== null) {
            // Load and save widget areas
            $widgetAreas = $layout->widgetAreas;
            $first = reset($widgetAreas);
            $formName = $first->formName();
            if (isset($data[$formName])) {
                $addData = ['layout_id' => $layout->id];
                if ($owner !== null) {
                    $pks = $owner->primaryKey();
                    $addData['owner_id'] = $owner->$pks[0];
                    $addData['owner_type'] = $this->getOwnerType($owner);
                }
                else
                    $addData['owner_id'] = 0;
                $data[$formName] = array_map(function($array) use ($addData) {
                    return array_merge($array, $addData);
                }, $data[$formName]);
                if (Model::loadMultiple($widgetAreas, $data) && Model::validateMultiple($widgetAreas)) {
                    foreach ($widgetAreas as $area)
                        $area->save(false);
                }
            }
            // Get only areas with custom set of widgets
            $customWidgetAreas = array_filter($widgetAreas, function(WidgetArea $widgetArea) {
                return $widgetArea->display == WidgetArea::DISPLAY_CUSTOM
                || $widgetArea->display == WidgetArea::DISPLAY_ALWAYS;
            });
            // Link widgets to areas
            $this->linkWidgets($customWidgetAreas, $data);
            $this->invalidateWidgetAreasCache($owner);
        }
    }

    /**
     * @param ActiveRecord $owner
     */
    public function invalidateWidgetAreasCache($owner = null)
    {
        /* @var $cache Cache */
        $cache = \Yii::$app->cache;
        TagDependency::invalidate($cache, $this->getWidgetAreasCacheTag($owner));
    }

    /**
     * Returns the cache tag name.
     * This allows to invalidate all cached widget areas.
     * @param ActiveRecord $owner
     * @return string the cache tag name
     */
    public function getWidgetAreasCacheTag($owner = null)
    {
        return md5(serialize([
            'WidgetArea',
            $owner !== null ? $this->getOwnerType($owner) : ''
        ]));
    }

    /**
     * Sets layout's widget areas
     * Create/update layout's widget areas from descriptors of widget areas available for layout
     * @param Layout $layout
     * @param WidgetArea[] $widgetAreas
     * @param ActiveRecord $owner
     */
    protected function setLayoutWidgetAreas($layout, $widgetAreas, $owner = null)
    {
        $availableWidgetAreas = $layout->getAvailableWidgetAreas();
        $widgetAreas = ArrayHelper::index($widgetAreas, 'code');
        $layoutWidgetAreas = [];
        foreach ($availableWidgetAreas as $availableArea) {
            $widgetArea = isset($widgetAreas[$availableArea->code]) ? $widgetAreas[$availableArea->code] : new WidgetArea();
            $widgetArea->title = $availableArea->title;
            $widgetArea->code = $availableArea->code;
            if ($owner === null)
                $widgetArea->display = WidgetArea::DISPLAY_ALWAYS;
            $layoutWidgetAreas[$availableArea->code] = $widgetArea;
        }
        $layout->widgetAreas = $layoutWidgetAreas;
    }

    /**
     * Sets widgets areas' widgets from another areas with same code
     * @param WidgetArea[] $widgetAreas
     * @param ActiveRecord $owner
     */
    protected function setDefaultWidgets($widgetAreas, $owner = null)
    {
        foreach ($widgetAreas as $widgetArea) {
            if (!$widgetArea->widgets) {
                if (!isset($allWidgetAreas)) {
                    if ($owner !== null) {
                        $pks = $owner->primaryKey();
                        $condition = ['owner_id' => $owner->$pks[0], 'owner_type'=> $this->getOwnerType($owner)];
                    }
                    else
                        $condition = ['owner_id' => 0];
                    /** @var WidgetArea[] $allWidgetAreas */
                    $allWidgetAreas = $this->getWidgetAreas()->where($condition)->all();
                }
                $widgets = [];
                foreach ($allWidgetAreas as $area) {
                    if ($area->code == $widgetArea->code)
                        $widgets = array_merge($widgets, $area->widgets);
                }
                $widgetArea->populateRelation('widgets', $widgets);
            }
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
            $condition = ['widget_area_id' => ArrayHelper::map($widgetAreas, 'id', 'id')];
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
                    && !empty($widgetOwnerData['widget_area_id'])) {
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
        foreach ($data as $key => $value) {
            if (empty($value['widget_area_id']) && !empty($value['widget_area_code'])) {
                unset($data[$key]['widget_area_code']);
                $widgetArea = ArrayHelper::getValue($widgetAreas, $value['widget_area_code']);
                if ($widgetArea !== null) {
                    $data[$key]['widget_area_id'] = $widgetArea->id;
                    $data[$key]['owner_id'] = $widgetArea->owner_id;
                    $data[$key]['owner_type'] = $widgetArea->owner_type;
                    if ($data[$key]['owner_id'] == 0)
                        unset($data[$key]['owner_id'], $data[$key]['owner_type']);
                }
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
                    && !empty($widgetOwnerData['owner_id']) && !empty($widgetOwnerData['owner_type'])
                    && !empty($widgetOwnerData['widget_area_id'])) {
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
        && $widgetOwner->widget_area_id == $record->widget_area_id && $widgetOwner->owner_id == $record->owner_id
        && $widgetOwner->owner_type == $record->owner_type;
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

} 