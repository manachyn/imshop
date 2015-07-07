<?php

/**
 * @var yii\base\View $this View
 */

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;

NavBar::begin([
    'brandLabel' => $this->context->title,
    'brandUrl' => $this->context->url,
    'renderInnerContainer' => false
]);
    echo Nav::widget([
        'items' => $this->context->items,
        'options' => [
            'class' => 'navbar-nav navbar-right'
        ],
        'encodeLabels' => false
    ]);
NavBar::end();