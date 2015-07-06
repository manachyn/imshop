<?php

namespace im\cms\components\layout;

use yii\base\Behavior;
use Yii;
use yii\base\InvalidConfigException;
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
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ActiveRecord::EVENT_INIT => 'ownerInit'
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
        Yii::$app->layoutManager->saveWidgetAreas($this->owner->{$this->layoutAttribute}, Yii::$app->request->post(), $this->owner);
    }

    /**
     * Handles init event of the owner.
     */
    public function ownerInit()
    {
        $this->addFormFields();
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
        $layoutId = isset($layoutId) ? $layoutId : $this->owner->{$this->layoutAttribute};
        $layout = Yii::$app->layoutManager->getLayout($layoutId, $withWidgetAreas, $withDefaultWidgets, $this->owner);
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

    /**
     * @return string
     */
    public function getOwnerType()
    {
        return Yii::$app->layoutManager->getOwnerType($this->owner);
    }

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

    protected function addFormFields()
    {
        if ($this->owner->hasMethod('addFormField')) {
            $field = [
                'fieldType' => 'dropDownList',
                'inputOptions' => [
                    'items' => $this->getAvailableLayoutsList(),
                    'prompt' => $this->getDefaultLayout()->getName()
                ],
                'labelOptions' => ['label' => 'Layout']
            ];
            $this->owner->addFormField('layout_id', $field);
        }
    }
} 