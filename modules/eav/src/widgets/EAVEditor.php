<?php

namespace im\eav\widgets;

use im\eav\models\AttributeValue;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

class EAVEditor extends Widget
{
    /**
     * @var array the HTML attributes for the container tag of the widget.
     */
    public $options = [];

    /**
     * @var array the js options
     */
    public $clientOptions = [];

    /**
     * @var AttributeValue[]
     */
    public $attributes = [];

    /**
     * @var ActiveForm
     */
    public $form;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->form === null) {
            throw new InvalidConfigException('The "form" property must be set.');
        }
        $this->initOptions();
        echo Html::beginTag(isset($this->options['tag']) ? $this->options['tag'] : 'div', $this->options);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientScript();
        echo $this->render('eav_editor', ['attributes' => $this->attributes, 'form' => $this->form]);
        echo Html::endTag(isset($this->options['tag']) ? $this->options['tag'] : 'div');
    }

    /**
     * Registers the needed JavaScript.
     */
    public function registerClientScript()
    {
        $id = $this->options['id'];
        $view = $this->getView();
        EAVEditorAsset::register($view);
        $options = Json::encode($this->clientOptions);
        $view->registerJs("$('#$id').eavEditor($options)");
    }

    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    protected function initOptions()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        $this->clientOptions = ArrayHelper::merge([
            'form' => $this->form,
            'fieldsUrl' => Url::to(['/eav/attributes/fields'])
        ], $this->clientOptions);
    }
} 