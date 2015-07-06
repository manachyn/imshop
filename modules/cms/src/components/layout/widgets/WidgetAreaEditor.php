<?php

namespace im\cms\components\layout\widgets;

use im\cms\models\WidgetArea;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\jui\Widget;
use yii\web\JsExpression;
use yii\widgets\ActiveField;

class WidgetAreaEditor extends Widget
{
    /**
     * @var array options for the widget container.
     */
    public $containerOptions = [];

    /**
     * @var array options for the widget are.
     */
    public $options = [];

    /**
     * @var WidgetArea
     */
    public $widgetArea;

    /**
     * @var string widget view in the format of 'path/to/view'
     */
    public $widgetView = '@app/modules/cms/backend/views/widget/_selected_widget';

    /**
     * @var string css class for placeholder
     */
    public $placeholderClass = 'droppable-area-placeholder';

    public $editMode = 'modal';

    public $modal = '#remotePjaxModal';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->options['id'] = 'widget-area-' . $this->widgetArea->code;
        parent::init();
        if ($this->widgetArea === null) {
            throw new InvalidConfigException('The "widgetArea" property must be set.');
        }
        $this->initOptions();
        echo Html::beginTag('div', $this->containerOptions) . "\n";
        echo Html::tag('h4', $this->widgetArea->title) . "\n";
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        /** @var ActiveField $field */
        $field = \Yii::createObject([
            'class' => 'yii\widgets\ActiveField',
            'model' => $this->widgetArea,
            'attribute' => "[{$this->widgetArea->code}]display",
            //'form' => new ActiveForm()
        ]);
        echo $this->widgetArea->display == WidgetArea::DISPLAY_ALWAYS ? $field->hiddenInput()->label(false)
            : $field->dropDownList(WidgetArea::getDisplayOptions(), ['data-action' => 'display']);
        echo Html::beginTag('div', $this->options) . "\n";
        if ($widgets = $this->widgetArea->widgets)
            foreach ($widgets as $sort => $widget) {
                echo $this->render($this->widgetView, ['widget' => $widget, 'sort' => $sort, 'widgetAreaCode' => $this->widgetArea->code]);
            }
        else
            echo Html::tag('div', 'Drag widgets here', ['class' => $this->placeholderClass]) . "\n";

        echo Html::endTag('div') . "\n";
        echo Html::endTag('div') . "\n";
        $this->registerWidget('droppable');
        $this->registerWidget('sortable');
        $id = $this->options['id'];
        $options = [
            'widgetArea' => $this->widgetArea,
            'droppable' => new JsExpression("$('#$id').droppable('instance')"),
            'sortable' => new JsExpression("$('#$id').sortable('instance')"),
            'widgetTemplate' => $this->render($this->widgetView),
            'modal' => $this->modal,
            'items' => '.selected-widget',
            'placeholder' => '.' . $this->placeholderClass
        ];
        $options = Json::encode($options);
        $view = $this->getView();
        WidgetAreaEditorAsset::register($view);
        $view->registerJs("jQuery('#{$this->containerOptions['id']}').widgetAreaEditor($options);");
    }

    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    protected function initOptions()
    {
        $this->options = array_merge(['class' => 'widget-area droppable-area'], $this->options);
        if ($this->widgetArea->display != WidgetArea::DISPLAY_CUSTOM
            && $this->widgetArea->display != WidgetArea::DISPLAY_ALWAYS)
            $this->options['style'] = 'display:none;';
        $this->containerOptions = array_merge(['class' => 'widget-area-container', 'id' => $this->options['id'] . '-container'], $this->containerOptions);
        $this->clientOptions = array_merge([
            // Droppable
            'accept' => '.available-widget',
            'activeClass' => 'active',
            'hoverClass' => 'hover',
            // Sortable
            'items' => '.selected-widget',
            'placeholder' => 'sortable-placeholder',
            'axis' => 'y'
        ], $this->clientOptions);
    }

} 