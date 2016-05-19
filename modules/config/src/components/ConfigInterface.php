<?php

namespace im\config\components;

/**
 * Interface ConfigInterface
 * @package im\config\components
 */
interface ConfigInterface
{
    /**
     * @return string unique key for config
     */
    public function getKey();

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @return array
     */
    public function getUserSpecificOptions();
}