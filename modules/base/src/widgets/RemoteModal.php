<?php

namespace im\base\widgets;

use yii\bootstrap\Modal;
use yii\bootstrap\Widget;
use yii\helpers\Html;

class RemoteModal extends Modal
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        Widget::init();

        $this->initOptions();

        echo $this->renderToggleButton() . "\n";
        echo Html::beginTag('div', $this->options) . "\n";
        echo Html::beginTag('div', ['class' => 'modal-dialog ' . $this->size]) . "\n";
        $this->renderContentBegin();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->renderContentEnd();
        echo "\n" . Html::endTag('div'); // modal-dialog
        echo "\n" . Html::endTag('div');

        $this->registerPlugin('modal');
    }

    /**
     * Renders the opening tag of the modal content.
     * @return string the rendering result
     */
    protected function renderContentBegin()
    {
        echo Html::beginTag('div', ['class' => 'modal-content']) . "\n";
    }

    /**
     * Renders the closing tag of the modal content.
     * @return string the rendering result
     */
    protected function renderContentEnd()
    {
        echo "\n" . Html::endTag('div');
    }
}
