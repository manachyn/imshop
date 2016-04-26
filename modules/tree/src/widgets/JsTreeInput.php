<?php

namespace im\tree\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;
use Yii;
use yii\widgets\InputWidget;

class JsTreeInput extends InputWidget
{
    /**
     * @var array the options for the underlying jsTree plugin.
     */
    public $clientOptions = [];

    /**
     * @var array the event handlers for the underlying jsTree widget.
     */
    public $clientEvents = [];

    /**
     * @var array the options for api.
     */
    public $apiOptions = [];

    /**
     * @var array the options for input.
     */
    public $inputOptions = [];

    /**
     * @var string|array the input value.
     */
    public $value;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->value === null && $this->hasModel()) {
            $this->value = Html::getAttributeValue($this->model, $this->attribute);
        }
        if ($this->name === null && $this->hasModel()) {
            $this->name = Html::getInputName($this->model, $this->attribute);
        }
        $this->initOptions();
        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'div');
        echo Html::beginTag($tag, $options);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientScript();
        echo Html::endTag(isset($this->options['tag']) ? $this->options['tag'] : 'div');
        echo $input = $this->getInput();
    }

    /**
     * Registers the needed JavaScript.
     */
    public function registerClientScript()
    {
        $id = $this->options['id'];
        $view = $this->getView();
        JsTreeAsset::register($view);
        $options = Json::encode($this->clientOptions);
        $apiOptions = Json::encode($this->apiOptions);
        $view->registerJs("$('#$id').jsTreeApi($apiOptions)");
        $view->registerJs("$('#$id').jstree($options)");
        $this->registerClientEvents();
    }

    /**
     * Generates the hidden input
     * @return string
     */
    public function getInput()
    {
        $options = $this->inputOptions;
        $hidden = Html::hiddenInput($this->name, '');
        $tag = isset($options['tag']) ? $options['tag'] : 'div';
        $selected = [];
        if ($this->value) {
            foreach ($this->value as $item) {
                $selected[] = Html::hiddenInput($this->name . '[]', $item);
            }
        }
        return Html::tag($tag, $hidden . "\n" . implode("\n", $selected), $options);
    }

    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    protected function initOptions()
    {
        if (!isset($this->inputOptions['id'])) {
            $this->inputOptions['id'] = $this->options['id'] . '-input';
        }
        $this->inputOptions['name'] = $this->name;
        $this->apiOptions = array_merge([
            'rootsUrl' => Url::to(['roots']),
            'childrenUrl' => Url::to(['descendants', 'level' => 1]),
            'parentsUrl' => Url::to(['ancestors']),
            'searchUrl' => Url::to(['search']),
            'searchInput' => '.tree-search',
            'searchableAttributes' => false,
            'checked' => $this->value,
            'input' => $this->inputOptions
        ], $this->apiOptions);
        $this->clientOptions = ArrayHelper::merge([
            'core' => [
                'animation' => 0,
                'data' => [
                    'url' => new JsExpression(
                            "function (node) {
                                var url;
                                if (node.id === '#') {
                                    url = '" . $this->apiOptions['rootsUrl'] . "';
                            } else {
                                url = '" . $this->apiOptions['childrenUrl'] . "';
                                url = url.replace('{id}', node.id);
                            }
                            return url;
                        }"
                        ),
//                    'data' => new JsExpression(
//                        "function (node) {
//                            return {'id': node.id};
//                        }"
//                    )
                ]
            ],
            'search' => [
                'ajax' => [
                    'url' => $this->apiOptions['searchUrl'],
                    'method' => 'POST',
                    'processData' => false
                ]
            ],
            'checkbox' => [
                'three_state' => false,
                'cascade' => 'undetermined',
                'tie_selection' => false
            ],
            'plugins' => ['search', 'checkbox'/*, 'state'*/]
        ], $this->clientOptions);
    }

    /**
     * Registers JS event handlers that are listed in [[clientEvents]].
     */
    protected function registerClientEvents()
    {
        if (!empty($this->clientEvents)) {
            $id = $this->options['id'];
            $js = [];
            foreach ($this->clientEvents as $event => $handler) {
                $js[] = ".on('$event', $handler)";
            }
            $js = "$('#$id')" . implode("\n", $js). ";";
            $this->getView()->registerJs($js);
        }
    }
}