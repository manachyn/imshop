<?php

namespace im\forms\widgets;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * Class FieldSet
 * @package im\forms\widgets
 * @deprecated
 */
class FieldSet extends Widget
{
    /**
     * @var ActiveForm the form instance
     */
    public $form;

    /**
     * @var Model|ActiveRecord the model used for the form
     */
    public $model;

    /**
     * @var array the HTML attributes for the widget container tag.
     */
    public $options = [];

    /**
     * @var array fields
     */
    public $fields = [];

    /**
     * @var array fields to display
     */
    public $displayFields;

    /**
     * @var string the tag for the fields set
     */
    private $_tag;

    public function init()
    {
        parent::init();

        $this->checkConfig();
        $this->initOptions();
        //$this->registerAssets();
        echo Html::beginTag($this->_tag, $this->options) . "\n";
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        echo $this->renderFieldSet();
        echo Html::endTag($this->_tag);

        parent::run();
    }

    /**
     * Renders the field set
     *
     * @return string
     */
    protected function renderFieldSet()
    {
        $content = '';
        foreach ($this->fields as $fieldName => $settings) {
            if ($this->displayFields === null || in_array($fieldName, $this->displayFields))
                $content .= $this->renderField($fieldName, $settings) . "\n";
        }

        return $content;
    }

    protected function renderField($fieldName, $settings)
    {
        $fieldType = ArrayHelper::getValue($settings, 'fieldType', 'input');
        $fieldOptions = ArrayHelper::getValue($settings, 'fieldOptions', []);
        $labelOptions = ArrayHelper::getValue($settings, 'labelOptions', []);
        $field = $this->form->field($this->model, $fieldName, $fieldOptions);
        if ($labelOptions)
            $field->label(null, $labelOptions);
        $inputOptions = ArrayHelper::getValue($settings, 'inputOptions', []);
        ArrayHelper::remove($settings, 'rules');

        if (method_exists($field, $fieldType)) {
            $parameters = [];
            $class = new \ReflectionClass(get_class($field));
            $reflectionParameters = $class->getMethod($fieldType)->getParameters();
            foreach ($reflectionParameters as $parameter) {
                $name = $parameter->getName();
                $parameters[$name] = ArrayHelper::remove($inputOptions, $name,
                    $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null);
            }
            if (isset($parameters['options']))
                $parameters['options'] = $inputOptions;

            call_user_func_array(array($field, $fieldType), $parameters);
        }

        return $field;
    }

    /**
     * Checks config for Form widgets
     */
    protected function checkConfig()
    {
        if (empty($this->form) && empty($this->formName)) {
            throw new InvalidConfigException("The 'formName' property must be set when you are not using with ActiveForm.");
        }
        if (!empty($this->form) && !$this->form instanceof ActiveForm) {
            throw new InvalidConfigException("The 'form' property must be an instance of 'ActiveForm'.");
        }
        if (empty($this->fields)) {
            throw new InvalidConfigException("The 'fields' array must be set.");
        }
        if (!$this->hasModel() && empty($this->formName)) {
            throw new InvalidConfigException("Either the 'formName' has to be set or a valid 'model' property must be set extending from 'Model'.");
        }
        if (empty($this->formName) && (empty($this->form) || !$this->form instanceof ActiveForm)) {
            throw new InvalidConfigException("The 'form' property must be set and must be an instance of 'ActiveForm'.");
        }
    }

    /**
     * Check if a valid model is set for the object instance
     * @return boolean
     */
    protected function hasModel()
    {
        return isset($this->model) && $this->model instanceof Model;
    }


    /**
     * Initializes the widget options
     */
    protected function initOptions()
    {
        $this->_tag = ArrayHelper::remove($this->options, 'tag', 'fieldset');
        if (empty($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
    }
} 