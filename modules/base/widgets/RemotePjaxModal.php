<?php

namespace im\base\widgets;

use yii\widgets\Pjax;

class RemotePjaxModal extends RemoteModal
{
    /**
     * @var array|false the options for Pjax widget.
     * @see \yii\widgets\Pjax
     */
    public $pjaxOptions = [];

    /**
     * Begin Pjax widget
     * @return Pjax widget instance
     */
    protected function renderContentBegin()
    {
        Pjax::begin($this->pjaxOptions);
    }

    /**
     * End Pjax widget
     * @return Pjax widget instance that is ended.
     */
    protected function renderContentEnd()
    {
        Pjax::end();
    }

    /**
     * @inheritdoc
     */
    protected function initOptions()
    {
        parent::initOptions();
        if (isset($this->pjaxOptions['options']['class']))
            $this->pjaxOptions['options']['class'] .= ' modal-content';
        else
            $this->pjaxOptions['options']['class'] = 'modal-content';
    }
}
