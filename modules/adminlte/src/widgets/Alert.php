<?php

namespace im\adminlte\widgets;

use Yii;
use yii\helpers\Html;

/**
 * Class Alert
 * @package im\adminlte\widgets
 */
class Alert extends \yii\bootstrap\Alert
{
    /**
     * @var array Alert icons
     */
    public $alertIcons = [
        'error' => 'fa-ban',
        'danger' => 'fa-ban',
        'success' => 'fa-check',
        'info' => 'fa-info',
        'warning' => 'fa-warning'
    ];

    /**
     * @var string Alert type
     */
    public $type = 'success';

    /**
     * Renders the close button if any before rendering the content.
     * @return string the rendering result
     */
    protected function renderBodyBegin()
    {
        echo $this->renderCloseButton();
        return Html::tag('i', '', ['class' => 'fa ' . $this->alertIcons[$this->type]]);
    }

    /**
     * @inheritdoc
     */
    protected function initOptions()
    {
        parent::initOptions();
        if ($this->closeButton !== false) {
            Html::addCssClass($this->options, 'alert-dismissable');
        }
    }
}
