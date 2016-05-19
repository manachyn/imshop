<?php

namespace im\tinymce;

use yii\helpers\ArrayHelper;

/**
 * Class TinyMCETrait
 * @package im\tinymce
 */
trait TinyMCETrait
{
    /**
     * @var string
     */
    public $preset = 'standard';

    /**
     * @var array
     */
    public $clientOptions = [];

    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    protected function initOptions()
    {
        $preset = __DIR__ . '/presets/' . $this->preset . '.php';
        $options = require($preset);
        $this->clientOptions = ArrayHelper::merge($options, $this->clientOptions);
    }
}
