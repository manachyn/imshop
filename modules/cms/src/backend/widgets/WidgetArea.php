<?php

namespace im\cms\backend\widgets;

use im\forms\components\DynamicActiveForm;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\jui\Widget;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use im\cms\models\widgets\WidgetArea as WidgetAreaModel;

class WidgetArea extends Widget
{
    /**
     * @var array options for the widget area.
     */
    public $options = [];

    /**
     * @var array options for the drop area.
     */
    public $dropAreaOptions = [];

    /**
     * @var WidgetAreaModel
     */
    public $model;

    /**
     * @var ActiveForm|DynamicActiveForm
     */
    public $form;

    /**
     * @var string widget view in the format of 'path/to/view'
     */
    public $widgetView = 'selected_widget';

    /**
     * @var string css class for placeholder
     */
    public $placeholderClass = 'drop-area-placeholder';

    public $editMode = 'modal';

    public $modal = '#remotePjaxModal';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->model === null) {
            throw new InvalidConfigException('The "model" property must be set.');
        }
        $this->initOptions();
        echo Html::beginTag('div', $this->options) . "\n";
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        echo $this->render('widget_area', ['widgetArea' => $this, 'model' => $this->model, 'form' => $this->form, 'options' => $this->options]);
        echo Html::endTag('div') . "\n";
        $this->registerWidget('droppable', $this->dropAreaOptions['id']);
        $this->registerWidget('sortable', $this->dropAreaOptions['id']);
        $id = $this->options['id'];
        $options = [
            'widgetArea' => $this->model,
            'droppable' => new JsExpression("$('#{$this->dropAreaOptions['id']}').droppable('instance')"),
            'sortable' => new JsExpression("$('#{$this->dropAreaOptions['id']}').sortable('instance')"),
            //'widgetTemplate' => $this->render($this->widgetView),
            'modal' => $this->modal,
            'items' => '.selected-widget',
            'placeholder' => '.' . $this->placeholderClass,
            'form' => $this->form instanceof DynamicActiveForm ? $this->form->config : $this->form,
            'addUrl' => Url::to(['widget-area/add-widget'])
        ];
        $options = Json::encode($options);
        $view = $this->getView();
        WidgetAreaAsset::register($view);
        $view->registerJs("jQuery('#$id').widgetArea($options);");
    }

    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    protected function initOptions()
    {
        $this->options = array_merge(['class' => 'widget-area'], $this->options);
        $this->dropAreaOptions = array_merge(['class' => 'drop-area', 'id' => $this->options['id'] . '-drop-area', 'data-cont' => 'drop-area'], $this->dropAreaOptions);
        if ($this->model->display != WidgetAreaModel::DISPLAY_CUSTOM
            && $this->model->display != WidgetAreaModel::DISPLAY_ALWAYS)
            $this->dropAreaOptions['style'] = 'display:none;';

        $this->clientOptions = array_merge([
            // Droppable
            'accept' => '.available-widget',
            'activeClass' => 'active',
            'hoverClass' => 'hover',
            // Sortable
            'items' => '.selected-widget',
            'placeholder' => 'sortable-placeholder',
            'axis' => 'y',
            'forcePlaceholderSize' => true
        ], $this->clientOptions);
    }
} 