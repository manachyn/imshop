<?php

namespace im\forms\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\ActiveFormAsset;

/**
 * Class DynamicActiveField.
 * ActiveField which can be rendered without form (by ajax on server side) and dynamically added to the form by JS.
 *
 * @package im\forms\widgets
 */
class DynamicActiveField extends ActiveField
{
    /**
     * @var array form config
     */
    public $formConfig = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->formConfig = ArrayHelper::merge([
            'enableClientScript' => true,
            'requiredCssClass' => 'required',
            'errorCssClass' => 'has-error',
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
            'validateOnChange' => true,
            'validateOnBlur' => true,
            'validateOnType' => false,
            'validationDelay' => 500
        ], $this->normalizeFormConfig($this->formConfig));
    }

    /**
     * @inheritdoc
     */
    public function begin()
    {
        if (!$this->formConfig['enableClientScript']) {
            $clientOptions = $this->getClientOptions();
            if (!empty($clientOptions)) {
                if ($this->form) {
                    $this->form->attributes[] = $clientOptions;
                }
            }
        }

        $inputID = Html::getInputId($this->model, $this->attribute);
        $attribute = Html::getAttributeName($this->attribute);
        $options = $this->options;
        $class = isset($options['class']) ? [$options['class']] : [];
        $class[] = "field-$inputID";
        if ($this->model->isAttributeRequired($attribute)) {
            $class[] = $this->form ? $this->form->requiredCssClass : $this->formConfig['requiredCssClass'];
        }
        if ($this->model->hasErrors($attribute)) {
            $class[] = $this->form ? $this->form->errorCssClass : $this->formConfig['errorCssClass'];
        }
        $options['class'] = implode(' ', $class);
        $tag = ArrayHelper::remove($options, 'tag', 'div');

        return Html::beginTag($tag, $options);
    }

    /**
     * @inheritdoc
     */
    public function widget($class, $config = [])
    {
        /* @var $class \yii\base\Widget */
        $config['model'] = $this->model;
        $config['attribute'] = $this->attribute;
        $config['view'] = $this->form ? $this->form->getView() : \Yii::$app->getView();
        $this->parts['{input}'] = $class::widget($config);

        return $this;
    }

    public function end()
    {
        if ($this->formConfig['enableClientScript'] && !$this->form && !empty($this->formConfig['options']['id'])) {
            $view = \Yii::$app->getView();
            $clientOptions = $this->getClientOptions();
            if ($clientOptions) {
                ActiveFormAsset::register($view);
                $attribute = Json::encode($clientOptions);
                $view->registerJs("jQuery('#{$this->formConfig['options']['id']}').yiiActiveForm('add', $attribute);");
            }
        }
        return parent::end();
    }

    /**
     * Returns the JS options for the field.
     * @return array the JS options
     */
    public function getClientOptions()
    {
        $attribute = Html::getAttributeName($this->attribute);
        if (!in_array($attribute, $this->model->activeAttributes(), true)) {
            return [];
        }

        $enableClientValidation = $this->enableClientValidation || $this->enableClientValidation === null
            && ($this->form ? $this->form->enableClientValidation : $this->formConfig['enableClientValidation']);
        $enableAjaxValidation = $this->enableAjaxValidation || $this->enableAjaxValidation === null
            && ($this->form ? $this->form->enableAjaxValidation : $this->formConfig['enableAjaxValidation']);

        if ($enableClientValidation) {
            $validators = [];
            foreach ($this->model->getActiveValidators($attribute) as $validator) {
                /* @var $validator \yii\validators\Validator */
                $js = $validator->clientValidateAttribute($this->model, $attribute, $this->form ? $this->form->getView() : \Yii::$app->getView());
                if ($validator->enableClientValidation && $js != '') {
                    if ($validator->whenClient !== null) {
                        $js = "if (({$validator->whenClient})(attribute, value)) { $js }";
                    }
                    $validators[] = $js;
                }
            }
        }

        if (!$enableAjaxValidation && (!$enableClientValidation || empty($validators))) {
            return [];
        }

        $options = [];

        $inputID = Html::getInputId($this->model, $this->attribute);
        $options['id'] = $inputID;
        $options['name'] = $this->attribute;

        $options['container'] = isset($this->selectors['container']) ? $this->selectors['container'] : ".field-$inputID";
        $options['input'] = isset($this->selectors['input']) ? $this->selectors['input'] : "#$inputID";
        if (isset($this->selectors['error'])) {
            $options['error'] = $this->selectors['error'];
        } elseif (isset($this->errorOptions['class'])) {
            $options['error'] = '.' . implode('.', preg_split('/\s+/', $this->errorOptions['class'], -1, PREG_SPLIT_NO_EMPTY));
        } else {
            $options['error'] = isset($this->errorOptions['tag']) ? $this->errorOptions['tag'] : 'span';
        }

        $options['encodeError'] = !isset($this->errorOptions['encode']) || $this->errorOptions['encode'];
        if ($enableAjaxValidation) {
            $options['enableAjaxValidation'] = true;
        }
        foreach (['validateOnChange', 'validateOnBlur', 'validateOnType', 'validationDelay'] as $name) {
            $options[$name] = $this->$name === null ? ($this->form ? $this->form->$name : $this->formConfig[$name]) : $this->$name;
        }

        if (!empty($validators)) {
            $options['validate'] = new JsExpression("function (attribute, value, messages, deferred, \$form) {" . implode('', $validators) . '}');
        }

        // Only get the options that are different from the default ones (set in yii.activeForm.js)
        return array_diff_assoc($options, [
            'validateOnChange' => true,
            'validateOnBlur' => true,
            'validateOnType' => false,
            'validationDelay' => 500,
            'encodeError' => true,
            'error' => '.help-block',
        ]);
    }

    /**
     * @param array $config
     * @return array
     */
    protected function normalizeFormConfig($config)
    {
        array_walk_recursive($config, function(&$value) {
            if ($value === 'true') {
                $value = true;
            } elseif ($value === 'false') {
                $value = false;
            }
        });

        return $config;
    }
} 