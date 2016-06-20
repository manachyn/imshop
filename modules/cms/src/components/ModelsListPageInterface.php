<?php

namespace im\cms\components;

/**
 * Interface ModelsListPageInterface
 * @package im\cms\components
 */
interface ModelsListPageInterface extends PageInterface
{
    /**
     * Get list item model class.
     *
     * @return string
     */
    public function getModelClass();
}
