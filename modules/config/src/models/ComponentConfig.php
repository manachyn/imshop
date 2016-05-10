<?php

namespace im\config\models;

use yii\base\Model;

abstract class ComponentConfig extends Model
{
    /**
     * The name of the config edit view
     * This should be in the format of 'path/to/view'.
     * @return string
     */
    abstract public function getEditView();
} 