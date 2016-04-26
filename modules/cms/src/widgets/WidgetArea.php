<?php

namespace im\cms\widgets;

use im\base\context\ModelContextInterface;
use im\cms\components\TemplateBehavior;
use im\cms\models\Template;
use im\cms\models\widgets\WidgetArea as WidgetAreaModel;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use Yii;
use yii\web\Controller;

class WidgetArea extends Widget
{
    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $layout;

    /**
     * @var Template
     */
    public $template;

    /**
     * @var mixed
     */
    public $context;

    /**
     * @var WidgetAreaModel
     */
    protected $_widgetArea;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->code === null) {
            throw new InvalidConfigException('The "code" property must be set.');
        }
        $model = null;
        if ($this->context instanceof ModelContextInterface) {
            $model = $this->context->getModel();
        } elseif ($this->context instanceof Controller && $this->context->action instanceof ModelContextInterface) {
            /** @var ModelContextInterface $action */
            $action = $this->context->action;
            $model = $action->getModel();
        }
        if ($model && $model->getBehavior('template')) {
            /** @var TemplateBehavior $model */
            $this->template = $model->template;
        }
        if (!$this->template && $this->layout) {
            /** @var \im\cms\components\TemplateManager $templateManager */
            $templateManager = Yii::$app->get('templateManager');
            $this->template = $templateManager->getDefaultTemplate($this->layout);
        }
        /** @var \im\cms\components\LayoutManager $layoutManager */
        $layoutManager = Yii::$app->get('layoutManager');
        $this->_widgetArea = $layoutManager->getWidgetArea($this->code, $this->template ? $this->template->id : null);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->_widgetArea) {
            foreach ($this->_widgetArea->widgets as $widget) {
                $widget->context = $this->context;
                if ($output = $widget->run()) {
                    echo "\n" . $output;
                }
            }
        }
    }
} 