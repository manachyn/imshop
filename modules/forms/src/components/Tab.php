<?php

namespace im\forms\components;

use yii\base\Model;
use yii\widgets\ActiveForm;

/**
 * Class Tab represents item of TabSet.
 *
 * @package im\forms\components
 */
class Tab extends FieldSet
{
    /**
     * @var string tab label
     */
    protected $label;

    /**
     * Create tab instance.
     *
     * @param string $name tab name
     * @param string $label tab label
     * @param array $items tab items
     * @param ActiveForm $form form instance
     * @param Model $model model instance
     */
    function __construct($name, $label, $items = [], $form = null, $model = null)
    {
        $this->label = $label;
        
        parent::__construct($name, $items, $form, $model);
    }

    /**
     * Set tab label.
     *
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * Get tab label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
} 