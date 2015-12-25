<?php

namespace im\seo\components;

use yii\web\View;

/**
 * Interface MetaInterface
 * @package im\seo\components
 */
interface MetaInterface
{
    /**
     * @param View $view
     */
    public function applyTo(View $view);
} 