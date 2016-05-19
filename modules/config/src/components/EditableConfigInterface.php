<?php

namespace im\config\components;

/**
 * Interface EditableConfigInterface
 * @package im\config\components
 */
interface EditableConfigInterface
{
    /**
     * The name of the config edit view
     * This should be in the format of 'path/to/view'.
     * @return string
     */
    public function getEditView();

    /**
     * @return string config title for backend
     */
    public function getTitle();
}
