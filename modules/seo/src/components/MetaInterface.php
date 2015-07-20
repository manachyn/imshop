<?php

namespace im\seo\components;

use yii\web\View;

interface MetaInterface
{
    /**
     * @param View $view
     */
    public function applyTo(View $view);
} 