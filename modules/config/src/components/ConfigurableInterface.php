<?php

namespace im\config\components;

/**
 * Interface ConfigurableInterface
 * @package im\config\components
 */
interface ConfigurableInterface
{
    /**
     * @return string unique key for config
     */
    public function getConfigKey();

    /**
     * @return string config title for backend
     */
    public function getConfigTitle();

    /**
     * @return EditableAttribute[] array of editable attributes
     */
    public function getEditableAttributes();
} 