<?php

namespace im\cms\components;

use yii\db\ActiveQuery;

/**
 * Interface PageInterface
 * @package im\cms\components
 */
interface PageInterface
{
    /**
     * Find page by path.
     *
     * @param string $path
     * @return ActiveQuery
     */
    public static function findByPath($path);

    /**
     * Get route to display page.
     *
     * @return string
     */
    public static function getViewRoute();
}